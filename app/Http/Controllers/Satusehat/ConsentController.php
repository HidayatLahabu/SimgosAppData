<?php

namespace App\Http\Controllers\Satusehat;

use App\Http\Controllers\Controller;
use App\Models\SatusehatConsentModel;
use Illuminate\Http\Request;

class ConsentController extends Controller
{
    public function index()
    {
        // Define base query
        $query = SatusehatConsentModel::orderByDesc('id')->orderByDesc('sendDate');

        // Apply search filter if 'subject' query parameter is present
        if (request('search')) {
            $searchSubject = strtolower(request('search')); // Convert search term to lowercase
            // $query->whereRaw('LOWER(name) LIKE ?', ['%' . $searchName . '%']); 
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(bodySend) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(norm) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Satusehat/Consent/Index", [
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
        $query = SatusehatConsentModel::where('refId', $id)->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the data was not found
            return redirect()->route('consent.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the data
        return inertia("Satusehat/Consent/Detail", [
            'detail' => $query,
        ]);
    }
}