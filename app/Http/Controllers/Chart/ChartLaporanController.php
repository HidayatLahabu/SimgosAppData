<?php

namespace App\Http\Controllers\Chart;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ChartLaporanController extends Controller
{
    public function index()
    {
        $tahunIni = now()->year;
        $tahunLalu = $tahunIni - 1;
        $ttidur = env('TTIDUR', 246);
        $tglAwal = Carbon::now()->startOfYear()->format('Y-m-d');
        $tglAwalLalu = Carbon::now()->subYear()->startOfYear()->format('Y-m-d');
        $tglAkhir = Carbon::now()->endOfDay()->format('Y-m-d');
        $tglAkhirLalu = Carbon::now()->subYear()->endOfYear()->format('Y-m-d');

        $dataIndikatorPelayanan = $this->getStatistikPerBulan($ttidur);
        $dataPasienMasukKeluar = $this->getDataPasienMasukKeluar();
        $dataPasienRanap = $this->getDataPasienRanap();
        $dataPasienBelumGrouping =  $this->pasienBelumGroupingPerBulan($tahunIni);
        $dataPasienBelumGroupingLalu =  $this->pasienBelumGroupingPerBulan($tahunLalu);
        $dataLaporanRl32 =  $this->getLaporanRL32($tglAwal, $tglAkhir);
        $dataLaporanRl32Lalu =  $this->getLaporanRL32($tglAwalLalu, $tglAkhirLalu);
        $dataLaporanRl314 =  $this->getLaporanRL314($tglAwal, $tglAkhir);
        $dataLaporanRl314Lalu =  $this->getLaporanRL314($tglAwalLalu, $tglAkhirLalu);
        $dataLaporanRl315 =  $this->getLaporanRL315($tglAwal, $tglAkhir);
        $dataLaporanRl315Lalu =  $this->getLaporanRL315($tglAwalLalu, $tglAkhirLalu);

        return inertia("Chart/Laporan/Index", [
            'tahunIni' => $tahunIni,
            'tahunLalu' => $tahunLalu,
            'pasienBelumGrouping' => $dataPasienBelumGrouping->toArray(),
            'pasienBelumGroupingLalu' => $dataPasienBelumGroupingLalu->toArray(),
            'indikatorPelayanan' => $dataIndikatorPelayanan,
            'pasienMasukKeluar' => $dataPasienMasukKeluar,
            'pasienRanap' => $dataPasienRanap,
            'laporanRl32' => $dataLaporanRl32,
            'laporanRl32Lalu' => $dataLaporanRl32Lalu,
            'laporanRl314' => $dataLaporanRl314,
            'laporanRl314Lalu' => $dataLaporanRl314Lalu,
            'laporanRl315' => $dataLaporanRl315,
            'laporanRl315Lalu' => $dataLaporanRl315Lalu,
        ]);
    }

