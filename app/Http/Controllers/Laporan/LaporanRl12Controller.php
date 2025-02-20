<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\InformasiIndikatorRsModel;

class LaporanRl12Controller extends Controller
{
    public function index()
    {
        $tahunIni = Carbon::now()->startOfYear()->year;
        $ttidurIni = env('TTIDUR', 246);
        $tglAwal = Carbon::now()->startOfYear()->toDateString();
        $tglAkhir = Carbon::now()->toDateString();

        // Tahun lalu
        $tahunLalu = Carbon::now()->subYear()->startOfYear()->year;
        $ttidurLalu = env('TTIDURLALU', 246);
        $tglAwalLalu = Carbon::now()->subYear()->startOfYear()->toDateString();
        $tglAkhirLalu = Carbon::now()->subYear()->endOfYear()->toDateString();

        // Statistik
        $statistikTahunIni = $this->getStatistikTahun($tahunIni, $ttidurIni) ?? 0;
        $statistikTahunLalu = $this->getStatistikTahun($tahunLalu, $ttidurLalu) ?? 0;

        //jumlah kunjungan
        $jumlahKunjunganTahunIni = $this->getDataJumlahKunjungan($tglAwal, $tglAkhir);
        $jumlahKunjunganTahunLalu = $this->getDataJumlahKunjungan($tglAwalLalu, $tglAkhirLalu);

        // Data awal
        $awalTahunIni = $this->getDataAwalTahun($tglAwal) ?? 0;
        $awalTahunLalu = $this->getDataAwalTahun($tglAwalLalu) ?? 0;

        // Data masuk
        $masukTahunIni = $this->getDataMasukTahun($tahunIni) ?? 0;
        $masukTahunLalu = $this->getDataMasukTahun($tahunLalu) ?? 0;

        // Data keluar
        $keluarTahunIni = $this->getDataKeluarTahun($tahunIni) ?? 0;
        $keluarTahunLalu = $this->getDataKeluarTahun($tahunLalu) ?? 0;

        // Data mati > 48 jam
        $lebih48TahunIni = $this->getDataLebih48Tahun($tahunIni) ?? 0;
        $lebih48TahunLalu = $this->getDataLebih48Tahun($tahunLalu) ?? 0;

        // Data mati < 48 jam
        $kurang48TahunIni = $this->getDataKurang48Tahun($tahunIni) ?? 0;
        $kurang48TahunLalu = $this->getDataKurang48Tahun($tahunLalu) ?? 0;

        // Data awal
        $sisaTahunIni = $this->getDataSisaTahun($tglAkhir) ?? 0;
        $sisaTahunLalu = $this->getDataSisaTahun($tglAkhirLalu) ?? 0;

        // Return data to frontend
        return inertia("Laporan/Rl12/Index", [
            // Tahun ini
            'tahunIni' => $tahunIni,
            'ttidurIni' => $ttidurIni,
            'tgl_awal' => $tglAwal,
            'tgl_akhir' => $tglAkhir,
            'statistikTahunIni' => $statistikTahunIni,
            'jumlahKunjunganTahunIni' => $jumlahKunjunganTahunIni,
            'awalTahunIni' => $awalTahunIni,
            'masukTahunIni' => $masukTahunIni,
            'keluarTahunIni' => $keluarTahunIni,
            'lebih48TahunIni' => $lebih48TahunIni,
            'kurang48TahunIni' => $kurang48TahunIni,
            'sisaTahunIni' => $sisaTahunIni,
            // Tahun lalu
            'tahunLalu' => $tahunLalu,
            'ttidurLalu' => $ttidurLalu,
            'tgl_awal_lalu' => $tglAwalLalu,
            'tgl_akhir_lalu' => $tglAkhirLalu,
            'statistikTahunLalu' => $statistikTahunLalu,
            'jumlahKunjunganTahunLalu' => $jumlahKunjunganTahunLalu,
            'awalTahunLalu' => $awalTahunLalu,
            'masukTahunLalu' => $masukTahunLalu,
            'keluarTahunLalu' => $keluarTahunLalu,
            'lebih48TahunLalu' => $lebih48TahunLalu,
            'kurang48TahunLalu' => $kurang48TahunLalu,
            'sisaTahunLalu' => $sisaTahunLalu,
        ]);
    }

