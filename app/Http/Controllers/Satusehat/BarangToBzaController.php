<?php

namespace App\Http\Controllers\Satusehat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BarangToBzaController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql4')->table('kemkes-ihs.barang_to_bza as barangBza')
            ->select(
                'barangBza.ID as id',
                'barang.NAMA as barang',
                'bza.display as bzaDisplay',
                'barangBza.DOSIS_KFA as dosisKfa',
                'bza.unit_of_mesure as satuanKfa',
                'barangBza.DOSIS_PERSATUAN as dosisSatuan',
                'tor.display as satuan',
                'barangBza.STATUS as status'
            )
            ->leftJoin('inventory.barang as barang', 'barang.ID', '=', 'barangBza.BARANG')
            ->leftJoin('kemkes-ihs.bza as bza', 'bza.ID', '=', 'barangBza.KODE_BZA')
            ->leftJoin('kemkes-ihs.type_code_reference as tor', 'tor.ID', '=', 'barangBza.SATUAN')
            ->where('tor.TYPE', 19);

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(barang.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderBy('barang.NAMA')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Satusehat/BarangBza/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}
