<?php

namespace App\Http\Controllers\Layanan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LaboratoriumController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql7')->table('layanan.order_lab as orderLab')
            ->select(
                'orderLab.NOMOR as nomor',
                'orderLab.TANGGAL as tanggal',
                'orderLab.KUNJUNGAN as kunjungan',
                'pegawai.NAMA as dokter',
                'pegawai.GELAR_DEPAN as gelarDepan',
                'pegawai.GELAR_BELAKANG as gelarBelakang',
                'pasien.norm',
                'pasien.nama',
                'peserta.noKartu'
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'orderLab.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.ID', '=', 'orderLab.DOKTER_ASAL')
            ->leftJoin('bpjs.peserta as peserta', 'pasien.NORM', '=', 'peserta.norm');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pasien.nama) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('orderLab.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Layanan/Laboratorium/Index", [
            'layananLab' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}