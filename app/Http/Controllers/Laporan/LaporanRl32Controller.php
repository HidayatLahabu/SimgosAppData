<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaporanRl32Controller extends Controller
{
    public function index()
    {
        // Tanggal awal bulan berjalan
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();

        // Tanggal hari ini
        $tgl_akhir = Carbon::now()->toDateString();

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRL32(?, ?)', [$tgl_awal, $tgl_akhir]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->RUJUKAN) && $row->RUJUKAN !== 0) ||
                (!is_null($row->NONRUJUKAN) && $row->NONRUJUKAN !== 0) ||
                (!is_null($row->DIRAWAT) && $row->DIRAWAT !== 0) ||
                (!is_null($row->DIRUJUK) && $row->DIRUJUK !== 0) ||
                (!is_null($row->PULANG) && $row->PULANG !== 0) ||
                (!is_null($row->MENINGGAL) && $row->MENINGGAL !== 0) ||
                (!is_null($row->DOA) && $row->DOA !== 0)
            );
        }));

        return inertia("Laporan/Rl32/Index", [
            'data' => $filteredData,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
        ]);
    }

    public function print(Request $request)
    {
        $request->validate([
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        // Ambil nilai input
        $tgl_awal = Carbon::parse($request->input('dari_tanggal'))->format('Y-m-d');
        $tgl_akhir = Carbon::parse($request->input('sampai_tanggal'))->endOfDay()->format('Y-m-d');

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRL32(?, ?)', [$tgl_awal, $tgl_akhir]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->RUJUKAN) && $row->RUJUKAN !== 0) ||
                (!is_null($row->NONRUJUKAN) && $row->NONRUJUKAN !== 0) ||
                (!is_null($row->DIRAWAT) && $row->DIRAWAT !== 0) ||
                (!is_null($row->DIRUJUK) && $row->DIRUJUK !== 0) ||
                (!is_null($row->PULANG) && $row->PULANG !== 0) ||
                (!is_null($row->MENINGGAL) && $row->MENINGGAL !== 0) ||
                (!is_null($row->DOA) && $row->DOA !== 0)
            );
        }));

        return inertia("Laporan/Rl32/Print", [
            'data' => $filteredData,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
        ]);
    }
}