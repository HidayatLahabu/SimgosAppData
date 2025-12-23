<?php

namespace App\Http\Controllers\Tools;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\PendaftaranKunjunganModel;

class ToolsKunjunganController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select(
                'kunjungan.NOMOR as nomor',
                DB::raw('master.getNamaLengkap(pasien.NORM) as nama'),
                'pasien.NORM as norm',
                'ruangan.DESKRIPSI as ruangan',
                'kunjungan.MASUK as masuk',
                'kunjungan.KELUAR as keluar',
                'kunjungan.STATUS as status',
            )
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->where('pasien.STATUS', 1);

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(kunjungan.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(ruangan.DESKRIPSI) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('kunjungan.MASUK')->paginate(7)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Tools/Kunjungan/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    protected function getDetailKunjungan($id)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as NOMOR_KUNJUNGAN',
                'kunjungan.NOPEN as NOMOR_PENDAFTARAN',
                'pasien.NORM as NORM',
                DB::raw('master.getNamaLengkap(pasien.NORM) as NAMA_PASIEN'),
                DB::raw('master.getNamaLengkapPegawai(dokter.NIP) as DPJP'),
                'kunjungan.RUANGAN as ID_RUANGAN',
                'ruangan.DESKRIPSI as RUANGAN_TUJUAN',
                'ruang_kamar.KAMAR as KAMAR_TUJUAN',
                'ruang_kamar_tidur.TEMPAT_TIDUR as TEMPAT_TIDUR',
                'kunjungan.MASUK as TANGGAL_MASUK',
                'kunjungan.KELUAR as TANGGAL_KELUAR',
                'kunjungan.REF as REF',
                'penerima_kunjungan.NAMA as DITERIMA_OLEH',
                DB::raw('
                    CASE
                        WHEN DATE(kunjungan.MASUK) = DATE(pasien.TANGGAL) THEN 1
                        ELSE 0
                    END as STATUS_KUNJUNGAN
                '),
                'kunjungan.TITIPAN as TITIPAN',
                'kunjungan.TITIPAN_KELAS as TITIPAN_KELAS',
                'kunjungan.STATUS as STATUS_AKTIFITAS_KUNJUNGAN',
                'final_kunjungan.NAMA as FINAL_HASIL_OLEH',
                'kunjungan.FINAL_HASIL_TANGGAL as FINAL_HASIL_TANGGAL',
                'perubahan_tanggal_kunjungan.TANGGAL_LAMA as TANGGAL_KUNJUNGAN_LAMA',
                'perubahan_tanggal_kunjungan.TANGGAL_BARU as TANGGAL_KUNJUNGAN_BARU',
                'perubahan_oleh.NAMA as PERUBAHAN_KUNJUNGAN_OLEH',
                'perubahan_tanggal_kunjungan.STATUS as STATUS_PERUBAHAN_KUNJUNGAN',
            ])
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->leftJoin('master.ruang_kamar as ruang_kamar', 'ruang_kamar.RUANGAN', '=', 'kunjungan.RUANGAN')
            ->leftJoin('master.ruang_kamar_tidur as ruang_kamar_tidur', 'ruang_kamar_tidur.ID', '=', 'kunjungan.RUANG_KAMAR_TIDUR')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'kunjungan.DPJP')
            ->leftJoin('master.diagnosa_masuk as diagnosa_masuk', 'diagnosa_masuk.ID', '=', 'pendaftaran.DIAGNOSA_MASUK')
            ->leftJoin('aplikasi.pengguna as penerima_kunjungan', 'penerima_kunjungan.ID', '=', 'kunjungan.DITERIMA_OLEH')
            ->leftJoin('aplikasi.pengguna as final_kunjungan', 'final_kunjungan.ID', '=', 'kunjungan.FINAL_HASIL_OLEH')
            ->leftJoin('pendaftaran.perubahan_tanggal_kunjungan AS perubahan_tanggal_kunjungan', 'perubahan_tanggal_kunjungan.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('aplikasi.pengguna AS perubahan_oleh', 'perubahan_oleh.ID', '=', 'perubahan_tanggal_kunjungan.OLEH')
            ->where('kunjungan.NOMOR', $id)
            ->distinct()
            ->firstOrFail();
    }

    public function edit($id)
    {
        $kunjunganModel = PendaftaranKunjunganModel::where('NOMOR', $id)->firstOrFail();

        $this->authorize('update', $kunjunganModel);

        // Ambil detail kunjungan
        $query = $this->getDetailKunjungan($id);

        if (!$query) {
            return redirect()
                ->route('toolsKunjungan.index')
                ->with('error', 'Data tidak ditemukan.');
        }

        $ruangan = DB::connection('mysql2')->table('master.ruangan as ruangan')
            ->select(
                'ruangan.ID as id',
                'ruangan.DESKRIPSI as namaRuangan',
            )
            ->where('ruangan.STATUS', 1)
            ->where('ruangan.JENIS', 5)
            ->whereIn('ruangan.JENIS_KUNJUNGAN', [1, 2, 3])
            ->orderBy('ruangan.ID')
            ->orderBy('ruangan.DESKRIPSI')
            ->get();

        return inertia('Tools/Kunjungan/Edit', [
            'kunjungan' => [
                'nomor_kunjungan'   => $query->NOMOR_KUNJUNGAN,
                'nomor_pendaftaran' => $query->NOMOR_PENDAFTARAN,
                'norm'              => $query->NORM,
                'nama_pasien'       => $query->NAMA_PASIEN,
                'dpjp'              => $query->DPJP,
                'ruangan_id'        => $query->ID_RUANGAN,
                'ruangan_tujuan'    => $query->RUANGAN_TUJUAN,
                'tanggal_masuk'     => $query->TANGGAL_MASUK,
                'tanggal_keluar'    => $query->TANGGAL_KELUAR,
                'status_kunjungan'  => $query->STATUS_AKTIFITAS_KUNJUNGAN,
            ],
            'ruanganList' => $ruangan,
        ]);
    }

    public function update(Request $request, $nomor)
    {
        // 🔐 Ambil model & authorize
        $kunjunganModel = PendaftaranKunjunganModel::where('NOMOR', $nomor)->firstOrFail();
        $this->authorize('update', $kunjunganModel);

        // ✅ Validasi input
        $validated = $request->validate([
            'tanggal_masuk'    => ['required', 'date'],
            'tanggal_keluar'   => ['nullable', 'date', 'after_or_equal:tanggal_masuk'],
            'status_kunjungan' => ['required', 'integer'],
            'ruangan_id'       => ['required', 'string'],
        ]);

        DB::beginTransaction();

        try {
            // 📌 Ambil data kunjungan saat ini
            $current = DB::connection('mysql5')
                ->table('pendaftaran.kunjungan')
                ->where('NOMOR', $nomor)
                ->first([
                    'MASUK',
                    'KELUAR',
                    'STATUS',
                    'RUANGAN',
                    'NOPEN',
                ]);

            if (!$current) {
                throw new \Exception('Data kunjungan tidak ditemukan');
            }

            // 🕒 Normalisasi tanggal
            $tanggal_masuk  = Carbon::parse($validated['tanggal_masuk'])->format('Y-m-d H:i:s');
            $tanggal_keluar = $validated['tanggal_keluar']
                ? Carbon::parse($validated['tanggal_keluar'])->format('Y-m-d H:i:s')
                : null;

            // 🧾 Siapkan data update kunjungan
            $updateData = [];

            if ($tanggal_masuk !== $current->MASUK) {
                $updateData['MASUK'] = $tanggal_masuk;
            }

            if ($tanggal_keluar !== $current->KELUAR) {
                $updateData['KELUAR'] = $tanggal_keluar;
            }

            if ((int) $validated['status_kunjungan'] !== (int) $current->STATUS) {
                $updateData['STATUS'] = $validated['status_kunjungan'];
            }

            if ($validated['ruangan_id'] !== $current->RUANGAN) {
                $updateData['RUANGAN'] = $validated['ruangan_id'];
            }

            // 🔄 Update tabel kunjungan
            if (!empty($updateData)) {
                DB::connection('mysql5')
                    ->table('kunjungan')
                    ->where('NOMOR', $nomor)
                    ->update($updateData);

                // 1️⃣ Update tujuan_pasien jika RUANGAN berubah
                if (
                    array_key_exists('RUANGAN', $updateData) &&
                    !empty($current->NOPEN)
                ) {
                    $affected = DB::connection('mysql5')
                        ->table('tujuan_pasien')
                        ->where('NOPEN', $current->NOPEN)
                        ->update([
                            'RUANGAN' => $validated['ruangan_id'],
                        ]);

                    if ($affected === 0) {
                        Log::warning('Ruangan tujuan tidak ter-update', [
                            'nomor_kunjungan' => $nomor,
                            'nopen' => $current->NOPEN,
                        ]);
                    }
                }

                // 2️⃣ Update pendaftaran jika MASUK berubah
                if (
                    array_key_exists('MASUK', $updateData) &&
                    !empty($current->NOPEN)
                ) {
                    $affected = DB::connection('mysql5')
                        ->table('pendaftaran')
                        ->where('NOMOR', $current->NOPEN)
                        ->update(['TANGGAL' => $tanggal_masuk]);

                    if ($affected === 0) {
                        Log::warning('Pendaftaran tidak ter-update', [
                            'nomor_kunjungan' => $nomor,
                            'nopen' => $current->NOPEN,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('toolsKunjungan.index')
                ->with('success', 'Data kunjungan berhasil diperbarui');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal update kunjungan', [
                'nomor'   => $nomor,
                'payload' => $request->all(),
                'error'   => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'update' => 'Gagal menyimpan data. Perubahan dibatalkan.'
                ]);
        }
    }
}