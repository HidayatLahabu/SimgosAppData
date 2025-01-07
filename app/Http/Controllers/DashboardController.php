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

        $tahunIni = Carbon::now()->startOfYear()->year;
        $bulanIni = Carbon::now()->format('m');
        $ttidurIni = env('TTIDUR', 246);

        // Tahun lalu
        $tahunLalu = Carbon::now()->subYear()->startOfYear()->year;
        $bulanLalu = Carbon::now()->subMonth()->format('m');
        $ttidurLalu = env('TTIDURLALU', 246);

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
        $statistikTahunIni = $this->getStatistikTahun($tahunIni, $ttidurIni) ?? 0;
        $statistikTahunLalu = $this->getStatistikTahun($tahunLalu, $ttidurLalu) ?? 0;
        $statistikBulanIni = $this->getStatistikBulan($tahunIni, $bulanIni, $ttidurIni);
        $statistikBulanLalu = $this->getStatistikBulan($tahunLalu, $bulanLalu, $ttidurLalu);
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
            'statistikTahunIni' => $statistikTahunIni,
            'statistikTahunLalu' => $statistikTahunLalu,
            'statistikBulanIni' => $statistikBulanIni,
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

    public function getStatistikBulan($tahun, $bulan, $ttidur)
    {
        // Query SQL menggunakan parameter binding
        $sql = "
            SELECT 
                YEAR(t.TANGGAL) AS TAHUN, 
                MONTH(t.TANGGAL) AS BULAN,
                ROUND((SUM(HP) * 100) / (? * SUM(JMLHARI)), 2) AS BOR,
                ROUND(SUM(LD) / SUM(JMLKLR), 2) AS AVLOS,
                (SUM(JMLKLR) / ?) AS BTO,
                ROUND(((? * SUM(JMLHARI)) - SUM(HP)) / SUM(JMLKLR), 2) AS TOI,
                ((SUM(LEBIH48JAM) * 1000) / SUM(JMLKLR)) AS NDR,
                (((SUM(KURANG48JAM) + SUM(LEBIH48JAM)) * 1000) / SUM(JMLKLR)) AS GDR
            FROM informasi.indikator_rs r
            JOIN master.tanggal t ON r.TANGGAL = t.TANGGAL
            WHERE YEAR(t.TANGGAL) = ? AND MONTH(t.TANGGAL) = ? AND HP > 0
            GROUP BY TAHUN, BULAN
        ";

        // Eksekusi query dengan parameter binding
        $data = DB::connection('mysql12')->select($sql, [$ttidur, $ttidur, $ttidur, $tahun, $bulan]);

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

    protected function getStatistikKunjungan()
    {
        $todayData = DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                'statistikKunjungan.RJ as rajal',
                'statistikKunjungan.RD as darurat',
                'statistikKunjungan.RI as ranap',
                'statistikKunjungan.TANGGAL_UPDATED as tanggalUpdated',
            )
            ->where(function ($query) {
                $query->where('statistikKunjungan.RJ', '>', 0)
                    ->orWhere('statistikKunjungan.RD', '>', 0)
                    ->orWhere('statistikKunjungan.RI', '>', 0);
            })
            ->whereDate('statistikKunjungan.TANGGAL', '=', now()->toDateString())
            ->first();

        $yesterdayData = DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                'statistikKunjungan.RJ as rajal',
                'statistikKunjungan.RD as darurat',
                'statistikKunjungan.RI as ranap',
                'statistikKunjungan.TANGGAL as tanggal',
            )
            ->where(function ($query) {
                $query->where('statistikKunjungan.RJ', '>', 0)
                    ->orWhere('statistikKunjungan.RD', '>', 0)
                    ->orWhere('statistikKunjungan.RI', '>', 0);
            })
            ->whereDate('statistikKunjungan.TANGGAL', '=', now()->subDay()->toDateString())
            ->first();

        $todayLab = DB::connection('mysql12')->table('informasi.penunjang as penunjang')
            ->selectRaw(
                'SUM(penunjang.VALUE) as total_value, 
                MAX(penunjang.LASTUPDATED) as last_updated,
                penunjang.TANGGAL'
            )
            ->where('penunjang.ID', '4')
            ->whereDate('penunjang.TANGGAL', '=', now()->toDateString())
            ->groupBy('penunjang.TANGGAL')
            ->first();

        $yesterdayLab = DB::connection('mysql12')->table('informasi.penunjang as penunjang')
            ->selectRaw(
                'SUM(penunjang.VALUE) as total_value, 
                MAX(penunjang.TANGGAL) as tanggal'
            )
            ->where('penunjang.ID', '4')
            ->whereDate('penunjang.TANGGAL', '=', now()->subDay()->toDateString())
            ->groupBy('penunjang.TANGGAL')
            ->first();

        $todayRadiologi = DB::connection('mysql12')->table('informasi.penunjang as penunjang')
            ->selectRaw(
                'SUM(penunjang.VALUE) as total_value, 
                MAX(penunjang.LASTUPDATED) as last_updated,
                penunjang.TANGGAL'
            )
            ->where('penunjang.ID', '5')
            ->whereDate('penunjang.TANGGAL', '=', now()->toDateString())
            ->groupBy('penunjang.TANGGAL')
            ->first();

        $yesterdayRadiologi = DB::connection('mysql12')->table('informasi.penunjang as penunjang')
            ->selectRaw(
                'SUM(penunjang.VALUE) as total_value, 
                MAX(penunjang.TANGGAL) as tanggal'
            )
            ->where('penunjang.ID', '5')
            ->whereDate('penunjang.TANGGAL', '=', now()->subDay()->toDateString())
            ->groupBy('penunjang.TANGGAL')
            ->first();

        // Mengembalikan hasil jika ada data, jika tidak ada data return default value
        return [
            'todayRajal' => (float) ($todayData->rajal ?? 0),
            'todayRanap' => (float) ($todayData->ranap ?? 0),
            'todayDarurat' => (float) ($todayData->darurat ?? 0),
            'todayUpdated' => $todayData->tanggalUpdated ?? null,

            'yesterdayRajal' => (float) ($yesterdayData->rajal ?? 0),
            'yesterdayRanap' => (float) ($yesterdayData->ranap ?? 0),
            'yesterdayDarurat' => (float) ($yesterdayData->darurat ?? 0),
            'yesterdayUpdated' => $yesterdayData->tanggal ?? null,

            'todayLabData' => (float) ($todayLab->total_value ?? 0),
            'todayLabUpdate' => $todayLab->last_updated ?? null,
            'yesterdayLabData' => (float) ($yesterdayLab->total_value ?? 0),
            'yesterdayUpdateLab' => $yesterdayLab->tanggal ?? null,

            'todayRadiologi' => (float) ($todayRadiologi->total_value ?? 0),
            'todayRadiologiUpdate' => $todayRadiologi->last_updated ?? null,
            'yesterdayRadiologi' => (float) ($yesterdayRadiologi->total_value ?? 0),
            'yesterdayRadiologiUpdate' => $yesterdayRadiologi->tanggal ?? null,
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