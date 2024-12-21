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
        $query = SatusehatCompositionModel::orderByDesc('sendDate');

        // Apply search filter if 'subject' query parameter is present
        if (request('search')) {
            $searchSubject = strtolower(request('search'));
            $query->whereRaw('LOWER(subject) LIKE ?', ['%' . $searchSubject . '%'])
                ->orWhereRaw('LOWER(nopen) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Modify 'data' to extract JSON fields
        $data->getCollection()->transform(function ($item) {
            // Decode 'subject' JSON and combine 'display' and 'reference'
            $subject = json_decode($item->subject, true);
            if (is_array($subject)) {
                $display = $subject['display'] ?? '';
                $reference = $subject['reference'] ?? '';
                $item->subject = trim("{$display}, ({$reference})");
            }

            return $item;
        });

        // Return Inertia view with paginated data
        return inertia("Satusehat/Composition/Index", [
            'dataTable' => [
                'data' => $data->toArray()['data'], // Only the paginated data
                'links' => $data->toArray()['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function filterByTime($filter)
    {
        // Define base query
        $query = SatusehatCompositionModel::orderByDesc('sendDate');

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
        if (request('search')) {
            $searchSubject = strtolower(request('search'));
            $query->whereRaw('LOWER(subject) LIKE ?', ['%' . $searchSubject . '%'])
                ->orWhereRaw('LOWER(nopen) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Modify 'data' to extract JSON fields
        $data->getCollection()->transform(function ($item) {
            // Decode 'subject' JSON and combine 'display' and 'reference'
            $subject = json_decode($item->subject, true);
            if (is_array($subject)) {
                $display = $subject['display'] ?? '';
                $reference = $subject['reference'] ?? '';
                $item->subject = trim("{$display}, ({$reference})");
            }

            return $item;
        });

        // Return Inertia view with paginated data
        return inertia("Satusehat/Composition/Index", [
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

    public function detail($id)
    {
        // Fetch the specific data
        $query = SatusehatCompositionModel::where('nopen', $id)->firstOrFail();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the data was not found
            return redirect()->route('composition.index')->with('error', 'Data not found.');
        }

        // Convert all JSON fields in the model to strings without curly braces
        foreach ($query->getAttributes() as $key => $value) {
            if ($this->isJson($value)) {
                $query->$key = $this->formatJson($value); // Format JSON
            }
        }

        // Return Inertia view with the data
        return inertia("Satusehat/Composition/Detail", [
            'detail' => $query,
        ]);
    }

    /**
     * Check if a string is JSON.
     */
    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Format JSON string into a plain text string without curly braces.
     */
    private function formatJson($json)
    {
        $data = json_decode($json, true); // Decode JSON to array
        if (is_array($data)) {
            $flattened = [];
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    // Handle nested arrays
                    $nested = implode(', ', array_map(
                        fn($nestedKey, $nestedValue) => is_array($nestedValue)
                            ? "{$nestedKey}: [" . json_encode($nestedValue) . "]"
                            : "{$nestedKey}: {$nestedValue}",
                        array_keys($value),
                        $value
                    ));
                    $flattened[] = "{$key}: [{$nested}]";
                } else {
                    // Handle scalar values
                    $flattened[] = "{$key}: {$value}";
                }
            }
            return implode(', ', $flattened); // Combine into a single string
        }
        return $json; // Return original if not a valid array
    }
}