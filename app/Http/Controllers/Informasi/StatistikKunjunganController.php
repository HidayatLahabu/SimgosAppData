<?php

namespace App\Http\Controllers\Informasi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\InformasiStatistikIndikatorModel;

class StatistikKunjunganController extends Controller
{
    public function index()
    {
        $kunjungan = $this->getKunjungan();
        $dataKunjungan = $kunjungan->orderByDesc('statistikKunjungan.TANGGAL')->paginate(7)->appends(request()->query());
        $dataKunjungan = $dataKunjungan->toArray();

        $rujukan = $this->getRujukan();
        $dataRujukan = $rujukan->orderByDesc('statistikRujukan.TANGGAL')->paginate(7)->appends(request()->query());
        $dataRujukan = $dataRujukan->toArray();

        $kunjunganMingguan = $this->getWeeklyKunjungan();
        $dataKunjunganMingguan = $kunjunganMingguan->paginate(4)->appends(request()->query());
        $dataKunjunganMingguan = $dataKunjunganMingguan->toArray();

        $rujukanMingguan = $this->getWeeklyRujukan();
        $dataRujukanMingguan = $rujukanMingguan->paginate(4)->appends(request()->query());
        $dataRujukanMingguan = $dataRujukanMingguan->toArray();

        $perPage = 12; // Items per page
        $kunjunganBulanan = $this->getMonthlyKunjungan($perPage);
        $rujukanBulanan = $this->getMonthlyRujukan($perPage);

        $indikator = $this->getIndikator();
        $statistikIndikator = $indikator->paginate(8)->appends(request()->query());
        $statistikIndikator = $statistikIndikator->toArray();

        return inertia("Informasi/StatistikKunjungan/Index", [
            'tableKunjungan' => [
                'dataKunjungan' => $dataKunjungan['data'],
                'linksKunjungan' => $dataKunjungan['links'],
            ],
            'tableRujukan' => [
                'dataRujukan' => $dataRujukan['data'],
                'linksRujukan' => $dataRujukan['links'],
            ],
            'kunjunganMingguan' => [
                'dataKunjunganMingguan' => $dataKunjunganMingguan['data'],
                'linksKunjunganMingguan' => $dataKunjunganMingguan['links'],
            ],
            'rujukanMingguan' => [
                'dataRujukanMingguan' => $dataRujukanMingguan['data'],
                'linksRujukanMingguan' => $dataRujukanMingguan['links'],
            ],
            'kunjunganBulanan' => $kunjunganBulanan,
            'rujukanBulanan' => $rujukanBulanan,
            'statistikIndikator' => [
                'dataIndikator' => $statistikIndikator['data'],
                'linksIndikator' => $statistikIndikator['links'],
            ],
        ]);
    }

