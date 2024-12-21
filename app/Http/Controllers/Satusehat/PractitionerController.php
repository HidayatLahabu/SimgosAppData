<?php

namespace App\Http\Controllers\Satusehat;

use App\Http\Controllers\Controller;
use App\Models\SatusehatPractitionerModel;
use Illuminate\Http\Request;

class PractitionerController extends Controller
{
    public function index()
    {
        // Define base query
        $query = SatusehatPractitionerModel::whereNotNull('id')->orderByDesc('getDate');

        // Apply search filter if 'name' query parameter is present
        if (request('name')) {
            $searchName = strtolower(request('name')); // Convert search term to lowercase
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . $searchName . '%']); // Convert column to lowercase for comparison
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
        return inertia("Satusehat/Practitioner/Index", [
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
        $query = SatusehatPractitionerModel::where('refId', $id)->firstOrFail();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the data was not found
            return redirect()->route('practitioner.index')->with('error', 'Data not found.');
        }

        // Convert all JSON fields in the model to strings without curly braces
        foreach ($query->getAttributes() as $key => $value) {
            if ($this->isJson($value)) {
                $query->$key = $this->formatJson($value); // Format JSON
            }
        }

        // Return Inertia view with the data
        return inertia("Satusehat/Practitioner/Detail", [
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