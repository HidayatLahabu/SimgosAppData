<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartBpjsController extends Controller
{
    public function index()
    {
        $tahunIni = now()->year;
        $tahunLalu = $tahunIni - 1;

        $dataPendaftaranTahunIni =  $this->pendaftaranTahunIni($tahunIni);
        $dataPendaftaranTahunLalu =  $this->pendaftaranTahunLalu($tahunLalu);

        $dataKunjunganTahunIni =  $this->kunjunganTahunIni($tahunIni);
        $dataKunjunganTahunLalu =  $this->kunjunganTahunLalu($tahunLalu);

        $dataKonsulTahunIni =  $this->konsulTahunIni($tahunIni);
        $dataKonsulTahunLalu =  $this->konsulTahunLalu($tahunLalu);

        $dataMutasiTahunIni =  $this->mutasiTahunIni($tahunIni);
        $dataMutasiTahunLalu =  $this->mutasiTahunLalu($tahunLalu);

        return inertia("Chart/Pendaftaran/Index", [
            'tahunIni' => $tahunIni,
            'tahunLalu' => $tahunLalu,
            'pendaftaranTahunIni' => $dataPendaftaranTahunIni->toArray(),
            'pendaftaranTahunLalu' => $dataPendaftaranTahunLalu->toArray(),
            'kunjunganTahunIni' => $dataKunjunganTahunIni->toArray(),
            'kunjunganTahunLalu' => $dataKunjunganTahunLalu->toArray(),
            'konsulTahunIni' => $dataKonsulTahunIni->toArray(),
            'konsulTahunLalu' => $dataKonsulTahunLalu->toArray(),
            'mutasiTahunIni' => $dataMutasiTahunIni->toArray(),
            'mutasiTahunLalu' => $dataMutasiTahunLalu->toArray(),
        ]);
    }

    private function pendaftaranTahunIni($tahun)
    {
        return DB::connection('mysql5')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select(
                DB::raw('YEAR(pendaftaran.TANGGAL) as tahun'),
                DB::raw('MONTH(pendaftaran.TANGGAL) as bulan'),
                DB::raw('COUNT(pendaftaran.NOMOR) as total')
            )
            ->whereYear('pendaftaran.TANGGAL', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function pendaftaranTahunLalu($tahun)
    {
        return DB::connection('mysql5')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select(
                DB::raw('YEAR(pendaftaran.TANGGAL) as tahun'),
                DB::raw('MONTH(pendaftaran.TANGGAL) as bulan'),
                DB::raw('COUNT(pendaftaran.NOMOR) as total')
            )
            ->whereYear('pendaftaran.TANGGAL', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function kunjunganTahunIni($tahun)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select(
                DB::raw('YEAR(kunjungan.MASUK) as tahun'),
                DB::raw('MONTH(kunjungan.MASUK) as bulan'),
                DB::raw('COUNT(kunjungan.NOMOR) as total')
            )
            ->whereYear('kunjungan.MASUK', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function kunjunganTahunLalu($tahun)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select(
                DB::raw('YEAR(kunjungan.MASUK) as tahun'),
                DB::raw('MONTH(kunjungan.MASUK) as bulan'),
                DB::raw('COUNT(kunjungan.NOMOR) as total')
            )
            ->whereYear('kunjungan.MASUK', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function konsulTahunIni($tahun)
    {
        return DB::connection('mysql5')->table('pendaftaran.konsul as konsul')
            ->select(
                DB::raw('YEAR(konsul.TANGGAL) as tahun'),
                DB::raw('MONTH(konsul.TANGGAL) as bulan'),
                DB::raw('COUNT(konsul.NOMOR) as total')
            )
            ->whereYear('konsul.TANGGAL', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function konsulTahunLalu($tahun)
    {
        return DB::connection('mysql5')->table('pendaftaran.konsul as konsul')
            ->select(
                DB::raw('YEAR(konsul.TANGGAL) as tahun'),
                DB::raw('MONTH(konsul.TANGGAL) as bulan'),
                DB::raw('COUNT(konsul.NOMOR) as total')
            )
            ->whereYear('konsul.TANGGAL', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function mutasiTahunIni($tahun)
    {
        return DB::connection('mysql5')->table('pendaftaran.mutasi as mutasi')
            ->select(
                DB::raw('YEAR(mutasi.TANGGAL) as tahun'),
                DB::raw('MONTH(mutasi.TANGGAL) as bulan'),
                DB::raw('COUNT(mutasi.NOMOR) as total')
            )
            ->whereYear('mutasi.TANGGAL', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function mutasiTahunLalu($tahun)
    {
        return DB::connection('mysql5')->table('pendaftaran.mutasi as mutasi')
            ->select(
                DB::raw('YEAR(mutasi.TANGGAL) as tahun'),
                DB::raw('MONTH(mutasi.TANGGAL) as bulan'),
                DB::raw('COUNT(mutasi.NOMOR) as total')
            )
            ->whereYear('mutasi.TANGGAL', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }
}