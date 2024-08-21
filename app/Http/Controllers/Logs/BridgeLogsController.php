<?php

namespace App\Http\Controllers\Logs;

use App\Http\Controllers\Controller;
use App\Models\LogsBridgeModel;
use Illuminate\Http\Request;

class BridgeLogsController extends Controller
{
    public function index()
    {
        // Query untuk mengambil 100 data berdasarkan tanggal hari ini
        $query = LogsBridgeModel::orderByDesc('TGL_REQUEST')
            ->take(50); // Ambil hanya 100 data

        // Apply search filter jika parameter 'nama' ada
        if (request('nama')) {
            $searchSubject = strtolower(request('nama')); // Convert search term ke lowercase
            $query->whereRaw('LOWER(URL) LIKE ?', ['%' . $searchSubject . '%']); // Convert kolom ke lowercase untuk perbandingan
        }

        // Get all results first and then paginate manually
        $results = $query->get();
        $data = new \Illuminate\Pagination\LengthAwarePaginator(
            $results->forPage(request()->get('page', 1), 10),
            $results->count(),
            10,
            request()->get('page', 1),
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Convert data ke array
        $dataArray = $data->toArray();

        // Return Inertia view dengan data yang sudah dipaginasi
        return inertia("Logs/Bridge/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Hanya data yang sudah dipaginasi
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}