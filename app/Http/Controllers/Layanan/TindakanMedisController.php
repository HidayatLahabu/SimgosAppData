<?php

namespace App\Http\Controllers\Layanan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TindakanMedisController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql7')->table('layanan.tindakan_medis as tindakan')
            ->select(
                'tindakan.ID as id',
                'tindakan.TANGGAL as tanggal',
                'tindakan.KUNJUNGAN as kunjungan',
                'pengguna.NAMA as pelaksana',
                'pasien.NORM as norm',
                'pasien.NAMA as nama',
                'masterTindakan.NAMA as jenisTindakan',
                'tindakan.STATUS as statusHasil'
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'tindakan.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'tindakan.OLEH')
            ->leftJoin('master.tindakan as masterTindakan', 'tindakan.TINDAKAN', '=', 'masterTindakan.ID')
            ->where('tindakan.STATUS', 1);

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pasien.nama) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('tindakan.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Layanan/Tindakan/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}