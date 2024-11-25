<?php

namespace App\Http\Controllers\Satusehat;

use App\Http\Controllers\Controller;
use App\Models\SatusehatPatientModel;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        // Define base query
        $query = SatusehatPatientModel::orderByDesc('getDate');

        // Apply search filter if 'name' query parameter is present
        if (request('search')) {
            $searchSubject = strtolower(request('search')); // Convert search term to lowercase
            // $query->whereRaw('LOWER(name) LIKE ?', ['%' . $searchName . '%']); 
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(id) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(name) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(refId) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(nik) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        $data->getCollection()->transform(function ($item) {
            // Decode 'name' JSON (array) and extract 'use' and 'text'
            $nameArray = json_decode($item->name, true);
            if (is_array($nameArray) && isset($nameArray[0])) {
                $firstElement = $nameArray[0];
                $text = $firstElement['text'] ?? '';
                $item->name = trim("{$text}");
            }

            return $item;
        });

        // Return Inertia view with paginated data
        return inertia("Satusehat/Patient/Index", [
            'dataTable' => [
                'data' => $data->toArray()['data'], // Only the paginated data
                'links' => $data->toArray()['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function detail($id)
    {
        // Fetch the specific data
        $query = SatusehatPatientModel::where('refId', $id)->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the data was not found
            return redirect()->route('patient.index')->with('error', 'Data not found.');
        }

        // Convert all JSON fields in the model to strings without curly braces
        foreach ($query->getAttributes() as $key => $value) {
            if ($this->isJson($value)) {
                $query->$key = $this->formatJson($value); // Format JSON
            }
        }

        // Return Inertia view with the data
        return inertia("Satusehat/Patient/Detail", [
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