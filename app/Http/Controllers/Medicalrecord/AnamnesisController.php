<?php

namespace App\Http\Controllers\Medicalrecord;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AnamnesisController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql11')->table('medicalrecord.anamnesis as anamnesis')
            ->select(
                'anamnesis.ID as id',
                'anamnesis.KUNJUNGAN as kunjungan',
                'anamnesis.PENDAFTARAN as pendaftaran',
                'pasien.NORM as norm',
                DB::raw('master.getNamaLengkap(pasien.NORM) as nama'),
                'anamnesis.TANGGAL as tanggal',
                'ruangan.DESKRIPSI as ruangan',
                DB::raw('master.getNamaLengkapPegawai(pegawai.NIP) as oleh'),
                'anamnesis.STATUS as status',
            )
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'anamnesis.PENDAFTARAN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'anamnesis.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'anamnesis.KUNJUNGAN')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->where('anamnesis.STATUS', 1);

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(anamnesis.KUNJUNGAN) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(anamnesis.PENDAFTARAN) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pegawai.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('anamnesis.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Medicalrecord/Anamnesis/Index", [
            'dataTable' => [
                'data' => $dataArray['data'],
                'links' => $dataArray['links'],
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function filterByTime($filter)
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql11')->table('medicalrecord.anamnesis as anamnesis')
            ->select(
                'anamnesis.ID as id',
                'anamnesis.KUNJUNGAN as kunjungan',
                'anamnesis.PENDAFTARAN as pendaftaran',
                'pasien.NORM as norm',
                DB::raw('master.getNamaLengkap(pasien.NORM) as nama'),
                'anamnesis.TANGGAL as tanggal',
                'ruangan.DESKRIPSI as ruangan',
                DB::raw('master.getNamaLengkapPegawai(pegawai.NIP) as oleh'),
                'anamnesis.STATUS as status',
            )
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'anamnesis.PENDAFTARAN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'anamnesis.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'anamnesis.KUNJUNGAN')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->where('anamnesis.STATUS', 1);

        // Clone query for count calculation
        $countQuery = clone $query;

        switch ($filter) {
            case 'hariIni':
                $query->whereDate('anamnesis.TANGGAL', now()->format('Y-m-d'));
                $countQuery->whereDate('anamnesis.TANGGAL', now()->format('Y-m-d'));
                $header = 'HARI INI';
                break;

            case 'mingguIni':
                $query->whereBetween('anamnesis.TANGGAL', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $countQuery->whereBetween('anamnesis.TANGGAL', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $header = 'MINGGU INI';
                break;

            case 'bulanIni':
                $query->whereMonth('anamnesis.TANGGAL', now()->month)
                    ->whereYear('anamnesis.TANGGAL', now()->year);
                $countQuery->whereMonth('anamnesis.TANGGAL', now()->month)
                    ->whereYear('anamnesis.TANGGAL', now()->year);
                $header = 'BULAN INI';
                break;

            case 'tahunIni':
                $query->whereYear('anamnesis.TANGGAL', now()->year);
                $countQuery->whereYear('anamnesis.TANGGAL', now()->year);
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
                    ->orWhereRaw('LOWER(anamnesis.KUNJUNGAN) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(anamnesis.PENDAFTARAN) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pegawai.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('anamnesis.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Medicalrecord/Anamnesis/Index", [
            'dataTable' => [
                'data' => $dataArray['data'],
                'links' => $dataArray['links'],
            ],
            'queryParams' => request()->all(),
            'header' => $header,
            'totalCount' => number_format($count, 0, ',', '.'),
        ]);
    }

    public function detail($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql11')->table('medicalrecord.anamnesis as anamnesis')
            ->select([
                'anamnesis.*',
                DB::raw('master.getNamaLengkapPegawai(pegawai.NIP) as OLEH'),
                'ruangan.DESKRIPSI as RUANGAN_RAWATAN',
                'pasien.NORM as NORM',
                DB::raw('master.getNamaLengkap(pasien.NORM) as NAMA_PASIEN'),
            ])
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'anamnesis.PENDAFTARAN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'anamnesis.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'anamnesis.KUNJUNGAN')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->where('anamnesis.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('anamnesis.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Medicalrecord/Anamnesis/Detail", [
            'detail' => $query,
        ]);
    }
}
