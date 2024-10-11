<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PenggunaLogController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Get today's date
        $today = now()->format('Y-m-d'); // 'Y-m-d' is the format for dates

        // Start building the query using the query builder
        $query = DB::connection('mysql9')->table('aplikasi.pengguna_log as penggunaLog')
            ->select(
                'penggunaLog.ID as id',
                'penggunaLog.TANGGAL_AKSES as mulai',
                'penggunaLog.LOKASI_AKSES as asal',
                'penggunaLog.TUJUAN_AKSES as tujuan',
                'penggunaLog.AGENT as agent',
                'pengguna.NAMA as nama'
            )
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'penggunaLog.PENGGUNA');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pengguna.nama) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Add filter for today's date
        $query->whereDate('penggunaLog.TANGGAL_AKSES', $today);

        // Paginate the results
        $data = $query->orderByDesc('penggunaLog.TANGGAL_AKSES')->paginate(10); // 10 items per page

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Logs/Pengguna/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}