<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Http\Request;
use App\Models\LogsAksesModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PenggunaAksesController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Get today's date
        $today = now()->format('Y-m-d'); // 'Y-m-d' is the format for dates

        // Start building the query using the query builder
        $query = DB::connection('mysql8')->table('logs.pengguna_akses_log as akses')
            ->select(
                'akses.ID as id',
                'akses.TANGGAL as tanggal',
                'akses.AKSI as aksi',
                'akses.SEBELUM as sebelum',
                'akses.SESUDAH as sesudah',
                'pengguna.NAMA as nama'
            )
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'akses.PENGGUNA');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pengguna.nama) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Add filter for today's date
        $query->whereDate('akses.TANGGAL', $today);

        // Paginate the results
        $data = $query->orderByDesc('akses.TANGGAL')->paginate(10); // 10 items per page

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Logs/Akses/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}