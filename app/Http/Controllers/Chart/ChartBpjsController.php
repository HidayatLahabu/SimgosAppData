<?php

namespace App\Http\Controllers\Chart;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ChartBpjsController extends Controller
{
    public function index()
    {
        $tahunIni = now()->year;
        $tahunLalu = $tahunIni - 1;
        $tglAkhir = Carbon::now()->endOfDay()->format('Y-m-d');
        $tglAkhirLalu = Carbon::now()->subYear()->endOfYear()->format('Y-m-d');

        $dataBpjsTahunIni =  $this->bpjsTahunIni($tahunIni);
        $dataBpjsTahunLalu =  $this->bpjsTahunLalu($tahunLalu);

        $dataKunjunganTahunIni =  $this->kunjunganTahunIni($tahunIni);
        $dataKunjunganTahunLalu =  $this->kunjunganTahunLalu($tahunLalu);

        $dataRekonTahunIni =  $this->rekonTahun($tahunIni, $tglAkhir);
        $dataRekonTahunLalu =  $this->rekonTahun($tahunLalu, $tglAkhirLalu);

        $dataMonitoringTahunIni =  $this->monitoringTahun($tahunIni, $tglAkhir);
        $dataMonitoringTahunLalu =  $this->monitoringTahun($tahunLalu, $tglAkhirLalu);

        $dataBatalTahunIni =  $this->batalTahun($tahunIni, $tglAkhir);
        $dataBatalTahunLalu =  $this->batalTahun($tahunLalu, $tglAkhirLalu);

        $dataKontrolTahunIni = $this->getChartData($tahunIni, $tglAkhir);
        $dataKontrolTahunLalu = $this->getChartData($tahunLalu, $tglAkhirLalu);

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
            'batalTahunIni' => $dataBatalTahunIni->toArray(),
            'batalTahunLalu' => $dataBatalTahunLalu->toArray(),
            'kontrolTahunIni' => $dataKontrolTahunIni,
            'kontrolTahunLalu' => $dataKontrolTahunLalu,
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

    private function rekonTahun($tahun, $tanggal)
    {
        return DB::connection('mysql6')->table('bpjs.rencana_kontrol as rekon')
            ->select(
                DB::raw('YEAR(rekon.tglRencanaKontrol) as tahun'),
                DB::raw('MONTH(rekon.tglRencanaKontrol) as bulan'),
                DB::raw('COUNT(rekon.noSurat) as total')
            )
            ->where('rekon.tglRencanaKontrol', '<=', $tanggal)
            ->whereYear('rekon.tglRencanaKontrol', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function monitoringTahun($tahun, $tanggal)
    {
        return DB::connection('mysql6')->table('bpjs.monitoring_rencana_kontrol as monitoring')
            ->select(
                DB::raw('YEAR(monitoring.tglRencanaKontrol) as tahun'),
                DB::raw('MONTH(monitoring.tglRencanaKontrol) as bulan'),
                DB::raw('COUNT(monitoring.noSuratKontrol) as total')
            )
            ->where('monitoring.tglRencanaKontrol', '<=', $tanggal)
            ->whereYear('monitoring.tglRencanaKontrol', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    private function batalTahun($tahun, $tanggal)
    {
        return DB::connection('mysql6')->table('bpjs.rencana_kontrol as rekon')
            ->select(
                DB::raw('YEAR(rekon.tglRencanaKontrol) as tahun'),
                DB::raw('MONTH(rekon.tglRencanaKontrol) as bulan'),
                DB::raw('COUNT(rekon.noSurat) as total')
            )
            ->leftJoin('bpjs.monitoring_rencana_kontrol as monitor', 'monitor.noSuratKontrol', '=', 'rekon.noSurat')
            ->whereNull('monitor.noSuratKontrol')
            ->where('rekon.tglRencanaKontrol', '<=', $tanggal)
            ->whereYear('rekon.tglRencanaKontrol', $tahun)
            ->groupBy('tahun', 'bulan')
            ->get();
    }

    public function getChartData($tahun, $tanggal)
    {
        return [
            'rekon' => $this->rekonTahun($tahun, $tanggal),
            'monitoring' => $this->monitoringTahun($tahun, $tanggal),
            'batal' => $this->batalTahun($tahun, $tanggal),
        ];
    }
}