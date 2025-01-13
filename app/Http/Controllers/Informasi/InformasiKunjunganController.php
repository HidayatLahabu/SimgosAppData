<?php

namespace App\Http\Controllers\Informasi;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InformasiKunjunganController extends Controller
{

    public function index()
    {
        $data = $this->getKunjungan();
        $dataArray = $data->toArray();

        // Get weekly and monthly data
        $kunjunganMingguan = $this->getWeeklyKunjungan();
        $dataKunjunganMingguan = $kunjunganMingguan->toArray();

        $kunjunganBulanan = $this->getMonthlyKunjungan();
        $dataKunjunganBulanan = $kunjunganBulanan->toArray();

        $kunjunganTahunan = $this->getYearlyKunjungan();
        $dataKunjunganTahunan = $kunjunganTahunan->toArray();

        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->whereIn('JENIS_KUNJUNGAN', [1])
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        // Return Inertia view with paginated data
        return inertia("Informasi/Kunjungan/Index", [
            'ruangan' => $ruangan,
            'harian' => [
                'data' => $dataArray['data'],
                'links' => $dataArray['links'],
            ],
            'mingguan' => $dataKunjunganMingguan,
            'bulanan' => $dataKunjunganBulanan,
            'tahunan' => $dataKunjunganTahunan,
            'queryParams' => request()->all()
        ]);
    }

    private function getKunjungan()
    {
        // Start building the query using the query builder
        $query = DB::connection('mysql12')->table('informasi.kunjungan as kunjungan')
            ->select(
                'kunjungan.TANGGAL as tanggal',
                'kunjungan.IDSUBUNIT as idSubUnit',
                DB::raw('MIN(kunjungan.DESKRIPSI) as jenisKunjungan'),
                DB::raw('MIN(kunjungan.INSTALASI) as instalasi'),
                DB::raw('MIN(kunjungan.UNIT) as unit'),
                DB::raw('MIN(kunjungan.SUBUNIT) as subUnit'),
                DB::raw('SUM(kunjungan.VALUE) as jumlah'),
                DB::raw('MAX(kunjungan.LASTUPDATED) as lastUpdated')
            )
            ->groupBy('kunjungan.TANGGAL', 'kunjungan.IDSUBUNIT', 'kunjungan.SUBUNIT');

        // Return the paginated results
        return $query->orderByDesc('kunjungan.TANGGAL')->orderBy('kunjungan.SUBUNIT')
            ->paginate(5)->appends(request()->query());
    }

    private function getWeeklyKunjungan()
    {
        // Start building the query using the query builder
        $query = DB::connection('mysql12')->table('informasi.kunjungan as kunjungan')
            ->select(
                'kunjungan.IDSUBUNIT as idSubUnit',
                DB::raw('MIN(kunjungan.DESKRIPSI) as jenisKunjungan'),
                DB::raw('MIN(kunjungan.INSTALASI) as instalasi'),
                DB::raw('MIN(kunjungan.UNIT) as unit'),
                DB::raw('MIN(kunjungan.SUBUNIT) as subUnit'),
                DB::raw('MAX(kunjungan.LASTUPDATED) as lastUpdated'),
                DB::raw('YEAR(kunjungan.TANGGAL) as tahun'),
                DB::raw('WEEK(kunjungan.TANGGAL, 1) as minggu'),
                DB::raw('SUM(kunjungan.VALUE) as jumlah')
            )
            ->groupBy(
                'kunjungan.IDSUBUNIT',
                DB::raw('YEAR(kunjungan.TANGGAL)'),
                DB::raw('WEEK(kunjungan.TANGGAL, 1)'),
                'kunjungan.SUBUNIT'
            );

        // Return the paginated results
        return $query->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc')
            ->orderBy('kunjungan.SUBUNIT')
            ->paginate(5)->appends(request()->query());
    }

