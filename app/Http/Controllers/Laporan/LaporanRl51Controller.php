<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaporanRl51Controller extends Controller
{
    public function index(Request $request)
    {
        // Tanggal awal bulan berjalan
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();
        // Tanggal hari ini
        $tgl_akhir = Carbon::now()->toDateString();

        $data = DB::connection('mysql10')->select("
                SELECT 
                    INST.KODE, INST.NAMAINST, INST.KODEPROP, INST.KOTA,
                    a.IDSTATUSPENGUNJUNG, a.JUMLAH,
                    ref.ID, CONCAT('Pengunjung ', ref.DESKRIPSI) AS DESKRIPSI,
                    'Rawat Jalan' AS JENIS_KUNJUNGAN
                FROM master.referensi ref
                LEFT JOIN (
                    SELECT 
                        IF(DATE_FORMAT(p.TANGGAL, '%d-%m-%Y') = DATE_FORMAT(tk.MASUK, '%d-%m-%Y'), 1, 2) AS IDSTATUSPENGUNJUNG,
                        COUNT(pd.NOMOR) AS JUMLAH
                    FROM master.pasien p
                    JOIN pendaftaran.pendaftaran pd ON p.NORM = pd.NORM
                    JOIN pendaftaran.tujuan_pasien tp ON pd.NOMOR = tp.NOPEN
                    LEFT JOIN master.ruangan r ON tp.RUANGAN = r.ID AND r.JENIS = 5
                    JOIN pendaftaran.kunjungan tk ON pd.NOMOR = tk.NOPEN AND tp.RUANGAN = tk.RUANGAN
                    WHERE pd.STATUS IN (1, 2)
                    AND tk.RUANGAN = r.ID 
                    AND tk.MASUK BETWEEN ? AND ?
                    AND r.JENIS_KUNJUNGAN = 1
                    GROUP BY IF(DATE_FORMAT(p.TANGGAL, '%d-%m-%Y') = DATE_FORMAT(tk.MASUK, '%d-%m-%Y'), 1, 2)
                ) a ON a.IDSTATUSPENGUNJUNG = ref.ID
                JOIN (
                    SELECT p.KODE, p.NAMA AS NAMAINST, p.WILAYAH AS KODEPROP, w.DESKRIPSI AS KOTA
                    FROM aplikasi.instansi ai
                    JOIN master.ppk p ON ai.PPK = p.ID
                    JOIN master.wilayah w ON p.WILAYAH = w.ID
                ) INST
                WHERE ref.JENIS = 22
                UNION ALL
                SELECT 
                    INST.KODE, INST.NAMAINST, INST.KODEPROP, INST.KOTA,
                    a.IDSTATUSPENGUNJUNG, a.JUMLAH,
                    ref.ID, CONCAT('Pengunjung ', ref.DESKRIPSI) AS DESKRIPSI,
                    'Rawat Inap' AS JENIS_KUNJUNGAN
                FROM master.referensi ref
                LEFT JOIN (
                    SELECT 
                        IF(DATE_FORMAT(p.TANGGAL, '%d-%m-%Y') = DATE_FORMAT(tk.MASUK, '%d-%m-%Y'), 1, 2) AS IDSTATUSPENGUNJUNG,
                        COUNT(pd.NOMOR) AS JUMLAH
                    FROM master.pasien p
                    JOIN pendaftaran.pendaftaran pd ON p.NORM = pd.NORM
                    JOIN pendaftaran.tujuan_pasien tp ON pd.NOMOR = tp.NOPEN
                    LEFT JOIN master.ruangan r ON tp.RUANGAN = r.ID AND r.JENIS = 5
                    JOIN pendaftaran.kunjungan tk ON pd.NOMOR = tk.NOPEN AND tp.RUANGAN = tk.RUANGAN
                    WHERE pd.STATUS IN (1, 2)
                    AND tk.RUANGAN = r.ID 
                    AND tk.MASUK BETWEEN ? AND ?
                    AND r.JENIS_KUNJUNGAN = 3
                    GROUP BY IF(DATE_FORMAT(p.TANGGAL, '%d-%m-%Y') = DATE_FORMAT(tk.MASUK, '%d-%m-%Y'), 1, 2)
                ) a ON a.IDSTATUSPENGUNJUNG = ref.ID
                JOIN (
                    SELECT p.KODE, p.NAMA AS NAMAINST, p.WILAYAH AS KODEPROP, w.DESKRIPSI AS KOTA
                    FROM aplikasi.instansi ai
                    JOIN master.ppk p ON ai.PPK = p.ID
                    JOIN master.wilayah w ON p.WILAYAH = w.ID
                ) INST
                WHERE ref.JENIS = 22
            ", [$tgl_awal, $tgl_akhir, $tgl_awal, $tgl_akhir]);

        return inertia("Laporan/Rl51/Index", [
            'data' => $data,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
        ]);
    }

    public function print(Request $request)
    {
        $request->validate([
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        // Ambil nilai input
        $tgl_awal = Carbon::parse($request->input('dari_tanggal'))->format('Y-m-d');
        $tgl_akhir = Carbon::parse($request->input('sampai_tanggal'))->endOfDay()->format('Y-m-d');

        $data = DB::connection('mysql10')->select("
                SELECT 
                    INST.KODE, INST.NAMAINST, INST.KODEPROP, INST.KOTA,
                    a.IDSTATUSPENGUNJUNG, a.JUMLAH,
                    ref.ID, CONCAT('Pengunjung ', ref.DESKRIPSI) AS DESKRIPSI,
                    'Rawat Jalan' AS JENIS_KUNJUNGAN
                FROM master.referensi ref
                LEFT JOIN (
                    SELECT 
                        IF(DATE_FORMAT(p.TANGGAL, '%d-%m-%Y') = DATE_FORMAT(tk.MASUK, '%d-%m-%Y'), 1, 2) AS IDSTATUSPENGUNJUNG,
                        COUNT(pd.NOMOR) AS JUMLAH
                    FROM master.pasien p
                    JOIN pendaftaran.pendaftaran pd ON p.NORM = pd.NORM
                    JOIN pendaftaran.tujuan_pasien tp ON pd.NOMOR = tp.NOPEN
                    LEFT JOIN master.ruangan r ON tp.RUANGAN = r.ID AND r.JENIS = 5
                    JOIN pendaftaran.kunjungan tk ON pd.NOMOR = tk.NOPEN AND tp.RUANGAN = tk.RUANGAN
                    WHERE pd.STATUS IN (1, 2)
                    AND tk.RUANGAN = r.ID 
                    AND tk.MASUK BETWEEN ? AND ?
                    AND r.JENIS_KUNJUNGAN = 1
                    GROUP BY IF(DATE_FORMAT(p.TANGGAL, '%d-%m-%Y') = DATE_FORMAT(tk.MASUK, '%d-%m-%Y'), 1, 2)
                ) a ON a.IDSTATUSPENGUNJUNG = ref.ID
                JOIN (
                    SELECT p.KODE, p.NAMA AS NAMAINST, p.WILAYAH AS KODEPROP, w.DESKRIPSI AS KOTA
                    FROM aplikasi.instansi ai
                    JOIN master.ppk p ON ai.PPK = p.ID
                    JOIN master.wilayah w ON p.WILAYAH = w.ID
                ) INST
                WHERE ref.JENIS = 22
                UNION ALL
                SELECT 
                    INST.KODE, INST.NAMAINST, INST.KODEPROP, INST.KOTA,
                    a.IDSTATUSPENGUNJUNG, a.JUMLAH,
                    ref.ID, CONCAT('Pengunjung ', ref.DESKRIPSI) AS DESKRIPSI,
                    'Rawat Inap' AS JENIS_KUNJUNGAN
                FROM master.referensi ref
                LEFT JOIN (
                    SELECT 
                        IF(DATE_FORMAT(p.TANGGAL, '%d-%m-%Y') = DATE_FORMAT(tk.MASUK, '%d-%m-%Y'), 1, 2) AS IDSTATUSPENGUNJUNG,
                        COUNT(pd.NOMOR) AS JUMLAH
                    FROM master.pasien p
                    JOIN pendaftaran.pendaftaran pd ON p.NORM = pd.NORM
                    JOIN pendaftaran.tujuan_pasien tp ON pd.NOMOR = tp.NOPEN
                    LEFT JOIN master.ruangan r ON tp.RUANGAN = r.ID AND r.JENIS = 5
                    JOIN pendaftaran.kunjungan tk ON pd.NOMOR = tk.NOPEN AND tp.RUANGAN = tk.RUANGAN
                    WHERE pd.STATUS IN (1, 2)
                    AND tk.RUANGAN = r.ID 
                    AND tk.MASUK BETWEEN ? AND ?
                    AND r.JENIS_KUNJUNGAN = 3
                    GROUP BY IF(DATE_FORMAT(p.TANGGAL, '%d-%m-%Y') = DATE_FORMAT(tk.MASUK, '%d-%m-%Y'), 1, 2)
                ) a ON a.IDSTATUSPENGUNJUNG = ref.ID
                JOIN (
                    SELECT p.KODE, p.NAMA AS NAMAINST, p.WILAYAH AS KODEPROP, w.DESKRIPSI AS KOTA
                    FROM aplikasi.instansi ai
                    JOIN master.ppk p ON ai.PPK = p.ID
                    JOIN master.wilayah w ON p.WILAYAH = w.ID
                ) INST
                WHERE ref.JENIS = 22
            ", [$tgl_awal, $tgl_akhir, $tgl_awal, $tgl_akhir]);

        return inertia("Laporan/Rl51/Print", [
            'data' => $data,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
        ]);
    }
}