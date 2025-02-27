<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PasienMeninggalRekapController extends Controller
{
    public function index()
    {
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();
        $tgl_akhir = Carbon::now()->toDateString();

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRekapPasienMeninggal(?, ?, ?, ?, ?, ?)', [$tgl_awal, $tgl_akhir, 0, '%', 3, 0]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->UNIT) && $row->UNIT !== 0) ||
                (!is_null($row->LAKILAKI) && $row->LAKILAKI !== 0) ||
                (!is_null($row->PEREMPUAN) && $row->PEREMPUAN !== 0) ||
                (!is_null($row->UMUM) && $row->UMUM !== 0) ||
                (!is_null($row->JKN) && $row->JKN !== 0) ||
                (!is_null($row->JKD) && $row->JKD !== 0) ||
                (!is_null($row->INHEALTH) && $row->INHEALTH !== 0) ||
                (!is_null($row->IKS) && $row->IKS !== 0) ||
                (!is_null($row->JUMLAH) && $row->JUMLAH !== 0)
            );
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($a->UNIT, $b->UNIT);
        });

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'LAKILAKI' => 0,
            'PEREMPUAN' => 0,
            'UMUM' => 0,
            'JKN' => 0,
            'JKD' => 0,
            'INHEALTH' => 0,
            'IKS' => 0,
            'JUMLAH' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($filteredData as $row) {
            $total['LAKILAKI'] += $row->LAKILAKI ?? 0;
            $total['PEREMPUAN'] += $row->PEREMPUAN ?? 0;
            $total['UMUM'] += $row->UMUM ?? 0;
            $total['JKN'] += $row->JKN ?? 0;
            $total['JKD'] += $row->JKD ?? 0;
            $total['INHEALTH'] += $row->INHEALTH ?? 0;
            $total['IKS'] += $row->IKS ?? 0;
            $total['JUMLAH'] += $row->FREKUENSI ?? 0;
        }

        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        return inertia("Laporan/PasienMeninggalRekap/Index", [
            'dataTable' => $filteredData,
            'tglAwal' => $tgl_awal,
            'tglAkhir' => $tgl_akhir,
            'total' => $total,
            'ruangan' => $ruangan,
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
        $tgl_awal = Carbon::parse($request->input('dari_tanggal'))->format('Y-m-d');
        $tgl_akhir = Carbon::parse($request->input('sampai_tanggal'))->endOfDay()->format('Y-m-d');
        $ruangan = $request->input('ruangan', '%');

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRekapPasienMeninggal(?, ?, ?, ?, ?, ?)', [$tgl_awal, $tgl_akhir, 0, $ruangan, 3, 0]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->UNIT) && $row->UNIT !== 0) ||
                (!is_null($row->LAKILAKI) && $row->LAKILAKI !== 0) ||
                (!is_null($row->PEREMPUAN) && $row->PEREMPUAN !== 0) ||
                (!is_null($row->UMUM) && $row->UMUM !== 0) ||
                (!is_null($row->JKN) && $row->JKN !== 0) ||
                (!is_null($row->JKD) && $row->JKD !== 0) ||
                (!is_null($row->INHEALTH) && $row->INHEALTH !== 0) ||
                (!is_null($row->IKS) && $row->IKS !== 0) ||
                (!is_null($row->JUMLAH) && $row->JUMLAH !== 0)
            );
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($a->UNIT, $b->UNIT);
        });

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'LAKILAKI' => 0,
            'PEREMPUAN' => 0,
            'UMUM' => 0,
            'JKN' => 0,
            'JKD' => 0,
            'INHEALTH' => 0,
            'IKS' => 0,
            'JUMLAH' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($filteredData as $row) {
            $total['LAKILAKI'] += $row->LAKILAKI ?? 0;
            $total['PEREMPUAN'] += $row->PEREMPUAN ?? 0;
            $total['UMUM'] += $row->UMUM ?? 0;
            $total['JKN'] += $row->JKN ?? 0;
            $total['JKD'] += $row->JKD ?? 0;
            $total['INHEALTH'] += $row->INHEALTH ?? 0;
            $total['IKS'] += $row->IKS ?? 0;
            $total['JUMLAH'] += $row->JUMLAH ?? 0;
        }

        return inertia("Laporan/PasienMeninggalRekap/Print", [
            'data' => $filteredData,
            'dariTanggal' => $tgl_awal,
            'sampaiTanggal' => $tgl_akhir,
            'total' => $total,
        ]);
    }
}
