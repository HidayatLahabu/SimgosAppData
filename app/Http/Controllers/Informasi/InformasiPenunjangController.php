<?php

namespace App\Http\Controllers\Informasi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InformasiPenunjangController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql12')->table('informasi.penunjang as penunjang')
            ->select(
                'penunjang.TANGGAL as tanggal',
                'penunjang.IDSUBUNIT as idSubUnit',
                DB::raw('MIN(penunjang.DESKRIPSI) as jenisKunjungan'),
                DB::raw('MIN(penunjang.INSTALASI) as instalasi'),
                DB::raw('MIN(penunjang.UNIT) as unit'),
                DB::raw('MIN(penunjang.SUBUNIT) as subUnit'),
                DB::raw('SUM(penunjang.VALUE) as jumlah'),
                DB::raw('MAX(penunjang.LASTUPDATED) as lastUpdated')
            )
            ->groupBy('penunjang.TANGGAL', 'penunjang.IDSUBUNIT');



        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(penunjang.SUBUNIT) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('penunjang.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Informasi/Penunjang/Index", [
            'dataTable' => [
                'data' => $dataArray['data'],
                'links' => $dataArray['links'],
            ],
            'queryParams' => request()->all()
        ]);
    }
}
