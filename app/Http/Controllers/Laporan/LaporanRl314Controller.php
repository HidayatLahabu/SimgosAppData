<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaporanRl314Controller extends Controller
{
    public function index()
    {
        // Tanggal awal bulan berjalan
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();

        // Tanggal hari ini
        $tgl_akhir = Carbon::now()->toDateString();

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRL314(?, ?)', [$tgl_awal, $tgl_akhir]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->PUSKESMAS) && $row->PUSKESMAS !== 0) ||
                (!is_null($row->FASKES) && $row->FASKES !== 0) ||
                (!is_null($row->RS) && $row->RS !== 0) ||
                (!is_null($row->KEMBALIPUSKESMAS) && $row->KEMBALIPUSKESMAS !== 0) ||
                (!is_null($row->KEMBALIFASKES) && $row->KEMBALIFASKES !== 0) ||
                (!is_null($row->KEMBALIRS) && $row->KEMBALIRS !== 0) ||
                (!is_null($row->PASIENRUJUKAN) && $row->PASIENRUJUKAN !== 0) ||
                (!is_null($row->DATANGSENDIRI) && $row->DATANGSENDIRI !== 0) ||
                (!is_null($row->DITERIMAKEMBALI) && $row->DITERIMAKEMBALI !== 0)
            );
        }));

        return inertia("Laporan/Rl314/Index", [
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
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRL314(?, ?)', [$tgl_awal, $tgl_akhir]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->PUSKESMAS) && $row->PUSKESMAS !== 0) ||
                (!is_null($row->FASKES) && $row->FASKES !== 0) ||
                (!is_null($row->RS) && $row->RS !== 0) ||
                (!is_null($row->KEMBALIPUSKESMAS) && $row->KEMBALIPUSKESMAS !== 0) ||
                (!is_null($row->KEMBALIFASKES) && $row->KEMBALIFASKES !== 0) ||
                (!is_null($row->KEMBALIRS) && $row->KEMBALIRS !== 0) ||
                (!is_null($row->PASIENRUJUKAN) && $row->PASIENRUJUKAN !== 0) ||
                (!is_null($row->DATANGSENDIRI) && $row->DATANGSENDIRI !== 0) ||
                (!is_null($row->DITERIMAKEMBALI) && $row->DITERIMAKEMBALI !== 0)
            );
        }));

        return inertia("Laporan/Rl314/Print", [
            'data' => $filteredData,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
        ]);
    }
}