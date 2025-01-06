<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaporanRl12Controller extends Controller
{
    public function index(Request $request)
    {
        // Tanggal awal bulan berjalan (Current year)
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();
        // Tanggal hari ini (Current date)
        $tgl_akhir = Carbon::now()->toDateString();

        // Tanggal awal bulan berjalan (Previous year)
        $tgl_awal_last_year = Carbon::now()->subYear()->startOfYear()->toDateString();
        // Tanggal akhir bulan berjalan (Previous year)
        $tgl_akhir_last_year = Carbon::now()->subYear()->endOfYear()->toDateString();

        // Panggil prosedur yang telah dibuat untuk current year
        $data_current_year = DB::connection('mysql10')->select('CALL laporan.LaporanRL12(?, ?)', [$tgl_awal, $tgl_akhir]);

        // Panggil prosedur yang telah dibuat untuk previous year
        $data_last_year = DB::connection('mysql10')->select('CALL laporan.LaporanRL12(?, ?)', [$tgl_awal_last_year, $tgl_akhir_last_year]);

        // Return data to frontend
        return inertia("Laporan/Rl12/Index", [
            'items_current_year' => $data_current_year,
            'items_last_year' => $data_last_year,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'tgl_awal_last_year' => $tgl_awal_last_year,
            'tgl_akhir_last_year' => $tgl_akhir_last_year,
        ]);
    }
}