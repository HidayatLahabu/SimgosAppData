<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaporanRl31Controller extends Controller
{
    public function index()
    {
        // Tanggal awal bulan berjalan
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();

        // Tanggal hari ini
        $tgl_akhir = Carbon::now()->toDateString();

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRL31(?, ?)', [$tgl_awal, $tgl_akhir]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                // Cek jika kombinasi MASUK + PINDAHAN menghasilkan nilai
                $row->MASUK + $row->PINDAHAN !== 0 ||
                // Cek jika kombinasi DIPINDAHKAN + HIDUP menghasilkan nilai
                $row->DIPINDAHKAN + $row->HIDUP !== 0 ||
                // Cek kolom lainnya
                (!is_null($row->AWAL) && $row->AWAL !== 0) ||
                (!is_null($row->PINDAHAN) && $row->PINDAHAN !== 0) ||
                (!is_null($row->DIPINDAHKAN) && $row->DIPINDAHKAN !== 0) ||
                (!is_null($row->HIDUP) && $row->HIDUP !== 0) ||
                (!is_null($row->MATIKURANG48) && $row->MATIKURANG48 !== 0) ||
                (!is_null($row->MATILEBIH48) && $row->MATILEBIH48 !== 0) ||
                (!is_null($row->LD) && $row->LD !== 0) ||
                (!is_null($row->SISA) && $row->SISA !== 0) ||
                (!is_null($row->HP) && $row->HP !== 0) ||
                (!is_null($row->VVIP) && $row->VVIP !== 0) ||
                (!is_null($row->VIP) && $row->VIP !== 0) ||
                (!is_null($row->KLSI) && $row->KLSI !== 0) ||
                (!is_null($row->KLSII) && $row->KLSII !== 0) ||
                (!is_null($row->KLSIII) && $row->KLSIII !== 0) ||
                (!is_null($row->KLSKHUSUS) && $row->KLSKHUSUS !== 0)
            );
        }));

        // Sort berdasarkan MASUK secara descending (tertinggi ke terendah)
        usort($filteredData, function ($a, $b) {
            return $b->MASUK <=> $a->MASUK;
        });

        return inertia("Laporan/Rl31/Index", [
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
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRL31(?, ?)', [$tgl_awal, $tgl_akhir]);
        //dd($data);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                // Cek jika kombinasi MASUK + PINDAHAN menghasilkan nilai
                $row->MASUK + $row->PINDAHAN !== 0 ||
                // Cek jika kombinasi DIPINDAHKAN + HIDUP menghasilkan nilai
                $row->DIPINDAHKAN + $row->HIDUP !== 0 ||
                // Cek kolom lainnya
                (!is_null($row->AWAL) && $row->AWAL !== 0) ||
                (!is_null($row->PINDAHAN) && $row->PINDAHAN !== 0) ||
                (!is_null($row->DIPINDAHKAN) && $row->DIPINDAHKAN !== 0) ||
                (!is_null($row->HIDUP) && $row->HIDUP !== 0) ||
                (!is_null($row->MATIKURANG48) && $row->MATIKURANG48 !== 0) ||
                (!is_null($row->MATILEBIH48) && $row->MATILEBIH48 !== 0) ||
                (!is_null($row->LD) && $row->LD !== 0) ||
                (!is_null($row->SISA) && $row->SISA !== 0) ||
                (!is_null($row->HP) && $row->HP !== 0) ||
                (!is_null($row->VVIP) && $row->VVIP !== 0) ||
                (!is_null($row->VIP) && $row->VIP !== 0) ||
                (!is_null($row->KLSI) && $row->KLSI !== 0) ||
                (!is_null($row->KLSII) && $row->KLSII !== 0) ||
                (!is_null($row->KLSIII) && $row->KLSIII !== 0) ||
                (!is_null($row->KLSKHUSUS) && $row->KLSKHUSUS !== 0)
            );
        }));

        return inertia("Laporan/Rl31/Print", [
            'items' => ['data' => $filteredData],
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
        ]);
    }
}