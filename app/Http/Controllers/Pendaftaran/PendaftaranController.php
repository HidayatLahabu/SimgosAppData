<?php

namespace App\Http\Controllers\Pendaftaran;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select(
                'pendaftaran.NOMOR as nomor',
                'pendaftaran.NORM as norm',
                'pasien.NAMA as nama',
                'kip.ALAMAT as alamat',
                'pendaftaran.TANGGAL as tanggal'
            )
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.kartu_identitas_pasien as kip', 'pendaftaran.NORM', '=', 'kip.NORM')
            ->leftJoin('bpjs.peserta as peserta', 'pendaftaran.NORM', '=', 'peserta.norm')
            ->where('pendaftaran.STATUS', 1)
            ->where('pasien.STATUS', 1);

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('pendaftaran.NOMOR')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Pendaftaran/Index", [
            'pendaftaran' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}