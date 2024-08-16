<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PegawaiController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql2')->table('master.pegawai as pegawai')
            ->select(
                'pegawai.ID as id',
                'pegawai.GELAR_DEPAN as depan',
                'pegawai.NAMA as nama',
                'pegawai.GELAR_BELAKANG as belakang',
                'pegawai.NIP as nip',
                'pengguna.NIK as nik',
                'referensi.DESKRIPSI as profesi'
            )
            ->leftJoin('aplikasi.pengguna as pengguna', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('master.referensi as referensi', 'pegawai.SMF', '=', 'referensi.ID')
            ->where('pegawai.STATUS', 1)
            ->whereNotNull('pegawai.NAMA')
            ->where('referensi.JENIS', 26)
            ->orderBy('pegawai.NAMA');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pegawai.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Master/Pegawai/Index", [
            'pegawai' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}