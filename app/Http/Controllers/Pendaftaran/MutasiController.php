<?php

namespace App\Http\Controllers\Pendaftaran;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MutasiController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.mutasi as mutasi')
            ->select(
                'mutasi.NOMOR as nomor',
                'pasien.NAMA as nama',
                'pasien.NORM as norm',
                'ruanganTujuan.DESKRIPSI as tujuan',
                'mutasi.TANGGAL as tanggal',
                'mutasi.RESERVASI as reservasi',
                'mutasi.STATUS as status',
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'mutasi.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruanganTujuan', 'ruanganTujuan.ID', '=', 'mutasi.TUJUAN')
            ->where('pasien.STATUS', 1);

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(mutasi.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('mutasi.TANGGAL')->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Hitung rata-rata
        $rataRata = $this->rataRata();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Mutasi/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'rataRata' => $rataRata, // Pass rata-rata data to frontend
            'queryParams' => request()->all()
        ]);
    }

    protected function rataRata()
    {
        return DB::connection('mysql5')->table('pendaftaran.mutasi as mutasi')
            ->selectRaw('
            ROUND(COUNT(*) / COUNT(DISTINCT DATE(mutasi.TANGGAL))) AS rata_rata_per_hari,
            ROUND(COUNT(*) / COUNT(DISTINCT WEEK(mutasi.TANGGAL, 1))) AS rata_rata_per_minggu,
            ROUND(COUNT(*) / COUNT(DISTINCT DATE_FORMAT(mutasi.TANGGAL, "%Y-%m"))) AS rata_rata_per_bulan,
            ROUND(COUNT(*) / COUNT(DISTINCT YEAR(mutasi.TANGGAL))) AS rata_rata_per_tahun
        ')
            ->whereIn('STATUS', [1, 2])
            ->first();
    }

    public function filterByTime($filter)
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.mutasi as mutasi')
            ->select(
                'mutasi.NOMOR as nomor',
                'pasien.NAMA as nama',
                'pasien.NORM as norm',
                'ruanganTujuan.DESKRIPSI as tujuan',
                'mutasi.TANGGAL as tanggal',
                'mutasi.RESERVASI as reservasi',
                'mutasi.STATUS as status',
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'mutasi.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruanganTujuan', 'ruanganTujuan.ID', '=', 'mutasi.TUJUAN')
            ->where('pasien.STATUS', 1);

        // Clone query for count calculation
        $countQuery = clone $query;

        switch ($filter) {
            case 'hariIni':
                $query->whereDate('mutasi.TANGGAL', now()->format('Y-m-d'));
                $countQuery->whereDate('mutasi.TANGGAL', now()->format('Y-m-d'));
                $header = 'HARI INI';
                break;

            case 'mingguIni':
                $query->whereBetween('mutasi.TANGGAL', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $countQuery->whereBetween('mutasi.TANGGAL', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $header = 'MINGGU INI';
                break;

            case 'bulanIni':
                $query->whereMonth('mutasi.TANGGAL', now()->month)
                    ->whereYear('mutasi.TANGGAL', now()->year);
                $countQuery->whereMonth('mutasi.TANGGAL', now()->month)
                    ->whereYear('mutasi.TANGGAL', now()->year);
                $header = 'BULAN INI';
                break;

            case 'tahunIni':
                $query->whereYear('mutasi.TANGGAL', now()->year);
                $countQuery->whereYear('mutasi.TANGGAL', now()->year);
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
                    ->orWhereRaw('LOWER(mutasi.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('mutasi.TANGGAL')->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Hitung rata-rata
        $rataRata = $this->rataRata();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Mutasi/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'rataRata' => $rataRata, // Pass rata-rata data to frontend
            'queryParams' => request()->all(),
            'header' => $header,
            'totalCount' => $count,
        ]);
    }

    public function detail($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('pendaftaran.mutasi as mutasi')
            ->select([
                'mutasi.NOMOR as NOMOR',
                'mutasi.KUNJUNGAN as KUNJUNGAN',
                'mutasi.TANGGAL as TANGGAL',
                'pasien.NORM as NORM',
                'pasien.NAMA as NAMA',
                'mutasi.STATUS as STATUS_MUTASI',
                'mutasi_oleh.NAMA as MUTASI_OLEH',
                'mutasi.STATUS as STATUS_MUTASI',
                'mutasi.RESERVASI as RESERVASI',
                'ruangan.DESKRIPSI as RUANGAN_TUJUAN',
                'ruang_kamar.KAMAR as KAMAR_TUJUAN',
                'ruang_kamar_tidur.TEMPAT_TIDUR as TEMPAT_TIDUR',
                'reservasi.ATAS_NAMA as ATAS_NAMA',
                'reservasi.KONTAK_INFO as NOMOR_KONTAK',
                'reservasi_oleh.NAMA as RESERVASI_OLEH',
                'reservasi.STATUS as STATUS_RESERVASI'
            ])
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'mutasi.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('pendaftaran.reservasi as reservasi', 'reservasi.NOMOR', '=', 'mutasi.RESERVASI')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'mutasi.TUJUAN')
            ->leftJoin('master.ruang_kamar_tidur as ruang_kamar_tidur', 'ruang_kamar_tidur.ID', '=', 'reservasi.RUANG_KAMAR_TIDUR')
            ->leftJoin('master.ruang_kamar as ruang_kamar', 'ruang_kamar.ID', '=', 'ruang_kamar_tidur.RUANG_KAMAR')
            ->leftJoin('aplikasi.pengguna as mutasi_oleh', 'mutasi_oleh.ID', '=', 'mutasi.OLEH')
            ->leftJoin('aplikasi.pengguna as reservasi_oleh', 'reservasi_oleh.ID', '=', 'reservasi.OLEH')
            ->where('mutasi.NOMOR', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('mutasi.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Pendaftaran/Mutasi/Detail", [
            'detail' => $query,
        ]);
    }
}