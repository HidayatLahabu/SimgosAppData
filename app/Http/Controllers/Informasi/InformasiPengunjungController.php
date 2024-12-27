<?php

namespace App\Http\Controllers\Informasi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InformasiPengunjungController extends Controller
{
    public function index()
    {
        // Start building the query using the query builder
        $queryPengunjung = DB::connection('mysql12')->table('informasi.pengunjung as pengunjung')
            ->select(
                'pengunjung.TANGGAL as tanggal',
                DB::raw('SUM(pengunjung.VALUE) as semua'),
                DB::raw('SUM(CASE WHEN pengunjung.ID = "1" THEN pengunjung.VALUE ELSE 0 END) as rajal'),
                DB::raw('SUM(CASE WHEN pengunjung.ID = "2" THEN pengunjung.VALUE ELSE 0 END) as darurat'),
                DB::raw('MAX(pengunjung.LASTUPDATED) as lastUpdated')
            )
            ->groupBy('pengunjung.TANGGAL');

        // Paginate the results
        $dataPengunjung = $queryPengunjung->orderByDesc('pengunjung.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataPengunjung = $dataPengunjung->toArray();

        $queryRanap = DB::connection('mysql12')->table('informasi.pasien_rawat_inap as pasienRanap')
            ->select(
                'pasienRanap.TANGGAL as tanggal',
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "1" THEN pasienRanap.VALUE ELSE 0 END) as masuk'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "2" THEN pasienRanap.VALUE ELSE 0 END) as dirawat'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "3" THEN pasienRanap.VALUE ELSE 0 END) as keluar'),
                DB::raw('MAX(pasienRanap.LASTUPDATED) as lastUpdated')
            )
            ->groupBy('pasienRanap.TANGGAL');

        // Paginate the results
        $dataRanap = $queryRanap->orderByDesc('pasienRanap.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataRanap = $dataRanap->toArray();

        // Return Inertia view with paginated data
        return inertia("Informasi/Pengunjung/Index", [
            'tablePengunjung' => [
                'dataPengunjung' => $dataPengunjung['data'],
                'linksPengunjung' => $dataPengunjung['links'],
            ],
            'tableRanap' => [
                'dataRanap' => $dataRanap['data'],
                'linksRanap' => $dataRanap['links'],
            ],
        ]);
    }
}
