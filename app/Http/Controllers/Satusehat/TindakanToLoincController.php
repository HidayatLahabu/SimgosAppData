<?php

namespace App\Http\Controllers\Satusehat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TindakanToLoincController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql4')->table('kemkes-ihs.tindakan_to_loinc as tindakan')
            ->select(
                'tindakan.TINDAKAN as tindakan_id',
                'master.NAMA as tindakan_nama',
                'tindakan.LOINC_TERMINOLOGI as loinc_id',
                'loinc.kategori_pemeriksaan as loinc_kategori',
                'loinc.nama_pemeriksaan as loinc_nama',
                'tindakan.STATUS as status'
            )
            ->leftJoin('master.tindakan as master', 'master.ID', '=', 'tindakan.TINDAKAN')
            ->leftJoin('kemkes-ihs.loinc_terminologi as loinc', 'loinc.id', '=', 'tindakan.LOINC_TERMINOLOGI');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(master.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderBy('master.NAMA')
            ->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Satusehat/Tindakan/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}