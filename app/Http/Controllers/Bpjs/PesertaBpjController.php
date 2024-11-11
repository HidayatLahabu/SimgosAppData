<?php

namespace App\Http\Controllers\Bpjs;

use App\Http\Controllers\Controller;
use App\Models\BpjsPesertaModel;
use Illuminate\Http\Request;

class PesertaBpjController extends Controller
{
    public function index()
    {
        // Define base query
        $query = BpjsPesertaModel::orderByDesc('tglTMT')->where('ketStatusPeserta', 'AKTIF');

        // Apply search filter if 'subject' query parameter is present
        if (request('search')) {
            $searchSubject = strtolower(request('search'));
            $query->where(function ($q) use ($searchSubject) {
                $q->where('noKartu', 'LIKE', '%' . $searchSubject . '%')
                    ->orWhere('nik', 'LIKE', '%' . $searchSubject . '%')
                    ->orWhere('nama', 'LIKE', '%' . $searchSubject . '%');
            });
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Bpjs/Peserta/Index", [
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
        $query = BpjsPesertaModel::where('noKartu', $id)->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the data was not found
            return redirect()->route('pesertaBpjs.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the data
        return inertia("Bpjs/Peserta/Detail", [
            'detail' => $query,
        ]);
    }
}