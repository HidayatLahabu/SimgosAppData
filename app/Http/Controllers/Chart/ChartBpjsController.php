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

        $dataBpjsTahunIni =  $this->bpjsTahunIni($tahunIni);
        $dataBpjsTahunLalu =  $this->bpjsTahunLalu($tahunLalu);

        $dataKunjunganTahunIni =  $this->kunjunganTahunIni($tahunIni);
        $dataKunjunganTahunLalu =  $this->kunjunganTahunLalu($tahunLalu);

        $dataRekonTahunIni =  $this->rekonTahunIni($tahunIni);
        $dataRekonTahunLalu =  $this->rekonTahunLalu($tahunLalu);

        $dataMonitoringTahunIni =  $this->monitoringTahunIni($tahunIni);
        $dataMonitoringTahunLalu =  $this->monitoringTahunIni($tahunLalu);

        return inertia("Chart/Bpjs/Index", [
            'tahunIni' => $tahunIni,
            'tahunLalu' => $tahunLalu,
            'bpjsTahunIni' => $dataBpjsTahunIni->toArray(),
            'bpjsTahunLalu' => $dataBpjsTahunLalu->toArray(),
            'kunjunganTahunIni' => $dataKunjunganTahunIni->toArray(),
            'kunjunganTahunLalu' => $dataKunjunganTahunLalu->toArray(),
            'rekonTahunIni' => $dataRekonTahunIni->toArray(),
            'rekonTahunLalu' => $dataRekonTahunLalu->toArray(),
            'monitoringTahunIni' => $dataMonitoringTahunIni->toArray(),
            'monitoringTahunLalu' => $dataMonitoringTahunLalu->toArray(),
        ]);
    }

    private function bpjsTahunIni($tahun)
    {
        return DB::connection('mysql6')->table('bpjs.peserta as peserta')
            ->select(
                DB::raw('YEAR(peserta.tanggal) as tahun'),
                DB::raw('MONTH(peserta.tanggal) as bulan'),
                DB::raw('COUNT(peserta.noKartu) as total')
            )
            ->whereYear('peserta.tanggal', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function bpjsTahunLalu($tahun)
    {
        return DB::connection('mysql5')->table('bpjs.peserta as peserta')
            ->select(
                DB::raw('YEAR(peserta.tanggal) as tahun'),
                DB::raw('MONTH(peserta.tanggal) as bulan'),
                DB::raw('COUNT(peserta.noKartu) as total')
            )
            ->whereYear('peserta.tanggal', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function kunjunganTahunIni($tahun)
    {
        return DB::connection('mysql6')->table('bpjs.kunjungan as kunjungan')
            ->select(
                DB::raw('YEAR(kunjungan.tglSEP) as tahun'),
                DB::raw('MONTH(kunjungan.tglSEP) as bulan'),
                DB::raw('COUNT(kunjungan.noSEP) as total')
            )
            ->whereYear('kunjungan.tglSEP', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function kunjunganTahunLalu($tahun)
    {
        return DB::connection('mysql6')->table('bpjs.kunjungan as kunjungan')
            ->select(
                DB::raw('YEAR(kunjungan.tglSEP) as tahun'),
                DB::raw('MONTH(kunjungan.tglSEP) as bulan'),
                DB::raw('COUNT(kunjungan.noSEP) as total')
            )
            ->whereYear('kunjungan.tglSEP', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function rekonTahunIni($tahun)
    {
        return DB::connection('mysql6')->table('bpjs.rencana_kontrol as rekon')
            ->select(
                DB::raw('YEAR(rekon.tglRencanaKontrol) as tahun'),
                DB::raw('MONTH(rekon.tglRencanaKontrol) as bulan'),
                DB::raw('COUNT(rekon.noSurat) as total')
            )
            ->whereYear('rekon.tglRencanaKontrol', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function rekonTahunLalu($tahun)
    {
        return DB::connection('mysql6')->table('bpjs.rencana_kontrol as rekon')
            ->select(
                DB::raw('YEAR(rekon.tglRencanaKontrol) as tahun'),
                DB::raw('MONTH(rekon.tglRencanaKontrol) as bulan'),
                DB::raw('COUNT(rekon.noSurat) as total')
            )
            ->whereYear('rekon.tglRencanaKontrol', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function monitoringTahunIni($tahun)
    {
        return DB::connection('mysql6')->table('bpjs.monitoring_rencana_kontrol as monitoring')
            ->select(
                DB::raw('YEAR(monitoring.tglRencanaKontrol) as tahun'),
                DB::raw('MONTH(monitoring.tglRencanaKontrol) as bulan'),
                DB::raw('COUNT(monitoring.noSuratKontrol) as total')
            )
            ->whereYear('monitoring.tglRencanaKontrol', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function monitoringTahunLalu($tahun)
    {
        return DB::connection('mysql6')->table('bpjs.monitoring_rencana_kontrol as monitoring')
            ->select(
                DB::raw('YEAR(monitoring.tglRencanaKontrol) as tahun'),
                DB::raw('MONTH(monitoring.tglRencanaKontrol) as bulan'),
                DB::raw('COUNT(monitoring.noSuratKontrol) as total')
            )
            ->whereYear('monitoring.tglRencanaKontrol', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }
}