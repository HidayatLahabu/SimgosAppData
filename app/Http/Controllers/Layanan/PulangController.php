<?php

namespace App\Http\Controllers\Layanan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PulangController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql7')->table('layanan.pasien_pulang as pulang')
            ->select(
                'pulang.ID as id',
                'pulang.TANGGAL as tanggal',
                'pegawai.NAMA as dokter',
                'pegawai.GELAR_DEPAN as gelarDepan',
                'pegawai.GELAR_BELAKANG as gelarBelakang',
                'pasien.NORM as norm',
                'pasien.NAMA as nama',
                'refCara.DESKRIPSI as status',
                'refKeadaan.DESKRIPSI as keadaan'
            )
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'pulang.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'pulang.DOKTER')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('bpjs.peserta as peserta', 'pasien.NORM', '=', 'peserta.norm')
            ->leftJoin('master.referensi as refCara', function ($join) {
                $join->on('refCara.ID', '=', 'pulang.CARA')
                    ->where('refCara.JENIS', '=', 13);
            })
            ->leftJoin('master.referensi as refKeadaan', function ($join) {
                $join->on('refKeadaan.ID', '=', 'pulang.KEADAAN')
                    ->where('refKeadaan.JENIS', '=', 46);
            });

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pasien.nama) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('pulang.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Layanan/Pulang/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}