<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LayananTindakanRadGroupController extends Controller
{
    public function index()
    {
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();
        $tgl_akhir = Carbon::now()->toDateString();
        $idRad = env('IDRAD', 10220);

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanVolTindakanGroupTindakan(?, ?, ?, ?, ?, ?)', [$tgl_awal, $tgl_akhir, $idRad, 0, 0, 0]);

        $filteredData = array_values(array_filter($data, function ($row) {
            $row->FREKUENSI = ($row->UMUM ?? 0) + ($row->BPJS ?? 0) + ($row->IKS ?? 0);
            return (
                (!is_null($row->LAYANAN) && $row->LAYANAN !== 0) ||
                (!is_null($row->UMUM) && $row->UMUM !== 0) ||
                (!is_null($row->BPJS) && $row->BPJS !== 0) ||
                (!is_null($row->IKS) && $row->IKS !== 0) ||
                $row->FREKUENSI !== 0 ||
                (!is_null($row->TOTALTAGIHAN) && $row->TOTALTAGIHAN !== 0)
            );
        }));

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'UMUM' => 0,
            'BPJS' => 0,
            'IKS' => 0,
            'FREKUENSI' => 0,
            'TOTALTAGIHAN' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($filteredData as $row) {
            $total['UMUM'] += $row->UMUM ?? 0;
            $total['BPJS'] += $row->BPJS ?? 0;
            $total['IKS'] += $row->IKS ?? 0;
            $total['FREKUENSI'] += $row->FREKUENSI ?? 0;
            $total['TOTALTAGIHAN'] += $row->TOTALTAGIHAN ?? 0;
        }

        return inertia("Laporan/TindakanRad/Index", [
            'data' => $filteredData,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'total' => $total,
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
        $idRad = env('IDRAD', 10220);

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanVolTindakanGroupTindakan(?, ?, ?, ?, ?, ?)', [$tgl_awal, $tgl_akhir, $idRad, 0, 0, 0]);

        $filteredData = array_values(array_filter($data, function ($row) {
            $row->FREKUENSI = ($row->UMUM ?? 0) + ($row->BPJS ?? 0) + ($row->IKS ?? 0);
            return (
                (!is_null($row->LAYANAN) && $row->LAYANAN !== 0) ||
                (!is_null($row->UMUM) && $row->UMUM !== 0) ||
                (!is_null($row->BPJS) && $row->BPJS !== 0) ||
                (!is_null($row->IKS) && $row->IKS !== 0) ||
                $row->FREKUENSI !== 0 ||
                (!is_null($row->TOTALTAGIHAN) && $row->TOTALTAGIHAN !== 0)
            );
        }));

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'UMUM' => 0,
            'BPJS' => 0,
            'IKS' => 0,
            'FREKUENSI' => 0,
            'TOTALTAGIHAN' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($filteredData as $row) {
            $total['UMUM'] += $row->UMUM ?? 0;
            $total['BPJS'] += $row->BPJS ?? 0;
            $total['IKS'] += $row->IKS ?? 0;
            $total['FREKUENSI'] += $row->FREKUENSI ?? 0;
            $total['TOTALTAGIHAN'] += $row->TOTALTAGIHAN ?? 0;
        }

        return inertia("Laporan/TindakanRad/Print", [
            'data' => $filteredData,
            'dariTanggal' => $tgl_awal,
            'sampaiTanggal' => $tgl_akhir,
            'total' => $total,
        ]);
    }
}