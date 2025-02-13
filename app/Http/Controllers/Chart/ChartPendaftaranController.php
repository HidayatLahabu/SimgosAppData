<?php

namespace App\Http\Controllers\Chart;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ChartPendaftaranController extends Controller
{
    public function index()
    {
        $tahunIni = now()->year;
        $tahunLalu = $tahunIni - 1;

        $dataPendaftaranTahunIni =  $this->pendaftaran($tahunIni);
        $dataPendaftaranTahunLalu =  $this->pendaftaran($tahunLalu);

        $dataKunjunganTahunIni =  $this->kunjungan($tahunIni);
        $dataKunjunganTahunLalu =  $this->kunjungan($tahunLalu);

        $dataKonsulTahunIni =  $this->konsul($tahunIni);
        $dataKonsulTahunLalu =  $this->konsul($tahunLalu);

        $dataMutasiTahunIni =  $this->mutasi($tahunIni);
        $dataMutasiTahunLalu =  $this->mutasi($tahunLalu);

        $dataAntrianTahunIni =  $this->antrian($tahunIni);
        $dataAntrianTahunLalu =  $this->antrian($tahunLalu);

        $dataReservasiTahunIni =  $this->reservasi($tahunIni);
        $dataReservasiTahunLalu =  $this->reservasi($tahunLalu);

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
            'antrianTahunIni' => $dataAntrianTahunIni->toArray(),
            'antrianTahunLalu' => $dataAntrianTahunLalu->toArray(),
            'reservasiTahunIni' => $dataReservasiTahunIni->toArray(),
            'reservasiTahunLalu' => $dataReservasiTahunLalu->toArray(),
        ]);
    }

    private function pendaftaran($tahun)
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

    private function kunjungan($tahun)
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

    private function konsul($tahun)
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

    private function mutasi($tahun)
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

    private function antrian($tahun)
    {
        return DB::connection('mysql5')->table('pendaftaran.antrian_ruangan as antrian')
            ->select(
                DB::raw('YEAR(antrian.TANGGAL) as tahun'),
                DB::raw('MONTH(antrian.TANGGAL) as bulan'),
                DB::raw('COUNT(antrian.ID) as total')
            )
            ->whereYear('antrian.TANGGAL', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function reservasi($tahun)
    {
        return DB::connection('mysql5')->table('pendaftaran.reservasi as reservasi')
            ->select(
                DB::raw('YEAR(reservasi.TANGGAL) as tahun'),
                DB::raw('MONTH(reservasi.TANGGAL) as bulan'),
                DB::raw('COUNT(reservasi.NOMOR) as total')
            )
            ->whereYear('reservasi.TANGGAL', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }
}