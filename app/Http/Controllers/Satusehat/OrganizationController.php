<?php

namespace App\Http\Controllers\Satusehat;

use Illuminate\Http\Request;
use App\Models\SatusehatOrganizationModel;
use App\Http\Controllers\Controller;

class OrganizationController extends Controller
{
    public function index()
    {
        // Define base query
        $query = SatusehatOrganizationModel::orderBy('refID');

        // Apply search filter if 'subject' query parameter is present
        if (request('name')) {
            $searchSubject = strtolower(request('name')); // Convert search term to lowercase
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . $searchSubject . '%']); // Convert column to lowercase for comparison
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Satusehat/Organization/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function detail($id)
    {
        // Fetch the specific encounter by ID
        $query = SatusehatOrganizationModel::where('refId', $id)->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('encounter.index')->with('error', 'Encounter not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Satusehat/Organization/Detail", [
            'detail' => $query,
        ]);
    }
}