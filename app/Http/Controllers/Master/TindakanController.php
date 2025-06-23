<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TindakanController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql2')->table('master.tindakan as tindakan')
            ->select(
                'tindakan.ID as id',
                'referensi.DESKRIPSI as jenis',
                'tindakan.NAMA as nama',
                'tarif.TARIF as tarif',
                'tarif.TANGGAL_SK as tanggalSK',
                'tarif.NOMOR_SK as nomorSK',
            )
            ->distinct()
            ->leftJoin('master.tarif_tindakan as tarif', 'tindakan.ID', '=', 'tarif.TINDAKAN')
            ->leftJoin('master.referensi as referensi', 'referensi.ID', '=', 'tindakan.JENIS')
            ->where('tindakan.STATUS', 1)
            ->where('tarif.STATUS', 1)
            ->where('referensi.JENIS', 74)
            ->orderBy('referensi.DESKRIPSI')
            ->orderBy('tindakan.NAMA');

        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(tindakan.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(referensi.DESKRIPSI) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Master/Tindakan/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }
}