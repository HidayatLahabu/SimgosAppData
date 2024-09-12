<?php

namespace App\Http\Controllers\Pendaftaran;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MutasiController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.mutasi as mutasi')
            ->select(
                'mutasi.NOMOR as nomor',
                'pasien.NAMA as nama',
                'pasien.NORM as norm',
                'ruanganTujuan.DESKRIPSI as tujuan',
                'mutasi.TANGGAL as tanggal',
                'mutasi.RESERVASI as reservasi',
                'mutasi.STATUS as status',
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'mutasi.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruanganTujuan', 'ruanganTujuan.ID', '=', 'mutasi.TUJUAN')
            ->where('pasien.STATUS', 1);

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('mutasi.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Mutasi/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function detail($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('pendaftaran.mutasi as mutasi')
            ->select([
                'mutasi.NOMOR as NOMOR',
                'mutasi.KUNJUNGAN as KUNJUNGAN',
                'mutasi.TANGGAL as TANGGAL',
                'pasien.NORM as NORM',
                'pasien.NAMA as NAMA',
                'mutasi.STATUS as STATUS_MUTASI',
                'mutasi_oleh.NAMA as MUTASI_OLEH',
                'mutasi.STATUS as STATUS_MUTASI',
                'mutasi.RESERVASI as RESERVASI',
                'ruangan.DESKRIPSI as RUANGAN_TUJUAN',
                'ruang_kamar.KAMAR as KAMAR_TUJUAN',
                'ruang_kamar_tidur.TEMPAT_TIDUR as TEMPAT_TIDUR',
                'reservasi.ATAS_NAMA as ATAS_NAMA',
                'reservasi.KONTAK_INFO as NOMOR_KONTAK',
                'reservasi_oleh.NAMA as RESERVASI_OLEH',
                'reservasi.STATUS as STATUS_RESERVASI',
            ])
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'mutasi.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('pendaftaran.reservasi as reservasi', 'reservasi.NOMOR', '=', 'mutasi.RESERVASI')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'mutasi.TUJUAN')
            ->leftJoin('master.ruang_kamar_tidur as ruang_kamar_tidur', 'ruang_kamar_tidur.ID', '=', 'reservasi.RUANG_KAMAR_TIDUR')
            ->leftJoin('master.ruang_kamar as ruang_kamar', 'ruang_kamar.ID', '=', 'ruang_kamar_tidur.RUANG_KAMAR')
            ->leftJoin('aplikasi.pengguna as mutasi_oleh', 'mutasi_oleh.ID', '=', 'mutasi.OLEH')
            ->leftJoin('aplikasi.pengguna as reservasi_oleh', 'reservasi_oleh.ID', '=', 'reservasi.OLEH')
            ->where('mutasi.NOMOR', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('konsul.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Pendaftaran/Konsul/Detail", [
            'detail' => $query,
        ]);
    }
}