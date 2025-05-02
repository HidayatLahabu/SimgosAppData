<?php

namespace App\Http\Controllers\Chart;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ChartStatistikKunjunganController extends Controller
{
    public function index()
    {
        $tahunIni = now()->year; // ambil tahun sekarang
        $tahunLalu = $tahunIni - 1;

        $kunjunganHarian = $this->getKunjunganHarian();
        $rujukanHarian = $this->getRujukanHarian();
        $kunjunganMingguan = $this->getKunjunganMingguan();
        $rujukanMingguan = $this->getRujukanMingguan();
        $kunjunganBulanan = $this->getKunjunganBulanan();
        $rujukanBulanan = $this->getRujukanBulanan();
        $kunjunganTahunan = $this->getKunjunganTahunan();
        $rujukanTahunan = $this->getRujukanTahunan();
        $rajalBulanan = $this->getRajalBulanan($tahunIni);
        $rajalBulananLalu = $this->getRajalBulanan($tahunLalu);
        $ranapBulananIni = $this->getRanapBulanan($tahunIni);
        $ranapBulananLalu = $this->getRanapBulanan($tahunLalu);
        $penunjangBulanan = $this->getPenunjangBulanan($tahunIni);
        $penunjangBulananLalu = $this->getPenunjangBulanan($tahunLalu);

        return inertia("Chart/Informasi/Index", [
            'tahunIni' => $tahunIni,
            'tahunLalu' => $tahunLalu,
            'kunjunganHarian' => $kunjunganHarian->toArray(),
            'rujukanHarian' => $rujukanHarian->toArray(),
            'kunjunganMingguan' => $kunjunganMingguan->toArray(),
            'rujukanMingguan' => $rujukanMingguan->toArray(),
            'kunjunganBulanan' => $kunjunganBulanan->toArray(),
            'rujukanBulanan' => $rujukanBulanan->toArray(),
            'kunjunganTahunan' => $kunjunganTahunan->toArray(),
            'rujukanTahunan' => $rujukanTahunan->toArray(),
            'rajalBulanan' => $rajalBulanan->toArray(),
            'rajalBulananLalu' => $rajalBulananLalu->toArray(),
            'ranapBulananIni' => $ranapBulananIni->toArray(),
            'ranapBulananLalu' => $ranapBulananLalu->toArray(),
            'rajalBulanan' => $rajalBulanan->toArray(),
            'rajalBulananLalu' => $rajalBulananLalu->toArray(),
            'penunjangBulanan' => $penunjangBulanan->toArray(),
            'penunjangBulananLalu' => $penunjangBulananLalu->toArray(),
        ]);
    }

