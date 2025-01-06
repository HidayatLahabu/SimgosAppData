<?php

namespace App\Http\Controllers\Informasi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InformasiKunjunganController extends Controller
{

    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        $data = $this->getKunjungan($searchSubject);
        $dataArray = $data->toArray();

        // Get weekly and monthly data
        $kunjunganMingguan = $this->getWeeklyKunjungan($searchSubject);
        $dataKunjunganMingguan = $kunjunganMingguan->toArray();

        // $perPage = 5; // Items per page
        // $kunjunganBulanan = $this->getMonthlyKunjungan($perPage);

        $kunjunganBulanan = $this->getMonthlyKunjungan($searchSubject);
        $dataKunjunganBulanan = $kunjunganBulanan->toArray();

        // Return Inertia view with paginated data
        return inertia("Informasi/Kunjungan/Index", [
            'harian' => [
                'data' => $dataArray['data'],
                'links' => $dataArray['links'],
            ],
            'mingguan' => $dataKunjunganMingguan,
            'bulanan' => $dataKunjunganBulanan,
            'queryParams' => request()->all()
        ]);
    }

    private function getKunjungan($searchSubject = null)
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

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(kunjungan.SUBUNIT) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Return the paginated results
        return $query->orderByDesc('kunjungan.TANGGAL')->orderBy('kunjungan.SUBUNIT')
            ->paginate(5)->appends(request()->query());
    }

    private function getWeeklyKunjungan($searchSubject = null)
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

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(kunjungan.SUBUNIT) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Return the paginated results
        return $query->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc')
            ->orderBy('kunjungan.SUBUNIT')
            ->paginate(5)->appends(request()->query());
    }

    private function getMonthlyKunjungan($searchSubject = null)
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

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(kunjungan.SUBUNIT) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Return the paginated results
        return $query->orderBy(DB::raw('YEAR(kunjungan.TANGGAL)'), 'desc')
            ->orderBy(DB::raw('MONTH(kunjungan.TANGGAL)'), 'desc')
            ->orderBy('kunjungan.SUBUNIT')
            ->paginate(5)->appends(request()->query());
    }
}