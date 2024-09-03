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
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql6')->table('bpjs.kunjungan as kunjungan')
            ->select(
                'kunjungan.noSEP',
                'kunjungan.tglSEP',
                'kunjungan.noRujukan',
                'kunjungan.tglRujukan',
                'peserta.norm',
                'peserta.nama'
            )
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'kunjungan.noKartu');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(peserta.nama) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('kunjungan.tglRujukan')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Bpjs/Kunjungan/Index", [
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
        $query = DB::connection('mysql6')->table('bpjs.kunjungan as kunjungan')
            ->select(
                'kunjungan.noKartu',
                'peserta.norm as norm',
                'peserta.nama as nama',
                'kunjungan.noSEP',
                'kunjungan.tglSEP',
                'kunjungan.tglRujukan',
                'kunjungan.asalRujukan',
                'kunjungan.noRujukan',
                'ppkRujukan.nama as ppkRujukan',
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
                'kunjungan.dpjpSKDP',
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
            )
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'kunjungan.noKartu')
            ->leftJoin('bpjs.ppk as ppkRujukan', 'ppkRujukan.kode', '=', 'kunjungan.ppkRujukan')
            ->leftJoin('bpjs.ppk as ppkPelayanan', 'ppkPelayanan.kode', '=', 'kunjungan.ppkPelayanan')
            ->where('kunjungan.noSEP', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('kunjunganBpjs.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Bpjs/Kunjungan/Detail", [
            'detail' => $query,
        ]);
    }
}