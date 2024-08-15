<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StafController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql2')->table('master.staff as staf')
            ->select(
                'pegawai.GELAR_DEPAN as depan',
                'pegawai.NAMA as nama',
                'pegawai.GELAR_BELAKANG as belakang',
                'pegawai.NIP as nip',
                'pengguna.NIK as nik',
                'ruangan_kerja.RUANGAN as ruangan_kerja',
                'ruangan.DESKRIPSI as ruangan'
            )
            ->distinct()
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'staf.NIP')
            ->leftJoin('aplikasi.pengguna as pengguna', 'staf.NIP', '=', 'pengguna.NIP')
            ->leftJoin('master.staff_ruangan as ruangan_kerja', 'staf.ID', '=', 'ruangan_kerja.STAFF')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'ruangan_kerja.RUANGAN')
            ->where('pegawai.STATUS', 1)
            ->where('staf.STATUS', 1)
            ->where('ruangan_kerja.STATUS', 1)
            ->whereNotNull('pegawai.NAMA')
            ->whereNotNull('ruangan_kerja.RUANGAN')
            ->whereNotNull('ruangan.DESKRIPSI');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pegawai.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderBy('pegawai.NAMA')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Master/Staf/Index", [
            'staf' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}