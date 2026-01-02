<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangRuanganController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql3')->table('inventory.barang_ruangan as barangruangan')
            ->select(
                'barang.ID as id',
                'ruangan.DESKRIPSI as namaRuangan',
                'barangruangan.ID as id_ruangan',
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
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(barang.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(ruangan.DESKRIPSI) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('CAST(barangruangan.ID AS CHAR) = ?', [$searchSubject]);
            })
                ->whereRaw('CAST(barangruangan.RUANGAN AS CHAR) LIKE ?', ['10216%']);
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Inventory/Ruangan/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}