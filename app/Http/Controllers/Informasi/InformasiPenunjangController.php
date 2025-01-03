<?php

namespace App\Http\Controllers\Informasi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InformasiPenunjangController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        $data = $this->getHarian($searchSubject);
        $dataArray = $data->toArray();

        $kunjunganMingguan = $this->getMingguan();
        $dataKunjunganMingguan = $kunjunganMingguan->paginate(5)->appends(request()->query());
        $dataKunjunganMingguan = $dataKunjunganMingguan->toArray();

        $perPage = 5; // Items per page
        $kunjunganBulanan = $this->getBulanan($perPage);

        // Return Inertia view with paginated data
        return inertia("Informasi/Penunjang/Index", [
            'harian' => [
                'data' => $dataArray['data'],
                'links' => $dataArray['links'],
            ],
            'mingguan' => [
                'dataKunjunganMingguan' => $dataKunjunganMingguan['data'],
                'linksKunjunganMingguan' => $dataKunjunganMingguan['links'],
            ],
            'bulanan' => $kunjunganBulanan,
            'queryParams' => request()->all()
        ]);
    }

    protected function getHarian($searchSubject = null)
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
            ->groupBy('penunjang.TANGGAL', 'penunjang.IDSUBUNIT');

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(penunjang.SUBUNIT) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        return $query->orderByDesc('penunjang.TANGGAL')->paginate(5)->appends(request()->query());
    }

    protected function getMingguan()
    {
        return DB::connection('mysql12')->table('informasi.penunjang as penunjang')
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
            )
            ->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc')
            ->orderBy('penunjang.SUBUNIT');
    }

    protected function getBulanan($perPage)
    {
        return DB::connection('mysql12')->table('informasi.penunjang as penunjang')
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

            )
            ->orderBy(DB::raw('YEAR(penunjang.TANGGAL)'), 'desc')
            ->orderBy(DB::raw('MONTH(penunjang.TANGGAL)'), 'desc')
            ->orderBy('penunjang.SUBUNIT')
            ->paginate($perPage);
    }
}