    private function getDataAwalTahun($tgl_awal)
    {

        return InformasiIndikatorRsModel::where('TANGGAL', $tgl_awal)
            ->value('AWAL');
    }

    private function getDataMasukTahun($tahun)
    {
        return InformasiIndikatorRsModel::whereYear('TANGGAL', $tahun)
            ->selectRaw('(SUM(MASUK) + SUM(DIPINDAHKAN)) as MASUK')
            ->first()->MASUK;
    }

    private function getDataKeluarTahun($tahun)
    {

        return InformasiIndikatorRsModel::whereYear('TANGGAL', $tahun)
            ->selectRaw('SUM(JMLKLR) as JMLKLR')
            ->first()->JMLKLR;
    }

    private function getDataLebih48Tahun($tahun)
    {

        return InformasiIndikatorRsModel::whereYear('TANGGAL', $tahun)
            ->selectRaw('SUM(LEBIH48JAM) as LEBIH48JAM')
            ->first()->LEBIH48JAM;
    }

    private function getDataKurang48Tahun($tahun)
    {

        return InformasiIndikatorRsModel::whereYear('TANGGAL', $tahun)
            ->selectRaw('SUM(KURANG48JAM) as KURANG48JAM')
            ->first()->KURANG48JAM;
    }

    private function getDataSisaTahun($tgl_akhir)
    {

        return InformasiIndikatorRsModel::where('TANGGAL', $tgl_akhir)
            ->value('SISA');
    }

    private function getDataJumlahKunjungan($tgl_awal, $tgl_akhir)
    {
        $query = "
            SELECT 
                COUNT(pd.NOMOR) / (DATEDIFF(?, ?) + 1) AS JMLKUNJUNGAN
            FROM 
                pendaftaran.pendaftaran pd
                JOIN pendaftaran.kunjungan tk ON pd.NOMOR = tk.NOPEN
                JOIN master.ruangan jkr ON tk.RUANGAN = jkr.ID
            WHERE 
                jkr.JENIS = 5
                AND jkr.JENIS_KUNJUNGAN IN (1, 2)
                AND tk.MASUK BETWEEN ? AND ?
                AND pd.STATUS IN (1, 2)
                AND tk.STATUS IN (1, 2)
        ";

        // Eksekusi query dengan parameter tanggal awal dan akhir
        $results = DB::connection('mysql5')->select($query, [$tgl_akhir, $tgl_awal, $tgl_awal, $tgl_akhir]);

        // Return hasil pertama (jika ada), atau null
        return isset($results[0]->JMLKUNJUNGAN)
            ? round($results[0]->JMLKUNJUNGAN, 2)
            : null;
    }

