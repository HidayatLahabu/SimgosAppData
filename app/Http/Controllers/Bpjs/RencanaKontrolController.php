<?php

namespace App\Http\Controllers\Bpjs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RencanaKontrolController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql6')->table('bpjs.rencana_kontrol as rekon')
            ->select(
                'rekon.noSurat',
                'rekon.nomor as noSep',
                'rekon.tglRencanaKontrol as tanggal',
                'poli.nama as poliTujuan',
                'peserta.norm',
                'peserta.nama'
            )
            ->leftJoin('bpjs.kunjungan as kunjungan', 'kunjungan.noSEP', '=', 'rekon.nomor')
            ->leftJoin('bpjs.poli as poli', 'poli.kode', '=', 'rekon.poliKontrol')
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'kunjungan.noKartu');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(peserta.nama) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('rekon.tglRencanaKontrol')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Bpjs/Rekon/Index", [
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
        $query = DB::connection('mysql6')->table('bpjs.rencana_kontrol as rekon')
            ->select(
                'rekon.noSurat',
                'rekon.jnsKontrol',
                'rekon.nomor',
                'peserta.norm',
                'peserta.nama',
                'rekon.tglRencanaKontrol',
                'dpjp.nama as namaDokter',
                'poli.nama as poliKontrol',
                'rekon.user',
                'rekon.status',
            )
            ->leftJoin('bpjs.dpjp as dpjp', 'dpjp.kode', '=', 'rekon.kodeDokter')
            ->leftJoin('bpjs.kunjungan as kunjungan', 'kunjungan.noSEP', '=', 'rekon.nomor')
            ->leftJoin('bpjs.poli as poli', 'poli.kode', '=', 'rekon.poliKontrol')
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'kunjungan.noKartu')
            ->where('rekon.noSurat', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('rekonBpjs.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Bpjs/Rekon/Detail", [
            'detail' => $query,
        ]);
    }
}