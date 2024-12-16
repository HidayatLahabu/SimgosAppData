<?php

namespace App\Http\Controllers\Pendaftaran;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AntrianRuanganController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.antrian_ruangan as antrian')
            ->select(
                'antrian.ID as nomor',
                'antrian.TANGGAL as tanggal',
                'pendaftaran.NORM as norm',
                'pasien.NAMA as nama',
                'ruangan.DESKRIPSI as ruangan',
                'antrian.NOMOR as urut',
                'antrian.STATUS as status',
                'antrian.REF as pendaftaran',
            )
            ->leftJoin('pendaftaran.pendaftaran AS pendaftaran', 'pendaftaran.NOMOR', '=', 'antrian.REF')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'antrian.RUANGAN')
            ->where('pasien.STATUS', 1);

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(antrian.ID) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pendaftaran.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('antrian.TANGGAL')
            ->orderBy('ruangan.DESKRIPSI')
            ->orderBy('antrian.NOMOR')
            ->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        $dataAntrian = $this->hitungAntrian();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Antrian/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'antrianData' => $dataAntrian,
            'queryParams' => request()->all()
        ]);
    }

    public function filterByStatus($filter)
    {
        // Validasi filter
        $filters = [
            'batal' => ['status' => 0, 'header' => 'BATAL'],
            'belumDiterima' => ['status' => 1, 'header' => 'BELUM DITERIMA'],
            'diterima' => ['status' => 2, 'header' => 'SUDAH DITERIMA'],
        ];

        if (!isset($filters[$filter])) {
            abort(404, 'Filter not found');
        }

        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Build query
        $query = DB::connection('mysql5')->table('pendaftaran.antrian_ruangan as antrian')
            ->select(
                'antrian.ID as nomor',
                'antrian.TANGGAL as tanggal',
                'pendaftaran.NORM as norm',
                'pasien.NAMA as nama',
                'ruangan.DESKRIPSI as ruangan',
                'antrian.NOMOR as urut',
                'antrian.STATUS as status',
                'antrian.REF as pendaftaran',
            )
            ->leftJoin('pendaftaran.pendaftaran AS pendaftaran', 'pendaftaran.NOMOR', '=', 'antrian.REF')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'antrian.RUANGAN')
            ->where('pasien.STATUS', 1)
            ->where('antrian.STATUS', $filters[$filter]['status']);

        // Filter pencarian
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(antrian.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginasi data
        $data = $query->orderByDesc('antrian.TANGGAL')
            ->orderBy('ruangan.DESKRIPSI')
            ->orderBy('antrian.NOMOR')
            ->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        $dataAntrian = $this->hitungAntrian();

        return inertia("Pendaftaran/Antrian/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'filter' => $filter,
            'header' => $filters[$filter]['header'],
            'antrianData' => $dataAntrian,
            'queryParams' => request()->all(),
        ]);
    }

    public function filterByKunjungan($filterKunjungan)
    {
        $filterOptions = [
            'rajal' => ['jenisKunjungan' => 1, 'header' => 'RAWAT JALAN'],
            'darurat' => ['jenisKunjungan' => 2, 'header' => 'RAWAT DARURAT'],
            'ranap' => ['jenisKunjungan' => 3, 'header' => 'RAWAT INAP'],
        ];

        // Validasi filter
        if (!isset($filterOptions[$filterKunjungan])) {
            abort(404, 'Filter not found');
        }

        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Build query
        $query = DB::connection('mysql5')->table('pendaftaran.antrian_ruangan as antrian')
            ->select(
                'antrian.ID as nomor',
                'antrian.TANGGAL as tanggal',
                'pendaftaran.NORM as norm',
                'pasien.NAMA as nama',
                'ruangan.DESKRIPSI as ruangan',
                'antrian.NOMOR as urut',
                'antrian.STATUS as status',
                'antrian.REF as pendaftaran',
            )
            ->leftJoin('pendaftaran.pendaftaran AS pendaftaran', 'pendaftaran.NOMOR', '=', 'antrian.REF')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'antrian.RUANGAN')
            ->where('pasien.STATUS', 1)
            ->whereIn('antrian.STATUS', [1, 2])
            ->where('ruangan.STATUS', 1)
            ->where('ruangan.JENIS_KUNJUNGAN', $filterOptions[$filterKunjungan]['jenisKunjungan']);

        // Filter pencarian
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(antrian.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginasi data
        $data = $query->orderByDesc('antrian.TANGGAL')
            ->orderBy('ruangan.DESKRIPSI')
            ->orderBy('antrian.NOMOR')
            ->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        $dataAntrian = $this->hitungAntrian();

        return inertia("Pendaftaran/Antrian/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'filterKunjungan' => $filterKunjungan,
            'headerKunjungan' => $filterOptions[$filterKunjungan]['header'],
            'antrianData' => $dataAntrian,
            'queryParams' => request()->all(),
        ]);
    }


    protected function hitungAntrian()
    {
        return DB::connection('mysql5')->table('pendaftaran.antrian_ruangan as antrian')
            ->selectRaw('
            COUNT(*) AS total_antrian,
            SUM(CASE WHEN antrian.STATUS = 0 THEN 1 ELSE 0 END) AS total_batal,
            SUM(CASE WHEN antrian.STATUS = 1 THEN 1 ELSE 0 END) AS total_belum_diterima,
            SUM(CASE WHEN antrian.STATUS = 2 THEN 1 ELSE 0 END) AS total_diterima
        ')
            ->leftJoin('pendaftaran.pendaftaran AS pendaftaran', 'pendaftaran.NOMOR', '=', 'antrian.REF')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->where('pasien.STATUS', 1)
            ->first();
    }
}