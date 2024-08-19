<?php

namespace App\Http\Controllers\Pendaftaran;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AntrianRuanganController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.antrian_ruangan as antrian')
            ->select(
                'antrian.ID as nomor',
                'antrian.TANGGAL as tanggal',
                'pendaftaran.NORM as norm',
                'pasien.NAMA as nama',
                'ruangan.DESKRIPSI as ruangan',
                'antrian.NOMOR as urut'
            )
            ->leftJoin('pendaftaran.pendaftaran AS pendaftaran', 'pendaftaran.NOMOR', '=', 'antrian.REF')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'antrian.RUANGAN')
            ->where('pasien.STATUS', 1);

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('antrian.TANGGAL')
            ->orderBy('ruangan.DESKRIPSI')
            ->orderBy('antrian.NOMOR')
            ->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Antrian/Index", [
            'antrian' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}