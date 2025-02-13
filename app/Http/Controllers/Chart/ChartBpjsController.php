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

        $dataBpjsTahunIni =  $this->peserta($tahunIni);
        $dataBpjsTahunLalu =  $this->peserta($tahunLalu);

        $dataKunjunganTahunIni =  $this->kunjungan($tahunIni);
        $dataKunjunganTahunLalu =  $this->kunjungan($tahunLalu);

        $dataRekonTahunIni =  $this->rekon($tahunIni, $tglAkhir);
        $dataRekonTahunLalu =  $this->rekon($tahunLalu, $tglAkhirLalu);

        $dataMonitoringTahunIni =  $this->monitoring($tahunIni, $tglAkhir);
        $dataMonitoringTahunLalu =  $this->monitoring($tahunLalu, $tglAkhirLalu);

        $dataBatalTahunIni =  $this->batal($tahunIni, $tglAkhir);
        $dataBatalTahunLalu =  $this->batal($tahunLalu, $tglAkhirLalu);

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

    private function peserta($tahun)
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

    private function kunjungan($tahun)
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

    private function rekon($tahun, $tanggal)
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

    private function monitoring($tahun, $tanggal)
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

    private function batal($tahun, $tanggal)
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
            'rekon' => $this->rekon($tahun, $tanggal),
            'monitoring' => $this->monitoring($tahun, $tanggal),
            'batal' => $this->batal($tahun, $tanggal),
        ];
    }
}