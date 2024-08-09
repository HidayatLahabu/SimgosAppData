<?php

namespace App\Http\Controllers\Satusehat;

use App\Http\Controllers\Controller;
use App\Models\SatusehatMedicationModel;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function index()
    {
        // Define base query
        $query = SatusehatMedicationModel::orderByDesc('id')->orderByDesc('sendDate');

        // Apply search filter if 'subject' query parameter is present
        if (request('manufacturer')) {
            $searchSubject = strtolower(request('manufacturer')); // Convert search term to lowercase
            $query->whereRaw('LOWER(manufacturer) LIKE ?', ['%' . $searchSubject . '%']); // Convert column to lowercase for comparison
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Satusehat/Medication/Index", [
            'medication' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}