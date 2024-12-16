<?php

namespace App\Http\Controllers\Bpjs;

use App\Http\Controllers\Controller;
use App\Models\BpjsMonitorRekonModel;
use Illuminate\Http\Request;

class MonitoringRekonController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Define base query
        $query = BpjsMonitorRekonModel::orderByDesc('tglRencanaKontrol');

        // Apply search filter if 'subject' query parameter is present
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(noSuratKontrol) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(nama) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(noSepAsalKontrol) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(namaDokter) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Bpjs/Monitoring/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function filterByTime($filter)
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = BpjsMonitorRekonModel::orderByDesc('tglRencanaKontrol');

        // Clone query for count calculation
        $countQuery = clone $query;

        switch ($filter) {
            case 'hariIni':
                $query->whereDate('tglTerbitKontrol', now()->format('Y-m-d'));
                $countQuery->whereDate('tglTerbitKontrol', now()->format('Y-m-d'));
                $header = 'HARI INI';
                $text = 'PASIEN';
                break;

            case 'mingguIni':
                $query->whereBetween('tglTerbitKontrol', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $countQuery->whereBetween('tglTerbitKontrol', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $header = 'MINGGU INI';
                $text = 'PASIEN';
                break;

            case 'bulanIni':
                $query->whereMonth('tglTerbitKontrol', now()->month)
                    ->whereYear('tglTerbitKontrol', now()->year);
                $countQuery->whereMonth('tglTerbitKontrol', now()->month)
                    ->whereYear('tglTerbitKontrol', now()->year);
                $header = 'BULAN INI';
                $text = 'PASIEN';
                break;

            case 'tahunIni':
                $query->whereYear('tglTerbitKontrol', now()->year);
                $countQuery->whereYear('tglTerbitKontrol', now()->year);
                $header = 'TAHUN INI';
                $text = 'PASIEN';
                break;

            default:
                abort(404, 'Filter not found');
        }

        // Get count
        $count = $countQuery->count();

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(noSuratKontrol) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(nama) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(noSepAsalKontrol) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(namaDokter) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(kunjungan.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Bpjs/Monitoring/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all(),
            'header' => $header,
            'totalCount' => $count,
            'text' => $text,
        ]);
    }

    public function detail($id)
    {
        // Fetch the specific data
        $query = BpjsMonitorRekonModel::where('noSuratKontrol', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('monitoringRekon.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Bpjs/Monitoring/Detail", [
            'detail' => $query,
        ]);
    }
}