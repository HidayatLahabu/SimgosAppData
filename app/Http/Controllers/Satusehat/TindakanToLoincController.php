<?php

namespace App\Http\Controllers\Satusehat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SatusehatLoincTerminologiModel;

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

    public function detail($id)
    {
        // Fetch data utama (main lab order details)
        $queryDetail = DB::connection('mysql4')->table('kemkes-ihs.tindakan_to_loinc as tindakan')
            ->select(
                'tindakan.TINDAKAN as tindakan_id',
                'master.NAMA as tindakan_nama',
                'tindakan.LOINC_TERMINOLOGI as loinc_terminologi',
                'specimen.display as specimen',
                'kategori.display as kategori',
                'tindakan.STATUS as status'
            )
            ->leftJoin('master.tindakan as master', 'master.ID', '=', 'tindakan.TINDAKAN')
            ->leftJoin('kemkes-ihs.loinc_terminologi as loinc', 'loinc.id', '=', 'tindakan.LOINC_TERMINOLOGI')
            ->leftJoin('kemkes-ihs.type_code_reference as specimen', 'specimen.id', '=', 'tindakan.SPESIMENT')
            ->leftJoin('kemkes-ihs.type_code_reference as kategori', 'kategori.id', '=', 'tindakan.KATEGORI')
            ->where('specimen.type', 52)
            ->where('kategori.type', 58)
            ->where('tindakan.TINDAKAN', $id)
            ->first();

        // Error handling: No data found
        if (!$queryDetail) {
            return redirect()->route('tindakanToLoinc.index')->with('error', 'Data tidak ditemukan.');
        }

        $loincId = $queryDetail->loinc_terminologi;
        $queryLoinc = SatusehatLoincTerminologiModel::where('id', $loincId)->first();

        // Return both data to the view
        return inertia("Satusehat/Tindakan/Detail", [
            'detail' => $queryDetail,
            'detailLoinc' => $queryLoinc,
        ]);
    }
}