<?php

namespace App\Http\Controllers\Bpjs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RujukanMasukController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql6')->table('bpjs.rujukan_masuk as rujukan')
            ->select(
                'rujukan.noKunjungan',
                'rujukan.tglKunjungan',
                'rujukan.noKartu',
                'rujukan.provPerujuk',
                'pasien.NORM as norm',
                'peserta.nama'
            )
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'rujukan.noKartu')
            ->leftJoin('master.kartu_identitas_pasien as pasien', 'pasien.NOMOR', '=', 'peserta.nik');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(peserta.nama) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('rujukan.tglKunjungan')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Bpjs/Rujukan/Index", [
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
        $query = DB::connection('mysql6')->table('bpjs.rujukan_masuk as rujukan')
            ->select(
                'rujukan.noKunjungan',
                'rujukan.tglKunjungan',
                'rujukan.noKartu',
                'rujukan.provPerujuk',
                'rujukan.diagnosa',
                'rujukan.keluhan',
                'rujukan.poliRujukan',
                'rujukan.pelayanan',
                'pasien.NORM as norm',
                'peserta.nama',
            )
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'rujukan.noKartu')
            ->leftJoin('master.kartu_identitas_pasien as pasien', 'pasien.NOMOR', '=', 'peserta.nik')
            ->where('rujukan.noKunjungan', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('rujukanBpjs.index')->with('error', 'Data not found.');
        }

        // Convert all JSON fields in the object to strings without curly braces
        foreach (get_object_vars($query) as $key => $value) {
            if ($this->isJson($value)) {
                $query->$key = $this->formatJson($value); // Format JSON
            }
        }

        // Return Inertia view with the encounter data
        return inertia("Bpjs/Rujukan/Detail", [
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