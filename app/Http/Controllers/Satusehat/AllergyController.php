<?php

namespace App\Http\Controllers\Satusehat;

use App\Http\Controllers\Controller;
use App\Models\SatusehatAllergyModel;
use Illuminate\Http\Request;

class AllergyController extends Controller
{
    public function index()
    {
        // Define base query
        $query = SatusehatAllergyModel::orderByDesc('sendDate');

        // Apply search filter if 'subject' query parameter is present
        if (request('search')) {
            $searchSubject = strtolower(request('search'));
            $query->whereRaw('LOWER(patient) LIKE ?', ['%' . $searchSubject . '%'])
                ->orWhereRaw('LOWER(refId) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Satusehat/Allergy/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function filterByTime($filter)
    {
        // Define base query
        $query = SatusehatAllergyModel::orderByDesc('sendDate');

        // Clone query for count calculation
        $countQuery = clone $query;

        switch ($filter) {
            case 'hariIni':
                $query->whereDate('sendDate', now()->format('Y-m-d'));
                $countQuery->whereDate('sendDate', now()->format('Y-m-d'));
                $header = 'HARI INI';
                $text = 'REF ID';
                break;

            case 'mingguIni':
                $query->whereBetween('sendDate', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $countQuery->whereBetween('sendDate', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $header = 'MINGGU INI';
                $text = 'REF ID';
                break;

            case 'bulanIni':
                $query->whereMonth('sendDate', now()->month)
                    ->whereYear('sendDate', now()->year);
                $countQuery->whereMonth('sendDate', now()->month)
                    ->whereYear('sendDate', now()->year);
                $header = 'BULAN INI';
                $text = 'REF ID';
                break;

            case 'tahunIni':
                $query->whereYear('sendDate', now()->year);
                $countQuery->whereYear('sendDate', now()->year);
                $header = 'TAHUN INI';
                $text = 'REF ID';
                break;

            default:
                abort(404, 'Filter not found');
        }

        // Get count
        $count = $countQuery->count();

        // Apply search filter if 'name' query parameter is present
        if (request('patient')) {
            $searchSubject = strtolower(request('patient')); // Convert search term to lowercase
            $query->whereRaw('LOWER(patient) LIKE ?', ['%' . $searchSubject . '%'])
                ->orWhereRaw('LOWER(refId) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Return Inertia view with paginated data
        return inertia("Satusehat/Allergy/Index", [
            'dataTable' => [
                'data' => $data->toArray()['data'], // Only the paginated data
                'links' => $data->toArray()['links'], // Pagination links
            ],
            'queryParams' => request()->all(),
            'header' => $header,
            'totalCount' => $count,
            'text' => $text,
        ]);
    }
}
