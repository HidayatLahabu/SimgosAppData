<?php

namespace App\Http\Controllers\Informasi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StatistikKunjunganController extends Controller
{
    public function index()
    {
        // Start building the query using the query builder
        $kunjungan = DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                'statistikKunjungan.TANGGAL as tanggal',
                DB::raw('SUM(statistikKunjungan.RJ) as rajal'),
                DB::raw('SUM(statistikKunjungan.RD) as darurat'),
                DB::raw('SUM(statistikKunjungan.RI) as ranap'),
                'statistikKunjungan.TANGGAL_UPDATED as tanggalUpdated'
            )
            ->where('statistikKunjungan.RJ', '>', 0)
            ->orWhere('statistikKunjungan.RD', '>', 0)
            ->orWhere('statistikKunjungan.RI', '>', 0)
            ->groupBy('statistikKunjungan.TANGGAL');

        // Paginate the results
        $dataKunjungan = $kunjungan->orderByDesc('statistikKunjungan.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataKunjungan = $dataKunjungan->toArray();

        // Start building the query using the query builder
        $rujukan = DB::connection('mysql12')->table('informasi.statistik_rujukan as statistikRujukan')
            ->select(
                'statistikRujukan.TANGGAL as tanggal',
                DB::raw('SUM(statistikRujukan.MASUK) as masuk'),
                DB::raw('SUM(statistikRujukan.KELUAR) as keluar'),
                DB::raw('SUM(statistikRujukan.BALIK) as balik'),
                'statistikRujukan.TANGGAL_UPDATED as tanggalUpdated'
            )
            ->where('statistikRujukan.MASUK', '>', 0)
            ->orWhere('statistikRujukan.KELUAR', '>', 0)
            ->orWhere('statistikRujukan.BALIK', '>', 0)
            ->groupBy('statistikRujukan.TANGGAL');

        // Paginate the results
        $dataRujukan = $rujukan->orderByDesc('statistikRujukan.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataRujukan = $dataRujukan->toArray();

        // Return Inertia view with paginated data
        return inertia("Informasi/StatistikKunjungan/Index", [
            'tableKunjungan' => [
                'dataKunjungan' => $dataKunjungan['data'],
                'linksKunjungan' => $dataKunjungan['links'],
            ],
            'tableRujukan' => [
                'dataRujukan' => $dataRujukan['data'],
                'linksRujukan' => $dataRujukan['links'],
            ],
        ]);
    }
}
