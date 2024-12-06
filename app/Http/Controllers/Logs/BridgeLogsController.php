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

        // Get today's date
        $today = now()->format('Y-m-d'); // 'Y-m-d' is the format for dates

        // Add filter for today's date
        $query->whereDate('TGL_REQUEST', $today);

        // Add limit to the query
        $query->limit(100); // Limit the results to 100 records

        // Paginate the results
        $data = $query->paginate(10); // 10 items per page

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with limited data
        return inertia("Logs/Bridge/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}
