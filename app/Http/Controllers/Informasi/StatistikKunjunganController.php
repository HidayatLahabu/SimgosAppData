<?php

namespace App\Http\Controllers\Informasi;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StatistikKunjunganController extends Controller
{
    public function index()
    {
        $kunjunganHarian = $this->getKunjunganHarian();
        $dataKunjunganHarian = $kunjunganHarian->orderByDesc('statistikKunjungan.TANGGAL')->paginate(5)->appends(request()->query());
        $dataKunjunganHarian = $dataKunjunganHarian->toArray();

        $rujukanHarian = $this->getRujukanHarian();
        $dataRujukanHarian = $rujukanHarian->orderByDesc('statistikRujukan.TANGGAL')->paginate(5)->appends(request()->query());
        $dataRujukanHarian = $dataRujukanHarian->toArray();

        $kunjunganMingguan = $this->getKunjunganMingguan();
        $dataKunjunganMingguan = $kunjunganMingguan->paginate(5)->appends(request()->query());
        $dataKunjunganMingguan = $dataKunjunganMingguan->toArray();

        $rujukanMingguan = $this->getRujukanMingguan();
        $dataRujukanMingguan = $rujukanMingguan->paginate(5)->appends(request()->query());
        $dataRujukanMingguan = $dataRujukanMingguan->toArray();

        $pageBulan = 5; // Items per page
        $kunjunganBulanan = $this->getKunjunganBulanan($pageBulan);
        $rujukanBulanan = $this->getRujukanBulanan($pageBulan);

        $pageTahun = 5;
        $kunjunganTahunan = $this->getKunjunganTahunan($pageTahun);
        $rujukanTahunan = $this->getRujukanTahunan($pageTahun);

        return inertia("Informasi/StatistikKunjungan/Index", [
            'kunjunganHarian' => [
                'data' => $dataKunjunganHarian['data'],
                'links' => $dataKunjunganHarian['links'],
            ],
            'rujukanHarian' => [
                'data' => $dataRujukanHarian['data'],
                'links' => $dataRujukanHarian['links'],
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
            'kunjunganTahunan' => $kunjunganTahunan,
            'rujukanTahunan' => $rujukanTahunan,
        ]);
    }

    protected function getKunjunganHarian()
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

    protected function getRujukanHarian()
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

    protected function getKunjunganMingguan()
    {
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
            ->groupBy(
                DB::raw('YEAR(statistikKunjungan.TANGGAL)'),
                DB::raw('WEEK(statistikKunjungan.TANGGAL, 1)')
            )
            ->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc');
    }

    protected function getRujukanMingguan()
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

    protected function getKunjunganBulanan($perPage)
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

    protected function getRujukanBulanan($perPage)
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

    protected function getKunjunganTahunan($perPage)
    {
        return DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                DB::raw('SUM(statistikKunjungan.RJ) as rajal'),
                DB::raw('SUM(statistikKunjungan.RD) as darurat'),
                DB::raw('SUM(statistikKunjungan.RI) as ranap'),
                DB::raw('YEAR(statistikKunjungan.TANGGAL) as tahun')
            )
            ->havingRaw('SUM(statistikKunjungan.RJ) > 0')
            ->orHavingRaw('SUM(statistikKunjungan.RD) > 0')
            ->orHavingRaw('SUM(statistikKunjungan.RI) > 0')
            ->groupBy(
                DB::raw('YEAR(statistikKunjungan.TANGGAL)')
            )
            ->orderBy(DB::raw('YEAR(statistikKunjungan.TANGGAL)'), 'desc')
            ->paginate($perPage);
    }

    protected function getRujukanTahunan($perPage)
    {
        return DB::connection('mysql12')->table('informasi.statistik_rujukan as statistikRujukan')
            ->select(
                DB::raw('SUM(statistikRujukan.MASUK) as masuk'),
                DB::raw('SUM(statistikRujukan.KELUAR) as keluar'),
                DB::raw('SUM(statistikRujukan.BALIK) as balik'),
                DB::raw('YEAR(statistikRujukan.TANGGAL) as tahun'),
            )
            ->groupBy(
                DB::raw('YEAR(statistikRujukan.TANGGAL)')
            )
            ->orderBy(DB::raw('YEAR(statistikRujukan.TANGGAL)'), 'desc')
            ->paginate($perPage);
    }

