<?php

namespace App\Http\Controllers\Logs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PenggunaRequestController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Get today's date
        $today = now()->format('Y-m-d'); // 'Y-m-d' is the format for dates

        // Start building the query using the query builder
        $query = DB::connection('mysql8')->table('logs.pengguna_request_log as request')
            ->select(
                'request.ID as id',
                'request.TANGGAL_AKSES as mulai',
                'request.TANGGAL_SELESAI as selesai',
                'request.LOKASI_AKSES as asal',
                'request.TUJUAN_AKSES as tujuan',
                'request.REQUEST_URI as url',
                'pengguna.NAMA as nama'
            )
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'request.PENGGUNA');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pengguna.nama) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Add filter for today's date
        $query->whereDate('request.TANGGAL_AKSES', $today);

        // Paginate the results
        $data = $query->orderByDesc('request.TANGGAL_AKSES')->paginate(10); // 10 items per page

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Logs/Request/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}
