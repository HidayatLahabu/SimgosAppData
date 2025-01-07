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

        $statistikBulananIni = $this->getStatistikBulanan($tahunIni, $ttidurIni) ?? 0;
        $statistikBulananLalu = $this->getStatistikBulanan($tahunLalu, $ttidurLalu) ?? 0;

        $statistikTriwulanIni = $this->getStatistikTriwulan($tahunIni, $ttidurIni) ?? 0;
        $statistikTriwulanLalu = $this->getStatistikTriwulan($tahunLalu, $ttidurLalu) ?? 0;

        $statistikSemesterIni = $this->getStatistikSemester($tahunIni, $ttidurIni) ?? 0;
        $statistikSemesterLalu = $this->getStatistikSemester($tahunLalu, $ttidurLalu) ?? 0;

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
            'statistikBulananIni' => $statistikBulananIni,
            'statistikTriwulanIni' => $statistikTriwulanIni,
            'statistikSemesterIni' => $statistikSemesterIni,
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
            'statistikBulananLalu' => $statistikBulananLalu,
            'statistikTriwulanLalu' => $statistikTriwulanLalu,
            'statistikSemesterLalu' => $statistikSemesterLalu,
        ]);
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

    public function getStatistikBulanan($tahun, $ttidur)
    {
        // Query SQL untuk menghitung statistik bulanan
        $sql = sprintf("
            SELECT 
                YEAR(t.TANGGAL) AS TAHUN,
                MONTH(t.TANGGAL) AS BULAN,
                ROUND((SUM(HP) * 100) / (%d * SUM(JMLHARI)), 2) AS BOR,
                ROUND(SUM(LD) / SUM(JMLKLR), 2) AS AVLOS,
                (SUM(JMLKLR) / %d) AS BTO,
                ROUND(((%d * SUM(JMLHARI)) - SUM(HP)) / SUM(JMLKLR), 2) AS TOI,
                ((SUM(LEBIH48JAM) * 1000) / SUM(JMLKLR)) AS NDR,
                (((SUM(KURANG48JAM) + SUM(LEBIH48JAM)) * 1000) / SUM(JMLKLR)) AS GDR
            FROM informasi.indikator_rs r
            JOIN master.tanggal t ON r.TANGGAL = t.TANGGAL
            WHERE YEAR(t.TANGGAL) = %d AND HP > 0
            GROUP BY TAHUN, BULAN
        ", $ttidur, $ttidur, $ttidur, $tahun);

        // Eksekusi query dan ambil hasil
        $data = DB::connection('mysql12')->select($sql);

        // Proses data untuk membentuk struktur yang sesuai
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'TAHUN' => $row->TAHUN,
                'BULAN' => $row->BULAN,
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

    public function getStatistikTriwulan($tahun, $ttidur)
    {
        // Query SQL untuk menghitung statistik bulanan
        $sql = sprintf("
            SELECT 
                IF(MONTH(t.TANGGAL)>=1 AND MONTH(t.TANGGAL)<=12,YEAR(t.TANGGAL),'') TAHUN, 
                IF(MONTH(t.TANGGAL)>=1 AND MONTH(t.TANGGAL)<=3,'TRIWULAN I',
				IF(MONTH(t.TANGGAL)>=4 AND MONTH(t.TANGGAL)<=6,'TRIWULAN Ii',
				IF(MONTH(t.TANGGAL)>=7 AND MONTH(t.TANGGAL)<=9,'TRIWULAN III',
				IF(MONTH(t.TANGGAL)>=10 AND MONTH(t.TANGGAL)<=12,'TRIWULAN IV','')))) TRIWULAN,
                ROUND((SUM(HP) * 100) / (%d * SUM(JMLHARI)), 2) AS BOR,
                ROUND(SUM(LD) / SUM(JMLKLR), 2) AS AVLOS,
                (SUM(JMLKLR) / %d) AS BTO,
                ROUND(((%d * SUM(JMLHARI)) - SUM(HP)) / SUM(JMLKLR), 2) AS TOI,
                ((SUM(LEBIH48JAM) * 1000) / SUM(JMLKLR)) AS NDR,
                (((SUM(KURANG48JAM) + SUM(LEBIH48JAM)) * 1000) / SUM(JMLKLR)) AS GDR
            FROM informasi.indikator_rs r
            JOIN master.tanggal t ON r.TANGGAL = t.TANGGAL
            WHERE YEAR(t.TANGGAL) = %d AND HP > 0
            GROUP BY TAHUN, TRIWULAN
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
            IF(MONTH(t.TANGGAL)>=1 AND MONTH(t.TANGGAL)<=6,'SEMESTER I',
            IF(MONTH(t.TANGGAL)>=7 AND MONTH(t.TANGGAL)<=12,'SEMESTER II','')) AS SEMESTER,
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
}