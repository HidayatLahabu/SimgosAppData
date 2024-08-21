<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('namaRuangan') ? strtolower(request('namaRuangan')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql3')->table('inventory.stok_opname as stock')
            ->select(
                'stock.ID as id',
                'stock.TANGGAL as tanggal',
                'stock.TANGGAL_DIBUAT as dibuat',
                'stock.STATUS as status',
                'ruangan.DESKRIPSI as namaRuangan'
            )
            ->leftJoin('master.ruangan as ruangan', 'stock.RUANGAN', '=', 'ruangan.ID')
            ->orderBy('stock.TANGGAL');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(ruangan.DESKRIPSI) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Inventory/Stock/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function list($id)
    {
        // Start building the query using the query builder
        $query = DB::connection('mysql3')->table('inventory.stok_opname_detil as stockDetail')
            ->distinct()
            ->select(
                'barang.NAMA as nama',
                'satuan.NAMA as satuan',
                DB::raw('IFNULL(stockDetail.AWAL, 0) as awal'),
                DB::raw('IFNULL(stockDetail.MANUAL, 0) as manual'),
                DB::raw('IFNULL(stockDetail.BARANG_MASUK, 0) as masuk'),
                DB::raw('IFNULL(stockDetail.BARANG_KELUAR, 0) as keluar'),
                'barangRuangan.ID as idBarang',
                'stockDetail.ID as id'
            )
            ->leftJoin('inventory.stok_opname as stock', 'stockDetail.STOK_OPNAME', '=', 'stock.ID')
            ->leftJoin('inventory.barang_ruangan as barangRuangan', 'barangRuangan.ID', '=', 'stockDetail.BARANG_RUANGAN')
            ->leftJoin('inventory.barang as barang', 'barang.ID', '=', 'barangRuangan.BARANG')
            ->leftJoin('inventory.satuan as satuan', 'barang.SATUAN', '=', 'satuan.ID')
            ->where('stockDetail.STOK_OPNAME', $id)
            ->where('stockDetail.MANUAL', '>', 0)
            ->orderBy('barang.NAMA');

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Inventory/Stock/List", [
            'stockDetail' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
        ]);
    }
}