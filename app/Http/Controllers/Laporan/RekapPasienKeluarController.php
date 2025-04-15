<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RekapPasienKeluarController extends Controller
{
    public function index()
    {
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();
        $tgl_akhir = Carbon::now()->toDateString();

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRekapPasienKeluar(?, ?, ?, ?, ?, ?)', [$tgl_awal, $tgl_akhir, 0, '%', 3, 0]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                ($row->LAKILAKI ?? 0) > 0 ||
                ($row->PEREMPUAN ?? 0) > 0 ||
                ($row->UMUM ?? 0) > 0 ||
                ($row->INHEALTH ?? 0) > 0 ||
                ($row->JKN ?? 0) > 0 ||
                ($row->JKD ?? 0) > 0 ||
                ($row->IKS ?? 0) > 0 ||
                ($row->JUMLAH ?? 0) > 0
            );
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($a->UNIT, $b->UNIT);
        });

        // Filter agar hanya satu baris per DESKRIPSI
        $uniqueData = [];
        foreach ($filteredData as $row) {
            if (!isset($uniqueData[$row->UNIT])) {
                $uniqueData[$row->UNIT] = $row;
            }
        }

        // Konversi kembali ke array dengan Inertia
        $filteredData = array_values($uniqueData);

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'LAKILAKI' => 0,
            'PEREMPUAN' => 0,
            'UMUM' => 0,
            'INHEALTH' => 0,
            'JKN' => 0,
            'JKD' => 0,
            'IKS' => 0,
            'JUMLAH' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($filteredData as $row) {
            $total['LAKILAKI'] += $row->LAKILAKI ?? 0;
            $total['PEREMPUAN'] += $row->PEREMPUAN ?? 0;
            $total['UMUM'] += $row->UMUM ?? 0;
            $total['INHEALTH'] += $row->INHEALTH ?? 0;
            $total['JKN'] += $row->JKN ?? 0;
            $total['JKD'] += $row->JKD ?? 0;
            $total['IKS'] += $row->IKS ?? 0;
            $total['JUMLAH'] += $row->JUMLAH ?? 0;
        }

        return inertia("Laporan/RekapPasienKeluar/Index", [
            'dataTable' => $filteredData,
            'tglAwal' => $tgl_awal,
            'tglAkhir' => $tgl_akhir,
            'total' => $total,
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

        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRekapPasienKeluar(?, ?, ?, ?, ?, ?)', [$tgl_awal, $tgl_akhir, 0, '%', 3, 0]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                ($row->LAKILAKI ?? 0) > 0 ||
                ($row->PEREMPUAN ?? 0) > 0 ||
                ($row->UMUM ?? 0) > 0 ||
                ($row->INHEALTH ?? 0) > 0 ||
                ($row->JKN ?? 0) > 0 ||
                ($row->JKD ?? 0) > 0 ||
                ($row->IKS ?? 0) > 0 ||
                ($row->JUMLAH ?? 0) > 0
            );
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($a->UNIT, $b->UNIT);
        });

        // Filter agar hanya satu baris per DESKRIPSI
        $uniqueData = [];
        foreach ($filteredData as $row) {
            if (!isset($uniqueData[$row->UNIT])) {
                $uniqueData[$row->UNIT] = $row;
            }
        }

        // Konversi kembali ke array dengan Inertia
        $filteredData = array_values($uniqueData);

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'LAKILAKI' => 0,
            'PEREMPUAN' => 0,
            'UMUM' => 0,
            'INHEALTH' => 0,
            'JKN' => 0,
            'JKD' => 0,
            'IKS' => 0,
            'JUMLAH' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($filteredData as $row) {
            $total['LAKILAKI'] += $row->LAKILAKI ?? 0;
            $total['PEREMPUAN'] += $row->PEREMPUAN ?? 0;
            $total['UMUM'] += $row->UMUM ?? 0;
            $total['INHEALTH'] += $row->INHEALTH ?? 0;
            $total['JKN'] += $row->JKN ?? 0;
            $total['JKD'] += $row->JKD ?? 0;
            $total['IKS'] += $row->IKS ?? 0;
            $total['JUMLAH'] += $row->JUMLAH ?? 0;
        }

        return inertia("Laporan/RekapPasienKeluar/Print", [
            'items' => $filteredData,
            'total' => $total,
            'dariTanggal' => $tgl_awal,
            'sampaiTanggal' => $tgl_akhir,
        ]);
    }
}