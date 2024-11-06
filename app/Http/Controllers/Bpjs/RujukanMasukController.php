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
                'peserta.norm',
                'peserta.nama'
            )
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'rujukan.noKartu');

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
                'peserta.norm',
                'peserta.nama',
            )
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'rujukan.noKartu')
            ->where('rujukan.noKunjungan', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('rujukanBpjs.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Bpjs/Rujukan/Detail", [
            'detail' => $query,
        ]);
    }
}