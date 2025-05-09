<?php

namespace App\Http\Controllers\Pendaftaran;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PendaftaranController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select(
                'pendaftaran.NOMOR as nomor',
                'pendaftaran.NORM as norm',
                DB::raw('master.getNamaLengkap(pasien.NORM) as nama'),
                'pendaftaran.TANGGAL as tanggal',
                'penjamin.NOMOR as penjamin',
                'pendaftaran.STATUS as status',
                'ruangan.DESKRIPSI as ruangan',
            )
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.kartu_identitas_pasien as kip', 'pendaftaran.NORM', '=', 'kip.NORM')
            ->leftJoin('bpjs.peserta as peserta', 'pendaftaran.NORM', '=', 'peserta.norm')
            ->leftJoin('pendaftaran.penjamin as penjamin', 'penjamin.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('pendaftaran.tujuan_pasien as tujuan_pasien', 'tujuan_pasien.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'tujuan_pasien.RUANGAN')
            ->where('pasien.STATUS', 1);

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pendaftaran.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pendaftaran.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('pendaftaran.TANGGAL')->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Hitung rata-rata
        $rataRata = $this->rataRata();

        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->whereIn('JENIS_KUNJUNGAN', [1, 2, 3, 4, 5])
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        // Return Inertia view with paginated data and rata-rata
        return inertia("Pendaftaran/Pendaftaran/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'rataRata' => $rataRata, // Pass rata-rata data to frontend
            'ruangan' => $ruangan,
            'queryParams' => request()->all()
        ]);
    }

    protected function rataRata()
    {
        return DB::connection('mysql5')->table('pendaftaran.pendaftaran as pendaftaran')
            ->selectRaw('
                ROUND(COUNT(*) / COUNT(DISTINCT DATE(pendaftaran.TANGGAL))) AS rata_rata_per_hari,
                ROUND(COUNT(*) / COUNT(DISTINCT WEEK(pendaftaran.TANGGAL, 1))) AS rata_rata_per_minggu,
                ROUND(SUM(CASE WHEN pendaftaran.TANGGAL IS NOT NULL THEN 1 ELSE 0 END) / COUNT(DISTINCT DATE_FORMAT(pendaftaran.TANGGAL, "%Y-%m"))) AS rata_rata_per_bulan,
                ROUND(COUNT(*) / COUNT(DISTINCT YEAR(pendaftaran.TANGGAL))) AS rata_rata_per_tahun
            ')
            ->whereIn('pendaftaran.STATUS', [1, 2])
            ->where('pendaftaran.TANGGAL', '>', '0000-00-00')
            ->whereNotNull('pendaftaran.TANGGAL')
            ->whereYear('pendaftaran.TANGGAL', now()->year)
            ->first();
    }

    public function filterByTime($filter)
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Determine the date filter based on the type
        $query = DB::connection('mysql5')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select(
                'pendaftaran.NOMOR as nomor',
                'pendaftaran.NORM as norm',
                DB::raw('master.getNamaLengkap(pasien.NORM) as nama'),
                'pendaftaran.TANGGAL as tanggal',
                'penjamin.NOMOR as penjamin',
                'pendaftaran.STATUS as status',
            )
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.kartu_identitas_pasien as kip', 'pendaftaran.NORM', '=', 'kip.NORM')
            ->leftJoin('bpjs.peserta as peserta', 'pendaftaran.NORM', '=', 'peserta.norm')
            ->leftJoin('pendaftaran.penjamin as penjamin', 'penjamin.NOPEN', '=', 'pendaftaran.NOMOR')
            ->where('pasien.STATUS', 1);

        // Clone query for count calculation
        $countQuery = clone $query;

        switch ($filter) {
            case 'hariIni':
                $query->whereDate('pendaftaran.TANGGAL', now()->format('Y-m-d'));
                $countQuery->whereDate('pendaftaran.TANGGAL', now()->format('Y-m-d'));
                $header = 'HARI INI';
                break;

            case 'mingguIni':
                $query->whereBetween('pendaftaran.TANGGAL', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $countQuery->whereBetween('pendaftaran.TANGGAL', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $header = 'MINGGU INI';
                break;

            case 'bulanIni':
                $query->whereMonth('pendaftaran.TANGGAL', now()->month)
                    ->whereYear('pendaftaran.TANGGAL', now()->year);
                $countQuery->whereMonth('pendaftaran.TANGGAL', now()->month)
                    ->whereYear('pendaftaran.TANGGAL', now()->year);
                $header = 'BULAN INI';
                break;

            case 'tahunIni':
                $query->whereYear('pendaftaran.TANGGAL', now()->year);
                $countQuery->whereYear('pendaftaran.TANGGAL', now()->year);
                $header = 'TAHUN INI';
                break;

            default:
                abort(404, 'Filter not found');
        }

        // Get count
        $count = $countQuery->count();

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pendaftaran.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pendaftaran.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('pendaftaran.NOMOR')->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Hitung rata-rata
        $rataRata = $this->rataRata();

        // Return Inertia view with paginated data and rata-rata
        return inertia("Pendaftaran/Pendaftaran/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'rataRata' => $rataRata, // Pass rata-rata data to frontend
            'queryParams' => request()->all(),
            'header' => $header,
            'totalCount' => number_format($count, 0, ',', '.'),
        ]);
    }

    public function detail($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')
            ->table('pendaftaran.pendaftaran as pendaftaran')
            ->select([
                'pendaftaran.NOMOR as NOMOR_PENDAFTARAN',
                'pendaftaran.NORM as NORM',
                DB::raw('master.getNamaLengkap(pasien.NORM) as NAMA_PASIEN'),
                'pasien.TEMPAT_LAHIR as TEMPAT_LAHIR',
                'pasien.TANGGAL_LAHIR as TANGGAL_LAHIR',
                'kartu_asuransi_pasien.NOMOR as NOMOR_ASURANSI',
                'agama.DESKRIPSI as AGAMA',
                'kelamin.DESKRIPSI as JENIS_KELAMIN',
                'pendidikan.DESKRIPSI as PENDIDIKAN',
                'wilayah.DESKRIPSI as WILAYAH',
                'diagnosa_masuk.ICD as DIAGNOSA_MASUK_ICD',
                'mrconso.STR as DIAGNOSA_MASUK_STR',
                'penanggung_jawab_pasien.NAMA as PENANGGUNG_JAWAB_PASIEN',
                'kip_penanggung_jawab_pasien.NOMOR as IDENTITAS_PENANGGUNG_JAWAB_PASIEN',
                'ruangan.DESKRIPSI as RUANGAN_TUJUAN',
                DB::raw('master.getNamaLengkapPegawai(dokter.NIP) as DPJP'),
                'pendaftaran.STATUS as STATUS_PASIEN',
                'surat_rujukan_pasien.NOMOR as NOMOR_RUJUKAN_PASIEN',
                'surat_rujukan_pasien.TANGGAL as TANGGAL_RUJUKAN_PASIEN',
                'ppk.NAMA as FASKES_PERUJUK',
                'pengantar_pasien.NAMA as PENGANTAR_PASIEN',
                'penjamin.NOMOR as NOMOR_PENJAMIN',
                'jenis_peserta_penjamin.DESKRIPSI as JENIS_PESERTA_PENJAMIN',
                'penjamin.NO_SURAT as NOMOR_SURAT_PENJAMIN',
                'dpjp.nama as DPJP_PENJAMIN',
                'kecelakaan.NOMOR as KECELAKAAN_NOMOR',
                'kecelakaan_jenis.DESKRIPSI as JENIS_KECELAKAAN',
                'kecelakaan.NO_LP as LAPORAN_POLISI',
                'kecelakaan.LOKASI as LOKASI_KECELAKAAN',
                'kecelakaan.TANGGAL_KEJADIAN as TANGGAL_KEJADIAN',
                'pendaftaran.PAKET as PAKET',
                'pendaftaran.BERAT_BAYI as BERAT_BAYI',
                'pendaftaran.PANJANG_BAYI as PANJANG_BAYI',
                'pendaftaran.CITO as CITO',
                'pendaftaran.RESIKO_JATUH as RESIKO_JATUH',
                'pendaftaran.LOKASI_DITEMUKAN as LOKASI_DITEMUKAN',
                'pendaftaran.TANGGAL_DITEMUKAN as TANGGAL_DITEMUKAN',
                'pendaftaran.JAM_LAHIR as JAM_LAHIR',
                'pendaftaran.CONSENT_SATUSEHAT as CONSENT_SATUSEHAT',
                'pendaftaran.PAKET as PAKET',
                'pengguna.NAMA as OLEH',
                'pendaftaran.STATUS as STATUS_PENDAFTARAN',
                'diagnosa.KODE as KODE_DIAGNOSA',
                'diagnosa.DIAGNOSA as DESKRIPSI_DIAGNOSA',
                'diagnosa.UTAMA as DIAGNOSA_UTAMA',
                'diagnosa.INACBG as DIAGNOSA_INACBG',
                'diagnosa.BARU as DIAGNOSA_BARU',
                'diagnosa.TANGGAL as TANGGAL_DIAGNOSA',
                'diagnosaOleh.NAMA as DIAGNOSA_OLEH',
                'diagnosa.STATUS as STATUS_DIAGNOSA',
                'diagnosa.INA_GROUPER as INA_GROUPER_DIAGNOSA',
                'prosedur.KODE as KODE_PROSEDUR',
                'prosedur.TINDAKAN as PROSEDUR_TINDAKAN',
                'prosedur.INACBG as PROSEDUR_INACBG',
                'prosedur.TANGGAL as TANGGAL_PROSEDUR',
                'prosedurOleh.NAMA as PROSEDUR_OLEH',
                'prosedur.STATUS as STATUS_PROSEDUR',
                'prosedur.INA_GROUPER as INA_GROUPER_PROSEDUR',
            ])
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.kartu_asuransi_pasien as kartu_asuransi_pasien', 'pasien.NORM', '=', 'kartu_asuransi_pasien.NORM')
            ->leftJoin('master.referensi as kelamin', function ($join) {
                $join->on('pasien.JENIS_KELAMIN', '=', 'kelamin.ID')
                    ->where('kelamin.JENIS', '=', 2);
            })
            ->leftJoin('master.referensi as agama', function ($join) {
                $join->on('agama.ID', '=', 'pasien.AGAMA')
                    ->where('agama.JENIS', '=', 1);
            })
            ->leftJoin('master.referensi as pendidikan', function ($join) {
                $join->on('pendidikan.ID', '=', 'pasien.PENDIDIKAN')
                    ->where('pendidikan.JENIS', '=', 3);
            })
            ->leftJoin('master.wilayah as wilayah', 'wilayah.ID', '=', 'pasien.WILAYAH')
            ->leftJoin('pendaftaran.penanggung_jawab_pasien as penanggung_jawab_pasien', 'penanggung_jawab_pasien.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('pendaftaran.kartu_identitas_penanggung_jawab as kip_penanggung_jawab_pasien', 'kip_penanggung_jawab_pasien.PENANGGUNG_JAWAB_ID', '=', 'penanggung_jawab_pasien.ID')
            ->leftJoin('pendaftaran.tujuan_pasien as tujuan_pasien', 'tujuan_pasien.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'tujuan_pasien.RUANGAN')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'tujuan_pasien.DOKTER')
            ->leftJoin('master.diagnosa_masuk as diagnosa_masuk', 'diagnosa_masuk.ID', '=', 'pendaftaran.DIAGNOSA_MASUK')
            ->leftJoin('master.mrconso as mrconso', 'mrconso.CODE', '=', 'diagnosa_masuk.ICD')
            ->leftJoin('pendaftaran.surat_rujukan_pasien as surat_rujukan_pasien', 'surat_rujukan_pasien.ID', '=', 'pendaftaran.RUJUKAN')
            ->leftJoin('master.ppk as ppk', 'ppk.ID', '=', 'surat_rujukan_pasien.PPK')
            ->leftJoin('pendaftaran.pengantar_pasien as pengantar_pasien', 'pengantar_pasien.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('pendaftaran.penjamin as penjamin', 'penjamin.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('master.jenis_peserta_penjamin as jenis_peserta_penjamin', 'jenis_peserta_penjamin.ID', '=', 'penjamin.JENIS_PESERTA')
            ->leftJoin('bpjs.dpjp as dpjp', 'dpjp.kode', '=', 'penjamin.DPJP')
            ->leftJoin('pendaftaran.kecelakaan as kecelakaan', 'kecelakaan.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('master.referensi as kecelakaan_jenis', function ($join) {
                $join->on('kecelakaan_jenis.ID', '=', 'kecelakaan.JENIS')
                    ->where('kecelakaan_jenis.JENIS', '=', 212);
            })
            ->leftJoin('medicalrecord.diagnosa as diagnosa', 'diagnosa.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('medicalrecord.prosedur as prosedur', 'prosedur.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'pendaftaran.OLEH')
            ->leftJoin('aplikasi.pengguna as diagnosaOleh', 'diagnosaOleh.ID', '=', 'diagnosa.OLEH')
            ->leftJoin('aplikasi.pengguna as prosedurOleh', 'prosedurOleh.ID', '=', 'prosedur.OLEH')
            ->where('pendaftaran.NOMOR', $id)
            ->distinct()
            ->firstOrFail();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('pendaftaran.index')->with('error', 'Data not found.');
        }

        //nomor pendaftaran
        $noPendaftaran = $query->NOMOR_PENDAFTARAN;

        // Return Inertia view with the encounter data
        return inertia("Pendaftaran/Pendaftaran/Detail", [
            'detail'            => $query,
            'nomorPendaftaran'  => $noPendaftaran,
        ]);
    }

    public function print(Request $request)
    {
        // Validasi input
        $request->validate([
            'ruangan' => 'nullable|string',
            'statusKunjungan' => 'nullable|integer|in:1,2,3',
            'dari_tanggal' => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        // Ambil nilai input
        $ruangan = $request->input('ruangan');
        $statusKunjungan = $request->input('statusKunjungan');
        $dariTanggal = $request->input('dari_tanggal');
        $sampaiTanggal = $request->input('sampai_tanggal');
        $dariTanggal = Carbon::parse($dariTanggal)->format('Y-m-d H:i:s');
        $sampaiTanggal = Carbon::parse($sampaiTanggal)->endOfDay()->format('Y-m-d H:i:s');

        // Variabel default untuk label
        $namaRuangan = 'Semua Ruangan';
        $namaStatusPendaftaran = 'Semua Status Aktifitas Pendaftaran';

        // Query utama
        $query = DB::connection('mysql5')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select(
                'pendaftaran.NOMOR as nomor',
                'pendaftaran.NORM as norm',
                DB::raw('master.getNamaLengkap(pasien.NORM) as nama'),
                'pendaftaran.TANGGAL as tanggal',
                'penjamin.NOMOR as penjamin',
                'pendaftaran.STATUS as status',
                'ruangan.DESKRIPSI as ruangan',
            )
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.kartu_identitas_pasien as kip', 'pendaftaran.NORM', '=', 'kip.NORM')
            ->leftJoin('bpjs.peserta as peserta', 'pendaftaran.NORM', '=', 'peserta.norm')
            ->leftJoin('pendaftaran.penjamin as penjamin', 'penjamin.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('pendaftaran.tujuan_pasien as tujuan_pasien', 'tujuan_pasien.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'tujuan_pasien.RUANGAN')
            ->where('pasien.STATUS', 1);

        // Filter berdasarkan ruangan
        if (!empty($ruangan)) {
            $query->where('ruangan.ID', $ruangan);

            // Mendapatkan nama ruangan yang sesuai dari hasil query
            $namaRuangan = DB::connection('mysql5')->table('master.ruangan')
                ->where('ID', $ruangan)
                ->value('DESKRIPSI');
        }

        //Filter berdasarkan jenis kunjungan
        if ($statusKunjungan == null) {
            $query->whereIn('pendaftaran.STATUS', [0, 1, 2]);
        } elseif ($statusKunjungan == 1) {
            $query->where('pendaftaran.STATUS', 0); // Batal Kunjungan
            $namaStatusPendaftaran = 'Batal';
        } elseif ($statusKunjungan == 2) {
            $query->where('pendaftaran.STATUS', 1); // Sedang Dilayani
            $namaStatusPendaftaran = 'Aktif';
        } elseif ($statusKunjungan == 3) {
            $query->where('pendaftaran.STATUS', 2); // Selesai
            $namaStatusPendaftaran = 'Selesai';
        }

        // Filter berdasarkan tanggal
        $data = $query->whereBetween('pendaftaran.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->orderBy('pendaftaran.TANGGAL')
            ->get();

        // Kirim data ke frontend menggunakan Inertia
        return inertia("Pendaftaran/Pendaftaran/Print", [
            'data' => $data,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
            'namaRuangan' => $namaRuangan,
            'namaStatusPendaftaran' => $namaStatusPendaftaran,
        ]);
    }
}
