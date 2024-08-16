<?php

namespace App\Http\Controllers\Pendaftaran;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KonsulController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.konsul as konsul')
            ->select(
                'konsul.NOMOR as nomor',
                'pasien.NAMA as nama',
                'ruanganAsal.DESKRIPSI as asal',
                'ruanganTujuan.DESKRIPSI as tujuan',
                'konsul.TANGGAL as tanggal'
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'konsul.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruanganAsal', 'ruanganAsal.ID', '=', 'kunjungan.RUANGAN')
            ->leftJoin('master.ruangan as ruanganTujuan', 'ruanganTujuan.ID', '=', 'konsul.TUJUAN')
            ->where('pasien.STATUS', 1);

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('konsul.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Konsul/Index", [
            'konsul' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}