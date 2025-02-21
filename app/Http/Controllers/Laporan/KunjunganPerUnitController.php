<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterReferensiModel;

class KunjunganPerUnitController extends Controller
{
    public function index()
    {
        // Tanggal awal tahun
        $tgl_awal = Carbon::now()->startOfYear()->format('Y-m-d 00:00:00');

        // Tanggal hari ini
        $tgl_akhir = Carbon::now()->endOfDay()->format('Y-m-d 23:59:59');

        $query = DB::connection('mysql2')->table('master.pasien as pasien')
            ->select([
                'ruangan.DESKRIPSI as SUBUNIT',
                DB::raw('COUNT(kunjungan.NOMOR) AS JUMLAH'),
                DB::raw('SUM(IF(referensi.ID=1,1,0)) AS UMUM'),
                DB::raw('SUM(IF(referensi.ID=2,1,0)) AS JKN'),
                DB::raw('SUM(IF(referensi.ID=3,0,0)) AS INHEALTH'),
                DB::raw('SUM(IF(referensi.ID=4,0,0)) AS JKD'),
                DB::raw('SUM(IF(kerjaSama.ID IS NOT NULL,1,0)) AS IKS'),
                DB::raw('SUM(IF(pasien.JENIS_KELAMIN=1,1,0)) AS LAKILAKI'),
                DB::raw('SUM(IF(pasien.JENIS_KELAMIN=2,1,0)) AS PEREMPUAN'),
                DB::raw('SUM(IF(kunjungan.BARU=1,1,0)) AS BARU'),
                DB::raw('SUM(IF(kunjungan.BARU=0,1,0)) AS LAMA')
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
            ->join('pendaftaran.kunjungan as kunjungan', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->join('master.ruangan as kunjunganRuangan', function ($join) {
                $join->on('kunjungan.RUANGAN', '=', 'kunjunganRuangan.ID')
                    ->where('kunjunganRuangan.JENIS', '=', 5);
            })
            ->leftJoin('master.ruangan as ruangan', function ($join) {
                $join->on('ruangan.ID', '=', 'kunjunganRuangan.ID')
                    ->where('ruangan.JENIS', '=', 5);
            })
            ->whereIn('kunjungan.STATUS', [1, 2])
            ->whereIn('kunjunganRuangan.JENIS_KUNJUNGAN', [1, 2, 3])
            ->whereBetween('kunjungan.MASUK', [$tgl_awal, $tgl_akhir])
            ->where('kunjungan.RUANGAN', 'LIKE', '%')
            ->groupBy('kunjungan.RUANGAN')
            ->orderBy('kunjungan.RUANGAN')
            ->get()
            ->toArray();

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'LAKILAKI' => 0,
            'PEREMPUAN' => 0,
            'BARU' => 0,
            'LAMA' => 0,
            'UMUM' => 0,
            'JKN' => 0,
            'INHEALTH' => 0,
            'JKD' => 0,
            'IKS' => 0,
            'JUMLAH' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($query as $row) {
            $total['LAKILAKI'] += $row->LAKILAKI;
            $total['PEREMPUAN'] += $row->PEREMPUAN;
            $total['BARU'] += $row->BARU;
            $total['LAMA'] += $row->LAMA;
            $total['UMUM'] += $row->UMUM;
            $total['JKN'] += $row->JKN;
            $total['INHEALTH'] += $row->INHEALTH;
            $total['JKD'] += $row->JKD;
            $total['IKS'] += $row->IKS;
            $total['JUMLAH'] += $row->JUMLAH;
        }

        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        $caraBayar = MasterReferensiModel::where('JENIS', 10)
            ->where('STATUS', 1)
            ->orderBy('ID')
            ->get();

        return inertia('Laporan/KunjunganPerUnit/Index', [
            'ruangan' => $ruangan,
            'caraBayar' => $caraBayar,
            'dataTable' => $query,
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
            'caraBayar'      => 'nullable|integer',
        ]);

        // Ambil nilai input
        $ruangan  = $request->input('ruangan');
        $caraBayar = $request->input('caraBayar');
        $dariTanggal = Carbon::parse($request->input('dari_tanggal'))->toDateTimeString();
        $sampaiTanggal = Carbon::parse($request->input('sampai_tanggal'))->endOfDay()->toDateTimeString();

        $query = DB::connection('mysql2')->table('master.pasien as pasien')
            ->select([
                'ruangan.DESKRIPSI as SUBUNIT',
                DB::raw('COUNT(kunjungan.NOMOR) AS JUMLAH'),
                DB::raw('SUM(IF(referensi.ID=1,1,0)) AS UMUM'),
                DB::raw('SUM(IF(referensi.ID=2,1,0)) AS JKN'),
                DB::raw('SUM(IF(referensi.ID=3,0,0)) AS INHEALTH'),
                DB::raw('SUM(IF(referensi.ID=4,0,0)) AS JKD'),
                DB::raw('SUM(IF(kerjaSama.ID IS NOT NULL,1,0)) AS IKS'),
                DB::raw('SUM(IF(pasien.JENIS_KELAMIN=1,1,0)) AS LAKILAKI'),
                DB::raw('SUM(IF(pasien.JENIS_KELAMIN=2,1,0)) AS PEREMPUAN'),
                DB::raw('SUM(IF(kunjungan.BARU=1,1,0)) AS BARU'),
                DB::raw('SUM(IF(kunjungan.BARU=0,1,0)) AS LAMA')
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
            ->join('pendaftaran.kunjungan as kunjungan', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->join('master.ruangan as kunjunganRuangan', function ($join) {
                $join->on('kunjungan.RUANGAN', '=', 'kunjunganRuangan.ID')
                    ->where('kunjunganRuangan.JENIS', '=', 5);
            })
            ->leftJoin('master.ruangan as ruangan', function ($join) {
                $join->on('ruangan.ID', '=', 'kunjunganRuangan.ID')
                    ->where('ruangan.JENIS', '=', 5);
            })
            ->whereIn('kunjungan.STATUS', [1, 2])
            ->whereIn('kunjunganRuangan.JENIS_KUNJUNGAN', [1, 2, 3])
            ->groupBy('kunjungan.RUANGAN')
            ->orderBy('kunjungan.RUANGAN');

        if ($ruangan) {
            $query->where('kunjungan.RUANGAN', 'LIKE', $ruangan);
        }

        if ($caraBayar) {
            $query->where('penjamin.JENIS', '=', $caraBayar);
        }

        // Filter berdasarkan tanggal
        $data = $query
            ->whereBetween(DB::raw('DATE(kunjungan.MASUK)'), [$dariTanggal, $sampaiTanggal])
            ->get()
            ->toArray();

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'LAKILAKI' => 0,
            'PEREMPUAN' => 0,
            'BARU' => 0,
            'LAMA' => 0,
            'JUMLAH' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($data as $row) {
            $total['LAKILAKI'] += $row->LAKILAKI;
            $total['PEREMPUAN'] += $row->PEREMPUAN;
            $total['BARU'] += $row->BARU;
            $total['LAMA'] += $row->LAMA;
            $total['JUMLAH'] += $row->JUMLAH;
        }

        // Fetch ruangan description only if ruangan is provided
        if ($ruangan) {
            $ruangan = MasterRuanganModel::where('ID', $ruangan)->value('DESKRIPSI');
        }

        return inertia("Laporan/KunjunganPerUnit/Print", [
            'data' => $data,
            'ruangan' => $ruangan,
            'total' => $total,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}