<?php

namespace App\Http\Controllers\Layanan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ResepController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql7')->table('layanan.order_resep as orderResep')
            ->select(
                'orderResep.NOMOR as nomor',
                'orderResep.TANGGAL as tanggal',
                'orderResep.KUNJUNGAN as kunjungan',
                'pegawai.NAMA as dokter',
                'pegawai.GELAR_DEPAN as gelarDepan',
                'pegawai.GELAR_BELAKANG as gelarBelakang',
                'pasien.NORM as norm',
                'pasien.NAMA as nama',
                'peserta.noKartu'
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'orderResep.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'orderResep.DOKTER_DPJP')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('bpjs.peserta as peserta', 'pasien.NORM', '=', 'peserta.norm');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pasien.nama) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('orderResep.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Layanan/Resep/Index", [
            'layananResep' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}