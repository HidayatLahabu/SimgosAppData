<?php

namespace App\Http\Controllers\Medicalrecord;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class JadwalKontrolController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql11')->table('medicalrecord.jadwal_kontrol as jadwalKontrol')
            ->select(
                'jadwalKontrol.ID as id',
                'jadwalKontrol.KUNJUNGAN as kunjungan',
                'pasien.NORM as norm',
                'pasien.NAMA as nama',
                'jadwalKontrol.TANGGAL as tanggal',
                'jadwalKontrol.JAM as jam',
                'ruangan.DESKRIPSI as ruangan',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as oleh'),
                'jadwalKontrol.DIBUAT_TANGGAL as dibuat',
                'jadwalKontrol.STATUS as status',
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'jadwalKontrol.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'jadwalKontrol.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->where('jadwalKontrol.STATUS', 1);

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(jadwalKontrol.KUNJUNGAN) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pegawai.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('jadwalKontrol.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Medicalrecord/JadwalKontrol/Index", [
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
        $query = DB::connection('mysql11')->table('medicalrecord.jadwal_kontrol as jadwalKontrol')
            ->select(
                'jadwalKontrol.ID as id',
                'jadwalKontrol.KUNJUNGAN as kunjungan',
                'pasien.NORM as norm',
                'pasien.NAMA as nama',
                'jadwalKontrol.TANGGAL as tanggal',
                'jadwalKontrol.JAM as jam',
                'ruangan.DESKRIPSI as ruangan',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as oleh'),
                'jadwalKontrol.DIBUAT_TANGGAL as dibuat',
                'jadwalKontrol.STATUS as status',
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'jadwalKontrol.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'jadwalKontrol.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->where('jadwalKontrol.STATUS', 1);

        // Clone query for count calculation
        $countQuery = clone $query;

        switch ($filter) {
            case 'hariIni':
                $query->whereDate('jadwalKontrol.TANGGAL', now()->format('Y-m-d'));
                $countQuery->whereDate('jadwalKontrol.TANGGAL', now()->format('Y-m-d'));
                $header = 'HARI INI';
                break;

            case 'mingguIni':
                $query->whereBetween('jadwalKontrol.TANGGAL', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $countQuery->whereBetween('jadwalKontrol.TANGGAL', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $header = 'MINGGU INI';
                break;

            case 'bulanIni':
                $query->whereMonth('jadwalKontrol.TANGGAL', now()->month)
                    ->whereYear('jadwalKontrol.TANGGAL', now()->year);
                $countQuery->whereMonth('jadwalKontrol.TANGGAL', now()->month)
                    ->whereYear('jadwalKontrol.TANGGAL', now()->year);
                $header = 'BULAN INI';
                break;

            case 'tahunIni':
                $query->whereYear('jadwalKontrol.TANGGAL', now()->year);
                $countQuery->whereYear('jadwalKontrol.TANGGAL', now()->year);
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
                    ->orWhereRaw('LOWER(jadwalKontrol.KUNJUNGAN) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pegawai.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('jadwalKontrol.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Medicalrecord/JadwalKontrol/Index", [
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
        $query = DB::connection('mysql11')->table('medicalrecord.jadwal_kontrol as jadwalKontrol')
            ->select([
                'jadwalKontrol.*',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                DB::raw('CONCAT(dokter.GELAR_DEPAN, " ", dokter.NAMA, " ", dokter.GELAR_BELAKANG) as DOKTER'),
                'tujuan.DESKRIPSI as TUJUAN',
                'ruangan.DESKRIPSI as RUANGAN_RAWATAN',
                'pasien.NORM as NORM',
                'pasien.NAMA as NAMA_PASIEN',
            ])
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'jadwalKontrol.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as tenagaMedis', 'tenagaMedis.ID', '=', 'jadwalKontrol.DOKTER')
            ->leftJoin('master.pegawai as dokter', 'dokter.NIP', '=', 'tenagaMedis.NIP')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'jadwalKontrol.RUANGAN')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'jadwalKontrol.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('master.referensi as tujuan', function ($join) {
                $join->on('tujuan.ID', '=', 'jadwalKontrol.TUJUAN')
                    ->where('tujuan.JENIS', '=', 26);
            })
            ->where('jadwalKontrol.ID', $id)
            ->distinct()
            ->firstOrFail();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('jadwalKontrol.index')->with('error', 'Data not found.');
        }

        // Process all fields
        foreach ((array)$query as $key => $value) {
            $decoded = json_decode($value, true);

            // Remove fields with empty arrays
            if (is_array($decoded) && empty($decoded)) {
                unset($query->{$key});
            }
            // For arrays with data, convert them to a comma-separated string
            elseif (is_array($decoded)) {
                $query->{$key} = implode(', ', $decoded);
            }
            // For non-JSON fields, apply html_entity_decode and strip_tags
            else {
                $query->{$key} = html_entity_decode(strip_tags($value));
            }
        }

        // Return Inertia view with the encounter data
        return inertia("Medicalrecord/JadwalKontrol/Detail", [
            'detail' => $query,
        ]);
    }
}