    public function getStatistikTahun($tahun, $ttidur)
    {
        // Query SQL menggunakan parameter binding
        $sql = "
            SELECT 
                YEAR(t.TANGGAL) AS TAHUN, 
                ROUND((SUM(HP) * 100) / (? * SUM(JMLHARI)), 2) AS BOR,
                ROUND(SUM(LD) / SUM(JMLKLR), 2) AS AVLOS,
                (SUM(JMLKLR) / ?) AS BTO,
                ROUND(((? * SUM(JMLHARI)) - SUM(HP)) / SUM(JMLKLR), 2) AS TOI,
                ((SUM(LEBIH48JAM) * 1000) / SUM(JMLKLR)) AS NDR,
                (((SUM(KURANG48JAM) + SUM(LEBIH48JAM)) * 1000) / SUM(JMLKLR)) AS GDR
            FROM informasi.indikator_rs r
            JOIN master.tanggal t ON r.TANGGAL = t.TANGGAL
            WHERE YEAR(t.TANGGAL) = ? AND HP > 0
            GROUP BY TAHUN
        ";

        // Eksekusi query dengan parameter binding
        $data = DB::connection('mysql12')->select($sql, [$ttidur, $ttidur, $ttidur, $tahun]);

        if (count($data) > 0) {
            $result = $data[0]; // Ambil hasil baris pertama

            // Pastikan data hasil query valid sebelum digunakan
            return [
                'BOR' => $result->BOR ? number_format(round($result->BOR, 2), 2, '.', '') : '0.00',
                'AVLOS' => $result->AVLOS ? number_format(round($result->AVLOS, 2), 2, '.', '') : '0.00',
                'BTO' => $result->BTO ? number_format(round($result->BTO, 2), 2, '.', '') : '0.00',
                'TOI' => $result->TOI ? number_format(round($result->TOI, 2), 2, '.', '') : '0.00',
                'NDR' => $result->NDR ? number_format(round($result->NDR, 2), 2, '.', '') : '0.00',
                'GDR' => $result->GDR ? number_format(round($result->GDR, 2), 2, '.', '') : '0.00',
            ];
        }

        // Kembalikan array kosong jika tidak ada data
        return [];
    }

    public function getStatistikTriwulan($tahun, $ttidur)
    {
        // Query SQL untuk menghitung statistik triwulan berdasarkan tahun dan tempat tidur
        $sql = sprintf("
            SELECT 
                YEAR(t.TANGGAL) AS TAHUN,
                CASE 
                    WHEN MONTH(t.TANGGAL) BETWEEN 1 AND 3 THEN 'Triwulan I'
                    WHEN MONTH(t.TANGGAL) BETWEEN 4 AND 6 THEN 'Triwulan II'
                    WHEN MONTH(t.TANGGAL) BETWEEN 7 AND 9 THEN 'Triwulan III'
                    WHEN MONTH(t.TANGGAL) BETWEEN 10 AND 12 THEN 'Triwulan IV'
                    ELSE ''
                END AS TRIWULAN,
                ROUND((SUM(HP) * 100) / (%d * SUM(JMLHARI)), 2) AS BOR,
                ROUND(SUM(LD) / SUM(JMLKLR), 2) AS AVLOS,
                (SUM(JMLKLR) / %d) AS BTO,
                ROUND(((%d * SUM(JMLHARI)) - SUM(HP)) / SUM(JMLKLR), 2) AS TOI,
                ((SUM(LEBIH48JAM) * 1000) / SUM(JMLKLR)) AS NDR,
                (((SUM(KURANG48JAM) + SUM(LEBIH48JAM)) * 1000) / SUM(JMLKLR)) AS GDR
            FROM informasi.indikator_rs r
            JOIN master.tanggal t ON r.TANGGAL = t.TANGGAL
            WHERE YEAR(t.TANGGAL) = %d AND HP > 0
            GROUP BY YEAR(t.TANGGAL), TRIWULAN
        ", $ttidur, $ttidur, $ttidur, $tahun);

        // Eksekusi query dan ambil hasil
        $data = DB::connection('mysql12')->select($sql);

        // Proses data untuk membentuk struktur yang sesuai
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'TAHUN' => $row->TAHUN,
                'TRIWULAN' => $row->TRIWULAN,
                'BOR' => $row->BOR ? number_format(round($row->BOR, 2), 2, '.', '') : '0.00',
                'AVLOS' => $row->AVLOS ? number_format(round($row->AVLOS, 2), 2, '.', '') : '0.00',
                'BTO' => $row->BTO ? number_format(round($row->BTO, 2), 2, '.', '') : '0.00',
                'TOI' => $row->TOI ? number_format(round($row->TOI, 2), 2, '.', '') : '0.00',
                'NDR' => $row->NDR ? number_format(round($row->NDR, 2), 2, '.', '') : '0.00',
                'GDR' => $row->GDR ? number_format(round($row->GDR, 2), 2, '.', '') : '0.00',
            ];
        }

