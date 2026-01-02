<?php

namespace App\Http\Controllers\Tools;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ToolsStockOpnameController extends Controller
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
            ->orderBy('stock.ID')
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
        return inertia("Tools/StockOpname/Index", [
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
                DB::raw('IFNULL(stockDetail.SISTEM, 0) as sistem'),
                'barangRuangan.ID as idBarang',
                'stockDetail.ID as idSod',
                'stock.ID as idSo',
                'ruangan.DESKRIPSI as ruangan'
            )
            ->leftJoin('inventory.stok_opname as stock', 'stockDetail.STOK_OPNAME', '=', 'stock.ID')
            ->leftJoin('inventory.barang_ruangan as barangRuangan', 'barangRuangan.ID', '=', 'stockDetail.BARANG_RUANGAN')
            ->leftJoin('inventory.barang as barang', 'barang.ID', '=', 'barangRuangan.BARANG')
            ->leftJoin('inventory.satuan as satuan', 'barang.SATUAN', '=', 'satuan.ID')
            ->leftJoin('master.ruangan as ruangan', 'barangRuangan.RUANGAN', '=', 'ruangan.ID')
            ->where('stockDetail.STOK_OPNAME', $id)
            ->orderByDesc('stockDetail.ID');

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Tools/StockOpname/List", [
            'stockDetail' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
        ]);
    }

    public function create($id)
    {
        // Ambil data stock opname
        $stokOpname = DB::connection('mysql3')->table('inventory.stok_opname as s')
            ->select(
                's.ID as idSo',
                's.TANGGAL as tanggal',
                'r.DESKRIPSI as nama_ruangan',
                's.RUANGAN as ruangan_id'
            )
            ->leftJoin('master.ruangan as r', 's.RUANGAN', '=', 'r.ID')
            ->where('s.ID', $id)
            ->first();

        if (!$stokOpname) {
            abort(404, "Stok Opname tidak ditemukan");
        }

        // Ambil semua barang_ruangan yang sudah ada di stok_opname_detil untuk stok opname ini
        $existingIds = DB::connection('mysql3')->table('inventory.stok_opname_detil')
            ->where('STOK_OPNAME', $id)
            ->pluck('BARANG_RUANGAN')
            ->toArray();

        // Ambil barang_ruangan untuk ruangan yang sama yang belum ada di stok_opname_detil
        $barangBaru = DB::connection('mysql3')->table('inventory.barang_ruangan as br')
            ->select(
                'br.ID as id_barang_ruangan',
                'b.NAMA as nama_barang',
                'r.DESKRIPSI as nama_ruangan'
            )
            ->leftJoin('inventory.barang as b', 'br.BARANG', '=', 'b.ID')
            ->leftJoin('master.ruangan as r', 'br.RUANGAN', '=', 'r.ID')
            ->where('br.RUANGAN', $stokOpname->ruangan_id) // pastikan ruangan sama
            ->when(!empty($existingIds), function ($query) use ($existingIds) {
                $query->whereNotIn('br.ID', $existingIds);
            })
            ->orderBy('b.NAMA')
            ->get();

        return inertia('Tools/StockOpname/Tambah', [
            'stokOpname' => $stokOpname,
            'barangBaru' => $barangBaru,
        ]);
    }

    public function store(Request $request, $id)
    {
        // Validasi sederhana
        $request->validate([
            'barang_ruangan_id' => 'required|integer',
            'tanggal' => 'required|date',
            'exd' => 'nullable|date',
            'awal' => 'nullable|numeric',
            'sistem' => 'nullable|numeric',
            'manual' => 'nullable|numeric',
            'barang_masuk' => 'nullable|numeric',
            'barang_keluar' => 'nullable|numeric',
            'oleh' => 'required|integer',
            'status' => 'required|integer',
        ]);

        try {
            // 1️⃣ Cek apakah barang ini sudah ada di stok opname detil
            $exists = DB::connection('mysql3')->table('inventory.stok_opname_detil')
                ->where('STOK_OPNAME', $id)
                ->where('BARANG_RUANGAN', $request->barang_ruangan_id)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('error', 'Barang ini sudah pernah diinput di stok opname ini.')
                    ->withInput();
            }

            // 2️⃣ Insert ke tabel stok_opname_detil
            DB::connection('mysql3')->table('inventory.stok_opname_detil')->insert([
                'STOK_OPNAME'    => $id,
                'BARANG_RUANGAN' => $request->barang_ruangan_id,
                'AWAL'           => $request->awal ?? 0.00,
                'SISTEM'         => $request->sistem ?? 0.00,
                'MANUAL'         => $request->manual ?? 0.00,
                'BARANG_MASUK'   => $request->barang_masuk ?? 0.00,
                'BARANG_KELUAR'  => $request->barang_keluar ?? 0.00,
                'TANGGAL'        => $request->tanggal ?? now()->toDateString(),
                'EXD'            => $request->exd ?? null,
                'OLEH'           => $request->oleh,
                'STATUS'         => $request->status,
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('toolsSO.list', ['id' => $id])
                ->with('success', 'Barang berhasil ditambahkan ke stok opname.');
        } catch (\Exception $e) {
            // Tangani error jika insert gagal
            \Log::error("Gagal menambahkan stok opname detil: " . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan barang: ' . $e->getMessage())
                ->withInput();
        }
    }
}