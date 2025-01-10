<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaporanWaktuTungguRegistrasiController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters from request
        // $searchTerm = $request->input('search');
        $searchTerm = request('search') ? strtolower(request('search')) : null;
        $ruangan = '1021101';
        $cara_bayar = 0;
        $perPage = 5;

        // Retrieve the report data
        $reportData = $this->getLaporan($ruangan, $cara_bayar, $perPage, $searchTerm);
        $dataArray = $reportData->toArray();

        $averageWaitData = $this->getRataRata($ruangan, $cara_bayar, $perPage, $searchTerm);

        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->whereIn('JENIS_KUNJUNGAN', [1, 2, 3, 4, 5])
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        // Return the data to the Inertia.js view
        return inertia('Laporan/WaktuTunggu/Index', [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => $request->all(),
            'averageWaitData' => $averageWaitData,
        ]);
    }

    public function getLaporan($ruangan, $cara_bayar, $perPage = 5, $searchTerm = null)
    {
        $vRuangan = $ruangan . '%';

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.pendaftaran as pd')
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
            ->whereIn('tk.STATUS', [1, 2])
            ->where('tp.RUANGAN', 'LIKE', $vRuangan);

        // Apply 'cara_bayar' filter if provided
        if ($cara_bayar != 0) {
            $query->where('pj.JENIS', '=', $cara_bayar);
        }

        // Add search filter if provided
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('p.NORM', 'LIKE', "%{$searchTerm}%")
                    ->orWhere(DB::raw("CONCAT(master.getNamaLengkap(p.NORM))"), 'LIKE', "%{$searchTerm}%")
                    ->orWhere('pd.NOMOR', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('r.DESKRIPSI', 'LIKE', "%{$searchTerm}%")
                    ->orWhere(DB::raw("master.getNamaLengkapPegawai(dok.NIP)"), 'LIKE', "%{$searchTerm}%");
            });
        }

        $data = $query->orderBy('pd.TANGGAL', 'desc')->paginate($perPage)->appends(request()->query());

        // Return paginated data
        return $data;
    }

    public function getRataRata($ruangan, $cara_bayar, $perPage = 5, $searchTerm = null)
    {
        $vRuangan = $ruangan . '%';

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.pendaftaran as pd')
            ->select([
                'r.DESKRIPSI as UNITPELAYANAN',
                DB::raw("master.getNamaLengkapPegawai(dok.NIP) as DOKTER_REG"),
                DB::raw("AVG(TIMESTAMPDIFF(SECOND, pd.TANGGAL, tk.MASUK)) as AVERAGE_SELSIH"),
                DB::raw("COUNT(tp.NOPEN) as JUMLAH_PASIEN"),
                DB::raw("MONTH(tk.MASUK) as BULAN"),
                DB::raw("YEAR(tk.MASUK) as TAHUN"),
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
            ->whereIn('tk.STATUS', [1, 2])
            ->where('tp.RUANGAN', 'LIKE', $vRuangan);

        // Apply 'cara_bayar' filter if provided
        if ($cara_bayar != 0) {
            $query->where('pj.JENIS', '=', $cara_bayar);
        }

        // Add search filter if provided
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('r.DESKRIPSI', 'LIKE', "%{$searchTerm}%")
                    ->orWhere(DB::raw("master.getNamaLengkapPegawai(dok.NIP)"), 'LIKE', "%{$searchTerm}%");
            });
        }

        // Apply ordering and pagination
        $data = $query->groupBy(
            DB::raw('MONTH(tk.MASUK)'),
            DB::raw('YEAR(tk.MASUK)'),
            'dok.NIP',
            'r.DESKRIPSI'
        )
            ->orderBy(DB::raw('YEAR(tk.MASUK)'), 'desc') // Order by year descending
            ->orderBy(DB::raw('MONTH(tk.MASUK)'), 'desc') // Order by month descending
            ->orderBy(DB::raw("AVG(TIMESTAMPDIFF(SECOND, pd.TANGGAL, tk.MASUK))"), 'asc')
            ->paginate($perPage)
            ->appends(request()->query());

        // Return paginated data
        return $data;
    }
}