    protected function getKunjunganHarian()
    {
        $sevenDaysAgo = now()->subDays(7)->format('Y-m-d');

        return DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                'statistikKunjungan.TANGGAL as tanggal',
                DB::raw('SUM(statistikKunjungan.RJ) as rajal'),
                DB::raw('SUM(statistikKunjungan.RD) as darurat'),
                DB::raw('SUM(statistikKunjungan.RI) as ranap'),
                'statistikKunjungan.TANGGAL_UPDATED as tanggalUpdated'
            )
            ->whereDate('statistikKunjungan.TANGGAL', '>=', $sevenDaysAgo)
            ->groupBy('statistikKunjungan.TANGGAL')
            ->orderBy('statistikKunjungan.TANGGAL', 'asc')
            ->get();
    }

    protected function getRujukanHarian()
    {
        $sevenDaysAgo = now()->subDays(7)->format('Y-m-d');

        return DB::connection('mysql12')->table('informasi.statistik_rujukan as statistikRujukan')
            ->select(
                'statistikRujukan.TANGGAL as tanggal',
                DB::raw('SUM(statistikRujukan.MASUK) as masuk'),
                DB::raw('SUM(statistikRujukan.KELUAR) as keluar'),
                DB::raw('SUM(statistikRujukan.BALIK) as balik'),
                'statistikRujukan.TANGGAL_UPDATED as tanggalUpdated'
            )
            ->whereDate('statistikRujukan.TANGGAL', '>=', $sevenDaysAgo)
            ->groupBy('statistikRujukan.TANGGAL')
            ->orderBy('statistikRujukan.TANGGAL', 'asc')
            ->get();
    }

    protected function getKunjunganMingguan()
    {
        $eightWeeksAgo = now()->subWeeks(8)->format('Y-m-d');

        return DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                DB::raw('YEAR(statistikKunjungan.TANGGAL) as tahun'),
                DB::raw('WEEK(statistikKunjungan.TANGGAL, 1) as minggu'),
                DB::raw('SUM(statistikKunjungan.RJ) as rajal'),
                DB::raw('SUM(statistikKunjungan.RD) as darurat'),
                DB::raw('SUM(statistikKunjungan.RI) as ranap')
            )
            ->where(function ($query) {
                $query->where('statistikKunjungan.RJ', '>', 0)
                    ->orWhere('statistikKunjungan.RD', '>', 0)
                    ->orWhere('statistikKunjungan.RI', '>', 0);
            })
            ->whereDate('statistikKunjungan.TANGGAL', '>=', $eightWeeksAgo)
            ->groupBy(
                DB::raw('YEAR(statistikKunjungan.TANGGAL)'),
                DB::raw('WEEK(statistikKunjungan.TANGGAL, 1)')
            )
            ->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc')
            ->get();
    }

    protected function getRujukanMingguan()
    {
        $eightWeeksAgo = now()->subWeeks(8)->format('Y-m-d');

        return DB::connection('mysql12')->table('informasi.statistik_rujukan as statistikRujukan')
            ->select(
                DB::raw('YEAR(statistikRujukan.TANGGAL) as tahun'),
                DB::raw('WEEK(statistikRujukan.TANGGAL, 1) as minggu'),
                DB::raw('SUM(statistikRujukan.MASUK) as masuk'),
                DB::raw('SUM(statistikRujukan.KELUAR) as keluar'),
                DB::raw('SUM(statistikRujukan.BALIK) as balik')
            )
            ->where(function ($query) {
                $query->where('statistikRujukan.MASUK', '>', 0)
                    ->orWhere('statistikRujukan.KELUAR', '>', 0)
                    ->orWhere('statistikRujukan.BALIK', '>', 0);
            })
            ->whereDate('statistikRujukan.TANGGAL', '>=', $eightWeeksAgo)
            ->groupBy(
                DB::raw('YEAR(statistikRujukan.TANGGAL)'),
                DB::raw('WEEK(statistikRujukan.TANGGAL, 1)')
            )
            ->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc')
            ->get();
    }

    protected function getKunjunganBulanan()
    {
        $twelveMonthsAgo = now()->subMonths(12)->format('Y-m-d');

        return DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                DB::raw('SUM(statistikKunjungan.RJ) as rajal'),
                DB::raw('SUM(statistikKunjungan.RD) as darurat'),
                DB::raw('SUM(statistikKunjungan.RI) as ranap'),
                DB::raw('YEAR(statistikKunjungan.TANGGAL) as tahun'),
                DB::raw('MONTH(statistikKunjungan.TANGGAL) as bulan')
            )
            ->whereDate('statistikKunjungan.TANGGAL', '>=', $twelveMonthsAgo)
            ->groupBy(
                DB::raw('YEAR(statistikKunjungan.TANGGAL)'),
                DB::raw('MONTH(statistikKunjungan.TANGGAL)')
            )
            ->orderBy(DB::raw('YEAR(statistikKunjungan.TANGGAL)'), 'desc')
            ->orderBy(DB::raw('MONTH(statistikKunjungan.TANGGAL)'), 'desc')
            ->get();
    }

    protected function getRujukanBulanan()
    {
        $twelveMonthsAgo = now()->subMonths(12)->format('Y-m-d');

        return DB::connection('mysql12')->table('informasi.statistik_rujukan as statistikRujukan')
            ->select(
                DB::raw('SUM(statistikRujukan.MASUK) as masuk'),
                DB::raw('SUM(statistikRujukan.KELUAR) as keluar'),
                DB::raw('SUM(statistikRujukan.BALIK) as balik'),
                DB::raw('YEAR(statistikRujukan.TANGGAL) as tahun'),
                DB::raw('MONTH(statistikRujukan.TANGGAL) as bulan'),
            )
            ->whereDate('statistikRujukan.TANGGAL', '>=', $twelveMonthsAgo)
            ->groupBy(
                DB::raw('YEAR(statistikRujukan.TANGGAL)'),
                DB::raw('MONTH(statistikRujukan.TANGGAL)')
            )
            ->orderBy(DB::raw('YEAR(statistikRujukan.TANGGAL)'), 'desc')
            ->orderBy(DB::raw('MONTH(statistikRujukan.TANGGAL)'), 'desc')
            ->get();
    }

    protected function getKunjunganTahunan()
    {
        $fiveYearsAgo = now()->subYears(5)->format('Y-m-d');

        return DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                DB::raw('SUM(statistikKunjungan.RJ) as rajal'),
                DB::raw('SUM(statistikKunjungan.RD) as darurat'),
                DB::raw('SUM(statistikKunjungan.RI) as ranap'),
                DB::raw('YEAR(statistikKunjungan.TANGGAL) as tahun')
            )
            ->whereDate('statistikKunjungan.TANGGAL', '>=', $fiveYearsAgo)
            ->groupBy(DB::raw('YEAR(statistikKunjungan.TANGGAL)'))
            ->orderBy(DB::raw('YEAR(statistikKunjungan.TANGGAL)'), 'desc')
            ->get();
    }

    protected function getRujukanTahunan()
    {
        $fiveYearsAgo = now()->subYears(5)->format('Y-m-d');

        return DB::connection('mysql12')->table('informasi.statistik_rujukan as statistikRujukan')
            ->select(
                DB::raw('SUM(statistikRujukan.MASUK) as masuk'),
                DB::raw('SUM(statistikRujukan.KELUAR) as keluar'),
                DB::raw('SUM(statistikRujukan.BALIK) as balik'),
                DB::raw('YEAR(statistikRujukan.TANGGAL) as tahun'),
            )
            ->whereDate('statistikRujukan.TANGGAL', '>=', $fiveYearsAgo)
            ->groupBy(DB::raw('YEAR(statistikRujukan.TANGGAL)'))
            ->orderBy(DB::raw('YEAR(statistikRujukan.TANGGAL)'), 'desc')
            ->get();
    }

    protected function getRajalBulanan($tahun)
    {
        return DB::connection('mysql12')->table('informasi.kunjungan as kunjungan')
            ->select(
                DB::raw('MIN(kunjungan.SUBUNIT) as subUnit'),
                DB::raw('SUM(kunjungan.VALUE) as jumlah')
            )
            ->whereYear('kunjungan.TANGGAL', $tahun)
            ->groupBy('kunjungan.SUBUNIT')
            ->havingRaw('SUM(kunjungan.VALUE) > 0')
            ->orderByRaw('SUM(kunjungan.VALUE) DESC')
            ->get();
    }

    protected function getRanapBulanan($tahun)
    {
        return DB::connection('mysql12')->table('informasi.pasien_rawat_inap as pasienRanap')
            ->select(
                DB::raw('MONTH(pasienRanap.TANGGAL) as bulan'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "1" THEN pasienRanap.VALUE ELSE 0 END) as masuk'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "2" THEN pasienRanap.VALUE ELSE 0 END) as dirawat'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "3" THEN pasienRanap.VALUE ELSE 0 END) as keluar')
            )
            ->whereYear('pasienRanap.TANGGAL', $tahun)
            ->groupBy(DB::raw('YEAR(pasienRanap.TANGGAL), MONTH(pasienRanap.TANGGAL)'))
            ->orderBy(DB::raw('MONTH(pasienRanap.TANGGAL)'), 'asc')
            ->get();
    }

    protected function getPenunjangBulanan($tahun)
    {
        return DB::connection('mysql12')->table('informasi.penunjang as penunjang')
            ->select(
                DB::raw('MIN(penunjang.SUBUNIT) as subUnit'),
                DB::raw('SUM(penunjang.VALUE) as jumlah')
            )
            ->whereYear('penunjang.TANGGAL', $tahun)
            ->groupBy('penunjang.SUBUNIT')
            ->havingRaw('SUM(penunjang.VALUE) > 0')
            ->orderByRaw('SUM(penunjang.VALUE) DESC')
            ->get();
    }
}
