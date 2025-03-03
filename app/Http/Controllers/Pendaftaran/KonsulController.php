<?php

namespace App\Http\Controllers\Pendaftaran;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
                DB::raw('master.getNamaLengkap(pasien.NORM) as nama'),
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
        $data = $query->orderByDesc('konsul.TANGGAL')->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Hitung rata-rata
        $rataRata = $this->rataRata();

        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->whereIn('JENIS_KUNJUNGAN', [1, 2, 3, 4, 5])
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Konsul/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'rataRata' => $rataRata,
            'ruangan' => $ruangan,
            'queryParams' => request()->all()
        ]);
    }

    protected function rataRata()
    {
        return DB::connection('mysql5')->table('pendaftaran.konsul as konsul')
            ->selectRaw('
                ROUND(COUNT(*) / COUNT(DISTINCT DATE(konsul.TANGGAL))) AS rata_rata_per_hari,
                ROUND(COUNT(*) / COUNT(DISTINCT WEEK(konsul.TANGGAL, 1))) AS rata_rata_per_minggu,
                ROUND(SUM(CASE WHEN konsul.TANGGAL IS NOT NULL THEN 1 ELSE 0 END) / COUNT(DISTINCT DATE_FORMAT(konsul.TANGGAL, "%Y-%m"))) AS rata_rata_per_bulan,
                ROUND(COUNT(*) / COUNT(DISTINCT YEAR(konsul.TANGGAL))) AS rata_rata_per_tahun
            ')
            ->whereIn('konsul.STATUS', [1, 2])
            ->where('konsul.TANGGAL', '>', '0000-00-00')
            ->whereNotNull('konsul.TANGGAL')
            ->whereYear('konsul.TANGGAL', now()->year)
            ->first();
    }

    public function filterByTime($filter)
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.konsul as konsul')
            ->select(
                'konsul.NOMOR as nomor',
                DB::raw('master.getNamaLengkap(pasien.NORM) as nama'),
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
        $data = $query->orderByDesc('konsul.TANGGAL')->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Hitung rata-rata
        $rataRata = $this->rataRata();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Konsul/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'rataRata' => $rataRata, // Pass rata-rata data to frontend
            'queryParams' => request()->all(),
            'header' => $header,
            'totalCount' => number_format($count, 0, ',', '.'),
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
                DB::raw('master.getNamaLengkap(pasien.NORM) as NAMA_PASIEN'),
                'ruangan.DESKRIPSI as RUANGAN_TUJUAN',
                'konsul.ALASAN as ALASAN',
                'konsul.PERMINTAAN_TINDAKAN as PERMINTAAN_TINDAKAN',
                DB::raw('master.getNamaLengkapPegawai(dokter.NIP) as DOKTER_ASAL'),
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
            ->firstOrFail();

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

    public function print(Request $request)
    {
        // Validasi input
        $request->validate([
            'ruangan' => 'nullable|string',
            'statusKonsul' => 'nullable|integer|in:0,1,2',
            'dari_tanggal' => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        // Ambil nilai input
        $ruangan = $request->input('ruangan');
        $statusKonsul = $request->input('statusKonsul');
        $dariTanggal = $request->input('dari_tanggal');
        $sampaiTanggal = $request->input('sampai_tanggal');
        $dariTanggal = Carbon::parse($dariTanggal)->format('Y-m-d H:i:s');
        $sampaiTanggal = Carbon::parse($sampaiTanggal)->endOfDay()->format('Y-m-d H:i:s');

        // Variabel default untuk label
        $namaRuangan = 'Semua Ruangan';
        $namaStatusKonsul = 'Semua Status Konsul';

        // Query utama
        $query = DB::connection('mysql5')->table('pendaftaran.konsul as konsul')
            ->select(
                'konsul.NOMOR as nomor',
                DB::raw('master.getNamaLengkap(pasien.NORM) as nama'),
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

        // Filter berdasarkan ruangan
        if (!empty($ruangan)) {
            $query->where('ruanganTujuan.ID', $ruangan);

            // Mendapatkan nama ruangan yang sesuai dari hasil query
            $namaRuangan = DB::connection('mysql5')->table('master.ruangan')
                ->where('ID', $ruangan)
                ->value('DESKRIPSI');
        }

        // Filter berdasarkan jenis kunjungan
        if ($statusKonsul === null) {
            $query->whereIn('konsul.STATUS', [0, 1, 2]);
        } elseif ($statusKonsul == 1) {
            $query->where('konsul.STATUS', 1); // Sedang Dilayani
            $namaStatusKonsul = 'Belum Diterima';
        } elseif ($statusKonsul == 2) {
            $query->where('konsul.STATUS', 2); // Selesai
            $namaStatusKonsul = 'Sudah Diterima';
        } else {
            $query->where('konsul.STATUS', 0); // Batal Kunjungan
            $namaStatusKonsul = 'Batal Konsul';
        }

        // Filter berdasarkan tanggal
        $data = $query->whereBetween('konsul.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->orderBy('konsul.TANGGAL')
            ->get();

        // Kirim data ke frontend menggunakan Inertia
        return inertia("Pendaftaran/Konsul/Print", [
            'data' => $data,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
            'namaRuangan' => $namaRuangan,
            'namaStatusKonsul' => $namaStatusKonsul,
        ]);
    }
}
