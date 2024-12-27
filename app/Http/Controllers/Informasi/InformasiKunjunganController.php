<?php

namespace App\Http\Controllers\Informasi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InformasiKunjunganController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql12')->table('informasi.kunjungan as kunjungan')
            ->select(
                'kunjungan.TANGGAL as tanggal',
                'kunjungan.IDSUBUNIT as idSubUnit',
                DB::raw('MIN(kunjungan.DESKRIPSI) as jenisKunjungan'),
                DB::raw('MIN(kunjungan.INSTALASI) as instalasi'),
                DB::raw('MIN(kunjungan.UNIT) as unit'),
                DB::raw('MIN(kunjungan.SUBUNIT) as subUnit'),
                DB::raw('SUM(kunjungan.VALUE) as jumlah'),
                DB::raw('MAX(kunjungan.LASTUPDATED) as lastUpdated')
            )
            ->groupBy('kunjungan.TANGGAL', 'kunjungan.IDSUBUNIT');



        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(kunjungan.SUBUNIT) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('kunjungan.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Informasi/Kunjungan/Index", [
            'dataTable' => [
                'data' => $dataArray['data'],
                'links' => $dataArray['links'],
            ],
            'queryParams' => request()->all()
        ]);
    }
}
