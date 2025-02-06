<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterReferensiModel;

class PengunjungCaraBayarController extends Controller
{
    public function index()
    {
        // Tanggal awal tahun
        $tgl_awal = Carbon::now()->startOfYear()->format('Y-m-d 00:00:00');

        // Tanggal hari ini
        $tgl_akhir = Carbon::now()->endOfDay()->format('Y-m-d 23:59:59');

        $query = DB::connection('mysql2')->table('master.pasien as pasien')
            ->select([
                'referensi.ID as IDCARABAYAR',
                'referensi.DESKRIPSI as CARABAYAR',
                DB::raw('COUNT(pendaftaran.NOMOR) AS JUMLAH'),
                DB::raw('SUM(IF(pasien.JENIS_KELAMIN = 1, 1, 0)) AS LAKILAKI'),
                DB::raw('SUM(IF(pasien.JENIS_KELAMIN = 2, 1, 0)) AS PEREMPUAN'),
                DB::raw('SUM(IF(DATE_FORMAT(pasien.TANGGAL, "%d-%m-%Y") = DATE_FORMAT(pendaftaran.TANGGAL, "%d-%m-%Y"), 1, 0)) AS BARU'),
                DB::raw('SUM(IF(DATE_FORMAT(pasien.TANGGAL, "%d-%m-%Y") != DATE_FORMAT(pendaftaran.TANGGAL, "%d-%m-%Y"), 1, 0)) AS LAMA'),
            ])
            ->join('pendaftaran.pendaftaran as pendaftaran', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->join('pendaftaran.tujuan_pasien as tujuanPasien', 'pendaftaran.NOMOR', '=', 'tujuanPasien.NOPEN')
            ->join('pendaftaran.kunjungan as kunjungan', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('pendaftaran.penjamin as penjamin', 'pendaftaran.NOMOR', '=', 'penjamin.NOPEN')
            ->leftJoin('master.referensi as referensi', function ($join) {
                $join->on('penjamin.JENIS', '=', 'referensi.ID')
                    ->where('referensi.JENIS', '=', 10);
            })
            ->leftJoin('master.ikatan_kerja_sama as kerjaSama', 'referensi.ID', '=', 'kerjaSama.ID')
            ->leftJoin('master.kartu_asuransi_pasien as asuransi', function ($join) {
                $join->on('pendaftaran.NORM', '=', 'asuransi.NORM')
                    ->on('referensi.ID', '=', 'asuransi.JENIS');
            })
            ->leftJoin('pendaftaran.surat_rujukan_pasien as rujukan', 'pendaftaran.RUJUKAN', '=', 'rujukan.ID')
            ->leftJoin('master.ppk as ppk', 'rujukan.PPK', '=', 'ppk.ID')
            ->leftJoin('master.ruangan as ruangan', 'tujuanPasien.RUANGAN', '=', 'ruangan.ID')
            ->leftJoin('master.ruangan as ruanganKunjungan', 'kunjungan.RUANGAN', '=', 'ruanganKunjungan.ID')
            ->leftJoin('master.ruangan as ruanganSumber', 'ruanganSumber.ID', '=', 'ruanganKunjungan.ID')
            ->whereIn('pendaftaran.STATUS', [1, 2])
            ->whereBetween('pendaftaran.TANGGAL', [$tgl_awal, $tgl_akhir])
            ->where('tujuanPasien.RUANGAN', 'LIKE', '%')
            ->groupBy('referensi.ID', 'referensi.DESKRIPSI')
            ->get()
            ->toArray();

        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        $caraBayar = MasterReferensiModel::where('JENIS', 10)
            ->where('STATUS', 1)
            ->orderBy('ID')
            ->get();

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'LAKILAKI' => 0,
            'PEREMPUAN' => 0,
            'BARU' => 0,
            'LAMA' => 0,
            'JUMLAH' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($query as $row) {
            $total['LAKILAKI'] += $row->LAKILAKI;
            $total['PEREMPUAN'] += $row->PEREMPUAN;
            $total['BARU'] += $row->BARU;
            $total['LAMA'] += $row->LAMA;
            $total['JUMLAH'] += $row->JUMLAH;
        }

        return inertia('Laporan/PengunjungCaraBayar/Index', [
            'ruangan' => $ruangan,
            'caraBayar' => $caraBayar,
            'total' => $total,
            'dataTable' => $query,
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
        $dariTanggal    = $request->input('dari_tanggal');
        $sampaiTanggal  = $request->input('sampai_tanggal');
        $dariTanggal = Carbon::parse($dariTanggal)->format('Y-m-d H:i:s');
        $sampaiTanggal = Carbon::parse($sampaiTanggal)->endOfDay()->format('Y-m-d H:i:s');

        $query = DB::connection('mysql2')->table('master.pasien as pasien')
            ->select([
                'referensi.ID as IDCARABAYAR',
                'referensi.DESKRIPSI as CARABAYAR',
                DB::raw('COUNT(pendaftaran.NOMOR) AS JUMLAH'),
                DB::raw('SUM(IF(pasien.JENIS_KELAMIN = 1, 1, 0)) AS LAKILAKI'),
                DB::raw('SUM(IF(pasien.JENIS_KELAMIN = 2, 1, 0)) AS PEREMPUAN'),
                DB::raw('SUM(IF(DATE_FORMAT(pasien.TANGGAL, "%d-%m-%Y") = DATE_FORMAT(pendaftaran.TANGGAL, "%d-%m-%Y"), 1, 0)) AS BARU'),
                DB::raw('SUM(IF(DATE_FORMAT(pasien.TANGGAL, "%d-%m-%Y") != DATE_FORMAT(pendaftaran.TANGGAL, "%d-%m-%Y"), 1, 0)) AS LAMA'),
            ])
            ->join('pendaftaran.pendaftaran as pendaftaran', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->join('pendaftaran.tujuan_pasien as tujuanPasien', 'pendaftaran.NOMOR', '=', 'tujuanPasien.NOPEN')
            ->join('pendaftaran.kunjungan as kunjungan', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('pendaftaran.penjamin as penjamin', 'pendaftaran.NOMOR', '=', 'penjamin.NOPEN')
            ->leftJoin('master.referensi as referensi', function ($join) {
                $join->on('penjamin.JENIS', '=', 'referensi.ID')
                    ->where('referensi.JENIS', '=', 10);
            })
            ->leftJoin('master.ikatan_kerja_sama as kerjaSama', 'referensi.ID', '=', 'kerjaSama.ID')
            ->leftJoin('master.kartu_asuransi_pasien as asuransi', function ($join) {
                $join->on('pendaftaran.NORM', '=', 'asuransi.NORM')
                    ->on('referensi.ID', '=', 'asuransi.JENIS');
            })
            ->leftJoin('pendaftaran.surat_rujukan_pasien as rujukan', 'pendaftaran.RUJUKAN', '=', 'rujukan.ID')
            ->leftJoin('master.ppk as ppk', 'rujukan.PPK', '=', 'ppk.ID')
            ->leftJoin('master.ruangan as ruangan', 'tujuanPasien.RUANGAN', '=', 'ruangan.ID')
            ->leftJoin('master.ruangan as ruanganKunjungan', 'kunjungan.RUANGAN', '=', 'ruanganKunjungan.ID')
            ->leftJoin('master.ruangan as ruanganSumber', 'ruanganSumber.ID', '=', 'ruanganKunjungan.ID')
            ->whereIn('pendaftaran.STATUS', [1, 2])
            ->where(function ($query) use ($caraBayar) {
                if ($caraBayar) {
                    $query->where('penjamin.JENIS', '=', $caraBayar);
                }
            })
            ->where(function ($query) use ($ruangan) {
                if ($ruangan) {
                    $query->where('tujuanPasien.RUANGAN', '=', $ruangan);
                }
            })
            ->groupBy('referensi.ID', 'referensi.DESKRIPSI');

        // Filter berdasarkan tanggal
        $data = $query
            ->whereBetween(DB::raw('DATE(pendaftaran.TANGGAL)'), [$dariTanggal, $sampaiTanggal])
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

        return inertia("Laporan/PengunjungCaraBayar/Print", [
            'data' => $data,
            'ruangan' => $ruangan,
            'total' => $total,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}
