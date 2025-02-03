<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PengunjungRekapController extends Controller
{
    public function index()
    {
        // Tanggal awal tahun
        $tgl_awal = Carbon::now()->startOfYear()->format('Y-m-d 00:00:00');
        // Tanggal hari ini
        $tgl_akhir = Carbon::now()->endOfDay()->format('Y-m-d 23:59:59');

        // Subquery untuk agregasi data pasien dan status pengunjung
        $query = DB::connection('mysql2')
            ->table('master.pasien as p')
            ->select([
                DB::raw('IF(DATE_FORMAT(p.TANGGAL, "%d-%m-%Y") = DATE_FORMAT(tk.MASUK, "%d-%m-%Y"), 1, 2) AS IDSTATUSPENGUNJUNG'),
                DB::raw('COUNT(pd.NOMOR) AS JUMLAH'),
                DB::raw('SUM(IF(ref.ID = 1, 1, 0)) AS UMUM'),
                DB::raw('SUM(IF(ref.ID = 2, 1, 0)) AS JKN'),
                DB::raw('SUM(IF(ref.ID = 3, 1, 0)) AS INHEALTH'),
                DB::raw('SUM(IF(ref.ID = 4, 1, 0)) AS JKD'),
                DB::raw('SUM(IF(iks.ID IS NOT NULL, 1, 0)) AS IKS'),
                DB::raw('SUM(IF(p.JENIS_KELAMIN = 1, 1, 0)) AS LAKILAKI'),
                DB::raw('SUM(IF(p.JENIS_KELAMIN = 2, 1, 0)) AS PEREMPUAN'),
            ])
            ->join('pendaftaran.pendaftaran as pd', 'p.NORM', '=', 'pd.NORM')
            ->leftJoin('pendaftaran.penjamin as pj', 'pd.NOMOR', '=', 'pj.NOPEN')
            ->leftJoin('master.referensi as ref', function ($join) {
                $join->on('pj.JENIS', '=', 'ref.ID')
                    ->where('ref.JENIS', '=', 10);
            })
            ->leftJoin('master.ikatan_kerja_sama as iks', function ($join) {
                $join->on('ref.ID', '=', 'iks.ID')
                    ->where('ref.JENIS', '=', 10);
            })
            ->leftJoin('master.kartu_asuransi_pasien as kap', function ($join) {
                $join->on('pd.NORM', '=', 'kap.NORM')
                    ->on('ref.ID', '=', 'kap.JENIS')
                    ->where('ref.JENIS', '=', 10);
            })
            ->leftJoin('pendaftaran.surat_rujukan_pasien as srp', function ($join) {
                $join->on('pd.RUJUKAN', '=', 'srp.ID')
                    ->where('srp.STATUS', '!=', 0);
            })
            ->leftJoin('master.ppk as ppk', 'srp.PPK', '=', 'ppk.ID')
            ->join('pendaftaran.tujuan_pasien as tp', 'pd.NOMOR', '=', 'tp.NOPEN')
            ->leftJoin('master.ruangan as r', function ($join) {
                $join->on('tp.RUANGAN', '=', 'r.ID')
                    ->where('r.JENIS', '=', 5);
            })
            ->join('pendaftaran.kunjungan as tk', function ($join) {
                $join->on('pd.NOMOR', '=', 'tk.NOPEN')
                    ->on('tp.RUANGAN', '=', 'tk.RUANGAN');
            })
            ->join('master.ruangan as jkr', function ($join) {
                $join->on('tk.RUANGAN', '=', 'jkr.ID')
                    ->where('jkr.JENIS', '=', 5);
            })
            ->whereIn('pd.STATUS', [1, 2])
            ->whereNull('tk.REF')
            ->whereBetween('tk.MASUK', [$tgl_awal, $tgl_akhir])
            ->whereIn('tk.STATUS', [1, 2])
            ->where('tp.RUANGAN', 'LIKE', '%')
            ->groupBy(DB::raw('IF(DATE_FORMAT(p.TANGGAL, "%d-%m-%Y") = DATE_FORMAT(tk.MASUK, "%d-%m-%Y"), 1, 2)'));

        // Ambil data ruangan
        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        $data = $query->get();

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'JUMLAH' => 0,
            'UMUM' => 0,
            'JKN' => 0,
            'INHEALTH' => 0,
            'JKD' => 0,
            'IKS' => 0,
            'LAKILAKI' => 0,
            'PEREMPUAN' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($data as $row) {
            $total['JUMLAH'] += $row->JUMLAH;
            $total['UMUM'] += $row->UMUM;
            $total['JKN'] += $row->JKN;
            $total['INHEALTH'] += $row->INHEALTH;
            $total['JKD'] += $row->JKD;
            $total['IKS'] += $row->IKS;
            $total['LAKILAKI'] += $row->LAKILAKI;
            $total['PEREMPUAN'] += $row->PEREMPUAN;
        }

        // Return hasil ke frontend
        return inertia('Laporan/PengunjungRekap/Index', [
            'ruangan' => $ruangan,
            'dataTable' => $data,
            'total' => $total,
            'tglAwal' => $tgl_awal,
            'tglAkhir' => $tgl_akhir,
        ]);
    }


    public function print(Request $request)
    {
        $request->validate([
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
            'ruangan'        => 'nullable|integer',
        ]);

        // Ambil nilai input
        $ruangan  = $request->input('ruangan');
        $dariTanggal    = $request->input('dari_tanggal');
        $sampaiTanggal  = $request->input('sampai_tanggal');
        $dariTanggal = Carbon::parse($dariTanggal)->format('Y-m-d H:i:s');
        $sampaiTanggal = Carbon::parse($sampaiTanggal)->endOfDay()->format('Y-m-d H:i:s');

        $query = DB::connection('mysql2')
            ->table('master.pasien as p')
            ->select([
                DB::raw('IF(DATE_FORMAT(p.TANGGAL, "%d-%m-%Y") = DATE_FORMAT(tk.MASUK, "%d-%m-%Y"), 1, 2) AS IDSTATUSPENGUNJUNG'),
                DB::raw('COUNT(pd.NOMOR) AS JUMLAH'),
                DB::raw('SUM(IF(ref.ID = 1, 1, 0)) AS UMUM'),
                DB::raw('SUM(IF(ref.ID = 2, 1, 0)) AS JKN'),
                DB::raw('SUM(IF(ref.ID = 3, 1, 0)) AS INHEALTH'),
                DB::raw('SUM(IF(ref.ID = 4, 1, 0)) AS JKD'),
                DB::raw('SUM(IF(iks.ID IS NOT NULL, 1, 0)) AS IKS'),
                DB::raw('SUM(IF(p.JENIS_KELAMIN = 1, 1, 0)) AS LAKILAKI'),
                DB::raw('SUM(IF(p.JENIS_KELAMIN = 2, 1, 0)) AS PEREMPUAN'),
            ])
            ->join('pendaftaran.pendaftaran as pd', 'p.NORM', '=', 'pd.NORM')
            ->leftJoin('pendaftaran.penjamin as pj', 'pd.NOMOR', '=', 'pj.NOPEN')
            ->leftJoin('master.referensi as ref', function ($join) {
                $join->on('pj.JENIS', '=', 'ref.ID')
                    ->where('ref.JENIS', '=', 10);
            })
            ->leftJoin('master.ikatan_kerja_sama as iks', function ($join) {
                $join->on('ref.ID', '=', 'iks.ID')
                    ->where('ref.JENIS', '=', 10);
            })
            ->leftJoin('master.kartu_asuransi_pasien as kap', function ($join) {
                $join->on('pd.NORM', '=', 'kap.NORM')
                    ->on('ref.ID', '=', 'kap.JENIS')
                    ->where('ref.JENIS', '=', 10);
            })
            ->leftJoin('pendaftaran.surat_rujukan_pasien as srp', function ($join) {
                $join->on('pd.RUJUKAN', '=', 'srp.ID')
                    ->where('srp.STATUS', '!=', 0);
            })
            ->leftJoin('master.ppk as ppk', 'srp.PPK', '=', 'ppk.ID')
            ->join('pendaftaran.tujuan_pasien as tp', 'pd.NOMOR', '=', 'tp.NOPEN')
            ->leftJoin('master.ruangan as r', function ($join) {
                $join->on('tp.RUANGAN', '=', 'r.ID')
                    ->where('r.JENIS', '=', 5);
            })
            ->join('pendaftaran.kunjungan as tk', function ($join) {
                $join->on('pd.NOMOR', '=', 'tk.NOPEN')
                    ->on('tp.RUANGAN', '=', 'tk.RUANGAN');
            })
            ->join('master.ruangan as jkr', function ($join) {
                $join->on('tk.RUANGAN', '=', 'jkr.ID')
                    ->where('jkr.JENIS', '=', 5);
            })
            ->whereIn('pd.STATUS', [1, 2])
            ->whereNull('tk.REF')
            ->whereIn('tk.STATUS', [1, 2])
            ->groupBy(DB::raw('IF(DATE_FORMAT(p.TANGGAL, "%d-%m-%Y") = DATE_FORMAT(tk.MASUK, "%d-%m-%Y"), 1, 2)'));

        // Filter berdasarkan tanggal
        $data = $query
            ->whereBetween(DB::raw('DATE(tk.MASUK)'), [$dariTanggal, $sampaiTanggal])
            ->get()
            ->toArray();

        // Fetch ruangan description only if ruangan is provided
        if ($ruangan) {
            $query->where('tp.RUANGAN', 'LIKE', $ruangan);

            $ruangan = MasterRuanganModel::where('ID', $ruangan)->value('DESKRIPSI');
        }

        $data = $query->get();

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'JUMLAH' => 0,
            'UMUM' => 0,
            'JKN' => 0,
            'INHEALTH' => 0,
            'JKD' => 0,
            'IKS' => 0,
            'LAKILAKI' => 0,
            'PEREMPUAN' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($data as $row) {
            $total['JUMLAH'] += $row->JUMLAH;
            $total['UMUM'] += $row->UMUM;
            $total['JKN'] += $row->JKN;
            $total['INHEALTH'] += $row->INHEALTH;
            $total['JKD'] += $row->JKD;
            $total['IKS'] += $row->IKS;
            $total['LAKILAKI'] += $row->LAKILAKI;
            $total['PEREMPUAN'] += $row->PEREMPUAN;
        }

        return inertia("Laporan/PengunjungRekap/Print", [
            'data' => $data,
            'total' => $total,
            'ruangan' => $ruangan,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}
