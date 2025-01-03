<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaporanWaktuTungguRegistrasiController extends Controller
{
    /**
     * Display the report for waiting times during registration.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        // Get filter parameters from request
        $tgl_awal = $request->input('tgl_awal', Carbon::now()->startOfYear()->format('Y-m-d H:i:s'));
        $tgl_akhir = $request->input('tgl_akhir', Carbon::now()->endOfDay()->format('Y-m-d H:i:s'));
        $searchTerm = $request->input('search');
        $ruangan = '1021101'; // Default ruangan value
        $cara_bayar = 0; // Default cara bayar value
        $perPage = 5; // Items per page

        $namaBulan = date('m'); // e.g., 'Desember'
        $tahun = date('Y'); // e.g., '2024'

        // Retrieve the report data
        $reportData = $this->getLaporan($tgl_awal, $tgl_akhir, $ruangan, $cara_bayar, $perPage, $searchTerm);

        // Retrieve the average waiting time data (Rata-Rata)
        $averageWaitData = $this->getRataRata($ruangan, $cara_bayar, $perPage);

        // Return the data to the Inertia.js view
        return inertia('Laporan/WaktuTunggu/Index', [
            'dataTable' => $reportData,
            'queryParams' => $request->all(),
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'averageWaitData' => $averageWaitData,
            'namaBulan' => $namaBulan,  // Pass current month
            'tahun' => $tahun,    // Pass current year
        ]);
    }

    /**
     * Fetch the waiting time data from the database.
     *
     * @param string $tgl_awal
     * @param string $tgl_akhir
     * @param string $ruangan
     * @param int $cara_bayar
     * @return \Illuminate\Support\Collection
     */
    private function getLaporan($tgl_awal, $tgl_akhir, $ruangan, $cara_bayar, $perPage = 5, $searchTerm = null)
    {
        $vRuangan = $ruangan . '%';

        return DB::connection('mysql5')->table('pendaftaran.pendaftaran as pd')
            ->select([
                'p.NORM as NORM',
                DB::raw("CONCAT(master.getNamaLengkap(p.NORM)) as NAMALENGKAP"),
                'pd.NOMOR as NOPEN',
                'r.DESKRIPSI as UNITPELAYANAN',
                DB::raw("master.getNamaLengkapPegawai(dok.NIP) as DOKTER_REG"),
                DB::raw("DATE_FORMAT(pd.TANGGAL, '%d-%m-%Y %H:%i:%s') as TGLREG"),
                DB::raw("DATE_FORMAT(tk.MASUK, '%d-%m-%Y %H:%i:%s') as TGLTERIMA"),
                DB::raw("DATE_FORMAT(TIMEDIFF(tk.MASUK, pd.TANGGAL), '%H:%i:%s') as SELISIH"),
            ])
            ->join('master.pasien as p', 'pd.NORM', '=', 'p.NORM')
            ->join('pendaftaran.tujuan_pasien as tp', 'pd.NOMOR', '=', 'tp.NOPEN')
            ->join('pendaftaran.kunjungan as tk', function ($join) {
                $join->on('pd.NOMOR', '=', 'tk.NOPEN')
                    ->on('tp.RUANGAN', '=', 'tk.RUANGAN');
            })
            ->join('master.ruangan as r', function ($join) {
                $join->on('tp.RUANGAN', '=', 'r.ID')
                    ->where('r.JENIS', '=', 5);
            })
            ->leftJoin('master.dokter as dok', 'tp.DOKTER', '=', 'dok.ID')
            ->whereIn('pd.STATUS', [1])
            ->whereNull('tk.REF')
            ->whereBetween('tk.MASUK', [$tgl_awal, $tgl_akhir])
            ->whereIn('tk.STATUS', [1, 2])
            ->where('tp.RUANGAN', 'LIKE', $vRuangan)
            ->when($cara_bayar != 0, function ($query) use ($cara_bayar) {
                $query->where('pj.JENIS', '=', $cara_bayar);
            })
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('p.NORM', 'LIKE', "%{$searchTerm}%")
                        ->orWhere(DB::raw("CONCAT(master.getNamaLengkap(p.NORM))"), 'LIKE', "%{$searchTerm}%")
                        ->orWhere('pd.NOMOR', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('r.DESKRIPSI', 'LIKE', "%{$searchTerm}%")
                        ->orWhere(DB::raw("master.getNamaLengkapPegawai(dok.NIP)"), 'LIKE', "%{$searchTerm}%");
                });
            })
            ->orderBy('pd.TANGGAL', 'desc')
            ->paginate($perPage);
    }

    private function getRataRata($ruangan, $cara_bayar, $perPage = 5)
    {
        $vRuangan = $ruangan . '%';

        // Ambil bulan dan tahun saat ini
        $currentMonth = date('m');  // Bulan saat ini (01-12)
        $currentYear = date('Y');   // Tahun saat ini (yyyy)

        return DB::connection('mysql5')->table('pendaftaran.pendaftaran as pd')
            ->select([
                'r.DESKRIPSI as UNITPELAYANAN',
                DB::raw("master.getNamaLengkapPegawai(dok.NIP) as DOKTER_REG"),
                DB::raw("AVG(TIMESTAMPDIFF(SECOND, pd.TANGGAL, tk.MASUK)) as AVERAGE_SELSIH"),
                DB::raw("COUNT(tp.NOPEN) as JUMLAH_PASIEN"),
            ])
            ->join('master.pasien as p', 'pd.NORM', '=', 'p.NORM')
            ->join('pendaftaran.tujuan_pasien as tp', 'pd.NOMOR', '=', 'tp.NOPEN')
            ->join('pendaftaran.kunjungan as tk', function ($join) {
                $join->on('pd.NOMOR', '=', 'tk.NOPEN')
                    ->on('tp.RUANGAN', '=', 'tk.RUANGAN');
            })
            ->join('master.ruangan as r', function ($join) {
                $join->on('tp.RUANGAN', '=', 'r.ID')
                    ->where('r.JENIS', '=', 5);
            })
            ->leftJoin('master.dokter as dok', 'tp.DOKTER', '=', 'dok.ID')
            ->whereIn('pd.STATUS', [1])
            ->whereNull('tk.REF')
            ->whereYear('tk.MASUK', '=', $currentYear)
            ->whereMonth('tk.MASUK', '=', $currentMonth)
            ->whereIn('tk.STATUS', [1, 2])
            ->where('tp.RUANGAN', 'LIKE', $vRuangan)
            ->whereNotNull('dok.NIP')
            ->when($cara_bayar != 0, function ($query) use ($cara_bayar) {
                $query->where('pj.JENIS', '=', $cara_bayar);
            })
            ->groupBy('dok.NIP', 'r.DESKRIPSI')
            ->orderBy(DB::raw("AVG(TIMESTAMPDIFF(SECOND, pd.TANGGAL, tk.MASUK))", 'asc'))
            ->paginate($perPage);
    }
}