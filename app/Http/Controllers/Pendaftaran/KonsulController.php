<?php

namespace App\Http\Controllers\Pendaftaran;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KonsulController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.konsul as konsul')
            ->select(
                'konsul.NOMOR as nomor',
                'pasien.NAMA as nama',
                'pasien.NORM as norm',
                'ruanganAsal.DESKRIPSI as asal',
                'ruanganTujuan.DESKRIPSI as tujuan',
                'konsul.TANGGAL as tanggal',
                'konsul.STATUS as status',
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'konsul.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruanganAsal', 'ruanganAsal.ID', '=', 'kunjungan.RUANGAN')
            ->leftJoin('master.ruangan as ruanganTujuan', 'ruanganTujuan.ID', '=', 'konsul.TUJUAN')
            ->where('pasien.STATUS', 1);

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(konsul.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('konsul.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Konsul/Index", [
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
        $query = DB::connection('mysql5')->table('pendaftaran.konsul as konsul')
            ->select([
                'konsul.NOMOR as NOMOR',
                'konsul.TANGGAL as TANGGAL',
                'konsul.KUNJUNGAN as KUNJUNGAN',
                'pendaftaran.NOMOR as PENDAFTARAN',
                'pasien.NORM as NORM',
                'pasien.NAMA as NAMA',
                'ruangan.DESKRIPSI as RUANGAN_TUJUAN',
                'konsul.ALASAN as ALASAN',
                'konsul.PERMINTAAN_TINDAKAN as PERMINTAAN_TINDAKAN',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as DOKTER_ASAL'),
                'konsul.STATUS as STATUS_KONSUL'
            ])
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'konsul.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'konsul.TUJUAN')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'konsul.DOKTER_ASAL')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->where('konsul.NOMOR', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('konsul.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Pendaftaran/Konsul/Detail", [
            'detail' => $query,
        ]);
    }

    public function filterByTime($filter)
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.konsul as konsul')
            ->select(
                'konsul.NOMOR as nomor',
                'pasien.NAMA as nama',
                'pasien.NORM as norm',
                'ruanganAsal.DESKRIPSI as asal',
                'ruanganTujuan.DESKRIPSI as tujuan',
                'konsul.TANGGAL as tanggal',
                'konsul.STATUS as status',
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'konsul.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruanganAsal', 'ruanganAsal.ID', '=', 'kunjungan.RUANGAN')
            ->leftJoin('master.ruangan as ruanganTujuan', 'ruanganTujuan.ID', '=', 'konsul.TUJUAN')
            ->where('pasien.STATUS', 1);

        // Clone query for count calculation
        $countQuery = clone $query;

        switch ($filter) {
            case 'hariIni':
                $query->whereDate('konsul.TANGGAL', now()->format('Y-m-d'));
                $countQuery->whereDate('konsul.TANGGAL', now()->format('Y-m-d'));
                $header = 'HARI INI';
                break;

            case 'mingguIni':
                $query->whereBetween('konsul.TANGGAL', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $countQuery->whereBetween('konsul.TANGGAL', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $header = 'MINGGU INI';
                break;

            case 'bulanIni':
                $query->whereMonth('konsul.TANGGAL', now()->month)
                    ->whereYear('konsul.TANGGAL', now()->year);
                $countQuery->whereMonth('konsul.TANGGAL', now()->month)
                    ->whereYear('konsul.TANGGAL', now()->year);
                $header = 'BULAN INI';
                break;

            case 'tahunIni':
                $query->whereYear('konsul.TANGGAL', now()->year);
                $countQuery->whereYear('konsul.TANGGAL', now()->year);
                $header = 'TAHUN INI';
                break;

            default:
                abort(404, 'Filter not found');
        }

        // Get count
        $count = $countQuery->count();

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(konsul.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('konsul.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Konsul/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all(),
            'header' => $header,
            'totalCount' => $count,
        ]);
    }
}