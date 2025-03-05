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
                (!is_null($row->DESKRIPSI) && $row->DESKRIPSI !== 0) ||
                (!is_null($row->AWAL) && $row->AWAL !== 0) ||
                (!is_null($row->MASUK) && $row->MASUK !== 0) ||
                (!is_null($row->PINDAHAN) && $row->PINDAHAN !== 0) ||
                (!is_null($row->DIPINDAHKAN) && $row->DIPINDAHKAN !== 0) ||
                (!is_null($row->HIDUP) && $row->HIDUP !== 0) ||
                (!is_null($row->MATI) && $row->MATI !== 0) ||
                (!is_null($row->MATIKURANG48) && $row->MATIKURANG48 !== 0) ||
                (!is_null($row->MATILEBIH48) && $row->MATILEBIH48 !== 0) ||
                (!is_null($row->SISA) && $row->SISA !== 0) ||
                (!is_null($row->LD) && $row->LD !== 0) ||
                (!is_null($row->HP) && $row->HP !== 0)
            );
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($a->DESKRIPSI, $b->DESKRIPSI);
        });

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
        ]);

        // Ambil nilai input
        $tgl_awal = Carbon::parse($request->input('dari_tanggal'))->format('Y-m-d');
        $tgl_akhir = Carbon::parse($request->input('sampai_tanggal'))->endOfDay()->format('Y-m-d');
        $idLab = env('IDLAB', 10219);

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanVolTindakanGroupTindakan(?, ?, ?, ?, ?, ?)', [$tgl_awal, $tgl_akhir, $idLab, 0, 0, 0]);

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

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($a->LAYANAN, $b->LAYANAN);
        });

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

        return inertia("Laporan/TindakanLab/Print", [
            'data' => $filteredData,
            'dariTanggal' => $tgl_awal,
            'sampaiTanggal' => $tgl_akhir,
            'total' => $total,
        ]);
    }
}
