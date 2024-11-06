<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaporanRl51Controller extends Controller
{
    public function index(Request $request)
    {
        // Tanggal awal bulan berjalan
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();

        // Tanggal hari ini
        $tgl_akhir = Carbon::now()->toDateString();

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRL51(?, ?)', [$tgl_awal, $tgl_akhir]);

        //dd($data);
        return inertia("Laporan/Rl51/Index", [
            'items' => $data,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
        ]);
    }
}
