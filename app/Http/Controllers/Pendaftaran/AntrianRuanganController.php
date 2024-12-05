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
                'antrian.STATUS as status'
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
            ->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Antrian/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function filterByStatus($filter)
    {
        // Validasi filter
        $filters = [
            'batal' => ['status' => 0, 'header' => 'BATAL'],
            'belumDiterima' => ['status' => 1, 'header' => 'BELUM DITERIMA'],
            'diterima' => ['status' => 2, 'header' => 'DITERIMA'],
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
                'antrian.STATUS as status'
            )
            ->leftJoin('pendaftaran.pendaftaran AS pendaftaran', 'pendaftaran.NOMOR', '=', 'antrian.REF')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'antrian.RUANGAN')
            ->where('pasien.STATUS', 1)
            ->where('antrian.STATUS', $filters[$filter]['status']);

        // Hitung total
        $count = $query->count();

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
            ->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        return inertia("Pendaftaran/Antrian/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all(),
            'header' => $filters[$filter]['header'],
            'totalCount' => $count,
            'text' => 'PASIEN',
        ]);
    }
}