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
                'pasien.NORM as norm',
                'peserta.nama'
            )
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'pengajuan.noKartu')
            ->leftJoin('master.kartu_identitas_pasien as pasien', 'pasien.NOMOR', '=', 'peserta.nik');

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
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function detail($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql6')->table('bpjs.pengajuan as pengajuan')
            ->select(
                'pengajuan.noKartu',
                'pasien.NORM as norm',
                'peserta.nama as nama',
                'pengajuan.tglSep',
                'pengajuan.jnsPelayanan',
                'pengajuan.jnsPengajuan',
                'pengajuan.keterangan',
                'pengajuan.user',
                'pengajuan.tgl',
                'pengajuan.tglAprove',
                'pengajuan.userAprove',
                'pengajuan.status',
            )
            ->leftJoin('bpjs.peserta as peserta', 'peserta.noKartu', '=', 'pengajuan.noKartu')
            ->leftJoin('master.kartu_identitas_pasien as pasien', 'pasien.NOMOR', '=', 'peserta.nik')
            ->where('pengajuan.tgl', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('pengajuanSep.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Bpjs/Pengajuan/Detail", [
            'detail' => $query,
        ]);
    }
}