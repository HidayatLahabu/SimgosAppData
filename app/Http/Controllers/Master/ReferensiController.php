<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasterReferensiModel;
use Illuminate\Http\Request;

class ReferensiController extends Controller
{
    public function index()
    {
        // Define base query
        $query = MasterReferensiModel::orderBy('TABEL_ID');

        // Apply search filter if 'subject' query parameter is present
        if (request('deskripsi')) {
            $searchSubject = strtolower(request('deskripsi')); // Convert search term to lowercase
            $query->whereRaw('LOWER(DESKRIPSI) LIKE ?', ['%' . $searchSubject . '%']); // Convert column to lowercase for comparison
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Master/Referensi/Index", [
            'referensi' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}