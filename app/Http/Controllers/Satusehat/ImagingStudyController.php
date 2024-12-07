<?php

namespace App\Http\Controllers\Satusehat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ImagingStudyController extends Controller
{
    public function index()
    {
        // Start building the query using the query builder
        $query = DB::connection('mysql4')->table('kemkes-ihs.imaging_study as imaging')
            ->select(
                'imaging.id as id',
                'imaging.refId as refId',
                'imaging.nopen as nopen',
                'imaging.sendDate as sendDate',
                'imaging.get as get',
                'patient.name as patient'
            )
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'imaging.nopen')
            ->leftJoin('master.kartu_identitas_pasien as kip', 'kip.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('kemkes-ihs.patient as patient', 'patient.nik', '=', 'kip.NOMOR');

        // Add search filter if provided
        if (request('search')) {
            $searchSubject = strtolower(request('search'));
            $query->whereRaw('LOWER(patient.name) LIKE ?', ['%' . $searchSubject . '%'])
                ->orWhereRaw('LOWER(imaging.refId) LIKE ?', ['%' . $searchSubject . '%'])
                ->orWhereRaw('LOWER(imaging.nopen) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('imaging.sendDate')
            ->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Satusehat/ImagingStudy/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function filterByTime($filter)
    {
        // Define base query
        $query = DB::connection('mysql4')->table('kemkes-ihs.imaging_study as imaging')
            ->select(
                'imaging.id as id',
                'imaging.refId as refId',
                'imaging.nopen as nopen',
                'imaging.sendDate as sendDate',
                'imaging.get as get',
                'patient.name as patient'
            )
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'imaging.nopen')
            ->leftJoin('master.kartu_identitas_pasien as kip', 'kip.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('kemkes-ihs.patient as patient', 'patient.nik', '=', 'kip.NOMOR');

        // Clone query for count calculation
        $countQuery = clone $query;

        switch ($filter) {
            case 'hariIni':
                $query->whereDate('imaging.sendDate', now()->format('Y-m-d'));
                $countQuery->whereDate('imaging.sendDate', now()->format('Y-m-d'));
                $header = 'HARI INI';
                $text = 'REF ID';
                break;

            case 'mingguIni':
                $query->whereBetween('imaging.sendDate', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $countQuery->whereBetween('imaging.sendDate', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $header = 'MINGGU INI';
                $text = 'REF ID';
                break;

            case 'bulanIni':
                $query->whereMonth('imaging.sendDate', now()->month)
                    ->whereYear('imaging.sendDate', now()->year);
                $countQuery->whereMonth('imaging.sendDate', now()->month)
                    ->whereYear('imaging.sendDate', now()->year);
                $header = 'BULAN INI';
                $text = 'REF ID';
                break;

            case 'tahunIni':
                $query->whereYear('imaging.sendDate', now()->year);
                $countQuery->whereYear('imaging.sendDate', now()->year);
                $header = 'TAHUN INI';
                $text = 'REF ID';
                break;

            default:
                abort(404, 'Filter not found');
        }

        // Get count
        $count = $countQuery->count();

        // Add search filter if provided
        if (request('search')) {
            $searchSubject = strtolower(request('search'));
            $query->whereRaw('LOWER(patient.name) LIKE ?', ['%' . $searchSubject . '%'])
                ->orWhereRaw('LOWER(imaging.refId) LIKE ?', ['%' . $searchSubject . '%'])
                ->orWhereRaw('LOWER(imaging.nopen) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('imaging.sendDate')
            ->paginate(10)->appends(request()->query());

        // Return Inertia view with modified data
        return inertia("Satusehat/ImagingStudy/Index", [
            'dataTable' => [
                'data' => $data->toArray()['data'], // Only the paginated data
                'links' => $data->toArray()['links'], // Pagination links
            ],
            'queryParams' => request()->all(),
            'header' => $header,
            'totalCount' => $count,
            'text' => $text,
        ]);
    }
}