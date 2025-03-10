<?php

namespace App\Http\Controllers\Chart;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ChartLayananController extends Controller
{
    public function index()
    {
        $tahunIni = now()->year;
        $tahunLalu = $tahunIni - 1;

        $laboratoriumTahunIni =  $this->laboratorium($tahunIni);
        $laboratoriumTahunLalu =  $this->laboratorium($tahunLalu);

        $radiologiTahunIni =  $this->radiologi($tahunIni);
        $radiologiTahunLalu =  $this->radiologi($tahunLalu);

        $hasilLabTahunIni =  $this->hasilLab($tahunIni);
        $hasilLabTahunLalu =  $this->hasilLab($tahunLalu);

        $hasilRadTahunIni =  $this->hasilRad($tahunIni);
        $hasilRadTahunLalu =  $this->hasilRad($tahunLalu);

        return inertia("Chart/Layanan/Index", [
            'tahunIni' => $tahunIni,
            'tahunLalu' => $tahunLalu,
            'laboratoriumTahunIni' => $laboratoriumTahunIni->toArray(),
            'laboratoriumTahunLalu' => $laboratoriumTahunLalu->toArray(),
            'radiologiTahunIni' => $radiologiTahunIni->toArray(),
            'radiologiTahunLalu' => $radiologiTahunLalu->toArray(),
            'hasilLabTahunIni' => $hasilLabTahunIni->toArray(),
            'hasilLabTahunLalu' => $hasilLabTahunLalu->toArray(),
            'hasilRadTahunIni' => $hasilRadTahunIni->toArray(),
            'hasilRadTahunLalu' => $hasilRadTahunLalu->toArray(),
        ]);
    }

    private function laboratorium($tahun)
    {
        return DB::connection('mysql7')->table('layanan.order_lab as laboratorium')
            ->select(
                DB::raw('YEAR(laboratorium.TANGGAL) as tahun'),
                DB::raw('MONTH(laboratorium.TANGGAL) as bulan'),
                DB::raw('COUNT(laboratorium.NOMOR) as total')
            )
            ->whereYear('laboratorium.TANGGAL', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function radiologi($tahun)
    {
        return DB::connection('mysql7')->table('layanan.order_rad as radiologi')
            ->select(
                DB::raw('YEAR(radiologi.TANGGAL) as tahun'),
                DB::raw('MONTH(radiologi.TANGGAL) as bulan'),
                DB::raw('COUNT(radiologi.NOMOR) as total')
            )
            ->whereYear('radiologi.TANGGAL', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function hasilLab($tahun)
    {
        return DB::connection('mysql7')->table('layanan.hasil_lab as laboratorium')
            ->select(
                DB::raw('YEAR(laboratorium.TANGGAL) as tahun'),
                DB::raw('MONTH(laboratorium.TANGGAL) as bulan'),
                DB::raw('COUNT(laboratorium.ID) as total')
            )
            ->whereYear('laboratorium.TANGGAL', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function hasilRad($tahun)
    {
        return DB::connection('mysql7')->table('layanan.hasil_rad as radiologi')
            ->select(
                DB::raw('YEAR(radiologi.TANGGAL) as tahun'),
                DB::raw('MONTH(radiologi.TANGGAL) as bulan'),
                DB::raw('COUNT(radiologi.ID) as total')
            )
            ->whereYear('radiologi.TANGGAL', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }
}