        // Kembalikan hasil
        return $result;
    }

    public function getStatistikSemester($tahun, $ttidur)
    {
        // Query SQL untuk menghitung statistik per semester
        $sql = sprintf("
        SELECT 
            IF(MONTH(t.TANGGAL)>=1 AND MONTH(t.TANGGAL)<=12,YEAR(t.TANGGAL),'') AS TAHUN, 
            IF(MONTH(t.TANGGAL)>=1 AND MONTH(t.TANGGAL)<=6,'Semester I',
            IF(MONTH(t.TANGGAL)>=7 AND MONTH(t.TANGGAL)<=12,'Semester II','')) AS SEMESTER,
            ROUND((SUM(HP) * 100) / (%d * SUM(JMLHARI)), 2) AS BOR,
            ROUND(SUM(LD) / SUM(JMLKLR), 2) AS AVLOS,
            (SUM(JMLKLR) / %d) AS BTO,
            ROUND(((%d * SUM(JMLHARI)) - SUM(HP)) / SUM(JMLKLR), 2) AS TOI,
            ((SUM(LEBIH48JAM) * 1000) / SUM(JMLKLR)) AS NDR,
            (((SUM(KURANG48JAM) + SUM(LEBIH48JAM)) * 1000) / SUM(JMLKLR)) AS GDR
        FROM informasi.indikator_rs r
        JOIN master.tanggal t ON r.TANGGAL = t.TANGGAL
        WHERE YEAR(t.TANGGAL) = %d AND HP > 0
        GROUP BY TAHUN, SEMESTER
        ", $ttidur, $ttidur, $ttidur, $tahun);

        // Eksekusi query dan ambil hasil
        $data = DB::connection('mysql12')->select($sql);

        // Proses data untuk membentuk struktur yang sesuai
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'TAHUN' => $row->TAHUN,
                'SEMESTER' => $row->SEMESTER,
                'BOR' => $row->BOR ? number_format(round($row->BOR, 2), 2, '.', '') : '0.00',
                'AVLOS' => $row->AVLOS ? number_format(round($row->AVLOS, 2), 2, '.', '') : '0.00',
                'BTO' => $row->BTO ? number_format(round($row->BTO, 2), 2, '.', '') : '0.00',
                'TOI' => $row->TOI ? number_format(round($row->TOI, 2), 2, '.', '') : '0.00',
                'NDR' => $row->NDR ? number_format(round($row->NDR, 2), 2, '.', '') : '0.00',
                'GDR' => $row->GDR ? number_format(round($row->GDR, 2), 2, '.', '') : '0.00',
            ];
        }

        // Kembalikan hasil
        return $result;
    }

    public function getStatistikBulanan($ttidur, $tahun)
    {
        // Start building the query using the query builder
        $query = DB::connection('mysql12')->table('informasi.indikator_rs as r')
            ->select([
                DB::raw('YEAR(t.TANGGAL) AS TAHUN'),
                DB::raw('MONTH(t.TANGGAL) AS BULAN'),
                DB::raw('ROUND((SUM(HP) * 100) / (' . $ttidur . ' * SUM(JMLHARI)), 2) AS BOR'),
                DB::raw('ROUND(SUM(LD) / SUM(JMLKLR), 2) AS AVLOS'),
                DB::raw('(SUM(JMLKLR) / ' . $ttidur . ') AS BTO'),
                DB::raw('ROUND(((' . $ttidur . ' * SUM(JMLHARI)) - SUM(HP)) / SUM(JMLKLR), 2) AS TOI'),
                DB::raw('((SUM(LEBIH48JAM) * 1000) / SUM(JMLKLR)) AS NDR'),
                DB::raw('(((SUM(KURANG48JAM) + SUM(LEBIH48JAM)) * 1000) / SUM(JMLKLR)) AS GDR')
            ])
            ->join('master.tanggal as t', 'r.TANGGAL', '=', 't.TANGGAL')
            ->where('HP', '>', 0)
            ->whereYear('t.TANGGAL', $tahun) // Tambahkan filter untuk tahun
            ->groupBy(DB::raw('YEAR(t.TANGGAL), MONTH(t.TANGGAL)'))
            ->orderBy(DB::raw('YEAR(t.TANGGAL)'), 'desc') // Order by year descending
            ->orderBy(DB::raw('MONTH(t.TANGGAL)'), 'asc'); // Order by month descending

        // Apply pagination
        $data = $query->get();

        // Process data to format the result
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'TAHUN' => $row->TAHUN,
                'BULAN' => $row->BULAN,
                'BOR' => isset($row->BOR) ? number_format($row->BOR, 2, '.', '') : '0.00',
                'AVLOS' => isset($row->AVLOS) ? number_format($row->AVLOS, 2, '.', '') : '0.00',
                'BTO' => isset($row->BTO) ? number_format($row->BTO, 2, '.', '') : '0.00',
                'TOI' => isset($row->TOI) ? number_format($row->TOI, 2, '.', '') : '0.00',
                'NDR' => isset($row->NDR) ? number_format($row->NDR, 2, '.', '') : '0.00',
                'GDR' => isset($row->GDR) ? number_format($row->GDR, 2, '.', '') : '0.00',
            ];
        }

        return $result; // Pastikan mengembalikan $result, bukan $data
    }

    public function print(Request $request)
    {
        // Validasi input
        $request->validate([
            'tempatTidur'  => 'required|integer',
            'tahun' => 'required|integer',
        ]);

        $ttidurIni  = $request->input('tempatTidur');
        $tahunIni = $request->input('tahun');
        $tglAwal = Carbon::create($tahunIni, 1, 1)->toDateString();
        if ($tahunIni == Carbon::now()->year) {
            $tglAkhir = Carbon::now()->toDateString(); // Jika tahun berjalan, ambil tanggal sekarang
        } else {
            $tglAkhir = Carbon::create($tahunIni, 12, 31)->toDateString(); // Jika bukan, ambil 31 Desember
        }

        $awalTahun = $this->getDataAwalTahun($tglAwal) ?? 0;
        $masukTahun = $this->getDataMasukTahun($tahunIni) ?? 0;
        $keluarTahun = $this->getDataKeluarTahun($tahunIni) ?? 0;
        $lebih48Tahun = $this->getDataLebih48Tahun($tahunIni) ?? 0;
        $kurang48Tahun = $this->getDataKurang48Tahun($tahunIni) ?? 0;
        $sisaTahun = $this->getDataSisaTahun($tglAkhir) ?? 0;
        $statistikTriwulan = $this->getStatistikTriwulan($tahunIni, $ttidurIni) ?? 0;
        $statistikSemester = $this->getStatistikSemester($tahunIni, $ttidurIni) ?? 0;
        $statistikBulanan = $this->getStatistikBulanan($ttidurIni, $tahunIni,) ?? 0;
        $statistikTahun = $this->getStatistikTahun($tahunIni, $ttidurIni) ?? 0;
        // Return data to frontend
        return inertia("Laporan/Rl12/Print", [
            'tahun' => $tahunIni,
            'ttidur' => $ttidurIni,
            'awalTahun' => $awalTahun,
            'masukTahun' => $masukTahun,
            'keluarTahun' => $keluarTahun,
            'lebih48Tahun' => $lebih48Tahun,
            'kurang48Tahun' => $kurang48Tahun,
            'sisaTahun' => $sisaTahun,
            'statistikBulanan' => ['data' => $statistikBulanan],
            'statistikTriwulan' => ['data' => $statistikTriwulan],
            'statistikSemester' => ['data' => $statistikSemester],
            'statistikTahun' => $statistikTahun,
        ]);
    }
}