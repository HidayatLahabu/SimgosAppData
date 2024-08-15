<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterReferensiModel;

class ReferensiController extends Controller
{

    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('deskripsi') ? strtolower(request('deskripsi')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql2')->table('master.referensi as referensi')
            ->select(
                'referensi.TABEL_ID as tabel_id',
                'referensi.ID as id',
                'referensi.DESKRIPSI as deskripsi',
                'jenis.DESKRIPSI as jenis'
            )
            ->leftJoin('master.jenis_referensi as jenis', 'referensi.JENIS', '=', 'jenis.ID')
            ->orderBy('referensi.TABEL_ID');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(referensi.DESKRIPSI) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Master/Referensi/Index", [
            'referensi' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}