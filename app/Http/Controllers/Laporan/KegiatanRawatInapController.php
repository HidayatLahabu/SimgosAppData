<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterReferensiModel;

class KegiatanRawatInapController extends Controller
{
    public function index()
    {
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();
        $tgl_akhir = Carbon::now()->toDateString();

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanKegiatanRawatInap(?, ?, ?, ?, ?, ?)', [$tgl_awal, $tgl_akhir, 0, '%', 3, 0]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                ($row->AWAL ?? 0) > 0 ||
                ($row->MASUK ?? 0) > 0 ||
                ($row->PINDAHAN ?? 0) > 0 ||
                ($row->DIPINDAHKAN ?? 0) > 0 ||
                ($row->HIDUP ?? 0) > 0 ||
                ($row->MATI ?? 0) > 0 ||
                ($row->MATIKURANG48 ?? 0) > 0 ||
                ($row->MATILEBIH48 ?? 0) > 0 ||
                ($row->SISA ?? 0) > 0 ||
                ($row->LD ?? 0) > 0 ||
                ($row->HP ?? 0) > 0
            );
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($a->DESKRIPSI, $b->DESKRIPSI);
        });

        // Filter agar hanya satu baris per DESKRIPSI
        $uniqueData = [];
        foreach ($filteredData as $row) {
            if (!isset($uniqueData[$row->DESKRIPSI])) {
                $uniqueData[$row->DESKRIPSI] = $row;
            }
        }

        // Konversi kembali ke array dengan Inertia
        $filteredData = array_values($uniqueData);

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'AWAL' => 0,
            'MASUK' => 0,
            'PINDAHAN' => 0,
            'DIPINDAHKAN' => 0,
            'HIDUP' => 0,
            'MATI' => 0,
            'MATIKURANG48' => 0,
            'MATILEBIH48' => 0,
            'SISA' => 0,
            'LD' => 0,
            'HP' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($filteredData as $row) {
            $total['AWAL'] += $row->AWAL ?? 0;
            $total['MASUK'] += $row->MASUK ?? 0;
            $total['PINDAHAN'] += $row->PINDAHAN ?? 0;
            $total['DIPINDAHKAN'] += $row->DIPINDAHKAN ?? 0;
            $total['HIDUP'] += $row->HIDUP ?? 0;
            $total['MATI'] += $row->MATI ?? 0;
            $total['MATIKURANG48'] += $row->MATIKURANG48 ?? 0;
            $total['MATILEBIH48'] += $row->MATILEBIH48 ?? 0;
            $total['SISA'] += $row->SISA ?? 0;
            $total['LD'] += $row->LD ?? 0;
            $total['HP'] += $row->HP ?? 0;
        }

        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        $caraBayar = MasterReferensiModel::where('JENIS', 10)
            ->where('STATUS', 1)
            ->orderBy('ID')
            ->get();

        return inertia("Laporan/KegiatanRanap/Index", [
            'dataTable' => $filteredData,
            'tglAwal' => $tgl_awal,
            'tglAkhir' => $tgl_akhir,
            'total' => $total,
            'ruangan' => $ruangan,
            'caraBayar' => $caraBayar,
        ]);
    }

    public function print(Request $request)
    {
        $request->validate([
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
            'ruangan'  => 'nullable|integer',
            'caraBayar'      => 'nullable|integer',
        ]);

        // Ambil nilai input
        $ruangan = $request->input('ruangan', '%');
        $caraBayar = $request->input('caraBayar', 0);
        $dariTanggal = Carbon::parse($request->input('dari_tanggal'))->toDateTimeString();
        $sampaiTanggal = Carbon::parse($request->input('sampai_tanggal'))->endOfDay()->toDateTimeString();

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanKegiatanRawatInap(?, ?, ?, ?, ?, ?)', [$dariTanggal, $sampaiTanggal, 0, $ruangan, 3, $caraBayar]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                ($row->AWAL ?? 0) > 0 ||
                ($row->MASUK ?? 0) > 0 ||
                ($row->PINDAHAN ?? 0) > 0 ||
                ($row->DIPINDAHKAN ?? 0) > 0 ||
                ($row->HIDUP ?? 0) > 0 ||
                ($row->MATI ?? 0) > 0 ||
                ($row->MATIKURANG48 ?? 0) > 0 ||
                ($row->MATILEBIH48 ?? 0) > 0 ||
                ($row->SISA ?? 0) > 0 ||
                ($row->LD ?? 0) > 0 ||
                ($row->HP ?? 0) > 0
            );
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($a->DESKRIPSI, $b->DESKRIPSI);
        });

        // Filter agar hanya satu baris per DESKRIPSI
        $uniqueData = [];
        foreach ($filteredData as $row) {
            if (!isset($uniqueData[$row->DESKRIPSI])) {
                $uniqueData[$row->DESKRIPSI] = $row;
            }
        }

        // Konversi kembali ke array dengan Inertia
        $filteredData = array_values($uniqueData);

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'AWAL' => 0,
            'MASUK' => 0,
            'PINDAHAN' => 0,
            'DIPINDAHKAN' => 0,
            'HIDUP' => 0,
            'MATI' => 0,
            'MATIKURANG48' => 0,
            'MATILEBIH48' => 0,
            'SISA' => 0,
            'LD' => 0,
            'HP' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($filteredData as $row) {
            $total['AWAL'] += $row->AWAL ?? 0;
            $total['MASUK'] += $row->MASUK ?? 0;
            $total['PINDAHAN'] += $row->PINDAHAN ?? 0;
            $total['DIPINDAHKAN'] += $row->DIPINDAHKAN ?? 0;
            $total['HIDUP'] += $row->HIDUP ?? 0;
            $total['MATI'] += $row->MATI ?? 0;
            $total['MATIKURANG48'] += $row->MATIKURANG48 ?? 0;
            $total['MATILEBIH48'] += $row->MATILEBIH48 ?? 0;
            $total['SISA'] += $row->SISA ?? 0;
            $total['LD'] += $row->LD ?? 0;
            $total['HP'] += $row->HP ?? 0;
        }

        return inertia("Laporan/KegiatanRanap/Print", [
            'data' => $filteredData,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
            'total' => $total,
        ]);
    }
}