    protected function getKunjungan()
    {
        return DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                'statistikKunjungan.TANGGAL as tanggal',
                DB::raw('SUM(statistikKunjungan.RJ) as rajal'),
                DB::raw('SUM(statistikKunjungan.RD) as darurat'),
                DB::raw('SUM(statistikKunjungan.RI) as ranap'),
                'statistikKunjungan.TANGGAL_UPDATED as tanggalUpdated'
            )
            ->where('statistikKunjungan.RJ', '>', 0)
            ->orWhere('statistikKunjungan.RD', '>', 0)
            ->orWhere('statistikKunjungan.RI', '>', 0)
            ->groupBy('statistikKunjungan.TANGGAL');
    }

    protected function getRujukan()
    {
        return DB::connection('mysql12')->table('informasi.statistik_rujukan as statistikRujukan')
            ->select(
                'statistikRujukan.TANGGAL as tanggal',
                DB::raw('SUM(statistikRujukan.MASUK) as masuk'),
                DB::raw('SUM(statistikRujukan.KELUAR) as keluar'),
                DB::raw('SUM(statistikRujukan.BALIK) as balik'),
                'statistikRujukan.TANGGAL_UPDATED as tanggalUpdated'
            )
            ->where('statistikRujukan.MASUK', '>', 0)
            ->orWhere('statistikRujukan.KELUAR', '>', 0)
            ->orWhere('statistikRujukan.BALIK', '>', 0)
            ->groupBy('statistikRujukan.TANGGAL');
    }

    protected function getWeeklyKunjungan()
    {
        return DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                DB::raw('YEAR(statistikKunjungan.TANGGAL) as tahun'),
                DB::raw('WEEK(statistikKunjungan.TANGGAL, 1) as minggu'),
                DB::raw('SUM(statistikKunjungan.RJ) as total_rj'),
                DB::raw('SUM(statistikKunjungan.RD) as total_rd'),
                DB::raw('SUM(statistikKunjungan.RI) as total_ri')
            )
            ->where(function ($query) {
                $query->where('statistikKunjungan.RJ', '>', 0)
                    ->orWhere('statistikKunjungan.RD', '>', 0)
                    ->orWhere('statistikKunjungan.RI', '>', 0);
            })
            ->groupBy(
                DB::raw('YEAR(statistikKunjungan.TANGGAL)'),
                DB::raw('WEEK(statistikKunjungan.TANGGAL, 1)')
            )
            ->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc');
    }

    protected function getWeeklyRujukan()
    {
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
            ->groupBy(
                DB::raw('YEAR(statistikRujukan.TANGGAL)'),
                DB::raw('WEEK(statistikRujukan.TANGGAL, 1)')
            )
            ->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc');
    }

    protected function getMonthlyKunjungan($perPage)
    {
        return DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                DB::raw('SUM(statistikKunjungan.RJ) as rajal'),
                DB::raw('SUM(statistikKunjungan.RD) as darurat'),
                DB::raw('SUM(statistikKunjungan.RI) as ranap'),
                DB::raw('YEAR(statistikKunjungan.TANGGAL) as tahun'),
                DB::raw('MONTH(statistikKunjungan.TANGGAL) as bulan'),
            )
            ->havingRaw('SUM(statistikKunjungan.RJ) > 0')
            ->orHavingRaw('SUM(statistikKunjungan.RD) > 0')
            ->orHavingRaw('SUM(statistikKunjungan.RI) > 0')
            ->groupBy(
                DB::raw('YEAR(statistikKunjungan.TANGGAL)'),
                DB::raw('MONTH(statistikKunjungan.TANGGAL)'),
            )
            ->orderBy(DB::raw('YEAR(statistikKunjungan.TANGGAL)'), 'desc')
            ->orderBy(DB::raw('MONTH(statistikKunjungan.TANGGAL)'), 'desc')
            ->paginate($perPage);
    }

    protected function getMonthlyRujukan($perPage)
    {
        return DB::connection('mysql12')->table('informasi.statistik_rujukan as statistikRujukan')
            ->select(
                DB::raw('SUM(statistikRujukan.MASUK) as masuk'),
                DB::raw('SUM(statistikRujukan.KELUAR) as keluar'),
                DB::raw('SUM(statistikRujukan.BALIK) as balik'),
                DB::raw('YEAR(statistikRujukan.TANGGAL) as tahun'),
                DB::raw('MONTH(statistikRujukan.TANGGAL) as bulan'),
            )
            ->groupBy(
                DB::raw('YEAR(statistikRujukan.TANGGAL)'),
                DB::raw('MONTH(statistikRujukan.TANGGAL)'),
            )
            ->orderBy(DB::raw('YEAR(statistikRujukan.TANGGAL)'), 'desc')
            ->orderBy(DB::raw('MONTH(statistikRujukan.TANGGAL)'), 'desc')
            ->paginate($perPage);
    }

    protected function getIndikator()
    {
        return InformasiStatistikIndikatorModel::where('JENIS', 2)
            ->where('BOR', '>', 0)
            ->where('ALOS', '>', 0)
            ->where('BTO', '>', 0)
            ->where('TOI', '>', 0)
            ->where('NDR', '>', 0)
            ->where('GDR', '>', 0)
            ->orderByDesc('TANGGAL_UPDATED');
    }
}