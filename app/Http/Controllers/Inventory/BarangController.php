<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BarangController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql3')->table('inventory.barang as barang')
            ->select(
                'barang.ID as id',
                'barang.NAMA as nama',
                'kategori.NAMA as kategori',
                'satuan.NAMA as satuan',
                'merk.DESKRIPSI as merk',
                'rekanan.NAMA as rekanan',
                'barang.STOK as stock',
                'harga.HARGA_BELI as beli',
                'harga.HARGA_JUAL as jual'
            )
            ->leftJoin('inventory.kategori as kategori', 'barang.KATEGORI', '=', 'kategori.ID')
            ->leftJoin('inventory.satuan as satuan', 'barang.SATUAN', '=', 'satuan.ID')
            ->leftJoin('master.referensi as merk', 'barang.MERK', '=', 'merk.ID')
            ->leftJoin('inventory.penyedia as rekanan', 'barang.PENYEDIA', '=', 'rekanan.ID')
            ->leftJoin('inventory.harga_barang as harga', 'barang.ID', '=', 'harga.BARANG')
            ->where('barang.STATUS', 1)
            ->where('merk.JENIS', 39)
            ->where('harga.STATUS', 1)
            ->orderBy('barang.ID');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(barang.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Inventory/Barang/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function barang()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql3')->table('inventory.barang as barang')
            ->select(
                'barang.ID as id',
                'barang.NAMA as nama',
                'kategori.NAMA as kategori',
                'satuan.NAMA as satuan',
                'merk.DESKRIPSI as merk',
                'rekanan.NAMA as rekanan',
                'barang.STOK as stock',
                'harga.HARGA_BELI as beli',
                'harga.HARGA_JUAL as jual'
            )
            ->leftJoin('inventory.kategori as kategori', 'barang.KATEGORI', '=', 'kategori.ID')
            ->leftJoin('inventory.satuan as satuan', 'barang.SATUAN', '=', 'satuan.ID')
            ->leftJoin('master.referensi as merk', 'barang.MERK', '=', 'merk.ID')
            ->leftJoin('inventory.penyedia as rekanan', 'barang.PENYEDIA', '=', 'rekanan.ID')
            ->leftJoin('inventory.harga_barang as harga', 'barang.ID', '=', 'harga.BARANG')
            ->where('barang.STATUS', 1)
            ->where('merk.JENIS', 39)
            ->where('harga.STATUS', 1)
            ->orderBy('barang.ID');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(barang.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Inventory/Barang/List", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}