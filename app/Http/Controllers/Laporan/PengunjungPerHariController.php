<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PengunjungPerHariController extends Controller
{
    public function index()
    {
        // Tanggal awal tahun
        $tgl_awal = Carbon::now()->startOfYear()->format('Y-m-d 00:00:00');

        // Tanggal hari ini
        $tgl_akhir = Carbon::now()->endOfDay()->format('Y-m-d 23:59:59');

        $query = DB::connection('mysql2')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select([
                DB::raw('DATE(kunjungan.MASUK) as TANGGAL'),
                DB::raw('COUNT(pendaftaran.NOMOR) as JUMLAH'),
                DB::raw("SUM(IF(DATE_FORMAT(pasien.TANGGAL,'%d-%m-%Y') = DATE_FORMAT(kunjungan.MASUK,'%d-%m-%Y'),1,0)) as BARU"),
                DB::raw("SUM(IF(DATE_FORMAT(pasien.TANGGAL,'%d-%m-%Y') != DATE_FORMAT(kunjungan.MASUK,'%d-%m-%Y'),1,0)) as LAMA"),
                DB::raw("SUM(IF(referensi.ID=1,1,0)) as UMUM"),
                DB::raw("SUM(IF(referensi.ID=2,1,0)) as JKN"),
                DB::raw("SUM(IF(referensi.ID=3,0,0)) as INHEALTH"),
                DB::raw("SUM(IF(referensi.ID=4,0,0)) as JKD"),
                DB::raw("SUM(IF(kerjaSama.ID IS NOT NULL,1,0)) as IKS"),
                DB::raw("SUM(IF(pasien.JENIS_KELAMIN=1,1,0)) as LAKILAKI"),
                DB::raw("SUM(IF(pasien.JENIS_KELAMIN=2,1,0)) as PEREMPUAN"),
                DB::raw("MAX(kunjungan.MASUK) as MASUK_TERAKHIR")
            ])
            ->join('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->join('pendaftaran.tujuan_pasien as tujuanPasien', 'pendaftaran.NOMOR', '=', 'tujuanPasien.NOPEN')
            ->join('pendaftaran.kunjungan as kunjungan', function ($join) {
                $join->on('pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
                    ->on('tujuanPasien.RUANGAN', '=', 'kunjungan.RUANGAN');
            })
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
            ->join('master.ruangan as ruangan', 'kunjungan.RUANGAN', '=', 'ruangan.ID')
            ->whereIn('pendaftaran.STATUS', [1, 2])
            ->whereNull('kunjungan.REF')
            ->where('ruangan.JENIS', '=', 5)
            ->whereBetween(DB::raw('DATE(kunjungan.MASUK)'), [$tgl_awal, $tgl_akhir])
            ->whereIn('kunjungan.STATUS', [1, 2])
            ->groupBy(DB::raw('DATE(kunjungan.MASUK)'))
            ->orderByDesc(DB::raw('DATE(kunjungan.MASUK)'));

        $data = $query->paginate(5);
        $dataArray = $data->toArray();

        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        return inertia('Laporan/PengunjungPerHari/Index', [
            'ruangan' => $ruangan,
            'tglAwal' => $tgl_awal,
            'tglAkhir' => $tgl_akhir,
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
        ]);
    }

    public function print(Request $request)
    {
        $request->validate([
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
            'ruangan'  => 'nullable|integer',
        ]);

        // Ambil nilai input
        $ruangan  = $request->input('ruangan');
        $dariTanggal    = $request->input('dari_tanggal');
        $sampaiTanggal  = $request->input('sampai_tanggal');
        $dariTanggal = Carbon::parse($dariTanggal)->format('Y-m-d H:i:s');
        $sampaiTanggal = Carbon::parse($sampaiTanggal)->endOfDay()->format('Y-m-d H:i:s');

        $query = DB::connection('mysql2')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select([
                DB::raw('DATE(kunjungan.MASUK) as TANGGAL'),
                DB::raw('COUNT(pendaftaran.NOMOR) as JUMLAH'),
                DB::raw("SUM(IF(DATE_FORMAT(pasien.TANGGAL,'%d-%m-%Y') = DATE_FORMAT(kunjungan.MASUK,'%d-%m-%Y'),1,0)) as BARU"),
                DB::raw("SUM(IF(DATE_FORMAT(pasien.TANGGAL,'%d-%m-%Y') != DATE_FORMAT(kunjungan.MASUK,'%d-%m-%Y'),1,0)) as LAMA"),
                DB::raw("SUM(IF(referensi.ID=1,1,0)) as UMUM"),
                DB::raw("SUM(IF(referensi.ID=2,1,0)) as JKN"),
                DB::raw("SUM(IF(referensi.ID=3,0,0)) as INHEALTH"),
                DB::raw("SUM(IF(referensi.ID=4,0,0)) as JKD"),
                DB::raw("SUM(IF(kerjaSama.ID IS NOT NULL,1,0)) as IKS"),
                DB::raw("SUM(IF(pasien.JENIS_KELAMIN=1,1,0)) as LAKILAKI"),
                DB::raw("SUM(IF(pasien.JENIS_KELAMIN=2,1,0)) as PEREMPUAN"),
                DB::raw("MAX(kunjungan.MASUK) as MASUK_TERAKHIR")
            ])
            ->join('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->join('pendaftaran.tujuan_pasien as tujuanPasien', 'pendaftaran.NOMOR', '=', 'tujuanPasien.NOPEN')
            ->join('pendaftaran.kunjungan as kunjungan', function ($join) {
                $join->on('pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
                    ->on('tujuanPasien.RUANGAN', '=', 'kunjungan.RUANGAN');
            })
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
            ->join('master.ruangan as ruangan', 'kunjungan.RUANGAN', '=', 'ruangan.ID')
            ->whereIn('pendaftaran.STATUS', [1, 2])
            ->whereNull('kunjungan.REF')
            ->where('ruangan.JENIS', '=', 5)
            ->whereIn('kunjungan.STATUS', [1, 2])
            ->groupBy(DB::raw('DATE(kunjungan.MASUK)'))
            ->orderByDesc(DB::raw('DATE(kunjungan.MASUK)'));

        if ($ruangan) {
            $query->where('tujuanPasien.RUANGAN', 'LIKE', $ruangan);

            $ruangan = MasterRuanganModel::where('ID', $ruangan)
                ->value('DESKRIPSI');
        }

        // Filter berdasarkan tanggal
        $data = $query
            ->whereBetween(DB::raw('DATE(kunjungan.MASUK)'), [$dariTanggal, $sampaiTanggal])
            ->get();

        return inertia("Laporan/PengunjungPerHari/Print", [
            'data' => $data,
            'ruangan' => $ruangan,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}