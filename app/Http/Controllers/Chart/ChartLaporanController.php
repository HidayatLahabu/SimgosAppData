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

        $dataIndikatorPelayanan = $this->getStatistikPerBulan($ttidur);
        $dataPasienMasukKeluar = $this->getDataPasienMasukKeluar();

        $dataPasienBelumGrouping =  $this->pasienBelumGroupingPerBulan($tahunIni);
        $dataPasienBelumGroupingLalu =  $this->pasienBelumGroupingPerBulan($tahunLalu);

        return inertia("Chart/Laporan/Index", [
            'tahunIni' => $tahunIni,
            'tahunLalu' => $tahunLalu,
            'pasienBelumGrouping' => $dataPasienBelumGrouping->toArray(),
            'pasienBelumGroupingLalu' => $dataPasienBelumGroupingLalu->toArray(),
            'indikatorPelayanan' => $dataIndikatorPelayanan,
            'pasienMasukKeluar' => $dataPasienMasukKeluar,
        ]);
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
