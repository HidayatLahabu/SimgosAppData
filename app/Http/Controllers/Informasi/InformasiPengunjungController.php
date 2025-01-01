<?php

namespace App\Http\Controllers\Informasi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InformasiPengunjungController extends Controller
{
    public function index()
    {
        $queryPengunjung = $this->getRajal();
        $dataPengunjung = $queryPengunjung->orderByDesc('pengunjung.TANGGAL')->paginate(7)->appends(request()->query());
        $dataPengunjung = $dataPengunjung->toArray();

        $queryRanap = $this->getRanap();
        $dataRanap = $queryRanap->orderByDesc('pasienRanap.TANGGAL')->paginate(7)->appends(request()->query());
        $dataRanap = $dataRanap->toArray();

        $rajalMingguan = $this->getWeeklyRajal();
        $dataRajalMingguan = $rajalMingguan->paginate(4)->appends(request()->query());
        $dataRajalMingguan = $dataRajalMingguan->toArray();

        $ranapMingguan = $this->getWeeklyRanap();
        $dataRanapMingguan = $ranapMingguan->paginate(4)->appends(request()->query());
        $dataRanapMingguan = $dataRanapMingguan->toArray();

        $perPage = 12; // Items per page
        $rajalBulanan = $this->getMonthlyRajal($perPage);
        $ranapBulanan = $this->getMonthlyRanap($perPage);

        // Return Inertia view with paginated data
        return inertia("Informasi/Pengunjung/Index", [
            'tablePengunjung' => [
                'dataPengunjung' => $dataPengunjung['data'],
                'linksPengunjung' => $dataPengunjung['links'],
            ],
            'tableRanap' => [
                'dataRanap' => $dataRanap['data'],
                'linksRanap' => $dataRanap['links'],
            ],
            'rajalMingguan' => [
                'dataRajalMingguan' => $dataRajalMingguan['data'],
                'linksRajalMingguan' => $dataRajalMingguan['links'],
            ],
            'ranapMingguan' => [
                'dataRanapMingguan' => $dataRanapMingguan['data'],
                'linksRanapMingguan' => $dataRanapMingguan['links'],
            ],
            'rajalBulanan' => $rajalBulanan,
            'ranapBulanan' => $ranapBulanan,
        ]);
    }

    protected function getRajal()
    {
        return DB::connection('mysql12')->table('informasi.pengunjung as pengunjung')
            ->select(
                'pengunjung.TANGGAL as tanggal',
                DB::raw('SUM(pengunjung.VALUE) as semua'),
                DB::raw('SUM(CASE WHEN pengunjung.ID = "1" THEN pengunjung.VALUE ELSE 0 END) as rajal'),
                DB::raw('SUM(CASE WHEN pengunjung.ID = "2" THEN pengunjung.VALUE ELSE 0 END) as darurat'),
                DB::raw('MAX(pengunjung.LASTUPDATED) as lastUpdated')
            )
            ->groupBy('pengunjung.TANGGAL');
    }

    protected function getRanap()
    {
        return DB::connection('mysql12')->table('informasi.pasien_rawat_inap as pasienRanap')
            ->select(
                'pasienRanap.TANGGAL as tanggal',
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "1" THEN pasienRanap.VALUE ELSE 0 END) as masuk'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "2" THEN pasienRanap.VALUE ELSE 0 END) as dirawat'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "3" THEN pasienRanap.VALUE ELSE 0 END) as keluar'),
                DB::raw('MAX(pasienRanap.LASTUPDATED) as lastUpdated')
            )
            ->groupBy('pasienRanap.TANGGAL');
    }

    protected function getWeeklyRajal()
    {
        return DB::connection('mysql12')->table('informasi.pengunjung as pengunjung')
            ->select(
                DB::raw('YEAR(pengunjung.TANGGAL) as tahun'),
                DB::raw('WEEK(pengunjung.TANGGAL, 1) as minggu'),
                DB::raw('SUM(CASE WHEN pengunjung.ID = "1" THEN pengunjung.VALUE ELSE 0 END) as rajal'),
                DB::raw('SUM(CASE WHEN pengunjung.ID = "2" THEN pengunjung.VALUE ELSE 0 END) as darurat'),
                DB::raw('SUM(pengunjung.VALUE) as semua'),
            )
            ->groupBy(
                DB::raw('YEAR(pengunjung.TANGGAL)'),
                DB::raw('WEEK(pengunjung.TANGGAL, 1)')
            )
            ->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc');
    }

    protected function getWeeklyRanap()
    {
        return DB::connection('mysql12')->table('informasi.pasien_rawat_inap as pasienRanap')
            ->select(
                DB::raw('YEAR(pasienRanap.TANGGAL) as tahun'),
                DB::raw('WEEK(pasienRanap.TANGGAL, 1) as minggu'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "1" THEN pasienRanap.VALUE ELSE 0 END) as masuk'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "2" THEN pasienRanap.VALUE ELSE 0 END) as dirawat'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "3" THEN pasienRanap.VALUE ELSE 0 END) as keluar'),
            )
            ->groupBy(
                DB::raw('YEAR(pasienRanap.TANGGAL)'),
                DB::raw('WEEK(pasienRanap.TANGGAL, 1)')
            )
            ->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc');
    }

    protected function getMonthlyRajal($perPage)
    {
        return DB::connection('mysql12')->table('informasi.pengunjung as pengunjung')
            ->select(
                DB::raw('SUM(CASE WHEN pengunjung.ID = "1" THEN pengunjung.VALUE ELSE 0 END) as rajal'),
                DB::raw('SUM(CASE WHEN pengunjung.ID = "2" THEN pengunjung.VALUE ELSE 0 END) as darurat'),
                DB::raw('SUM(pengunjung.VALUE) as semua'),
                DB::raw('YEAR(pengunjung.TANGGAL) as tahun'),
                DB::raw('MONTH(pengunjung.TANGGAL) as bulan'),
            )
            ->groupBy(
                DB::raw('YEAR(pengunjung.TANGGAL)'),
                DB::raw('MONTH(pengunjung.TANGGAL)')
            )
            ->orderBy(DB::raw('YEAR(pengunjung.TANGGAL)'), 'desc')
            ->orderBy(DB::raw('MONTH(pengunjung.TANGGAL)'), 'desc')
            ->paginate($perPage);
    }

    protected function getMonthlyRanap($perPage)
    {
        return DB::connection('mysql12')->table('informasi.pasien_rawat_inap as pasienRanap')
            ->select(
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "1" THEN pasienRanap.VALUE ELSE 0 END) as masuk'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "2" THEN pasienRanap.VALUE ELSE 0 END) as dirawat'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "3" THEN pasienRanap.VALUE ELSE 0 END) as keluar'),
                DB::raw('YEAR(pasienRanap.TANGGAL) as tahun'),
                DB::raw('MONTH(pasienRanap.TANGGAL) as bulan'),
            )
            ->groupBy(
                DB::raw('YEAR(pasienRanap.TANGGAL)'),
                DB::raw('MONTH(pasienRanap.TANGGAL)')
            )
            ->orderBy(DB::raw('YEAR(pasienRanap.TANGGAL)'), 'desc')
            ->orderBy(DB::raw('MONTH(pasienRanap.TANGGAL)'), 'desc')
            ->paginate($perPage);
    }
}
