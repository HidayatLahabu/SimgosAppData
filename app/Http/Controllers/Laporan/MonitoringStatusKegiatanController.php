<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MonitoringStatusKegiatanController extends Controller
{
    public function index()
    {
        // Tanggal awal tahun
        $tgl_awal = Carbon::now()->startOfYear()->format('Y-m-d 00:00:00');

        // Tanggal hari ini
        $tgl_akhir = Carbon::now()->endOfDay()->format('Y-m-d 23:59:59');

        $search = request('search') ? strtolower(request('search')) : null;

        $query = DB::connection('mysql2')
            ->table(DB::raw('(SELECT 
                p.NOMOR AS NOPEN,
                p.NORM,
                p.TANGGAL,
                k.NOMOR AS KUNJUNGAN,
                k.MASUK,
                psn.NAMA,
                orders.NOMOR,
                IF(tpa.NOPEN IS NOT NULL, "Pendaftaran", ru2.DESKRIPSI) AS RUASAL,
                IF(tpa.NOPEN IS NOT NULL, tpa.RUANGAN, orders.TUJUAN) AS TUJUAN,
                IF(tpa.NOPEN IS NOT NULL, ruA.DESKRIPSI, ru.DESKRIPSI) AS RUTUJUAN,
                IF(tpa.NOPEN IS NOT NULL, "Kunjungan Awal", orders.JENIS) AS JENIS,
                IF(tpa.NOPEN IS NOT NULL, tpa.RUANGAN, orders.TUJUAN) AS TUJUANLYN,
                IF(tpa.NOPEN IS NOT NULL, p.TANGGAL, orders.TANGGAL) AS TGLLYN,
                IF(tpa.NOPEN IS NOT NULL, tpa.STATUS, orders.STATUS) AS STATUSLYN,
                IF(
                    orders.JENIS = "Kunjungan Layanan",
                    IF(orders.STATUS = 1, "Belum Final Layanan", IF(orders.STATUS = 2, "Sudah Final Layanan", "-")),
                    IF(
                        orders.STATUS = 1,
                        CONCAT("Belum Terima ", orders.JENIS),
                        IF(
                            orders.STATUS = 2,
                            CONCAT("Sudah Terima ", orders.JENIS),
                            IF(tpa.NOPEN IS NOT NULL, "Belum Terima Kunjungan", "-")
                        )
                    )
                ) AS STATUSLAYANAN,
                IF(0 = 0, "Semua Ruangan", IF(tpa.NOPEN IS NOT NULL, ruA.DESKRIPSI, ru.DESKRIPSI)) AS RUANGANTUJUAN,
                IF(0 = 0, "Semua Ruangan", IF(tpa.NOPEN IS NOT NULL, "Pendaftaran", ru2.DESKRIPSI)) AS RUANGANASAL
                FROM pendaftaran.pendaftaran p
                LEFT JOIN pendaftaran.tujuan_pasien tpa ON tpa.NOPEN = p.NOMOR AND tpa.STATUS = 1
                LEFT JOIN master.ruangan ruA ON ruA.ID = tpa.RUANGAN
                LEFT JOIN pendaftaran.kunjungan k ON k.NOPEN = p.NOMOR
                LEFT JOIN (
                    SELECT res.NOMOR, res.KUNJUNGAN, res.TUJUAN, "Order Resep" JENIS, res.TANGGAL, res.STATUS FROM layanan.order_resep res WHERE res.STATUS != 0
                    UNION ALL
                    SELECT lab.NOMOR, lab.KUNJUNGAN, lab.TUJUAN, "Order Laboratorium" JENIS, lab.TANGGAL, lab.STATUS FROM layanan.order_lab lab WHERE lab.STATUS != 0
                    UNION ALL
                    SELECT rad.NOMOR, rad.KUNJUNGAN, rad.TUJUAN, "Order Radiologi" JENIS, rad.TANGGAL, rad.STATUS FROM layanan.order_rad rad WHERE rad.STATUS != 0
                    UNION ALL
                    SELECT kon.NOMOR, kon.KUNJUNGAN, kon.TUJUAN, "Order Konsul" JENIS, kon.TANGGAL, kon.STATUS FROM pendaftaran.konsul kon WHERE kon.STATUS != 0
                    UNION ALL
                    SELECT mut.NOMOR, mut.KUNJUNGAN, mut.TUJUAN, "Order Radiologi" JENIS, mut.TANGGAL, mut.STATUS FROM pendaftaran.mutasi mut WHERE mut.STATUS != 0
                    UNION ALL
                    SELECT kun.NOMOR, kun.NOMOR AS KUNJUNGAN, kun.RUANGAN AS TUJUAN, "Kunjungan Layanan" JENIS, kun.MASUK AS TANGGAL, kun.STATUS FROM pendaftaran.kunjungan kun WHERE kun.STATUS != 0
                ) orders ON orders.KUNJUNGAN = k.NOMOR
                    LEFT JOIN master.ruangan ru ON ru.ID = orders.TUJUAN
                    LEFT JOIN master.ruangan ru2 ON ru2.ID = k.RUANGAN
                    LEFT JOIN master.pasien psn ON psn.NORM = p.NORM
                    WHERE p.STATUS != 0 AND p.NOMOR IS NOT NULL
                ) AS datax'))
            ->leftJoin('pendaftaran.penjamin as pj', function ($join) {
                $join->on('datax.NOPEN', '=', 'pj.NOPEN')
                    ->whereNotNull('pj.JENIS');
            })
            ->whereBetween('datax.TANGGAL', [$tgl_awal, $tgl_akhir])
            ->where('datax.TUJUANLYN', 'like', '%')
            ->where('datax.STATUSLYN', 'like', '%');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(datax.NAMA) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(datax.NORM) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(datax.RUASAL) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(datax.RUTUJUAN) LIKE ?', ["%$search%"]);
            });
        }

        $data = $query->orderByDesc('datax.TANGGAL')->paginate(5)->appends(request()->query());
        $dataArray = $data->toArray();

        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        return inertia('Laporan/MonitoringKegiatan/Index', [
            'ruangan' => $ruangan,
            'tglAwal' => $tgl_awal,
            'tglAkhir' => $tgl_akhir,
            'dataTable' => [
                'data' => $dataArray['data'],
                'links' => $dataArray['links'],
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
        $dariTanggal = Carbon::parse($request->input('dari_tanggal'))->toDateTimeString();
        $sampaiTanggal = Carbon::parse($request->input('sampai_tanggal'))->endOfDay()->toDateTimeString();

        $query = DB::connection('mysql2')
            ->table(DB::raw('(SELECT 
                p.NOMOR AS NOPEN,
                p.NORM,
                p.TANGGAL,
                k.NOMOR AS KUNJUNGAN,
                k.MASUK,
                psn.NAMA,
                orders.NOMOR,
                IF(tpa.NOPEN IS NOT NULL, "Pendaftaran", ru2.DESKRIPSI) AS RUASAL,
                IF(tpa.NOPEN IS NOT NULL, tpa.RUANGAN, orders.TUJUAN) AS TUJUAN,
                IF(tpa.NOPEN IS NOT NULL, ruA.DESKRIPSI, ru.DESKRIPSI) AS RUTUJUAN,
                IF(tpa.NOPEN IS NOT NULL, "Kunjungan Awal", orders.JENIS) AS JENIS,
                IF(tpa.NOPEN IS NOT NULL, tpa.RUANGAN, orders.TUJUAN) AS TUJUANLYN,
                IF(tpa.NOPEN IS NOT NULL, p.TANGGAL, orders.TANGGAL) AS TGLLYN,
                IF(tpa.NOPEN IS NOT NULL, tpa.STATUS, orders.STATUS) AS STATUSLYN,
                IF(
                    orders.JENIS = "Kunjungan Layanan",
                    IF(orders.STATUS = 1, "Belum Final Layanan", IF(orders.STATUS = 2, "Sudah Final Layanan", "-")),
                    IF(
                        orders.STATUS = 1,
                        CONCAT("Belum Terima ", orders.JENIS),
                        IF(
                            orders.STATUS = 2,
                            CONCAT("Sudah Terima ", orders.JENIS),
                            IF(tpa.NOPEN IS NOT NULL, "Belum Terima Kunjungan", "-")
                        )
                    )
                ) AS STATUSLAYANAN,
                IF(0 = 0, "Semua Ruangan", IF(tpa.NOPEN IS NOT NULL, ruA.DESKRIPSI, ru.DESKRIPSI)) AS RUANGANTUJUAN,
                IF(0 = 0, "Semua Ruangan", IF(tpa.NOPEN IS NOT NULL, "Pendaftaran", ru2.DESKRIPSI)) AS RUANGANASAL
                FROM pendaftaran.pendaftaran p
                LEFT JOIN pendaftaran.tujuan_pasien tpa ON tpa.NOPEN = p.NOMOR AND tpa.STATUS = 1
                LEFT JOIN master.ruangan ruA ON ruA.ID = tpa.RUANGAN
                LEFT JOIN pendaftaran.kunjungan k ON k.NOPEN = p.NOMOR
                LEFT JOIN (
                    SELECT res.NOMOR, res.KUNJUNGAN, res.TUJUAN, "Order Resep" JENIS, res.TANGGAL, res.STATUS FROM layanan.order_resep res WHERE res.STATUS != 0
                    UNION ALL
                    SELECT lab.NOMOR, lab.KUNJUNGAN, lab.TUJUAN, "Order Laboratorium" JENIS, lab.TANGGAL, lab.STATUS FROM layanan.order_lab lab WHERE lab.STATUS != 0
                    UNION ALL
                    SELECT rad.NOMOR, rad.KUNJUNGAN, rad.TUJUAN, "Order Radiologi" JENIS, rad.TANGGAL, rad.STATUS FROM layanan.order_rad rad WHERE rad.STATUS != 0
                    UNION ALL
                    SELECT kon.NOMOR, kon.KUNJUNGAN, kon.TUJUAN, "Order Konsul" JENIS, kon.TANGGAL, kon.STATUS FROM pendaftaran.konsul kon WHERE kon.STATUS != 0
                    UNION ALL
                    SELECT mut.NOMOR, mut.KUNJUNGAN, mut.TUJUAN, "Order Radiologi" JENIS, mut.TANGGAL, mut.STATUS FROM pendaftaran.mutasi mut WHERE mut.STATUS != 0
                    UNION ALL
                    SELECT kun.NOMOR, kun.NOMOR AS KUNJUNGAN, kun.RUANGAN AS TUJUAN, "Kunjungan Layanan" JENIS, kun.MASUK AS TANGGAL, kun.STATUS FROM pendaftaran.kunjungan kun WHERE kun.STATUS != 0
                ) orders ON orders.KUNJUNGAN = k.NOMOR
                    LEFT JOIN master.ruangan ru ON ru.ID = orders.TUJUAN
                    LEFT JOIN master.ruangan ru2 ON ru2.ID = k.RUANGAN
                    LEFT JOIN master.pasien psn ON psn.NORM = p.NORM
                    WHERE p.STATUS != 0 AND p.NOMOR IS NOT NULL
                ) AS datax'))
            ->leftJoin('pendaftaran.penjamin as pj', function ($join) {
                $join->on('datax.NOPEN', '=', 'pj.NOPEN')
                    ->whereNotNull('pj.JENIS');
            })
            ->where('datax.STATUSLYN', 'like', '%');

        if ($ruangan) {
            $query->where('datax.TUJUAN', '=', $ruangan);
        }

        // Filter berdasarkan tanggal
        $data = $query
            ->whereBetween('datax.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->get();

        return inertia("Laporan/MonitoringKegiatan/Print", [
            'data' => $data,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}
