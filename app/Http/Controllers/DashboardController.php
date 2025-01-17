<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\LayananResepModel;
use App\Models\BpjsKunjunganModel;
use App\Models\BpjsRekonModel;
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
        $rencanaKontrol = $this->getRencanaKontrol();
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

        $waktuTungguTercepat = $this->getWaktuTungguTercepat();
        $waktuTungguTerlama = $this->getWaktuTungguTerlama();

        $dataLaboratorium = $this->getLaboratorium();
        $hasilLaboratorium = $this->getLaboratoriumHasil();
        $catatanLaboratorium = $this->getLaboratoriumCatatan();

        $dataRadiologi = $this->getRadiologi();
        $hasilRadiologi = $this->getRadiologiHasil();
        $catatanRadiologi = $this->getRadiologiCatatan();

        $dataFarmasi = $this->getFarmasi();
        $dataFarmasiOrder = $this->getFarmasiOrder();
        $dataTelaahFarmasi = $this->getFarmasiTelaah();

        // Pass the data to the Inertia view
        return Inertia::render('Dashboard', [
            'pendaftaran' => $pendaftaran,
            'kunjungan' => $kunjungan,
            'konsul' => $konsul,
            'mutasi' => $mutasi,
            'kunjunganBpjs' => $kunjunganBpjs,
            'rencanaKontrol' => $rencanaKontrol,
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
            'waktuTungguTercepat' => $waktuTungguTercepat,
            'waktuTungguTerlama' => $waktuTungguTerlama,
            'dataLaboratorium' => $dataLaboratorium,
            'hasilLaboratorium' => $hasilLaboratorium,
            'catatanLaboratorium' => $catatanLaboratorium,
            'dataRadiologi' => $dataRadiologi,
            'hasilRadiologi' => $hasilRadiologi,
            'catatanRadiologi' => $catatanRadiologi,
            'dataFarmasi' => $dataFarmasi,
            'orderFarmasi' => $dataFarmasiOrder,
            'telaahFarmasi' => $dataTelaahFarmasi,
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

    protected function getRencanaKontrol()
    {
        return BpjsRekonModel::whereBetween('tglRencanaKontrol', [
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
        $lastFiveDaysData = DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                'statistikKunjungan.RJ as rajal',
                'statistikKunjungan.RD as darurat',
                'statistikKunjungan.RI as ranap',
                'statistikKunjungan.TANGGAL as tanggal'
            )
            ->where(function ($query) {
                $query->where('statistikKunjungan.RJ', '>', 0)
                    ->orWhere('statistikKunjungan.RD', '>', 0)
                    ->orWhere('statistikKunjungan.RI', '>', 0);
            })
            ->whereBetween('statistikKunjungan.TANGGAL', [now()->subDays(7)->toDateString(), now()->toDateString()])
            ->orderBy('statistikKunjungan.TANGGAL', 'asc')
            ->get()
            ->map(function ($item) {
                // Format the 'tanggal' field to dd-mm-yyyy
                $item->tanggal = Carbon::parse($item->tanggal)->format('d M');
                return $item;
            });

        // Data statistik kunjungan untuk hari ini
        $todayData = DB::connection('mysql12')->table('informasi.statistik_kunjungan as statistikKunjungan')
            ->select(
                'statistikKunjungan.TANGGAL as updated'
            )
            ->whereDate('statistikKunjungan.TANGGAL', now()->toDateString())
            ->first();

        return [
            'todayUpdated' => $todayData->updated ?? null,
            'lastFiveDaysData' => $lastFiveDaysData,
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

    public function getWaktuTungguTercepat()
    {
        $vRuangan = '%';

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.pendaftaran as pd')
            ->select([
                'r.DESKRIPSI as UNITPELAYANAN',
                DB::raw("master.getNamaLengkapPegawai(dok.NIP) as DOKTER_REG"),
                DB::raw("AVG(TIMESTAMPDIFF(SECOND, pd.TANGGAL, tk.MASUK)) as WAKTU_TUNGGU_RATA_RATA"),
                DB::raw("COUNT(tp.NOPEN) as JUMLAH_PASIEN"),
                DB::raw("MONTH(tk.MASUK) as BULAN"),
                DB::raw("YEAR(tk.MASUK) as TAHUN"),
            ])
            ->join('master.pasien as p', 'pd.NORM', '=', 'p.NORM')
            ->join('pendaftaran.tujuan_pasien as tp', 'pd.NOMOR', '=', 'tp.NOPEN')
            ->join('pendaftaran.kunjungan as tk', function ($join) {
                $join->on('pd.NOMOR', '=', 'tk.NOPEN')
                    ->on('tp.RUANGAN', '=', 'tk.RUANGAN');
            })
            ->join('master.ruangan as r', function ($join) {
                $join->on('tp.RUANGAN', '=', 'r.ID')
                    ->where('r.JENIS', '=', 5)
                    ->where('r.JENIS_KUNJUNGAN', 1);
            })
            ->leftJoin('master.dokter as dok', 'tp.DOKTER', '=', 'dok.ID')
            ->whereIn('pd.STATUS', [1])
            ->whereNull('tk.REF')
            ->whereIn('tk.STATUS', [1, 2])
            ->where('tp.RUANGAN', 'LIKE', $vRuangan)
            ->whereYear('tk.MASUK', '=', date('Y'))
            ->whereMonth('tk.MASUK', '=', date('m'));

        // Apply ordering and pagination
        $data = $query->groupBy(
            DB::raw('MONTH(tk.MASUK)'),
            DB::raw('YEAR(tk.MASUK)'),
            'dok.NIP',
            'r.DESKRIPSI'
        )
            ->orderBy(DB::raw("AVG(TIMESTAMPDIFF(SECOND, pd.TANGGAL, tk.MASUK))"), 'asc')
            ->first();

        // Return paginated data
        return $data;
    }

    public function getWaktuTungguTerlama()
    {
        $vRuangan = '%';

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.pendaftaran as pd')
            ->select([
                'r.DESKRIPSI as UNITPELAYANAN',
                DB::raw("master.getNamaLengkapPegawai(dok.NIP) as DOKTER_REG"),
                DB::raw("AVG(TIMESTAMPDIFF(SECOND, pd.TANGGAL, tk.MASUK)) as WAKTU_TUNGGU_RATA_RATA"),
                DB::raw("COUNT(tp.NOPEN) as JUMLAH_PASIEN"),
                DB::raw("MONTH(tk.MASUK) as BULAN"),
                DB::raw("YEAR(tk.MASUK) as TAHUN"),
            ])
            ->join('master.pasien as p', 'pd.NORM', '=', 'p.NORM')
            ->join('pendaftaran.tujuan_pasien as tp', 'pd.NOMOR', '=', 'tp.NOPEN')
            ->join('pendaftaran.kunjungan as tk', function ($join) {
                $join->on('pd.NOMOR', '=', 'tk.NOPEN')
                    ->on('tp.RUANGAN', '=', 'tk.RUANGAN');
            })
            ->join('master.ruangan as r', function ($join) {
                $join->on('tp.RUANGAN', '=', 'r.ID')
                    ->where('r.JENIS', '=', 5)
                    ->where('r.JENIS_KUNJUNGAN', 1);
            })
            ->leftJoin('master.dokter as dok', 'tp.DOKTER', '=', 'dok.ID')
            ->whereIn('pd.STATUS', [1])
            ->whereNull('tk.REF')
            ->whereIn('tk.STATUS', [1, 2])
            ->where('tp.RUANGAN', 'LIKE', $vRuangan)
            ->whereYear('tk.MASUK', '=', date('Y'))
            ->whereMonth('tk.MASUK', '=', date('m'));

        // Apply ordering and pagination
        $data = $query->groupBy(
            DB::raw('MONTH(tk.MASUK)'),
            DB::raw('YEAR(tk.MASUK)'),
            'dok.NIP',
            'r.DESKRIPSI'
        )
            ->orderBy(DB::raw("AVG(TIMESTAMPDIFF(SECOND, pd.TANGGAL, tk.MASUK))"), 'desc')
            ->first();

        // Return paginated data
        return $data;
    }

    public function getLaboratorium()
    {
        $data = DB::connection('mysql7')->table('layanan.order_lab as orderLab')
            ->leftJoin('layanan.order_detil_lab as orderDetail', 'orderDetail.ORDER_ID', '=', 'orderLab.NOMOR')
            ->selectRaw('
            COUNT(DISTINCT orderLab.NOMOR) as orderLab, 
            COUNT(orderDetail.TINDAKAN) as tindakanLab
        ')
            ->whereDate('orderLab.TANGGAL', '=', Carbon::today()->toDateString())
            ->first();

        return $data ?? (object) [
            'orderLab' => 0,
            'tindakanLab' => 0,
        ];
    }


    public function getLaboratoriumHasil()
    {
        $data = DB::connection('mysql7')->table('layanan.hasil_lab as hasilLab')
            ->selectRaw('
            COUNT(hasilLab.ID) as hasilLab
        ')
            ->whereDate('hasilLab.TANGGAL', '=', Carbon::today()->toDateString())
            ->where('hasilLab.STATUS', 1)
            ->first();

        return $data ?? (object) [
            'hasilLab' => 0,
        ];
    }

    public function getLaboratoriumCatatan()
    {
        $data = DB::connection('mysql7')->table('layanan.catatan_hasil_lab as catatan')
            ->selectRaw('
                COUNT(catatan.KUNJUNGAN) as catatanLab
            ')
            ->whereDate('catatan.TANGGAL', '=', Carbon::today()->toDateString())
            ->where('catatan.STATUS', 1)
            ->first();

        return $data ?? (object) [
            'catatanLab' => 0
        ];
    }

    public function getRadiologi()
    {
        $data = DB::connection('mysql7')->table('layanan.order_rad as orderRad')
            ->leftJoin('layanan.order_detil_rad as orderDetail', 'orderDetail.ORDER_ID', '=', 'orderRad.NOMOR')
            ->selectRaw('
                COUNT(DISTINCT orderRad.NOMOR) as orderRad, 
                COUNT(orderDetail.TINDAKAN) as tindakanRad
            ')
            ->whereDate('orderRad.TANGGAL', '=', Carbon::today()->toDateString())
            ->first();

        return $data ?? (object) [
            'orderRad' => 0,
            'tindakanRad' => 0,
        ];
    }

    public function getRadiologiHasil()
    {
        $data = DB::connection('mysql7')->table('layanan.hasil_rad as hasilRad')
            ->selectRaw('
                COUNT(hasilRad.ID) as hasilRad
            ')
            ->whereDate('hasilRad.TANGGAL', '=', Carbon::today()->toDateString())
            ->first();

        return $data ?? (object) [
            'hasilRad' => 0,
        ];
    }

    public function getRadiologiCatatan()
    {
        $data = DB::connection('mysql7')->table('layanan.hasil_rad as hasilRad')
            ->selectRaw('
                COUNT(hasilRad.ID) as catatanRad
            ')
            ->whereDate('hasilRad.TANGGAL', '=', Carbon::today()->toDateString())
            ->where('hasilRad.STATUS', 1)
            ->first();

        return $data ?? (object) [
            'catatanRad' => 0,
        ];
    }

    public function getFarmasi()
    {
        $data = DB::connection('mysql7')->table('layanan.farmasi as farmasi')
            ->selectRaw('
                COUNT(farmasi.ID) as jumlahKunjungan
            ')
            ->whereDate('farmasi.TANGGAL', '=', Carbon::today()->toDateString())
            ->first();

        return $data ?? (object) [
            'jumlahKunjungan' => 0,
        ];
    }

    public function getFarmasiOrder()
    {
        $data = DB::connection('mysql7')->table('layanan.order_resep as orderResep')
            ->leftJoin('layanan.order_detil_resep as orderDetail', 'orderDetail.ORDER_ID', '=', 'orderResep.NOMOR')
            ->selectRaw('
            COUNT(orderResep.NOMOR) as jumlahOrder, 
            COUNT(orderDetail.ORDER_ID) as jumlahDetail
        ')
            ->whereDate('orderResep.TANGGAL', '=', Carbon::today()->toDateString())
            ->first();

        return $data ?? (object) [
            'jumlahOrder' => 0,
            'jumlahDetail' => 0,
        ];
    }

    public function getFarmasiTelaah()
    {

        $data = DB::connection('mysql7')->table('layanan.telaah_awal_resep as telaahAwal')
            ->selectRaw('
                COUNT(DISTINCT telaahAwal.RESEP) as jumlahTelaah
            ')
            ->whereDate('telaahAwal.INPUT_TIME', '=', Carbon::today()->toDateString())
            ->first();

        return $data ?? (object) [
            'jumlahTelaah' => 0,
        ];
    }
}