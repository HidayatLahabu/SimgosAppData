<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuanganController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('deskripsi') ? strtolower(request('deskripsi')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql2')->table('master.ruangan as ruangan')
            ->select(
                'ruangan.ID as id',
                'ruangan.JENIS as jenisId',
                'jenis.DESKRIPSI as jenisNama',
                'ruangan.JENIS_KUNJUNGAN as jenisKunjungan',
                'ruangan.DESKRIPSI as namaRuangan',
            )
            ->leftJoin('master.jenis_ruangan as jenis', 'ruangan.JENIS', '=', 'jenis.ID')
            ->where('ruangan.STATUS', 1)
            ->orderBy('ruangan.JENIS')
            ->orderBy('ruangan.ID');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(ruangan.DESKRIPSI) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Master/Ruangan/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}