    private function getMonthlyKunjungan()
    {
        // Start building the query using the query builder
        $query = DB::connection('mysql12')->table('informasi.kunjungan as kunjungan')
            ->select(
                'kunjungan.IDSUBUNIT as idSubUnit',
                DB::raw('MIN(kunjungan.DESKRIPSI) as jenisKunjungan'),
                DB::raw('MIN(kunjungan.INSTALASI) as instalasi'),
                DB::raw('MIN(kunjungan.UNIT) as unit'),
                DB::raw('MIN(kunjungan.SUBUNIT) as subUnit'),
                DB::raw('MAX(kunjungan.LASTUPDATED) as lastUpdated'),
                DB::raw('YEAR(kunjungan.TANGGAL) as tahun'),
                DB::raw('MONTH(kunjungan.TANGGAL) as bulan'),
                DB::raw('SUM(kunjungan.VALUE) as jumlah')
            )
            ->groupBy(
                'kunjungan.IDSUBUNIT',
                DB::raw('YEAR(kunjungan.TANGGAL)'),
                DB::raw('MONTH(kunjungan.TANGGAL)'),
                'kunjungan.SUBUNIT'
            );

        // Return the paginated results
        return $query->orderBy(DB::raw('YEAR(kunjungan.TANGGAL)'), 'desc')
            ->orderBy(DB::raw('MONTH(kunjungan.TANGGAL)'), 'desc')
            ->orderBy('kunjungan.SUBUNIT')
            ->paginate(5)->appends(request()->query());
    }

    private function getYearlyKunjungan()
    {
        // Start building the query using the query builder
        $query = DB::connection('mysql12')->table('informasi.kunjungan as kunjungan')
            ->select(
                'kunjungan.IDSUBUNIT as idSubUnit',
                DB::raw('MIN(kunjungan.DESKRIPSI) as jenisKunjungan'),
                DB::raw('MIN(kunjungan.INSTALASI) as instalasi'),
                DB::raw('MIN(kunjungan.UNIT) as unit'),
                DB::raw('MIN(kunjungan.SUBUNIT) as subUnit'),
                DB::raw('MAX(kunjungan.LASTUPDATED) as lastUpdated'),
                DB::raw('YEAR(kunjungan.TANGGAL) as tahun'),
                DB::raw('SUM(kunjungan.VALUE) as jumlah')
            )
            ->groupBy(
                'kunjungan.IDSUBUNIT',
                DB::raw('YEAR(kunjungan.TANGGAL)'),
                'kunjungan.SUBUNIT'
            );

        // Return the paginated results
        return $query->orderBy(DB::raw('YEAR(kunjungan.TANGGAL)'), 'desc')
            ->orderBy('kunjungan.SUBUNIT')
            ->paginate(5)->appends(request()->query());
    }

