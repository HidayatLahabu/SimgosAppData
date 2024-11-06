<?php

namespace App\Http\Controllers\Satusehat;

use App\Http\Controllers\Controller;
use App\Models\SatusehatObservationModel;
use Illuminate\Http\Request;

class ObservationController extends Controller
{
    public function index()
    {
        // Define base query
        $query = SatusehatObservationModel::orderByDesc('sendDate')->orderByDesc('id');

        // Apply search filter if 'subject' query parameter is present
        if (request('subject')) {
            $searchSubject = strtolower(request('subject')); // Convert search term to lowercase
            $query->whereRaw('LOWER(subject) LIKE ?', ['%' . $searchSubject . '%']); // Convert column to lowercase for comparison
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Satusehat/Observation/Index", [
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
        $query = SatusehatObservationModel::where('nopen', $id)->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the data was not found
            return redirect()->route('observation.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the data
        return inertia("Satusehat/Observation/Detail", [
            'detail' => $query,
        ]);
    }
}