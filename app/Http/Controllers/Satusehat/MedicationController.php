<?php

namespace App\Http\Controllers\Satusehat;

use App\Http\Controllers\Controller;
use App\Models\SatusehatMedicationModel;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    // public function index()
    // {
    //     // Define base query
    //     $query = SatusehatMedicationModel::orderByDesc('id')->orderByDesc('sendDate');

    //     // Apply search filter if 'subject' query parameter is present
    //     if (request('nopen')) {
    //         $searchSubject = strtolower(request('nopen')); // Convert search term to lowercase
    //         $query->whereRaw('LOWER(nopen) LIKE ?', ['%' . $searchSubject . '%']); // Convert column to lowercase for comparison
    //     }

    //     // Paginate the results
    //     $data = $query->paginate(10)->appends(request()->query());

    //     // Convert data to array
    //     $dataArray = $data->toArray();

    //     // Return Inertia view with paginated data
    //     return inertia("Satusehat/Medication/Index", [
    //         'dataTable' => [
    //             'data' => $dataArray['data'], // Only the paginated data
    //             'links' => $dataArray['links'], // Pagination links
    //         ],
    //         'queryParams' => request()->all()
    //     ]);
    // }

    public function index()
    {
        // Query dasar
        $query = SatusehatMedicationModel::orderByDesc('id')->orderByDesc('sendDate');

        // Filter pencarian berdasarkan nopen
        if (request('nopen')) {
            $search = strtolower(request('nopen'));
            $query->whereRaw('LOWER(nopen) LIKE ?', ['%' . $search . '%']);
        }

        // Paginate
        $data = $query->paginate(10)->appends(request()->query());

        // 🔥 Transform data collection
        $data->getCollection()->transform(function ($item) {

            // Contoh: jika ada field 'medication' berisi JSON
            if (!empty($item->medication) && $this->isJson($item->medication)) {
                $item->medication = $this->formatJson($item->medication);
            }

            // Contoh: jika ada field 'dosage' berisi JSON
            if (!empty($item->dosage) && $this->isJson($item->dosage)) {
                $item->dosage = $this->formatJson($item->dosage);
            }

            // Contoh: jika nopen juga JSON (kalau tidak, hapus saja)
            if (!empty($item->nopen) && $this->isJson($item->nopen)) {
                $item->nopen = $this->formatJson($item->nopen);
            }

            return $item;
        });

        // Balikkan ke Inertia
        $dataArray = $data->toArray();

        return inertia("Satusehat/Medication/Index", [
            'dataTable' => [
                'data'  => $dataArray['data'],
                'links' => $dataArray['links'],
            ],
            'queryParams' => request()->all()
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