<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\LayananResepModel;
use App\Models\BpjsKunjunganModel;
use Illuminate\Support\Facades\DB;
use App\Models\BpjsRujukanMasukModel;
use App\Models\InformasiPenunjangModel;
use App\Models\InformasiStatistikKunjunganModel;
use App\Models\LayananRadiologiModel;
use App\Models\PendaftaranKonsulModel;
use App\Models\PendaftaranMutasiModel;
use App\Models\LayananLaboratoriumModel;
use App\Models\LayananPulangModel;
use App\Models\PendaftaranKunjunganModel;
use App\Models\PendaftaranPendaftaranModel;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with data.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $pendaftaran = $this->getPendaftaran();
        $kunjungan = $this->getKunjungan();
        $konsul = $this->getKonsul();
        $mutasi = $this->getMutasi();
        $kunjunganBpjs = $this->getKunjunganBpjs();
        $laboratorium = $this->getOrderLaboratorium();
        $radiologi = $this->getOrderRadiologi();
        $resep = $this->getOrderResep();
        $pulang = $this->getPasienPulang();
        $pendaftaranBulanan = $this->getMonthlyPendaftaran();
        $kunjunganBulanan = $this->getMonthlyKunjungan();
        $konsulBulanan = $this->getMonthlyKonsul();
        $mutasiBulanan = $this->getMonthlyMutasi();
        $statistikKunjungan = $this->getStatistikKunjungan();
        $statistikTahun = $this->getStatistikTahun();
        $statistikTahunLalu = $this->getStatistikTahunLalu();
        $statistikBulan = $this->getStatistikBulan();
        $statistikBulanLalu = $this->getStatistikBulanLalu();
        $rawatJalanBulanan = $this->getMonthlyRawatJalan();
        $rawatDaruratBulanan = $this->getMonthlyRawatDarurat();
        $rawatInapBulanan = $this->getMonthlyRawatInap();
        $laboratoriumBulanan = $this->getMonthlyLaboratorium();
        $radiologiBulanan = $this->getMonthlyRadiologi();

        // Pass the data to the Inertia view
        return Inertia::render('Dashboard', [
            'pendaftaran' => $pendaftaran,
            'kunjungan' => $kunjungan,
            'konsul' => $konsul,
            'mutasi' => $mutasi,
            'kunjunganBpjs' => $kunjunganBpjs,
            'laboratorium' => $laboratorium,
            'radiologi' => $radiologi,
            'resep' => $resep,
            'pulang' => $pulang,
            'pendaftaranBulanan' => $pendaftaranBulanan,
            'kunjunganBulanan' => $kunjunganBulanan,
            'konsulBulanan' => $konsulBulanan,
            'mutasiBulanan' => $mutasiBulanan,
            'statistikKunjungan' => $statistikKunjungan,
            'statistikTahun' => $statistikTahun,
            'statistikTahunLalu' => $statistikTahunLalu,
            'statistikBulan' => $statistikBulan,
            'statistikBulanLalu' => $statistikBulanLalu,
            'rawatJalanBulanan' => $rawatJalanBulanan,
            'rawatDaruratBulanan' => $rawatDaruratBulanan,
            'rawatInapBulanan' => $rawatInapBulanan,
            'laboratoriumBulanan' => $laboratoriumBulanan,
            'radiologiBulanan' => $radiologiBulanan,
        ]);
    }

    protected function getPendaftaran()
    {
        return PendaftaranPendaftaranModel::where('STATUS', [1, 2])
            ->whereBetween('TANGGAL', [
                now()->startOfDay(),
                now()->endOfDay()
            ])
            ->count();
    }

    protected function getKunjungan()
    {
        return PendaftaranKunjunganModel::whereIn('STATUS', [1, 2])
            ->whereBetween('MASUK', [
                now()->startOfDay(),  // Mulai dari pukul 00:00:00 hari ini
                now()->endOfDay()     // Sampai dengan pukul 23:59:59 hari ini
            ])
            ->count();
    }

    protected function getKonsul()
    {
        return PendaftaranKonsulModel::whereIn('STATUS', [1, 2])
            ->whereBetween('TANGGAL', [
                now()->startOfDay(),  // Mulai dari pukul 00:00:00 hari ini
                now()->endOfDay()     // Sampai dengan pukul 23:59:59 hari ini
            ])
            ->count();
    }

    protected function getMutasi()
    {
        return PendaftaranMutasiModel::whereIn('STATUS', [1, 2])
            ->whereBetween('TANGGAL', [
                now()->startOfDay(),  // Mulai dari pukul 00:00:00 hari ini
                now()->endOfDay()     // Sampai dengan pukul 23:59:59 hari ini
            ])
            ->count();
    }

    protected function getKunjunganBpjs()
    {
        return BpjsKunjunganModel::whereBetween('tglSEP', [
            now()->startOfDay(),  // Mulai dari pukul 00:00:00 hari ini
            now()->endOfDay()     // Sampai dengan pukul 23:59:59 hari ini
        ])
            ->count();
    }

    protected function getOrderLaboratorium()
    {
        return LayananLaboratoriumModel::whereBetween('TANGGAL', [
            now()->startOfDay(),  // Mulai dari pukul 00:00:00 hari ini
            now()->endOfDay()     // Sampai dengan pukul 23:59:59 hari ini
        ])
            ->count();
    }

    protected function getOrderRadiologi()
    {
        return LayananRadiologiModel::whereBetween('TANGGAL', [
            now()->startOfDay(),  // Mulai dari pukul 00:00:00 hari ini
            now()->endOfDay()     // Sampai dengan pukul 23:59:59 hari ini
        ])
            ->count();
    }

    protected function getOrderResep()
    {
        return LayananResepModel::whereBetween('TANGGAL', [
            now()->startOfDay(),  // Mulai dari pukul 00:00:00 hari ini
            now()->endOfDay()     // Sampai dengan pukul 23:59:59 hari ini
        ])
            ->count();
    }

    protected function getPasienPulang()
    {
        return LayananPulangModel::where('STATUS', 1)
            ->whereBetween('TANGGAL', [
                now()->startOfDay(),  // Mulai dari pukul 00:00:00 hari ini
                now()->endOfDay()     // Sampai dengan pukul 23:59:59 hari ini
            ])
            ->count();
    }

    protected function getMonthlyPendaftaran()
    {
        return PendaftaranPendaftaranModel::selectRaw("
            CASE MONTH(TANGGAL)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END AS BULAN,
            COUNT(*) AS JUMLAH
        ")
            ->where('STATUS', [1, 2])
            ->whereYear('TANGGAL', now()->year)
            ->where('TANGGAL', '>', '0000-00-00')
            ->groupByRaw("
            CASE MONTH(TANGGAL)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END
        ")
            ->orderByRaw("FIELD(BULAN, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
            ->get();
    }

    protected function getMonthlyKunjungan()
    {
        return PendaftaranKunjunganModel::selectRaw("
            CASE MONTH(MASUK)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END AS BULAN,
            COUNT(*) AS JUMLAH
        ")
            ->whereIn('STATUS', [1, 2])
            ->whereYear('MASUK', now()->year)
            ->where('MASUK', '>', '0000-00-00')
            ->groupByRaw("
            CASE MONTH(MASUK)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END
        ")
            ->orderByRaw("FIELD(BULAN, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
            ->get();
    }

    protected function getMonthlyKonsul()
    {
        return PendaftaranKonsulModel::selectRaw("
            CASE MONTH(TANGGAL)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END AS BULAN,
            COUNT(*) AS JUMLAH
        ")
            ->whereIn('STATUS', [1, 2]) // Hanya pendaftaran aktif
            ->whereYear('TANGGAL', now()->year) // Filter untuk tahun berjalan
            ->where('TANGGAL', '>', '0000-00-00') // Menghilangkan nilai tidak valid
            ->groupByRaw("
            CASE MONTH(TANGGAL)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END
        ")
            ->orderByRaw("FIELD(BULAN, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
            ->get();
    }

    protected function getMonthlyMutasi()
    {
        return PendaftaranMutasiModel::selectRaw("
            CASE MONTH(TANGGAL)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END AS BULAN,
            COUNT(*) AS JUMLAH
        ")
            ->whereIn('STATUS', [1, 2]) // Hanya pendaftaran aktif
            ->whereYear('TANGGAL', now()->year) // Filter untuk tahun berjalan
            ->where('TANGGAL', '>', '0000-00-00') // Menghilangkan nilai tidak valid
            ->groupByRaw("
            CASE MONTH(TANGGAL)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END
        ")
            ->orderByRaw("FIELD(BULAN, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
            ->get();
    }

    public function getStatistikTahun()
    {
        // Definisikan tanggal awal dan akhir
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();
        $tgl_akhir = Carbon::now()->toDateString();
        $ttidur = env('TTIDUR', 246); // Ambil nilai TTIDUR dari .env atau 246 sebagai default

        // Query SQL untuk menghitung statistik dengan penggunaan sprintf untuk variabel
        $sql = sprintf("
            SELECT 
                ROUND((SUM(HP) * 100) / (%d * SUM(JMLHARI)), 2) AS BOR,
                ROUND(SUM(LD) / SUM(JMLKLR), 2) AS AVLOS,
                (SUM(JMLKLR) / %d) AS BTO,
                ROUND(((%d * SUM(JMLHARI)) - SUM(HP)) / SUM(JMLKLR), 2) AS TOI,
                ((SUM(LEBIH48JAM) * 1000) / SUM(JMLKLR)) AS NDR,
                (((SUM(KURANG48JAM) + SUM(LEBIH48JAM)) * 1000) / SUM(JMLKLR)) AS GDR
            FROM (
                SELECT 
                    SUM(irs.AWAL) AS AWAL, 0 AS MASUK, 0 AS PINDAHAN, 0 AS KURANG48JAM, 
                    0 AS LEBIH48JAM, 0 AS DIPINDAHKAN, 0 AS JMLKLR, 0 AS LD, 
                    0 AS HP, 0 AS TTIDUR, 0 AS JMLHARI, 0 AS PASIENDIRAWAT,
                    MAX(LASTUPDATED) AS LASTUPDATED
                FROM informasi.indikator_rs irs
                WHERE irs.TANGGAL = '%s'
                GROUP BY irs.TANGGAL
                UNION ALL
                SELECT 
                    0 AS AWAL, SUM(irs.MASUK) AS MASUK, SUM(irs.PINDAHAN) AS PINDAHAN, 
                    SUM(irs.KURANG48JAM) AS KURANG48JAM, SUM(irs.LEBIH48JAM) AS LEBIH48JAM, 
                    SUM(irs.DIPINDAHKAN) AS DIPINDAHKAN, SUM(irs.JMLKLR) AS JMLKLR, 
                    SUM(irs.LD) AS LD, SUM(irs.HP) AS HP, 
                    0 AS TTIDUR, SUM(irs.JMLHARI) AS JMLHARI, 0 AS PASIENDIRAWAT,
                    MAX(LASTUPDATED) AS LASTUPDATED
                FROM informasi.indikator_rs irs
                WHERE irs.TANGGAL BETWEEN '%s' AND '%s'
                GROUP BY irs.TANGGAL
                UNION ALL
                SELECT 
                    0 AS AWAL, 0 AS MASUK, 0 AS PINDAHAN, 0 AS KURANG48JAM, 
                    0 AS LEBIH48JAM, 0 AS DIPINDAHKAN, 0 AS JMLKLR, 0 AS LD, 
                    0 AS HP, %d AS TTIDUR, 0 AS JMLHARI, SUM(irs.SISA) AS PASIENDIRAWAT,
                    MAX(LASTUPDATED) AS LASTUPDATED
                FROM informasi.indikator_rs irs
                WHERE irs.TANGGAL = '%s'
                GROUP BY irs.TANGGAL
            ) AS b;
        ", $ttidur, $ttidur, $ttidur, $tgl_awal, $tgl_awal, $tgl_akhir, $ttidur, $tgl_akhir);

        // Eksekusi query dan ambil hasil
        $data = DB::connection('mysql12')->select($sql);

        // Proses data untuk membentuk struktur yang sesuai
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

    public function getStatistikTahunLalu()
    {
        // Definisikan tanggal awal dan akhir tahun lalu
        $tgl_awal = Carbon::now()->subYear()->startOfYear()->toDateString();
        $tgl_akhir = Carbon::now()->subYear()->endOfYear()->toDateString();
        $ttidur = env('TTIDUR', 246); // Ambil nilai TTIDUR dari .env atau 246 sebagai default

        // Query SQL untuk menghitung statistik dengan penggunaan sprintf untuk variabel
        $sql = sprintf("
            SELECT 
                ROUND((SUM(HP) * 100) / (%d * SUM(JMLHARI)), 2) AS BOR,
                ROUND(SUM(LD) / SUM(JMLKLR), 2) AS AVLOS,
                (SUM(JMLKLR) / %d) AS BTO,
                ROUND(((%d * SUM(JMLHARI)) - SUM(HP)) / SUM(JMLKLR), 2) AS TOI,
                ((SUM(LEBIH48JAM) * 1000) / SUM(JMLKLR)) AS NDR,
                (((SUM(KURANG48JAM) + SUM(LEBIH48JAM)) * 1000) / SUM(JMLKLR)) AS GDR
            FROM (
                SELECT 
                    SUM(irs.AWAL) AS AWAL, 0 AS MASUK, 0 AS PINDAHAN, 0 AS KURANG48JAM, 
                    0 AS LEBIH48JAM, 0 AS DIPINDAHKAN, 0 AS JMLKLR, 0 AS LD, 
                    0 AS HP, 0 AS TTIDUR, 0 AS JMLHARI, 0 AS PASIENDIRAWAT,
                    MAX(LASTUPDATED) AS LASTUPDATED
                FROM informasi.indikator_rs irs
                WHERE irs.TANGGAL = '%s'
                GROUP BY irs.TANGGAL
                UNION ALL
                SELECT 
                    0 AS AWAL, SUM(irs.MASUK) AS MASUK, SUM(irs.PINDAHAN) AS PINDAHAN, 
                    SUM(irs.KURANG48JAM) AS KURANG48JAM, SUM(irs.LEBIH48JAM) AS LEBIH48JAM, 
                    SUM(irs.DIPINDAHKAN) AS DIPINDAHKAN, SUM(irs.JMLKLR) AS JMLKLR, 
                    SUM(irs.LD) AS LD, SUM(irs.HP) AS HP, 
                    0 AS TTIDUR, SUM(irs.JMLHARI) AS JMLHARI, 0 AS PASIENDIRAWAT,
                    MAX(LASTUPDATED) AS LASTUPDATED
                FROM informasi.indikator_rs irs
                WHERE irs.TANGGAL BETWEEN '%s' AND '%s'
                GROUP BY irs.TANGGAL
                UNION ALL
                SELECT 
                    0 AS AWAL, 0 AS MASUK, 0 AS PINDAHAN, 0 AS KURANG48JAM, 
                    0 AS LEBIH48JAM, 0 AS DIPINDAHKAN, 0 AS JMLKLR, 0 AS LD, 
                    0 AS HP, %d AS TTIDUR, 0 AS JMLHARI, SUM(irs.SISA) AS PASIENDIRAWAT,
                    MAX(LASTUPDATED) AS LASTUPDATED
                FROM informasi.indikator_rs irs
                WHERE irs.TANGGAL = '%s'
                GROUP BY irs.TANGGAL
            ) AS b;
        ", $ttidur, $ttidur, $ttidur, $tgl_awal, $tgl_awal, $tgl_akhir, $ttidur, $tgl_akhir);

        // Eksekusi query dan ambil hasil
        $data = DB::connection('mysql12')->select($sql);

        // Proses data untuk membentuk struktur yang sesuai
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

    public function getStatistikBulan()
    {
        // Definisikan tanggal awal dan akhir
        $tgl_awal = Carbon::now()->startOfMonth()->toDateString();
        $tgl_akhir = Carbon::now()->endOfMonth()->toDateString();
        $ttidur = env('TTIDUR', 246); // Ambil nilai TTIDUR dari .env atau 246 sebagai default

        // Query SQL untuk menghitung statistik dengan penggunaan sprintf untuk variabel
        $sql = sprintf("
        SELECT 
            ROUND((SUM(HP) * 100) / (%d * SUM(JMLHARI)), 2) AS BOR,
            ROUND(SUM(LD) / SUM(JMLKLR), 2) AS AVLOS,
            (SUM(JMLKLR) / %d) AS BTO,
            ROUND(((%d * SUM(JMLHARI)) - SUM(HP)) / SUM(JMLKLR), 2) AS TOI,
            ((SUM(LEBIH48JAM) * 1000) / SUM(JMLKLR)) AS NDR,
            (((SUM(KURANG48JAM) + SUM(LEBIH48JAM)) * 1000) / SUM(JMLKLR)) AS GDR
        FROM (
            SELECT 
                SUM(irs.AWAL) AS AWAL, 0 AS MASUK, 0 AS PINDAHAN, 0 AS KURANG48JAM, 
                0 AS LEBIH48JAM, 0 AS DIPINDAHKAN, 0 AS JMLKLR, 0 AS LD, 
                0 AS HP, 0 AS TTIDUR, 0 AS JMLHARI, 0 AS PASIENDIRAWAT,
                MAX(LASTUPDATED) AS LASTUPDATED
            FROM informasi.indikator_rs irs
            WHERE irs.TANGGAL = '%s'
            GROUP BY irs.TANGGAL
            UNION ALL
            SELECT 
                0 AS AWAL, SUM(irs.MASUK) AS MASUK, SUM(irs.PINDAHAN) AS PINDAHAN, 
                SUM(irs.KURANG48JAM) AS KURANG48JAM, SUM(irs.LEBIH48JAM) AS LEBIH48JAM, 
                SUM(irs.DIPINDAHKAN) AS DIPINDAHKAN, SUM(irs.JMLKLR) AS JMLKLR, 
                SUM(irs.LD) AS LD, SUM(irs.HP) AS HP, 
                0 AS TTIDUR, SUM(irs.JMLHARI) AS JMLHARI, 0 AS PASIENDIRAWAT,
                MAX(LASTUPDATED) AS LASTUPDATED
            FROM informasi.indikator_rs irs
            WHERE irs.TANGGAL BETWEEN '%s' AND '%s'
            GROUP BY irs.TANGGAL
            UNION ALL
            SELECT 
                0 AS AWAL, 0 AS MASUK, 0 AS PINDAHAN, 0 AS KURANG48JAM, 
                0 AS LEBIH48JAM, 0 AS DIPINDAHKAN, 0 AS JMLKLR, 0 AS LD, 
                0 AS HP, %d AS TTIDUR, 0 AS JMLHARI, SUM(irs.SISA) AS PASIENDIRAWAT,
                MAX(LASTUPDATED) AS LASTUPDATED
            FROM informasi.indikator_rs irs
            WHERE irs.TANGGAL = '%s'
            GROUP BY irs.TANGGAL
        ) AS b;
        ", $ttidur, $ttidur, $ttidur, $tgl_awal, $tgl_awal, $tgl_akhir, $ttidur, $tgl_akhir);

        // Eksekusi query dan ambil hasil
        $data = DB::connection('mysql12')->select($sql);

        // Proses data untuk membentuk struktur yang sesuai
        if (count($data) > 0) {
            $result = $data[0]; // Ambil hasil baris pertama

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

    public function getStatistikBulanLalu()
    {
        // Definisikan tanggal awal dan akhir bulan lalu
        $tgl_awal = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $tgl_akhir = Carbon::now()->subMonth()->endOfMonth()->toDateString();
        $ttidur = env('TTIDUR', 246); // Ambil nilai TTIDUR dari .env atau 246 sebagai default

        // Query SQL untuk menghitung statistik dengan penggunaan sprintf untuk variabel
        $sql = sprintf("
            SELECT 
                ROUND((SUM(HP) * 100) / (%d * SUM(JMLHARI)), 2) AS BOR,
                ROUND(SUM(LD) / SUM(JMLKLR), 2) AS AVLOS,
                (SUM(JMLKLR) / %d) AS BTO,
                ROUND(((%d * SUM(JMLHARI)) - SUM(HP)) / SUM(JMLKLR), 2) AS TOI,
                ((SUM(LEBIH48JAM) * 1000) / SUM(JMLKLR)) AS NDR,
                (((SUM(KURANG48JAM) + SUM(LEBIH48JAM)) * 1000) / SUM(JMLKLR)) AS GDR
            FROM (
                SELECT 
                    SUM(irs.AWAL) AS AWAL, 0 AS MASUK, 0 AS PINDAHAN, 0 AS KURANG48JAM, 
                    0 AS LEBIH48JAM, 0 AS DIPINDAHKAN, 0 AS JMLKLR, 0 AS LD, 
                    0 AS HP, 0 AS TTIDUR, 0 AS JMLHARI, 0 AS PASIENDIRAWAT,
                    MAX(LASTUPDATED) AS LASTUPDATED
                FROM informasi.indikator_rs irs
                WHERE irs.TANGGAL = '%s'
                GROUP BY irs.TANGGAL
                UNION ALL
                SELECT 
                    0 AS AWAL, SUM(irs.MASUK) AS MASUK, SUM(irs.PINDAHAN) AS PINDAHAN, 
                    SUM(irs.KURANG48JAM) AS KURANG48JAM, SUM(irs.LEBIH48JAM) AS LEBIH48JAM, 
                    SUM(irs.DIPINDAHKAN) AS DIPINDAHKAN, SUM(irs.JMLKLR) AS JMLKLR, 
                    SUM(irs.LD) AS LD, SUM(irs.HP) AS HP, 
                    0 AS TTIDUR, SUM(irs.JMLHARI) AS JMLHARI, 0 AS PASIENDIRAWAT,
                    MAX(LASTUPDATED) AS LASTUPDATED
                FROM informasi.indikator_rs irs
                WHERE irs.TANGGAL BETWEEN '%s' AND '%s'
                GROUP BY irs.TANGGAL
                UNION ALL
                SELECT 
                    0 AS AWAL, 0 AS MASUK, 0 AS PINDAHAN, 0 AS KURANG48JAM, 
                    0 AS LEBIH48JAM, 0 AS DIPINDAHKAN, 0 AS JMLKLR, 0 AS LD, 
                    0 AS HP, %d AS TTIDUR, 0 AS JMLHARI, SUM(irs.SISA) AS PASIENDIRAWAT,
                    MAX(LASTUPDATED) AS LASTUPDATED
                FROM informasi.indikator_rs irs
                WHERE irs.TANGGAL = '%s'
                GROUP BY irs.TANGGAL
            ) AS b;
        ", $ttidur, $ttidur, $ttidur, $tgl_awal, $tgl_awal, $tgl_akhir, $ttidur, $tgl_akhir);

        // Eksekusi query dan ambil hasil
        $data = DB::connection('mysql12')->select($sql);

        // Proses data untuk membentuk struktur yang sesuai
        if (count($data) > 0) {
            $result = $data[0]; // Ambil hasil baris pertama

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

    protected function getStatistikKunjungan()
    {
        $data = DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                'statistikKunjungan.RJ as rajal',
                'statistikKunjungan.RD as darurat',
                'statistikKunjungan.RI as ranap',
                'statistikKunjungan.TANGGAL_UPDATED as tanggalUpdated'
            )
            ->where(function ($query) {
                $query->where('statistikKunjungan.RJ', '>', 0)
                    ->orWhere('statistikKunjungan.RD', '>', 0)
                    ->orWhere('statistikKunjungan.RI', '>', 0);
            })
            ->orderByDesc('statistikKunjungan.TANGGAL')
            ->first();

        $laboratorium = DB::connection('mysql12')->table('informasi.penunjang as penunjang')
            ->selectRaw(
                'SUM(penunjang.VALUE) as total_value, 
                MAX(penunjang.LASTUPDATED) as last_updated,
                penunjang.TANGGAL'
            )
            ->where('penunjang.ID', '4')
            ->orderByDesc('penunjang.TANGGAL')
            ->groupBy('penunjang.TANGGAL')
            ->first();

        $totalLaboratorium = $laboratorium->total_value ?? 0;
        $updateLaboratorium = $laboratorium->last_updated ?? null;

        $radiologi = DB::connection('mysql12')->table('informasi.penunjang as penunjang')
            ->selectRaw(
                'SUM(penunjang.VALUE) as total_value, 
                MAX(penunjang.LASTUPDATED) as last_updated,
                penunjang.TANGGAL'

            )
            ->where('penunjang.ID', '5')
            ->orderByDesc('penunjang.TANGGAL')
            ->groupBy('penunjang.TANGGAL')
            ->first();

        $totalRadiologi = $radiologi->total_value ?? 0;
        $updateRadiologi = $radiologi->last_updated ?? null;

        if ($data) {
            return [
                'rajal' => (float) ($data->rajal ?? 0),
                'ranap' => (float) ($data->ranap ?? 0),
                'darurat' => (float) ($data->darurat ?? 0),
                'tanggalUpdated' => $data->tanggalUpdated ?? null,
                'laboratorium' => (float) $totalLaboratorium ?? 0,
                'updateLaboratorium' => $updateLaboratorium,
                'radiologi' => (float) $totalRadiologi ?? 0,
                'updateRadiologi' => $updateRadiologi,
            ];
        }

        // Kembalikan array kosong jika tidak ada data
        return [
            'rajal' => 0,
            'ranap' => 0,
            'darurat' => 0,
            'tanggalUpdated' => null,
            'totalValue' => 0,
            'lastUpdated' => null,
            'laboratorium' => 0,
            'updateLaboratorium' => null,
            'radiologi' => 0,
            'updateRadiologi' => null,
        ];
    }

    protected function getMonthlyRawatJalan()
    {
        return InformasiStatistikKunjunganModel::selectRaw("
            CASE MONTH(TANGGAL)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END AS BULAN,
            SUM(RJ) AS JUMLAH
        ")
            ->whereYear('TANGGAL', now()->year)
            ->where('TANGGAL', '>', '0000-00-00')
            ->groupByRaw("
            CASE MONTH(TANGGAL)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END
        ")
            ->orderByRaw("FIELD(BULAN, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
            ->get();
    }

    protected function getMonthlyRawatDarurat()
    {
        return InformasiStatistikKunjunganModel::selectRaw("
            CASE MONTH(TANGGAL)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END AS BULAN,
            SUM(RD) AS JUMLAH
        ")
            ->whereYear('TANGGAL', now()->year)
            ->where('TANGGAL', '>', '0000-00-00')
            ->groupByRaw("
            CASE MONTH(TANGGAL)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END
        ")
            ->orderByRaw("FIELD(BULAN, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
            ->get();
    }

    protected function getMonthlyRawatInap()
    {
        return InformasiStatistikKunjunganModel::selectRaw("
            CASE MONTH(TANGGAL)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END AS BULAN,
            SUM(RI) AS JUMLAH
        ")
            ->whereYear('TANGGAL', now()->year)
            ->where('TANGGAL', '>', '0000-00-00')
            ->groupByRaw("
            CASE MONTH(TANGGAL)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END
        ")
            ->orderByRaw("FIELD(BULAN, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
            ->get();
    }

    protected function getMonthlyLaboratorium()
    {
        return InformasiPenunjangModel::selectRaw("
            CASE MONTH(TANGGAL)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END AS BULAN,
            SUM(VALUE) AS JUMLAH
        ")
            ->whereYear('TANGGAL', now()->year)
            ->where('TANGGAL', '>', '0000-00-00')
            ->where('ID', 4)
            ->groupByRaw("
            CASE MONTH(TANGGAL)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END
        ")
            ->orderByRaw("FIELD(BULAN, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
            ->get();
    }

    protected function getMonthlyRadiologi()
    {
        return InformasiPenunjangModel::selectRaw("
            CASE MONTH(TANGGAL)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END AS BULAN,
            SUM(VALUE) AS JUMLAH
        ")
            ->whereYear('TANGGAL', now()->year)
            ->where('TANGGAL', '>', '0000-00-00')
            ->where('ID', 5)
            ->groupByRaw("
            CASE MONTH(TANGGAL)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
            END
        ")
            ->orderByRaw("FIELD(BULAN, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
            ->get();
    }
}