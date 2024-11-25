<?php

namespace App\Http\Controllers\Satusehat;

use App\Http\Controllers\Controller;
use App\Models\SatusehatCarePlanModel;
use Illuminate\Http\Request;

class CarePlanController extends Controller
{
    public function index()
    {
        // Define base query
        $query = SatusehatCarePlanModel::orderByDesc('id')->orderByDesc('sendDate');

        // Apply search filter if 'subject' query parameter is present
        if (request('author')) {
            $searchSubject = strtolower(request('author')); // Convert search term to lowercase
            $query->whereRaw('LOWER(author) LIKE ?', ['%' . $searchSubject . '%']); // Convert column to lowercase for comparison
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Modify 'data' to extract JSON fields
        $data->getCollection()->transform(function ($item) {
            // Decode 'subject' JSON and combine 'display' and 'reference'
            $author = json_decode($item->author, true);
            if (is_array($author)) {
                $display = $author['display'] ?? '';
                $reference = $author['reference'] ?? '';
                $item->author = trim("{$display}, ({$reference})");
            }

            return $item;
        });

        // Return Inertia view with paginated data
        return inertia("Satusehat/CarePlan/Index", [
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
        $query = SatusehatCarePlanModel::where('nopen', $id)->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the data was not found
            return redirect()->route('carePlan.index')->with('error', 'Data not found.');
        }

        // Convert all JSON fields in the model to strings without curly braces
        foreach ($query->getAttributes() as $key => $value) {
            if ($this->isJson($value)) {
                $query->$key = $this->formatJson($value); // Format JSON
            }
        }

        // Return Inertia view with the data
        return inertia("Satusehat/CarePlan/Detail", [
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