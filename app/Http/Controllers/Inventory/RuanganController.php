<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RuanganController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('namaBarang') ? strtolower(request('namaBarang')) : null;
        $searchRuangan = request('namaRuangan') ? strtolower(request('namaRuangan')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql3')->table('inventory.barang_ruangan as barangruangan')
            ->select(
                'ruangan.DESKRIPSI as namaRuangan',
                'barang.NAMA as namaBarang',
                'satuan.NAMA as satuan',
                'barangruangan.STOK as stock',
                'barangruangan.TANGGAL as tanggal',
                'barang.ID as idBarang'
            )
            ->leftJoin('inventory.barang as barang', 'barangruangan.BARANG', '=', 'barang.ID')
            ->leftJoin('master.ruangan as ruangan', 'barangruangan.RUANGAN', '=', 'ruangan.ID')
            ->leftJoin('inventory.satuan as satuan', 'barang.SATUAN', '=', 'satuan.ID')
            ->where('barangruangan.STATUS', 1)
            ->orderBy('ruangan.ID')
            ->orderBy('barang.NAMA');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(barang.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
        }

        if ($searchRuangan) {
            $query->whereRaw('LOWER(ruangan.DESKRIPSI) LIKE ?', ['%' . $searchRuangan . '%']);
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Inventory/Ruangan/Index", [
            'ruangan' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}