<?php

namespace App\Http\Controllers\Pendaftaran;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReservasiController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.reservasi as reservasi')
            ->select(
                'reservasi.NOMOR as nomor',
                'reservasi.TANGGAL as tanggal',
                'reservasi.ATAS_NAMA as pasien',
                'ruangan.DESKRIPSI as ruangan',
                'kamar.KAMAR as kamar',
                'tempatTidur.TEMPAT_TIDUR as tempatTidur',
                'reservasi.STATUS as status',
                'reservasi.KONTAK_INFO as kontak',
            )
            ->leftJoin('master.ruang_kamar_tidur AS tempatTidur', 'tempatTidur.ID', '=', 'reservasi.RUANG_KAMAR_TIDUR')
            ->leftJoin('master.ruang_kamar as kamar', 'kamar.ID', '=', 'tempatTidur.RUANG_KAMAR')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kamar.RUANGAN');

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(reservasi.ATAS_NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(ruangan.DESKRIPSI) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(kamar.KAMAR) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('reservasi.TANGGAL')
            ->orderBy('ruangan.DESKRIPSI')
            ->orderBy('kamar.KAMAR')
            ->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        $dataReservasi = $this->hitungReservasi();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Reservasi/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'reservasiData' => $dataReservasi,
            'queryParams' => request()->all(),
        ]);
    }

    public function filterByStatus($filter)
    {
        // Validasi filter
        $filters = [
            'batal' => ['status' => 0, 'header' => 'BATAL'],
            'reservasi' => ['status' => 1, 'header' => 'DALAM PROSES'],
            'selesai' => ['status' => 2, 'header' => 'SELESAI'],
        ];

        if (!isset($filters[$filter])) {
            abort(404, 'Filter not found');
        }

        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.reservasi as reservasi')
            ->select(
                'reservasi.NOMOR as nomor',
                'reservasi.TANGGAL as tanggal',
                'reservasi.ATAS_NAMA as pasien',
                'ruangan.DESKRIPSI as ruangan',
                'kamar.KAMAR as kamar',
                'tempatTidur.TEMPAT_TIDUR as tempatTidur',
                'reservasi.STATUS as status',
                'reservasi.KONTAK_INFO as kontak',
            )
            ->leftJoin('master.ruang_kamar_tidur AS tempatTidur', 'tempatTidur.ID', '=', 'reservasi.RUANG_KAMAR_TIDUR')
            ->leftJoin('master.ruang_kamar as kamar', 'kamar.ID', '=', 'tempatTidur.RUANG_KAMAR')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kamar.RUANGAN')
            ->where('reservasi.STATUS', $filters[$filter]['status']);

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(reservasi.ATAS_NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(ruangan.DESKRIPSI) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(kamar.KAMAR) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('reservasi.TANGGAL')
            ->orderBy('ruangan.DESKRIPSI')
            ->orderBy('kamar.KAMAR')
            ->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        $dataReservasi = $this->hitungReservasi();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Reservasi/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'filter' => $filter,
            'header' => $filters[$filter]['header'],
            'reservasiData' => $dataReservasi,
            'queryParams' => request()->all(),
        ]);
    }

    protected function hitungReservasi()
    {
        return DB::connection('mysql5')->table('pendaftaran.reservasi as reservasi')
            ->selectRaw('
                COUNT(*) AS total_reservasi,
                SUM(CASE WHEN STATUS = 0 THEN 1 ELSE 0 END) AS total_batal_reservasi,
                SUM(CASE WHEN STATUS = 1 THEN 1 ELSE 0 END) AS total_proses_reservasi,
                SUM(CASE WHEN STATUS = 2 THEN 1 ELSE 0 END) AS total_selesai_reservasi
            ')
            ->first();
    }
}