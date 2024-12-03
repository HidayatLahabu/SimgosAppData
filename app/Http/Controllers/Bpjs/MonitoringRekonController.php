<?php

namespace App\Http\Controllers\Bpjs;

use App\Http\Controllers\Controller;
use App\Models\BpjsMonitorRekonModel;
use Illuminate\Http\Request;

class MonitoringRekonController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Define base query
        $query = BpjsMonitorRekonModel::orderByDesc('tglRencanaKontrol');

        // Apply search filter if 'subject' query parameter is present
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(noSuratKontrol) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(nama) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(noSepAsalKontrol) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(namaDokter) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Bpjs/Monitoring/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function detail($id)
    {
        // Fetch the specific data
        $query = BpjsMonitorRekonModel::where('noSuratKontrol', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('monitoringRekon.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Bpjs/Monitoring/Detail", [
            'detail' => $query,
        ]);
    }
}
