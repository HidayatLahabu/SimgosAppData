<?php

namespace App\Http\Controllers\Bpjs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class KunjunganBpjsController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql6')->table('bpjs.kunjungan as kunjungan')
            ->select(
                'kunjungan.noSEP',
                'kunjungan.tglSEP',
                'kunjungan.noRujukan',
                'kunjungan.tglRujukan',
                'pasien.NORM as norm',
                'peserta.nama'
            )
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'kunjungan.noKartu')
            ->leftJoin('master.kartu_identitas_pasien as pasien', 'pasien.NOMOR', '=', 'peserta.nik');

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(kunjungan.noSEP) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(kunjungan.noRujukan) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(peserta.nama) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhere('pasien.NORM', 'LIKE', '%' . $searchSubject . '%');
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('kunjungan.tglSEP')->paginate(10)->appends(request()->query());

        $countQuery = clone $query;
        $count = $countQuery->count();

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Bpjs/Kunjungan/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all(),
            'totalCount' => $count,
        ]);
    }

    public function filterByTime($filter)
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql6')->table('bpjs.kunjungan as kunjungan')
            ->select(
                'kunjungan.noSEP',
                'kunjungan.tglSEP',
                'kunjungan.noRujukan',
                'kunjungan.tglRujukan',
                'pasien.NORM as norm',
                'peserta.nama'
            )
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'kunjungan.noKartu')
            ->leftJoin('master.kartu_identitas_pasien as pasien', 'pasien.NOMOR', '=', 'peserta.nik');

        // Clone query for count calculation
        $countQuery = clone $query;

        switch ($filter) {
            case 'hariIni':
                $query->whereDate('kunjungan.tglSEP', now()->format('Y-m-d'));
                $countQuery->whereDate('kunjungan.tglSEP', now()->format('Y-m-d'));
                $header = 'HARI INI';
                break;

            case 'mingguIni':
                $query->whereBetween('kunjungan.tglSEP', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $countQuery->whereBetween('kunjungan.tglSEP', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $header = 'MINGGU INI';
                break;

            case 'bulanIni':
                $query->whereMonth('kunjungan.tglSEP', now()->month)
                    ->whereYear('kunjungan.tglSEP', now()->year);
                $countQuery->whereMonth('kunjungan.tglSEP', now()->month)
                    ->whereYear('kunjungan.tglSEP', now()->year);
                $header = 'BULAN INI';
                break;

            case 'tahunIni':
                $query->whereYear('kunjungan.tglSEP', now()->year);
                $countQuery->whereYear('kunjungan.tglSEP', now()->year);
                $header = 'TAHUN INI';
                break;

            default:
                abort(404, 'Filter not found');
        }

        // Get count
        $count = $countQuery->count();

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(kunjungan.noSEP) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(kunjungan.noRujukan) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(peserta.nama) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhere('pasien.NORM', 'LIKE', '%' . $searchSubject . '%');
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('kunjungan.tglSEP')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(kunjungan.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('kunjungan.tglSEP')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Bpjs/Kunjungan/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all(),
            'header' => $header,
            'totalCount' => $count,
        ]);
    }

    public function detail($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql6')->table('bpjs.kunjungan as kunjungan')
            ->select(
                'kunjungan.noKartu',
                'pasien.NORM as norm',
                'peserta.nama as nama',
                'kunjungan.noSEP',
                'kunjungan.tglSEP',
                'ppkPelayanan.nama as ppkPelayanan',
                'kunjungan.jenisPelayanan',
                'kunjungan.catatan',
                'kunjungan.diagAwal',
                'kunjungan.poliTujuan',
                'kunjungan.eksekutif',
                'kunjungan.klsRawat',
                'kunjungan.klsRawatNaik',
                'kunjungan.pembiayaan',
                'kunjungan.penanggungjawab',
                'kunjungan.cob',
                'kunjungan.katarak',
                'kunjungan.noSuratSKDP',
                'dpjp.nama as dpjpSKDP',
                'kunjungan.lakaLantas',
                'kunjungan.penjamin',
                'kunjungan.tglKejadian',
                'kunjungan.suplesi',
                'kunjungan.noSuplesi',
                'kunjungan.lokasiLaka',
                'kunjungan.propinsi',
                'kunjungan.kabupaten',
                'kunjungan.kecamatan',
                'kunjungan.tujuanKunj',
                'kunjungan.flagProcedure',
                'kunjungan.tglRujukan',
                'kunjungan.asalRujukan',
                'kunjungan.noRujukan',
                'kunjungan.ppkRujukan',
                'rujukan.tglKunjungan',
                'rujukan.provPerujuk',
                'rujukan.diagnosa',
                'rujukan.keluhan',
                'rujukan.poliRujukan',
                'rujukan.pelayanan',
            )
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'kunjungan.noKartu')
            ->leftJoin('master.kartu_identitas_pasien as pasien', 'pasien.NOMOR', '=', 'peserta.nik')
            ->leftJoin('bpjs.ppk as ppkPelayanan', 'ppkPelayanan.kode', '=', 'kunjungan.ppkPelayanan')
            ->leftJoin('bpjs.rujukan_masuk as rujukan', 'rujukan.noKunjungan', '=', 'kunjungan.noRujukan')
            ->leftJoin('bpjs.dpjp as dpjp', 'dpjp.kode', '=', 'kunjungan.dpjpSKDP')
            ->where('kunjungan.noSEP', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('kunjunganBpjs.index')->with('error', 'Data not found.');
        }

        // Convert all JSON fields in the object to strings without curly braces
        foreach (get_object_vars($query) as $key => $value) {
            if ($this->isJson($value)) {
                $query->$key = $this->formatJson($value); // Format JSON
            }
        }

        // Return Inertia view with the encounter data
        return inertia("Bpjs/Kunjungan/Detail", [
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