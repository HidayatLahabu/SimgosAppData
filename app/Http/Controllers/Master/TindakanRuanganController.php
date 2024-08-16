<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TindakanRuanganController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql2')->table('master.tindakan_ruangan as tindakanRuangan')
            ->select(
                'tindakanRuangan.ID as id',
                'tindakan.NAMA as nama',
                'tarif.TARIF as tarif',
                'referensi.DESKRIPSI as jenis',
                'ruangan.DESKRIPSI as ruangan',
                'tindakanRuangan.RUANGAN as id_ruangan'
            )
            ->distinct()
            ->leftJoin('master.tindakan as tindakan', 'tindakanRuangan.TINDAKAN', '=', 'tindakan.ID')
            ->leftJoin('master.ruangan as ruangan', 'tindakanRuangan.RUANGAN', '=', 'ruangan.ID')
            ->leftJoin('master.tarif_tindakan as tarif', 'tindakan.ID', '=', 'tarif.TINDAKAN')
            ->leftJoin('master.referensi as referensi', 'referensi.ID', '=', 'tindakan.JENIS')
            ->where('tindakanRuangan.STATUS', 1)
            ->where('tindakan.STATUS', 1)
            ->where('tarif.STATUS', 1)
            ->where('referensi.JENIS', 74)
            ->orderBy('tindakanRuangan.RUANGAN');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(tindakan.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Master/TindakanRuangan/Index", [
            'tindakanRuangan' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}