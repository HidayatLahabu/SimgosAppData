<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MonitoringKegiatanRekapController extends Controller
{
    public function index()
    {
        // Tanggal awal bulan berjalan
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();

        // Tanggal hari ini
        $tgl_akhir = Carbon::now()->toDateString();

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.MonitoringKegiatanPelayananRekap(?, ?)', [$tgl_awal, $tgl_akhir]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->BLM_TERIMA_KUNJUNGAN) && $row->BLM_TERIMA_KUNJUNGAN !== 0) ||
                (!is_null($row->BLM_FINAL_KUNJUNGAN) && $row->BLM_FINAL_KUNJUNGAN !== 0) ||
                (!is_null($row->BLM_TERIMA_RESEP) && $row->BLM_TERIMA_RESEP !== 0) ||
                (!is_null($row->BLM_TERIMA_LAB) && $row->BLM_TERIMA_LAB !== 0) ||
                (!is_null($row->BLM_TERIMA_RAD) && $row->BLM_TERIMA_RAD !== 0) ||
                (!is_null($row->BLM_TERIMA_KONSUL) && $row->BLM_TERIMA_KONSUL !== 0) ||
                (!is_null($row->BLM_TERIMA_MUTASI) && $row->BLM_TERIMA_MUTASI !== 0)
            );
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($a->DESKRIPSI, $b->DESKRIPSI);
        });

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'BLM_TERIMA_KUNJUNGAN' => 0,
            'BLM_FINAL_KUNJUNGAN' => 0,
            'BLM_TERIMA_RESEP' => 0,
            'BLM_TERIMA_LAB' => 0,
            'BLM_TERIMA_RAD' => 0,
            'BLM_TERIMA_KONSUL' => 0,
            'BLM_TERIMA_MUTASI' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($filteredData as $row) {
            $total['BLM_TERIMA_KUNJUNGAN'] += $row->BLM_TERIMA_KUNJUNGAN ?? 0;
            $total['BLM_FINAL_KUNJUNGAN'] += $row->BLM_FINAL_KUNJUNGAN ?? 0;
            $total['BLM_TERIMA_RESEP'] += $row->BLM_TERIMA_RESEP ?? 0;
            $total['BLM_TERIMA_LAB'] += $row->BLM_TERIMA_LAB ?? 0;
            $total['BLM_TERIMA_RAD'] += $row->BLM_TERIMA_RAD ?? 0;
            $total['BLM_TERIMA_KONSUL'] += $row->BLM_TERIMA_KONSUL ?? 0;
            $total['BLM_TERIMA_MUTASI'] += $row->BLM_TERIMA_MUTASI ?? 0;
        }

        return inertia("Laporan/MonitoringKegiatanRekap/Index", [
            'items' => $filteredData,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
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

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.MonitoringKegiatanPelayananRekap(?, ?)', [$tgl_awal, $tgl_akhir]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->BLM_TERIMA_KUNJUNGAN) && $row->BLM_TERIMA_KUNJUNGAN !== 0) ||
                (!is_null($row->BLM_FINAL_KUNJUNGAN) && $row->BLM_FINAL_KUNJUNGAN !== 0) ||
                (!is_null($row->BLM_TERIMA_RESEP) && $row->BLM_TERIMA_RESEP !== 0) ||
                (!is_null($row->BLM_TERIMA_LAB) && $row->BLM_TERIMA_LAB !== 0) ||
                (!is_null($row->BLM_TERIMA_RAD) && $row->BLM_TERIMA_RAD !== 0) ||
                (!is_null($row->BLM_TERIMA_KONSUL) && $row->BLM_TERIMA_KONSUL !== 0) ||
                (!is_null($row->BLM_TERIMA_MUTASI) && $row->BLM_TERIMA_MUTASI !== 0)
            );
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($a->DESKRIPSI, $b->DESKRIPSI);
        });

        // Filter agar hanya satu baris per DESKRIPSI
        $uniqueData = [];
        foreach ($filteredData as $row) {
            if (!isset($uniqueData[$row->DESKRIPSI])) {
                $uniqueData[$row->DESKRIPSI] = $row;
            }
        }

        // Konversi kembali ke array dengan Inertia
        $filteredData = array_values($uniqueData);

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'BLM_TERIMA_KUNJUNGAN' => 0,
            'BLM_FINAL_KUNJUNGAN' => 0,
            'BLM_TERIMA_RESEP' => 0,
            'BLM_TERIMA_LAB' => 0,
            'BLM_TERIMA_RAD' => 0,
            'BLM_TERIMA_KONSUL' => 0,
            'BLM_TERIMA_MUTASI' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($filteredData as $row) {
            $total['BLM_TERIMA_KUNJUNGAN'] += $row->BLM_TERIMA_KUNJUNGAN ?? 0;
            $total['BLM_FINAL_KUNJUNGAN'] += $row->BLM_FINAL_KUNJUNGAN ?? 0;
            $total['BLM_TERIMA_RESEP'] += $row->BLM_TERIMA_RESEP ?? 0;
            $total['BLM_TERIMA_LAB'] += $row->BLM_TERIMA_LAB ?? 0;
            $total['BLM_TERIMA_RAD'] += $row->BLM_TERIMA_RAD ?? 0;
            $total['BLM_TERIMA_KONSUL'] += $row->BLM_TERIMA_KONSUL ?? 0;
            $total['BLM_TERIMA_MUTASI'] += $row->BLM_TERIMA_MUTASI ?? 0;
        }

        return inertia("Laporan/MonitoringKegiatanRekap/Print", [
            'items' => ['data' => $filteredData],
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'total' => $total,
        ]);
    }
}