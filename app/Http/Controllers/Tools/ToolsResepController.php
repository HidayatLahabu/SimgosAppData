<?php

namespace App\Http\Controllers\Tools;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ToolsResepController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql7')->table('layanan.order_resep as orderResep')
            ->select(
                'orderResep.NOMOR as nomor',
                'orderResep.TANGGAL as tanggal',
                'orderResep.KUNJUNGAN as kunjungan',
                'ruangan.DESKRIPSI as ruangan',
                DB::raw('master.getNamaLengkapPegawai(dokter.NIP) as orderOleh'),
                'pasien.NORM as norm',
                DB::raw('master.getNamaLengkap(pasien.NORM) as nama'),
                'peserta.noKartu'
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'orderResep.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'orderResep.DOKTER_DPJP')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'orderResep.TUJUAN')
            ->leftJoin('bpjs.peserta as peserta', 'pasien.NORM', '=', 'peserta.norm');

        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(orderResep.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('orderResep.TANGGAL')->paginate(7)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Tools/Resep/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function edit($id)
    {
        // Ambil 1 data order resep
        $orderResep = DB::connection('mysql7')
            ->table('layanan.order_resep as orderResep')
            ->select(
                'orderResep.NOMOR as nomor',
                'orderResep.TANGGAL as tanggal',
                'orderResep.KUNJUNGAN as kunjungan',
                'orderResep.TUJUAN as ruangan_id',
                'ruangan.DESKRIPSI as ruangan_tujuan',
                'pengguna.NAMA as pemberi_resep',
                'orderResep.BERAT_BADAN as berat_badan',
                'orderResep.TINGGI_BADAN as tinggi_badan',
                'pasien.NORM as norm',
                DB::raw('master.getNamaLengkap(pasien.NORM) as nama_pasien'),
                DB::raw('master.getNamaLengkapPegawai(dokter.NIP) as dpjp')
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'orderResep.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'orderResep.DOKTER_DPJP')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'orderResep.TUJUAN')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'orderResep.PEMBERI_RESEP')
            ->where('orderResep.NOMOR', $id)
            ->first();

        if (!$orderResep) {
            abort(404, 'Order resep tidak ditemukan');
        }

        // List ruangan (untuk select)
        $ruanganList = DB::connection('mysql7')
            ->table('master.ruangan')
            ->select(
                'ID as id',
                'DESKRIPSI as namaRuangan'
            )
            ->where('ID', 'like', '%10216%')
            ->where('ID', 'not like', '%102110216%')
            ->where('JENIS', 5)
            ->orderBy('DESKRIPSI')
            ->get();

        return inertia('Tools/Resep/Edit', [
            'kunjungan'   => $orderResep,
            'ruanganList' => $ruanganList,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ruangan_id' => 'required|string|max:10',
        ]);

        DB::connection('mysql7')
            ->table('layanan.order_resep')
            ->where('NOMOR', $id)
            ->update([
                'TUJUAN' => $request->ruangan_id,
            ]);

        return redirect()
            ->route('toolsResep.index')
            ->with('success', 'Ruangan tujuan resep berhasil diperbarui');
    }
}