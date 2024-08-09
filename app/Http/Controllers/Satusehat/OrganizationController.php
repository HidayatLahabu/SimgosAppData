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
            'organization' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}