<?php

namespace App\Http\Controllers\Satusehat;

use App\Http\Controllers\Controller;
use App\Models\SatusehatCompositionModel;
use Illuminate\Http\Request;

class CompositionController extends Controller
{
    public function index()
    {
        // Define base query
        $query = SatusehatCompositionModel::orderByDesc('id')->orderByDesc('sendDate');

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
        return inertia("Satusehat/Composition/Index", [
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
        $query = SatusehatCompositionModel::where('nopen', $id)->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('condition.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Satusehat/Composition/Detail", [
            'detail' => $query,
        ]);
    }
}