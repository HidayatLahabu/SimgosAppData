<?php

namespace App\Http\Controllers\Bpjs;

use App\Http\Controllers\Controller;
use App\Models\BpjsMonitorRekonModel;
use Illuminate\Http\Request;

class MonitoringRekonController extends Controller
{
    public function index()
    {
        // Define base query
        $query = BpjsMonitorRekonModel::orderByDesc('tglRencanaKontrol');

        // Apply search filter if 'subject' query parameter is present
        if (request('nama')) {
            $searchSubject = strtolower(request('nama')); // Convert search term to lowercase
            $query->whereRaw('LOWER(nama) LIKE ?', ['%' . $searchSubject . '%']); // Convert column to lowercase for comparison
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Bpjs/Monitoring/Index", [
            'monitoringRekon' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}