    private function getLaporanRL315($tgl_awal, $tgl_akhir)
    {
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRL315(?, ?)', [$tgl_awal, $tgl_akhir]);

        // Filter hanya data yang memiliki nilai bukan 0 dan deskripsi bukan "Asuransi :"
        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                $row->DESKRIPSI !== 'Asuransi :' && // <-- baris tambahan untuk menghilangkan Asuransi umum
                (
                    (!is_null($row->RJ) && $row->RJ != 0) ||
                    (!is_null($row->LAB) && $row->LAB != 0) ||
                    (!is_null($row->RAD) && $row->RAD != 0) ||
                    (!is_null($row->JMLRI) && $row->JMLRI != 0)
                )
            );
        }));

        // Mapping ke format chart-friendly
        $chartData = collect($filteredData)->map(function ($item) {
            return [
                'deskripsi' => $item->DESKRIPSI ?? 'Tidak Diketahui',
                'RJ' => $item->RJ ?? 0,
                'LAB' => $item->LAB ?? 0,
                'RAD' => $item->RAD ?? 0,
                'JMLRI' => $item->JMLRI ?? 0,
            ];
        });

        return $chartData->values();
    }


    private function getLaporanRL314($tgl_awal, $tgl_akhir)
    {
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRL314(?, ?)', [$tgl_awal, $tgl_akhir]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->PUSKESMAS) && $row->PUSKESMAS !== 0) ||
                (!is_null($row->FASKES) && $row->FASKES !== 0) ||
                (!is_null($row->RS) && $row->RS !== 0) ||
                (!is_null($row->KEMBALIPUSKESMAS) && $row->KEMBALIPUSKESMAS !== 0) ||
                (!is_null($row->KEMBALIFASKES) && $row->KEMBALIFASKES !== 0) ||
                (!is_null($row->KEMBALIRS) && $row->KEMBALIRS !== 0) ||
                (!is_null($row->PASIENRUJUKAN) && $row->PASIENRUJUKAN !== 0) ||
                (!is_null($row->DATANGSENDIRI) && $row->DATANGSENDIRI !== 0) ||
                (!is_null($row->DITERIMAKEMBALI) && $row->DITERIMAKEMBALI !== 0)
            );
        }));

        $chartData = collect($filteredData)->map(function ($item) {
            return [
                'deskripsi' => $item->DESKRIPSI ?? 'Spesialisasi Lainnya',  // Deskripsi dari layanan
                'puskesmas' => $item->PUSKESMAS ?? 0,
                'faskes' => $item->FASKES ?? 0,
                'rs' => $item->RS ?? 0,
                'kembaliPuskesmas' => $item->KEMBALIPUSKESMAS ?? 0,
                'kembaliFaskes' => $item->KEMBALIFASKES ?? 0,
                'kembaliRs' => $item->KEMBALIRS ?? 0,
                'pasienRujukan' => $item->PASIENRUJUKAN ?? 0,
                'datangSendiri' => $item->DATANGSENDIRI ?? 0,
                'diterimaKembali' => $item->DITERIMAKEMBALI ?? 0,
            ];
        });

        return $chartData->values();
    }

    private function getLaporanRL32($tgl_awal, $tgl_akhir)
    {
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRL32(?, ?)', [$tgl_awal, $tgl_akhir]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->DESKRIPSI) && $row->DESKRIPSI !== '') ||
                (!is_null($row->RUJUKAN) && $row->RUJUKAN !== 0) ||
                (!is_null($row->NONRUJUKAN) && $row->NONRUJUKAN !== 0) ||
                (!is_null($row->DIRAWAT) && $row->DIRAWAT !== 0) ||
                (!is_null($row->DIRUJUK) && $row->DIRUJUK !== 0) ||
                (!is_null($row->PULANG) && $row->PULANG !== 0) ||
                (!is_null($row->MENINGGAL) && $row->MENINGGAL !== 0) ||
                (!is_null($row->DOA) && $row->DOA !== 0)
            );
        }));

        $chartData = collect($filteredData)->map(function ($item) {
            return [
                'jenis_pelayanan' => $item->DESKRIPSI,  // Deskripsi dari layanan
                'rujukan' => $item->RUJUKAN ?? 0,
                'nonrujukan' => $item->NONRUJUKAN ?? 0,
                'dirawat' => $item->DIRAWAT ?? 0,
                'dirujuk' => $item->DIRUJUK ?? 0,
                'pulang' => $item->PULANG ?? 0,
                'meninggal' => $item->MENINGGAL ?? 0,
                'doa' => $item->DOA ?? 0,
            ];
        });

        return $chartData->values();
    }

    private function getDataPasienMasukKeluar()
    {
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();
        $tgl_akhir = Carbon::now()->toDateString();

        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRL31(?, ?)', [$tgl_awal, $tgl_akhir]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                ($row->MASUK + $row->PINDAHAN) !== 0 ||
                ($row->DIPINDAHKAN + $row->HIDUP) !== 0 ||
                (!is_null($row->AWAL) && $row->AWAL !== 0) ||
                (!is_null($row->HIDUP) && $row->HIDUP !== 0) ||
                (!is_null($row->MATIKURANG48) && $row->MATIKURANG48 !== 0) ||
                (!is_null($row->MATILEBIH48) && $row->MATILEBIH48 !== 0)
            );
        }));

        $chartData = collect($filteredData)->map(function ($item) {
            return [
                'jenis_pelayanan' => $item->DESKRIPSI,  // Deskripsi dari layanan
                'awal' => $item->AWAL ?? 0,
                'masuk' => ($item->MASUK ?? 0) + ($item->PINDAHAN ?? 0),
                'keluar' => ($item->DIPINDAHKAN ?? 0) + ($item->HIDUP ?? 0),
                'mati' => ($item->MATIKURANG48 ?? 0) + ($item->MATILEBIH48 ?? 0),
            ];
        });

        return $chartData->values();
    }

    public function getStatistikPerBulan($ttidur)
    {
        $tahun = date('Y');  // Menentukan tahun saat ini (tahun berjalan)

        $data = DB::connection('mysql12')->select("
            SELECT 
                YEAR(t.TANGGAL) AS TAHUN,
                MONTH(t.TANGGAL) AS BULAN,
                ROUND((SUM(HP) * 100) / (? * SUM(JMLHARI)), 2) AS BOR,
                ROUND(SUM(LD) / SUM(JMLKLR), 2) AS AVLOS,
                ROUND(SUM(JMLKLR) / ?, 2) AS BTO,
                ROUND(((? * SUM(JMLHARI)) - SUM(HP)) / SUM(JMLKLR), 2) AS TOI,
                ROUND((SUM(LEBIH48JAM) * 1000) / SUM(JMLKLR), 2) AS NDR,
                ROUND(((SUM(KURANG48JAM) + SUM(LEBIH48JAM)) * 1000) / SUM(JMLKLR), 2) AS GDR
            FROM informasi.indikator_rs r
            JOIN master.tanggal t ON r.TANGGAL = t.TANGGAL
            WHERE YEAR(t.TANGGAL) = ? AND HP > 0
            GROUP BY YEAR(t.TANGGAL), MONTH(t.TANGGAL)
            ORDER BY TAHUN, BULAN
        ", [$ttidur, $ttidur, $ttidur, $tahun]);

        $result = [];

        foreach ($data as $d) {
            $bulan = str_pad($d->BULAN, 2, '0', STR_PAD_LEFT);
            $result[] = [
                'bulan' => "$tahun-$bulan",  // Format bulan dan tahun
                'bor' => round($d->BOR, 2),
                'avlos' => round($d->AVLOS, 2),
                'bto' => round($d->BTO, 2),
                'toi' => round($d->TOI, 2),
                'ndr' => round($d->NDR, 2),
                'gdr' => round($d->GDR, 2),
            ];
        }

        return $result;
    }

    private function getDataPasienRanap()
    {
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();
        $tgl_akhir = Carbon::now()->toDateString();

        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRL31(?, ?)', [$tgl_awal, $tgl_akhir]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->VVIP) && $row->VVIP !== 0) ||
                (!is_null($row->VIP) && $row->VIP !== 0) ||
                (!is_null($row->KLSI) && $row->KLSI !== 0) ||
                (!is_null($row->KLSII) && $row->KLSII !== 0) ||
                (!is_null($row->KLSIII) && $row->KLSIII !== 0) ||
                (!is_null($row->KLSKHUSUS) && $row->KLSKHUSUS !== 0)
            );
        }));

        $chartData = collect($filteredData)->map(function ($item) {
            return [
                'jenis_pelayanan' => $item->DESKRIPSI,  // Deskripsi dari layanan
                'vvip' => $item->VVIP ?? 0,
                'vip' => ($item->VIP ?? 0),
                'kls1' => ($item->KLSI ?? 0),
                'kls2' => ($item->KLSII ?? 0),
                'kls3' => ($item->KLSIII ?? 0),
                'kls_khusus' => ($item->KLSKHUSUS ?? 0),
            ];
        });

        return $chartData->values();
    }

    private function pasienBelumGroupingPerBulan($tahun)
    {
        $rawData = DB::connection('mysql5')
            ->table('pendaftaran.pendaftaran as p')
            ->leftJoin('inacbg.hasil_grouping as hl', 'p.NOMOR', '=', 'hl.NOPEN')
            ->select(
                DB::raw('YEAR(p.TANGGAL) as tahun'),
                DB::raw('MONTH(p.TANGGAL) as bulan'),
                DB::raw('COUNT(p.NOMOR) as total')
            )
            ->whereYear('p.TANGGAL', $tahun)
            ->where(function ($query) {
                $query->where('hl.STATUS', '!=', 1)
                    ->orWhereNull('hl.STATUS');
            })
            ->where('p.STATUS', '!=', 0)
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan'); // Gunakan bulan sebagai key

        // Generate bulan 1-12
        $dataLengkap = collect();
        foreach (range(1, 12) as $bulan) {
            $dataLengkap->push([
                'tahun' => $tahun,
                'bulan' => $bulan,
                'total' => $rawData->get($bulan)->total ?? 0
            ]);
        }

        return $dataLengkap;
    }
}
