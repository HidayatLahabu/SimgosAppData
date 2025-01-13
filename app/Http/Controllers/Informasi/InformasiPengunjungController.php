<?php

namespace App\Http\Controllers\Informasi;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InformasiPengunjungController extends Controller
{
    public function index()
    {
        $rajalHarian = $this->getRajalHarian();
        $dataRajalHarian = $rajalHarian->orderByDesc('pengunjung.TANGGAL')->paginate(5)->appends(request()->query());
        $dataRajalHarian = $dataRajalHarian->toArray();

        $ranapHarian = $this->getRanapHarian();
        $dataRanapHarian = $ranapHarian->orderByDesc('pasienRanap.TANGGAL')->paginate(5)->appends(request()->query());
        $dataRanapHarian = $dataRanapHarian->toArray();

        $rajalMingguan = $this->getRajalMingguan();
        $dataRajalMingguan = $rajalMingguan->paginate(5)->appends(request()->query());
        $dataRajalMingguan = $dataRajalMingguan->toArray();

        $ranapMingguan = $this->getRanapMingguan();
        $dataRanapMingguan = $ranapMingguan->paginate(5)->appends(request()->query());
        $dataRanapMingguan = $dataRanapMingguan->toArray();

        $perPage = 5; // Items per page
        $rajalBulanan = $this->getRajalBulanan($perPage);
        $ranapBulanan = $this->getRanapBulanan($perPage);

        $rajalTahunan = $this->getRajalTahunan($perPage);
        $ranapTahunan = $this->getRanapTahunan($perPage);

        // Return Inertia view with paginated data
        return inertia("Informasi/Pengunjung/Index", [
            'rajalHarian' => [
                'data' => $dataRajalHarian['data'],
                'links' => $dataRajalHarian['links'],
            ],
            'ranapHarian' => [
                'data' => $dataRanapHarian['data'],
                'links' => $dataRanapHarian['links'],
            ],
            'rajalMingguan' => [
                'data' => $dataRajalMingguan['data'],
                'links' => $dataRajalMingguan['links'],
            ],
            'ranapMingguan' => [
                'data' => $dataRanapMingguan['data'],
                'links' => $dataRanapMingguan['links'],
            ],
            'rajalBulanan' => $rajalBulanan,
            'ranapBulanan' => $ranapBulanan,
            'rajalTahunan' => $rajalTahunan,
            'ranapTahunan' => $ranapTahunan,
        ]);
    }

    protected function getRajalHarian()
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

    protected function getRanapHarian()
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

    protected function getRajalMingguan()
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

    protected function getRanapMingguan()
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

    protected function getRajalBulanan($perPage)
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

    protected function getRanapBulanan($perPage)
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

    protected function getRajalTahunan($perPage)
    {
        return DB::connection('mysql12')->table('informasi.pengunjung as pengunjung')
            ->select(
                DB::raw('SUM(CASE WHEN pengunjung.ID = "1" THEN pengunjung.VALUE ELSE 0 END) as rajal'),
                DB::raw('SUM(CASE WHEN pengunjung.ID = "2" THEN pengunjung.VALUE ELSE 0 END) as darurat'),
                DB::raw('SUM(pengunjung.VALUE) as semua'),
                DB::raw('YEAR(pengunjung.TANGGAL) as tahun'),
            )
            ->groupBy(
                DB::raw('YEAR(pengunjung.TANGGAL)'),
            )
            ->orderBy(DB::raw('YEAR(pengunjung.TANGGAL)'), 'desc')
            ->paginate($perPage);
    }

