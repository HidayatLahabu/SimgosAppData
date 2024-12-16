<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerawatController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql2')->table('master.perawat as perawat')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'perawat.NIP')
            ->leftJoin('aplikasi.pengguna as pengguna', 'perawat.NIP', '=', 'pengguna.NIP')
            ->leftJoin('master.perawat_ruangan as ruangan_kerja', 'perawat.ID', '=', 'ruangan_kerja.PERAWAT')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'ruangan_kerja.RUANGAN');

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pegawai.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pegawai.NIP) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pengguna.NIK) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Group by 'id' and select the first occurrence for other fields
        $query->selectRaw('
            perawat.ID as id,
            MIN(pegawai.GELAR_DEPAN) as depan,
            MIN(pegawai.NAMA) as nama,
            MIN(pegawai.GELAR_BELAKANG) as belakang,
            MIN(pegawai.NIP) as nip,
            MIN(pengguna.NIK) as nik,
            MIN(ruangan_kerja.RUANGAN) as ruangan_kerja,
            MIN(ruangan.DESKRIPSI) as ruangan
        ')
            ->where('pegawai.STATUS', 1)
            ->where('perawat.STATUS', 1)
            ->where('ruangan_kerja.STATUS', 1)
            ->whereNotNull('pegawai.NAMA')
            ->whereNotNull('ruangan_kerja.RUANGAN')
            ->whereNotNull('ruangan.DESKRIPSI')
            ->groupBy('perawat.ID');

        // Paginate the results
        $data = $query->orderBy('pegawai.NAMA')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Master/Perawat/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}