<?php

namespace App\Http\Controllers\Pendaftaran;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class KunjunganController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select(
                'kunjungan.NOMOR as nomor',
                'pasien.NAMA as nama',
                'pasien.NORM as norm',
                'ruangan.DESKRIPSI as ruangan',
                'kunjungan.MASUK as masuk',
                'kunjungan.KELUAR as keluar'
            )
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->where('pasien.STATUS', 1);

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('kunjungan.MASUK')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Kunjungan/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}