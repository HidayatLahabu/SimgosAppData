<?php

namespace App\Http\Controllers\Informasi;

use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InformasiPenunjangController extends Controller
{
    public function index()
    {
        $harian = $this->getHarian();
        $dataHarian = $harian->toArray();

        $mingguan = $this->getMingguan();
        $dataMingguan = $mingguan->toArray();

        $bulanan = $this->getBulanan();
        $dataBulanan = $bulanan->toArray();

        $tahunan = $this->getTahunan();
        $dataTahunan = $tahunan->toArray();

        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->whereIn('JENIS_KUNJUNGAN', [4, 5, 6, 7, 8, 9, 10, 11, 12])
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        // Return Inertia view with paginated data
        return inertia("Informasi/Penunjang/Index", [
            'ruangan' => $ruangan,
            'harian' => [
                'data' => $dataHarian['data'],
                'links' => $dataHarian['links'],
            ],
            'mingguan' => $dataMingguan,
            'bulanan' => $dataBulanan,
            'tahunan' => $dataTahunan,
        ]);
    }

    protected function getHarian()
    {
        $query = DB::connection('mysql12')->table('informasi.penunjang as penunjang')
            ->select(
                'penunjang.TANGGAL as tanggal',
                'penunjang.IDSUBUNIT as idSubUnit',
                DB::raw('MIN(penunjang.DESKRIPSI) as jenisKunjungan'),
                DB::raw('MIN(penunjang.INSTALASI) as instalasi'),
                DB::raw('MIN(penunjang.UNIT) as unit'),
                DB::raw('MIN(penunjang.SUBUNIT) as subUnit'),
                DB::raw('SUM(penunjang.VALUE) as jumlah'),
                DB::raw('MAX(penunjang.LASTUPDATED) as lastUpdated')
            )
            ->groupBy(
                'penunjang.TANGGAL',
                'penunjang.IDSUBUNIT',
                'penunjang.SUBUNIT',
            );

        // Paginate the results
        return $query->orderByDesc('penunjang.TANGGAL')->orderBy('penunjang.SUBUNIT')
            ->paginate(5)->appends(request()->query());
    }

    private function getMingguan()
    {
        // Start building the query using the query builder
        $query = DB::connection('mysql12')->table('informasi.penunjang as penunjang')
            ->select(
                DB::raw('MIN(penunjang.TANGGAL) as tanggal'),
                'penunjang.IDSUBUNIT as idSubUnit',
                DB::raw('MIN(penunjang.DESKRIPSI) as jenisKunjungan'),
                DB::raw('MIN(penunjang.INSTALASI) as instalasi'),
                DB::raw('MIN(penunjang.UNIT) as unit'),
                DB::raw('MIN(penunjang.SUBUNIT) as subUnit'),
                DB::raw('SUM(penunjang.VALUE) as jumlah'),
                DB::raw('MAX(penunjang.LASTUPDATED) as lastUpdated'),
                DB::raw('YEAR(penunjang.TANGGAL) as tahun'),
                DB::raw('WEEK(penunjang.TANGGAL, 1) as minggu'),
            )
            ->groupBy(
                'penunjang.IDSUBUNIT',
                DB::raw('YEAR(penunjang.TANGGAL)'),
                DB::raw('WEEK(penunjang.TANGGAL, 1)'),
                'penunjang.SUBUNIT',
            );

        // Return the paginated results
        return $query->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc')
            ->orderBy('penunjang.SUBUNIT')
            ->paginate(5)->appends(request()->query());
    }

    private function getBulanan()
    {
        // Start building the query using the query builder
        $query = DB::connection('mysql12')->table('informasi.penunjang as penunjang')
            ->select(
                DB::raw('MIN(penunjang.TANGGAL) as tanggal'),
                'penunjang.IDSUBUNIT as idSubUnit',
                DB::raw('MIN(penunjang.DESKRIPSI) as jenisKunjungan'),
                DB::raw('MIN(penunjang.INSTALASI) as instalasi'),
                DB::raw('MIN(penunjang.UNIT) as unit'),
                DB::raw('MIN(penunjang.SUBUNIT) as subUnit'),
                DB::raw('SUM(penunjang.VALUE) as jumlah'),
                DB::raw('MAX(penunjang.LASTUPDATED) as lastUpdated'),
                DB::raw('YEAR(penunjang.TANGGAL) as tahun'),
                DB::raw('MONTH(penunjang.TANGGAL) as bulan'),
            )
            ->groupBy(
                'penunjang.IDSUBUNIT',
                DB::raw('YEAR(penunjang.TANGGAL)'),
                DB::raw('MONTH(penunjang.TANGGAL)'),
                'penunjang.SUBUNIT'
            );

        // Return the paginated results
        return $query->orderBy(DB::raw('YEAR(penunjang.TANGGAL)'), 'desc')
            ->orderBy(DB::raw('MONTH(penunjang.TANGGAL)'), 'desc')
            ->orderBy('penunjang.SUBUNIT')
            ->paginate(5)->appends(request()->query());
    }

    private function getTahunan()
    {
        // Start building the query using the query builder
        $query = DB::connection('mysql12')->table('informasi.penunjang as penunjang')
            ->select(
                DB::raw('MIN(penunjang.TANGGAL) as tanggal'),
                'penunjang.IDSUBUNIT as idSubUnit',
                DB::raw('MIN(penunjang.DESKRIPSI) as jenisKunjungan'),
                DB::raw('MIN(penunjang.INSTALASI) as instalasi'),
                DB::raw('MIN(penunjang.UNIT) as unit'),
                DB::raw('MIN(penunjang.SUBUNIT) as subUnit'),
                DB::raw('SUM(penunjang.VALUE) as jumlah'),
                DB::raw('MAX(penunjang.LASTUPDATED) as lastUpdated'),
                DB::raw('YEAR(penunjang.TANGGAL) as tahun'),
            )
            ->groupBy(
                'penunjang.IDSUBUNIT',
                DB::raw('YEAR(penunjang.TANGGAL)'),
                'penunjang.SUBUNIT'
            );

        // Return the paginated results
        return $query->orderBy(DB::raw('YEAR(penunjang.TANGGAL)'), 'desc')
            ->orderBy('penunjang.SUBUNIT')
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
            ->where(DB::raw('YEAR(kunjungan.TANGGAL)'), '>=', DB::raw("YEAR('$dariTanggal')"))
            ->where(DB::raw('YEAR(kunjungan.TANGGAL)'), '<=', DB::raw("YEAR('$sampaiTanggal')"))
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