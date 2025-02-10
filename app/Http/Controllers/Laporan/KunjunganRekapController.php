<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class KunjunganRekapController extends Controller
{
    public function index()
    {
        // Tanggal awal tahun
        $tgl_awal = Carbon::now()->startOfYear()->format('Y-m-d 00:00:00');
        // Tanggal hari ini
        $tgl_akhir = Carbon::now()->endOfDay()->format('Y-m-d 23:59:59');

        // Subquery untuk agregasi data pasien dan status pengunjung
        $query = DB::connection('mysql2')
            ->table('master.pasien as pasien')
            ->select([
                DB::raw('IF(DATE_FORMAT(pasien.TANGGAL, "%d-%m-%Y") = DATE_FORMAT(pendaftaran.TANGGAL, "%d-%m-%Y"), 1, 2) AS IDSTATUSPENGUNJUNG'),
                DB::raw('COUNT(kunjungan.NOMOR) AS JUMLAH'),
                DB::raw('SUM(IF(referensi.ID = 1, 1, 0)) AS UMUM'),
                DB::raw('SUM(IF(referensi.ID = 2, 1, 0)) AS JKN'),
                DB::raw('SUM(IF(referensi.ID = 3, 1, 0)) AS INHEALTH'),
                DB::raw('SUM(IF(referensi.ID = 4, 1, 0)) AS JKD'),
                DB::raw('SUM(IF(kerjaSama.ID IS NOT NULL, 1, 0)) AS IKS'),
                DB::raw('SUM(IF(pasien.JENIS_KELAMIN = 1, 1, 0)) AS LAKILAKI'),
                DB::raw('SUM(IF(pasien.JENIS_KELAMIN = 2, 1, 0)) AS PEREMPUAN'),
            ])
            ->join('pendaftaran.pendaftaran as pendaftaran', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('pendaftaran.penjamin as penjamin', 'pendaftaran.NOMOR', '=', 'penjamin.NOPEN')
            ->leftJoin('master.referensi as referensi', function ($join) {
                $join->on('penjamin.JENIS', '=', 'referensi.ID')
                    ->where('referensi.JENIS', '=', 10);
            })
            ->leftJoin('master.ikatan_kerja_sama as kerjaSama', function ($join) {
                $join->on('referensi.ID', '=', 'kerjaSama.ID')
                    ->where('referensi.JENIS', '=', 10);
            })
            ->leftJoin('master.kartu_asuransi_pasien as asuransi', function ($join) {
                $join->on('pendaftaran.NORM', '=', 'asuransi.NORM')
                    ->on('referensi.ID', '=', 'asuransi.JENIS')
                    ->where('referensi.JENIS', '=', 10);
            })
            ->leftJoin('pendaftaran.surat_rujukan_pasien as rujukan', function ($join) {
                $join->on('pendaftaran.RUJUKAN', '=', 'rujukan.ID')
                    ->where('rujukan.STATUS', '!=', 0);
            })
            ->leftJoin('master.ppk as ppk', 'rujukan.PPK', '=', 'ppk.ID')
            ->join('pendaftaran.tujuan_pasien as tujuanPasien', 'pendaftaran.NOMOR', '=', 'tujuanPasien.NOPEN')
            ->leftJoin('master.ruangan as ruangan', function ($join) {
                $join->on('tujuanPasien.RUANGAN', '=', 'ruangan.ID')
                    ->where('ruangan.JENIS', '=', 5);
            })
            ->join('pendaftaran.kunjungan as kunjungan', function ($join) {
                $join->on('pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
                    ->on('tujuanPasien.RUANGAN', '=', 'kunjungan.RUANGAN');
            })
            ->join('master.ruangan as ruanganKunjungan', function ($join) {
                $join->on('kunjungan.RUANGAN', '=', 'ruanganKunjungan.ID')
                    ->where('ruanganKunjungan.JENIS', '=', 5);
            })
            ->whereIn('kunjungan.STATUS', [1, 2])
            ->whereNull('kunjungan.REF')
            ->whereBetween('kunjungan.MASUK', [$tgl_awal, $tgl_akhir])
            ->where('tujuanPasien.RUANGAN', 'LIKE', '%')
            ->groupBy(DB::raw('IF(DATE_FORMAT(pasien.TANGGAL, "%d-%m-%Y") = DATE_FORMAT(pendaftaran.TANGGAL, "%d-%m-%Y"), 1, 2)'));

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
        return inertia('Laporan/KunjunganRekap/Index', [
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
            ->table('master.pasien as pasien')
            ->select([
                DB::raw('IF(DATE_FORMAT(pasien.TANGGAL, "%d-%m-%Y") = DATE_FORMAT(pendaftaran.TANGGAL, "%d-%m-%Y"), 1, 2) AS IDSTATUSPENGUNJUNG'),
                DB::raw('COUNT(kunjungan.NOMOR) AS JUMLAH'),
                DB::raw('SUM(IF(referensi.ID = 1, 1, 0)) AS UMUM'),
                DB::raw('SUM(IF(referensi.ID = 2, 1, 0)) AS JKN'),
                DB::raw('SUM(IF(referensi.ID = 3, 1, 0)) AS INHEALTH'),
                DB::raw('SUM(IF(referensi.ID = 4, 1, 0)) AS JKD'),
                DB::raw('SUM(IF(kerjaSama.ID IS NOT NULL, 1, 0)) AS IKS'),
                DB::raw('SUM(IF(pasien.JENIS_KELAMIN = 1, 1, 0)) AS LAKILAKI'),
                DB::raw('SUM(IF(pasien.JENIS_KELAMIN = 2, 1, 0)) AS PEREMPUAN'),
            ])
            ->join('pendaftaran.pendaftaran as pendaftaran', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('pendaftaran.penjamin as penjamin', 'pendaftaran.NOMOR', '=', 'penjamin.NOPEN')
            ->leftJoin('master.referensi as referensi', function ($join) {
                $join->on('penjamin.JENIS', '=', 'referensi.ID')
                    ->where('referensi.JENIS', '=', 10);
            })
            ->leftJoin('master.ikatan_kerja_sama as kerjaSama', function ($join) {
                $join->on('referensi.ID', '=', 'kerjaSama.ID')
                    ->where('referensi.JENIS', '=', 10);
            })
            ->leftJoin('master.kartu_asuransi_pasien as asuransi', function ($join) {
                $join->on('pendaftaran.NORM', '=', 'asuransi.NORM')
                    ->on('referensi.ID', '=', 'asuransi.JENIS')
                    ->where('referensi.JENIS', '=', 10);
            })
            ->leftJoin('pendaftaran.surat_rujukan_pasien as rujukan', function ($join) {
                $join->on('pendaftaran.RUJUKAN', '=', 'rujukan.ID')
                    ->where('rujukan.STATUS', '!=', 0);
            })
            ->leftJoin('master.ppk as ppk', 'rujukan.PPK', '=', 'ppk.ID')
            ->join('pendaftaran.tujuan_pasien as tujuanPasien', 'pendaftaran.NOMOR', '=', 'tujuanPasien.NOPEN')
            ->leftJoin('master.ruangan as ruangan', function ($join) {
                $join->on('tujuanPasien.RUANGAN', '=', 'ruangan.ID')
                    ->where('ruangan.JENIS', '=', 5);
            })
            ->join('pendaftaran.kunjungan as kunjungan', function ($join) {
                $join->on('pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
                    ->on('tujuanPasien.RUANGAN', '=', 'kunjungan.RUANGAN');
            })
            ->join('master.ruangan as ruanganKunjungan', function ($join) {
                $join->on('kunjungan.RUANGAN', '=', 'ruanganKunjungan.ID')
                    ->where('ruanganKunjungan.JENIS', '=', 5);
            })
            ->whereIn('kunjungan.STATUS', [1, 2])
            ->whereNull('kunjungan.REF')
            ->groupBy(DB::raw('IF(DATE_FORMAT(pasien.TANGGAL, "%d-%m-%Y") = DATE_FORMAT(pendaftaran.TANGGAL, "%d-%m-%Y"), 1, 2)'));

        // Filter berdasarkan tanggal
        $data = $query
            ->whereBetween(DB::raw('DATE(kunjungan.MASUK)'), [$dariTanggal, $sampaiTanggal])
            ->get()
            ->toArray();

        // Fetch ruangan description only if ruangan is provided
        if ($ruangan) {
            $query->where('tujuanPasien.RUANGAN', 'LIKE', $ruangan);

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

        return inertia("Laporan/KunjunganRekap/Print", [
            'data' => $data,
            'total' => $total,
            'ruangan' => $ruangan,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}