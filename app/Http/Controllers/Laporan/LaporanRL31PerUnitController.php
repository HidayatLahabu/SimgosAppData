<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaporanRL31PerUnitController extends Controller
{
    public function index()
    {
        // Tanggal awal bulan berjalan
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();

        // Tanggal hari ini
        $tgl_akhir = Carbon::now()->toDateString();

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRL31PerUnit(?, ?, ?, ?, ?, ?)', [$tgl_awal, $tgl_akhir,  0, '%', 3, 0]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->AWAL) && $row->AWAL !== 0) ||
                (!is_null($row->MASUK) && $row->PINDAHAN !== 0) ||
                (!is_null($row->PINDAHAN) && $row->PINDAHAN !== 0) ||
                (!is_null($row->DIPINDAHKAN) && $row->DIPINDAHKAN !== 0) ||
                (!is_null($row->HIDUP) && $row->HIDUP !== 0) ||
                (!is_null($row->MATI) && $row->MATI !== 0) ||
                (!is_null($row->LD) && $row->LD !== 0) ||
                (!is_null($row->SISA) && $row->SISA !== 0) ||
                (!is_null($row->HP) && $row->HP !== 0) ||
                (!is_null($row->JMLTT) && $row->JMLTT !== 0) ||
                (!is_null($row->BOR) && $row->BOR !== 0) ||
                (!is_null($row->AVLOS) && $row->AVLOS !== 0) ||
                (!is_null($row->BTO) && $row->BTO !== 0) ||
                (!is_null($row->TOI) && $row->TOI !== 0) ||
                (!is_null($row->NDR) && $row->NDR !== 0) ||
                (!is_null($row->GDR) && $row->GDR !== 0)
            );
        }));

        return inertia("Laporan/RanapPerUnit/Index", [
            'items' => $filteredData,
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
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRL31PerUnit(?, ?, ?, ?, ?, ?)', [$tgl_awal, $tgl_akhir,  0, '%', 3, 0]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->AWAL) && $row->AWAL !== 0) ||
                (!is_null($row->MASUK) && $row->PINDAHAN !== 0) ||
                (!is_null($row->PINDAHAN) && $row->PINDAHAN !== 0) ||
                (!is_null($row->DIPINDAHKAN) && $row->DIPINDAHKAN !== 0) ||
                (!is_null($row->HIDUP) && $row->HIDUP !== 0) ||
                (!is_null($row->MATI) && $row->MATI !== 0) ||
                (!is_null($row->LD) && $row->LD !== 0) ||
                (!is_null($row->SISA) && $row->SISA !== 0) ||
                (!is_null($row->HP) && $row->HP !== 0) ||
                (!is_null($row->JMLTT) && $row->JMLTT !== 0) ||
                (!is_null($row->BOR) && $row->BOR !== 0) ||
                (!is_null($row->AVLOS) && $row->AVLOS !== 0) ||
                (!is_null($row->BTO) && $row->BTO !== 0) ||
                (!is_null($row->TOI) && $row->TOI !== 0) ||
                (!is_null($row->NDR) && $row->NDR !== 0) ||
                (!is_null($row->GDR) && $row->GDR !== 0)
            );
        }));

        return inertia("Laporan/RanapPerUnit/Print", [
            'items' => ['data' => $filteredData],
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
        ]);
    }
}