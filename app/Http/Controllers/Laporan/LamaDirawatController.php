<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterReferensiModel;
use Illuminate\Pagination\LengthAwarePaginator;

class LamaDirawatController extends Controller
{
    public function index()
    {
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();
        $tgl_akhir = Carbon::now()->toDateString();
        $search = request('search') ? strtolower(request('search')) : null;

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanLamaDirawat(?, ?, ?, ?, ?, ?)', [$tgl_awal, $tgl_akhir, 0, '%', 3, 0]);

        // Filter data sesuai dengan kondisi pencarian dan tetap mempertahankan data yang tidak kosong
        $filteredData = array_values(array_filter($data, function ($row) use ($search) {
            $hasValue = (
                (!is_null($row->NORM) && $row->NORM !== '') ||
                (!is_null($row->NAMALENGKAP) && $row->NAMALENGKAP !== '') ||
                (!is_null($row->CARABAYAR) && $row->CARABAYAR !== '') ||
                (!is_null($row->NOPEN) && $row->NOPEN !== '') ||
                (!is_null($row->TGLMASUK) && $row->TGLMASUK !== '') ||
                (!is_null($row->TGLKELUAR) && $row->TGL_KELUAR !== '') ||
                (!is_null($row->UNIT) && $row->UNIT !== '') ||
                (!is_null($row->KAMAR) && $row->KAMAR !== '') ||
                (!is_null($row->LD) && $row->LD !== 0)
            );

            if (!$search) {
                return $hasValue;
            }

            $matchesSearch = (
                (isset($row->NORM) && stripos($row->NORM, $search) !== false) ||
                (isset($row->NAMALENGKAP) && stripos($row->NAMALENGKAP, $search) !== false) ||
                (isset($row->UNIT) && stripos($row->UNIT, $search) !== false) ||
                (isset($row->KAMAR) && stripos($row->KAMAR, $search) !== false)
            );

            return $matchesSearch && $hasValue;
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($b->TGLMASUK, $a->TGLMASUK);
        });

        // Konversi hasil query menjadi Collection agar bisa dipaginasi
        $perPage = 5; // Jumlah data per halaman
        $page = request()->input('page', 1); // Ambil nomor halaman dari request
        $offset = ($page - 1) * $perPage;
        $paginatedData = new LengthAwarePaginator(
            array_slice($filteredData, $offset, $perPage),
            count($filteredData),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        $caraBayar = MasterReferensiModel::where('JENIS', 10)
            ->where('STATUS', 1)
            ->orderBy('ID')
            ->get();

        return inertia("Laporan/LamaDirawat/Index", [
            'dataTable' => $paginatedData,
            'tglAwal' => $tgl_awal,
            'tglAkhir' => $tgl_akhir,
            'ruangan' => $ruangan,
            'caraBayar' => $caraBayar,
            'queryParams' => request()->all(),
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
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanLamaDirawat(?, ?, ?, ?, ?, ?)', [$dariTanggal, $sampaiTanggal, 0, $ruangan, 3, $caraBayar]);

        // Filter data seperti sebelumnya
        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->NORM) && $row->NORM !== '') ||
                (!is_null($row->NAMALENGKAP) && $row->NAMALENGKAP !== '') ||
                (!is_null($row->CARABAYAR) && $row->CARABAYAR !== '') ||
                (!is_null($row->NOPEN) && $row->NOPEN !== '') ||
                (!is_null($row->TGLMASUK) && $row->TGLMASUK !== '') ||
                (!is_null($row->TGLKELUAR) && $row->TGL_KELUAR !== '') ||
                (!is_null($row->UNIT) && $row->UNIT !== '') ||
                (!is_null($row->KAMAR) && $row->KAMAR !== '') ||
                (!is_null($row->LD) && $row->LD !== 0)
            );
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($a->TGLMASUK, $b->TGLMASUK);
        });

        return inertia("Laporan/LamaDirawat/Print", [
            'data' => $filteredData,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}