    public function print(Request $request)
    {
        // Validasi input
        $request->validate([
            'ruangan'        => 'nullable|integer',
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        // Ambil nilai input
        $ruangan = $request->input('ruangan');
        $dariTanggal = Carbon::parse($request->input('dari_tanggal'))->format('Y-m-d H:i:s');
        $sampaiTanggal = Carbon::parse($request->input('sampai_tanggal'))->endOfDay()->format('Y-m-d H:i:s');

        // Variable default untuk label
        $namaRuangan = $ruangan ? MasterRuanganModel::where('ID', $ruangan)->value('DESKRIPSI') : 'SEMUA RUANGAN';

        // Query Harian
        $harian = DB::connection('mysql12')->table('informasi.kunjungan as kunjungan')
            ->select(
                'kunjungan.TANGGAL as tanggal',
                'kunjungan.IDSUBUNIT as idSubUnit',
                DB::raw('MIN(kunjungan.DESKRIPSI) as jenisKunjungan'),
                DB::raw('MIN(kunjungan.SUBUNIT) as subUnit'),
                DB::raw('SUM(kunjungan.VALUE) as jumlah'),
                DB::raw('MAX(kunjungan.LASTUPDATED) as lastUpdated')
            )
            ->whereBetween('kunjungan.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->when($ruangan, fn($harian) => $harian->where('kunjungan.IDSUBUNIT', $ruangan))
            ->groupBy('kunjungan.TANGGAL', 'kunjungan.IDSUBUNIT', 'kunjungan.SUBUNIT')
            ->orderByDesc('kunjungan.TANGGAL')
            ->orderBy('kunjungan.SUBUNIT')
            ->get();

        // Query Mingguan
        $mingguan = DB::connection('mysql12')->table('informasi.kunjungan as kunjungan')
            ->select(
                'kunjungan.IDSUBUNIT as idSubUnit',
                DB::raw('MIN(kunjungan.DESKRIPSI) as jenisKunjungan'),
                DB::raw('MIN(kunjungan.INSTALASI) as instalasi'),
                DB::raw('MIN(kunjungan.UNIT) as unit'),
                DB::raw('MIN(kunjungan.SUBUNIT) as subUnit'),
                DB::raw('MAX(kunjungan.LASTUPDATED) as lastUpdated'),
                DB::raw('YEAR(kunjungan.TANGGAL) as tahun'),
                DB::raw('WEEK(kunjungan.TANGGAL, 1) as minggu'),
                DB::raw('SUM(kunjungan.VALUE) as jumlah')
            )
            ->whereBetween('kunjungan.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->when($ruangan, fn($mingguan) => $mingguan->where('kunjungan.IDSUBUNIT', $ruangan))
            ->groupBy(
                'kunjungan.IDSUBUNIT',
                DB::raw('YEAR(kunjungan.TANGGAL)'),
                DB::raw('WEEK(kunjungan.TANGGAL, 1)'),
                'kunjungan.SUBUNIT'
            )
            ->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc')
            ->orderBy('kunjungan.SUBUNIT')
            ->get();

        // Query Bulanan
        $bulanan = DB::connection('mysql12')->table('informasi.kunjungan as kunjungan')
            ->select(
                'kunjungan.IDSUBUNIT as idSubUnit',
                DB::raw('MIN(kunjungan.DESKRIPSI) as jenisKunjungan'),
                DB::raw('MIN(kunjungan.INSTALASI) as instalasi'),
                DB::raw('MIN(kunjungan.UNIT) as unit'),
                DB::raw('MIN(kunjungan.SUBUNIT) as subUnit'),
                DB::raw('MAX(kunjungan.LASTUPDATED) as lastUpdated'),
                DB::raw('YEAR(kunjungan.TANGGAL) as tahun'),
                DB::raw('MONTH(kunjungan.TANGGAL) as bulan'),
                DB::raw('SUM(kunjungan.VALUE) as jumlah')
            )
            ->whereBetween('kunjungan.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->when($ruangan, fn($bulanan) => $bulanan->where('kunjungan.IDSUBUNIT', $ruangan))
            ->groupBy(
                'kunjungan.IDSUBUNIT',
                DB::raw('YEAR(kunjungan.TANGGAL)'),
                DB::raw('MONTH(kunjungan.TANGGAL)'),
                'kunjungan.SUBUNIT'
            )
            ->orderBy(DB::raw('YEAR(kunjungan.TANGGAL)'), 'desc')
            ->orderBy(DB::raw('MONTH(kunjungan.TANGGAL)'), 'desc')
            ->orderBy('kunjungan.SUBUNIT')
            ->get();

        // Query Tahunan
        $tahunan = DB::connection('mysql12')->table('informasi.kunjungan as kunjungan')
            ->select(
                'kunjungan.IDSUBUNIT as idSubUnit',
                DB::raw('MIN(kunjungan.DESKRIPSI) as jenisKunjungan'),
                DB::raw('MIN(kunjungan.INSTALASI) as instalasi'),
                DB::raw('MIN(kunjungan.UNIT) as unit'),
                DB::raw('MIN(kunjungan.SUBUNIT) as subUnit'),
                DB::raw('MAX(kunjungan.LASTUPDATED) as lastUpdated'),
                DB::raw('YEAR(kunjungan.TANGGAL) as tahun'),
                DB::raw('SUM(kunjungan.VALUE) as jumlah')
            )
            ->whereBetween('kunjungan.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->when($ruangan, fn($tahunan) => $tahunan->where('kunjungan.IDSUBUNIT', $ruangan))
            ->groupBy(
                'kunjungan.IDSUBUNIT',
                DB::raw('YEAR(kunjungan.TANGGAL)'),
                'kunjungan.SUBUNIT'
            )
            ->orderBy(DB::raw('YEAR(kunjungan.TANGGAL)'), 'desc')
            ->orderBy('kunjungan.SUBUNIT')
            ->get();

        // Kirim data ke frontend menggunakan Inertia
        return inertia("Informasi/Kunjungan/Print", [
            'harian'        => $harian,
            'mingguan'      => $mingguan,
            'bulanan'       => $bulanan,
            'tahunan'       => $tahunan,
            'dariTanggal'   => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
            'namaRuangan'   => $namaRuangan,
        ]);
    }
}