    public function print(Request $request)
    {
        // Validasi input
        $request->validate([
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        // Ambil nilai input
        $dariTanggal = Carbon::parse($request->input('dari_tanggal'))->format('Y-m-d H:i:s');
        $sampaiTanggal = Carbon::parse($request->input('sampai_tanggal'))->endOfDay()->format('Y-m-d H:i:s');

        // Query untuk kunjungan dan rujukan dalam satu fungsi
        $kunjunganHarian = DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                'statistikKunjungan.TANGGAL as tanggal',
                DB::raw('SUM(statistikKunjungan.RJ) as rajal'),
                DB::raw('SUM(statistikKunjungan.RD) as darurat'),
                DB::raw('SUM(statistikKunjungan.RI) as ranap'),
                'statistikKunjungan.TANGGAL_UPDATED as tanggalUpdated',
            )
            ->where(function ($query) {
                $query->where('statistikKunjungan.RJ', '>', 0)
                    ->orWhere('statistikKunjungan.RD', '>', 0)
                    ->orWhere('statistikKunjungan.RI', '>', 0);
            })
            ->whereBetween('statistikKunjungan.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->groupBy('statistikKunjungan.TANGGAL')
            ->orderByDesc('statistikKunjungan.TANGGAL')
            ->get();

        $rujukanHarian = DB::connection('mysql12')->table('informasi.statistik_rujukan as statistikRujukan')
            ->select(
                'statistikRujukan.TANGGAL as tanggal',
                DB::raw('SUM(statistikRujukan.MASUK) as masuk'),
                DB::raw('SUM(statistikRujukan.KELUAR) as keluar'),
                DB::raw('SUM(statistikRujukan.BALIK) as balik'),
                'statistikRujukan.TANGGAL_UPDATED as tanggalUpdated',
            )
            ->where(function ($query) {
                $query->where('statistikRujukan.MASUK', '>', 0)
                    ->orWhere('statistikRujukan.KELUAR', '>', 0)
                    ->orWhere('statistikRujukan.BALIK', '>', 0);
            })
            ->whereBetween('statistikRujukan.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->groupBy('statistikRujukan.TANGGAL')
            ->orderByDesc('statistikRujukan.TANGGAL')
            ->get();

        $kunjunganMingguan = DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                DB::raw('YEAR(statistikKunjungan.TANGGAL) as tahun'),
                DB::raw('WEEK(statistikKunjungan.TANGGAL, 1) as minggu'),
                DB::raw('MAX(statistikKunjungan.TANGGAL) as tanggal_max'),
                DB::raw('SUM(statistikKunjungan.RJ) as rajal'),
                DB::raw('SUM(statistikKunjungan.RD) as darurat'),
                DB::raw('SUM(statistikKunjungan.RI) as ranap')
            )
            ->where(function ($query) {
                $query->where('statistikKunjungan.RJ', '>', 0)
                    ->orWhere('statistikKunjungan.RD', '>', 0)
                    ->orWhere('statistikKunjungan.RI', '>', 0);
            })
            ->whereBetween('statistikKunjungan.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->groupBy(
                DB::raw('YEAR(statistikKunjungan.TANGGAL)'),
                DB::raw('WEEK(statistikKunjungan.TANGGAL, 1)')
            )
            ->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc')
            ->get();

        $rujukanMingguan = DB::connection('mysql12')->table('informasi.statistik_rujukan as statistikRujukan')
            ->select(
                DB::raw('YEAR(statistikRujukan.TANGGAL) as tahun'),
                DB::raw('WEEK(statistikRujukan.TANGGAL, 1) as minggu'),
                DB::raw('MAX(statistikRujukan.TANGGAL) as tanggal_max'),
                DB::raw('SUM(statistikRujukan.MASUK) as masuk'),
                DB::raw('SUM(statistikRujukan.KELUAR) as keluar'),
                DB::raw('SUM(statistikRujukan.BALIK) as balik'),
            )
            ->where(function ($query) {
                $query->where('statistikRujukan.MASUK', '>', 0)
                    ->orWhere('statistikRujukan.KELUAR', '>', 0)
                    ->orWhere('statistikRujukan.BALIK', '>', 0);
            })
            ->whereBetween('statistikRujukan.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->groupBy(
                DB::raw('YEAR(statistikRujukan.TANGGAL)'),
                DB::raw('WEEK(statistikRujukan.TANGGAL, 1)')
            )
            ->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc')
            ->get();

        $kunjunganBulanan = DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                DB::raw('YEAR(statistikKunjungan.TANGGAL) as tahun'),
                DB::raw('MONTH(statistikKunjungan.TANGGAL) as bulan'),
                DB::raw('SUM(statistikKunjungan.RJ) as rajal'),
                DB::raw('SUM(statistikKunjungan.RD) as darurat'),
                DB::raw('SUM(statistikKunjungan.RI) as ranap'),
            )
            ->where(function ($query) {
                $query->where('statistikKunjungan.RJ', '>', 0)
                    ->orWhere('statistikKunjungan.RD', '>', 0)
                    ->orWhere('statistikKunjungan.RI', '>', 0);
            })
            ->whereBetween('statistikKunjungan.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->groupBy(
                DB::raw('YEAR(statistikKunjungan.TANGGAL)'),
                DB::raw('MONTH(statistikKunjungan.TANGGAL)')
            )
            ->groupBy(DB::raw('YEAR(statistikKunjungan.TANGGAL)'), DB::raw('MONTH(statistikKunjungan.TANGGAL)'))
            ->orderByDesc(DB::raw('YEAR(statistikKunjungan.TANGGAL)'))
            ->orderByDesc(DB::raw('MONTH(statistikKunjungan.TANGGAL)'))
            ->get();

        $rujukanBulanan = DB::connection('mysql12')->table('informasi.statistik_rujukan as statistikRujukan')
            ->select(
                DB::raw('YEAR(statistikRujukan.TANGGAL) as tahun'),
                DB::raw('MONTH(statistikRujukan.TANGGAL) as bulan'),
                DB::raw('SUM(statistikRujukan.MASUK) as masuk'),
                DB::raw('SUM(statistikRujukan.KELUAR) as keluar'),
                DB::raw('SUM(statistikRujukan.BALIK) as balik')
            )
            ->where(function ($query) {
                $query->where('statistikRujukan.MASUK', '>', 0)
                    ->orWhere('statistikRujukan.KELUAR', '>', 0)
                    ->orWhere('statistikRujukan.BALIK', '>', 0);
            })
            ->whereBetween('statistikRujukan.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->groupBy(
                DB::raw('YEAR(statistikRujukan.TANGGAL)'),
                DB::raw('MONTH(statistikRujukan.TANGGAL)')
            )
            ->orderByDesc(DB::raw('YEAR(statistikRujukan.TANGGAL)'))
            ->orderByDesc(DB::raw('MONTH(statistikRujukan.TANGGAL)'))
            ->get();

        $kunjunganTahunan = DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                DB::raw('YEAR(statistikKunjungan.TANGGAL) as tahun'),
                DB::raw('SUM(statistikKunjungan.RJ) as rajal'),
                DB::raw('SUM(statistikKunjungan.RD) as darurat'),
                DB::raw('SUM(statistikKunjungan.RI) as ranap'),
            )
            ->where(DB::raw('YEAR(statistikKunjungan.TANGGAL)'), '>=', DB::raw("YEAR('$dariTanggal')"))
            ->where(DB::raw('YEAR(statistikKunjungan.TANGGAL)'), '<=', DB::raw("YEAR('$sampaiTanggal')"))
            ->groupBy(DB::raw('YEAR(statistikKunjungan.TANGGAL)'))
            ->orderByDesc(DB::raw('YEAR(statistikKunjungan.TANGGAL)'))
            ->get();

        $rujukanTahunan = DB::connection('mysql12')->table('informasi.statistik_rujukan as statistikRujukan')
            ->select(
                DB::raw('YEAR(statistikRujukan.TANGGAL) as tahun'),
                DB::raw('SUM(statistikRujukan.MASUK) as masuk'),
                DB::raw('SUM(statistikRujukan.KELUAR) as keluar'),
                DB::raw('SUM(statistikRujukan.BALIK) as balik')
            )
            ->where(DB::raw('YEAR(statistikRujukan.TANGGAL)'), '>=', DB::raw("YEAR('$dariTanggal')"))
            ->where(DB::raw('YEAR(statistikRujukan.TANGGAL)'), '<=', DB::raw("YEAR('$sampaiTanggal')"))
            ->groupBy(DB::raw('YEAR(statistikRujukan.TANGGAL)'))
            ->orderByDesc(DB::raw('YEAR(statistikRujukan.TANGGAL)'))
            ->get();

        return inertia("Informasi/StatistikKunjungan/Print", [
            'kunjunganHarian' => $kunjunganHarian,
            'rujukanHarian' => $rujukanHarian,
            'kunjunganMingguan' => $kunjunganMingguan,
            'rujukanMingguan' => $rujukanMingguan,
            'kunjunganBulanan' => $kunjunganBulanan,
            'rujukanBulanan' => $rujukanBulanan,
            'kunjunganTahunan' => $kunjunganTahunan,
            'rujukanTahunan' => $rujukanTahunan,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}