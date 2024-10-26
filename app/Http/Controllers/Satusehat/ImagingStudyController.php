<?php

namespace App\Http\Controllers\Satusehat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ImagingStudyController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('patient') ? strtolower(request('patient')) : null;

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
        if ($searchSubject) {
            $query->whereRaw('LOWER(patient.name) LIKE ?', ['%' . $searchSubject . '%']);
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
}
