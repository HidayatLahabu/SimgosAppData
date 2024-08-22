<?php

namespace App\Http\Controllers\Logs;

use App\Http\Controllers\Controller;
use App\Models\LogsBridgeModel;
use Illuminate\Http\Request;

class BridgeLogsController extends Controller
{
    public function index()
    {
        // Define base query
        $query = LogsBridgeModel::orderByDesc('TGL_REQUEST');

        // Apply search filter if 'subject' query parameter is present
        if (request('nama')) {
            $searchSubject = strtolower(request('nama')); // Convert search term to lowercase
            $query->whereRaw('LOWER(URL) LIKE ?', ['%' . $searchSubject . '%']); // Convert column to lowercase for comparison
        }

        // Limit the results to the last 50 rows
        $data = $query->take(50)->get();

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with limited data
        return inertia("Logs/Bridge/Index", [
            'dataTable' => [
                'data' => $dataArray, // Data without pagination
            ],
            'queryParams' => request()->all()
        ]);
    }

    // public function index()
    // {
    //     // Define base query
    //     $query = LogsBridgeModel::orderByDesc('TGL_REQUEST');

    //     // Apply search filter if 'subject' query parameter is present
    //     if (request('nama')) {
    //         $searchSubject = strtolower(request('nama')); // Convert search term to lowercase
    //         $query->whereRaw('LOWER(URL) LIKE ?', ['%' . $searchSubject . '%']); // Convert column to lowercase for comparison
    //     }

    //     // Paginate the results
    //     $data = $query->paginate(10)->appends(request()->query());

    //     // Convert data to array
    //     $dataArray = $data->toArray();

    //     // Return Inertia view with paginated data
    //     return inertia("Logs/Bridge/Index", [
    //         'dataTable' => [
    //             'data' => $dataArray['data'], // Hanya data yang sudah dipaginasi
    //             'links' => $dataArray['links'], // Pagination links
    //         ],
    //         'queryParams' => request()->all()
    //     ]);
    // }
}