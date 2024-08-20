<?php

namespace App\Http\Controllers\Bpjs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PengajuanSepController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql6')->table('bpjs.pengajuan as pengajuan')
            ->select(
                'pengajuan.noKartu',
                'pengajuan.tglSep',
                'pengajuan.keterangan',
                'pengajuan.tgl',
                'pengajuan.tglAprove',
                'peserta.norm',
                'peserta.nama'
            )
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'pengajuan.noKartu');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(peserta.nama) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('pengajuan.tgl')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Bpjs/Pengajuan/Index", [
            'pengajuanSep' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}