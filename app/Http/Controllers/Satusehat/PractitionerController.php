<?php

namespace App\Http\Controllers\Satusehat;

use App\Http\Controllers\Controller;
use App\Models\SatusehatPractitionerModel;
use Illuminate\Http\Request;

class PractitionerController extends Controller
{
    public function index()
    {
        // Define base query
        $query = SatusehatPractitionerModel::whereNotNull('id')->orderByDesc('getDate');

        // Apply search filter if 'name' query parameter is present
        if (request('name')) {
            $searchName = strtolower(request('name')); // Convert search term to lowercase
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . $searchName . '%']); // Convert column to lowercase for comparison
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Satusehat/Practitioner/Index", [
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
        $query = SatusehatPractitionerModel::where('refId', $id)->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the data was not found
            return redirect()->route('practitioner.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the data
        return inertia("Satusehat/Practitioner/Detail", [
            'detail' => $query,
        ]);
    }
}