    protected function getRanapTahunan($perPage)
    {
        return DB::connection('mysql12')->table('informasi.pasien_rawat_inap as pasienRanap')
            ->select(
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "1" THEN pasienRanap.VALUE ELSE 0 END) as masuk'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "2" THEN pasienRanap.VALUE ELSE 0 END) as dirawat'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "3" THEN pasienRanap.VALUE ELSE 0 END) as keluar'),
                DB::raw('YEAR(pasienRanap.TANGGAL) as tahun'),
            )
            ->groupBy(
                DB::raw('YEAR(pasienRanap.TANGGAL)'),
            )
            ->orderBy(DB::raw('YEAR(pasienRanap.TANGGAL)'), 'desc')
            ->paginate($perPage);
    }

    public function print(Request $request)
    {
        // Validasi input
        $request->validate([
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        $dariTanggal = Carbon::parse($request->input('dari_tanggal'))->format('Y-m-d H:i:s');
        $sampaiTanggal = Carbon::parse($request->input('sampai_tanggal'))->endOfDay()->format('Y-m-d H:i:s');


        // Query Harian
        $harianRajal = DB::connection('mysql12')->table('informasi.pengunjung as pengunjung')
            ->select(
                'pengunjung.TANGGAL as tanggal',
                DB::raw('SUM(pengunjung.VALUE) as semua'),
                DB::raw('SUM(CASE WHEN pengunjung.ID = "1" THEN pengunjung.VALUE ELSE 0 END) as rajal'),
                DB::raw('SUM(CASE WHEN pengunjung.ID = "2" THEN pengunjung.VALUE ELSE 0 END) as darurat'),
                DB::raw('MAX(pengunjung.LASTUPDATED) as lastUpdated')
            )
            ->whereBetween('pengunjung.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->groupBy('pengunjung.TANGGAL')
            ->orderByDesc('pengunjung.TANGGAL')
            ->get();

        $harianRanap = DB::connection('mysql12')->table('informasi.pasien_rawat_inap as pasienRanap')
            ->select(
                'pasienRanap.TANGGAL as tanggal',
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "1" THEN pasienRanap.VALUE ELSE 0 END) as masuk'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "2" THEN pasienRanap.VALUE ELSE 0 END) as dirawat'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "3" THEN pasienRanap.VALUE ELSE 0 END) as keluar'),
                DB::raw('MAX(pasienRanap.LASTUPDATED) as lastUpdated')
            )
            ->whereBetween('pasienRanap.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->groupBy('pasienRanap.TANGGAL')
            ->orderByDesc('pasienRanap.TANGGAL')
            ->get();

        // Query Mingguan
        $mingguanRajal = DB::connection('mysql12')->table('informasi.pengunjung as pengunjung')
            ->select(
                DB::raw('YEAR(pengunjung.TANGGAL) as tahun'),
                DB::raw('WEEK(pengunjung.TANGGAL, 1) as minggu'),
                DB::raw('SUM(CASE WHEN pengunjung.ID = "1" THEN pengunjung.VALUE ELSE 0 END) as rajal'),
                DB::raw('SUM(CASE WHEN pengunjung.ID = "2" THEN pengunjung.VALUE ELSE 0 END) as darurat'),
                DB::raw('SUM(pengunjung.VALUE) as semua'),
            )
            ->whereBetween('pengunjung.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->groupBy(
                DB::raw('YEAR(pengunjung.TANGGAL)'),
                DB::raw('WEEK(pengunjung.TANGGAL, 1)')
            )
            ->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc')
            ->get();

        $mingguanRanap = DB::connection('mysql12')->table('informasi.pasien_rawat_inap as pasienRanap')
            ->select(
                DB::raw('YEAR(pasienRanap.TANGGAL) as tahun'),
                DB::raw('WEEK(pasienRanap.TANGGAL, 1) as minggu'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "1" THEN pasienRanap.VALUE ELSE 0 END) as masuk'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "2" THEN pasienRanap.VALUE ELSE 0 END) as dirawat'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "3" THEN pasienRanap.VALUE ELSE 0 END) as keluar'),
            )
            ->whereBetween('pasienRanap.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->groupBy(
                DB::raw('YEAR(pasienRanap.TANGGAL)'),
                DB::raw('WEEK(pasienRanap.TANGGAL, 1)')
            )
            ->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc')
            ->get();

        // Query Bulanan
        $bulananRajal = DB::connection('mysql12')->table('informasi.pengunjung as pengunjung')
            ->select(
                DB::raw('SUM(CASE WHEN pengunjung.ID = "1" THEN pengunjung.VALUE ELSE 0 END) as rajal'),
                DB::raw('SUM(CASE WHEN pengunjung.ID = "2" THEN pengunjung.VALUE ELSE 0 END) as darurat'),
                DB::raw('SUM(pengunjung.VALUE) as semua'),
                DB::raw('YEAR(pengunjung.TANGGAL) as tahun'),
                DB::raw('MONTH(pengunjung.TANGGAL) as bulan'),
            )
            ->whereBetween('pengunjung.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->groupBy(
                DB::raw('YEAR(pengunjung.TANGGAL)'),
                DB::raw('MONTH(pengunjung.TANGGAL)'),
            )
            ->orderBy(DB::raw('YEAR(pengunjung.TANGGAL)'), 'desc')
            ->orderBy(DB::raw('MONTH(pengunjung.TANGGAL)'), 'desc')
            ->get();

        $bulananRanap = DB::connection('mysql12')->table('informasi.pasien_rawat_inap as pasienRanap')
            ->select(
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "1" THEN pasienRanap.VALUE ELSE 0 END) as masuk'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "2" THEN pasienRanap.VALUE ELSE 0 END) as dirawat'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "3" THEN pasienRanap.VALUE ELSE 0 END) as keluar'),
                DB::raw('YEAR(pasienRanap.TANGGAL) as tahun'),
                DB::raw('MONTH(pasienRanap.TANGGAL) as bulan'),
            )
            ->whereBetween('pasienRanap.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->groupBy(
                DB::raw('YEAR(pasienRanap.TANGGAL)'),
                DB::raw('MONTH(pasienRanap.TANGGAL)'),
            )
            ->orderBy(DB::raw('YEAR(pasienRanap.TANGGAL)'), 'desc')
            ->orderBy(DB::raw('MONTH(pasienRanap.TANGGAL)'), 'desc')
            ->get();

        // Query Tahunan
        $tahunanRajal = DB::connection('mysql12')->table('informasi.pengunjung as pengunjung')
            ->select(
                DB::raw('SUM(CASE WHEN pengunjung.ID = "1" THEN pengunjung.VALUE ELSE 0 END) as rajal'),
                DB::raw('SUM(CASE WHEN pengunjung.ID = "2" THEN pengunjung.VALUE ELSE 0 END) as darurat'),
                DB::raw('SUM(pengunjung.VALUE) as semua'),
                DB::raw('YEAR(pengunjung.TANGGAL) as tahun'),
            )
            ->whereBetween('pengunjung.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->groupBy(
                DB::raw('YEAR(pengunjung.TANGGAL)'),
            )
            ->orderBy(DB::raw('YEAR(pengunjung.TANGGAL)'), 'desc')
            ->get();

        $tahunanRanap = DB::connection('mysql12')->table('informasi.pasien_rawat_inap as pasienRanap')
            ->select(
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "1" THEN pasienRanap.VALUE ELSE 0 END) as masuk'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "2" THEN pasienRanap.VALUE ELSE 0 END) as dirawat'),
                DB::raw('SUM(CASE WHEN pasienRanap.ID = "3" THEN pasienRanap.VALUE ELSE 0 END) as keluar'),
                DB::raw('YEAR(pasienRanap.TANGGAL) as tahun'),
            )
            ->whereBetween('pasienRanap.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->groupBy(
                DB::raw('YEAR(pasienRanap.TANGGAL)'),
            )
            ->orderBy(DB::raw('YEAR(pasienRanap.TANGGAL)'), 'desc')
            ->get();

        // Kirim data ke frontend menggunakan Inertia
        return inertia("Informasi/Pengunjung/Print", [
            'rajalHarian'   => $harianRajal,
            'ranapHarian'   => $harianRanap,
            'rajalMingguan' => $mingguanRajal,
            'ranapMingguan' => $mingguanRanap,
            'rajalBulanan'  => $bulananRajal,
            'ranapBulanan'  => $bulananRanap,
            'rajalTahunan'  => $tahunanRajal,
            'ranapTahunan'  => $tahunanRanap,
            'dariTanggal'   => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}