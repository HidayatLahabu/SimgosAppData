<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class PasienBelumGroupingController extends Controller
{
    public function index()
    {
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();
        $tgl_akhir = Carbon::now()->toDateString();
        $search = request('search') ? strtolower(request('search')) : null;

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanPasienGrouping(?, ?, ?, ?, ?, ?, ?)', [$tgl_awal, $tgl_akhir, 0, '%', 3, 0, 0]);

        // Filter data sesuai dengan kondisi pencarian dan tetap mempertahankan data yang tidak kosong
        $filteredData = array_values(array_filter($data, function ($row) use ($search) {
            $hasValue = (
                (!is_null($row->TGLMASUK) && $row->TGLMASUK !== '') ||
                (!is_null($row->TGLKELUAR) && $row->TGLKELUAR !== '') ||
                (!is_null($row->NOMOR) && $row->NOMOR !== '') ||
                (!is_null($row->NORM) && $row->NORM !== '') ||
                (!is_null($row->NOMORKARTU) && $row->NOMORKARTU !== '') ||
                (!is_null($row->NOSEP) && $row->NOSEP !== '') ||
                (!is_null($row->NAMAPASIEN) && $row->NAMAPASIEN !== '') ||
                (!is_null($row->LOS) && $row->LOS !== 0) ||
                (!is_null($row->TARIFRS) && $row->TARIFRS !== 0) ||
                (!is_null($row->DPJP) && $row->TARIFRS !== 0) ||
                (!is_null($row->UNITPELAYANAN) && $row->UNITPELAYANAN !== '')
            );

            if (!$search) {
                return $hasValue;
            }

            $matchesSearch = (
                (isset($row->NORM) && stripos($row->NORM, $search) !== false) ||
                (isset($row->NOSEP) && stripos($row->NOSEP, $search) !== false) ||
                (isset($row->NAMAPASIEN) && stripos($row->NAMAPASIEN, $search) !== false) ||
                (isset($row->DPJP) && stripos($row->NAMAPASIEN, $search) !== false) ||
                (isset($row->UNITPELAYANAN) && stripos($row->UNITPELAYANAN, $search) !== false)
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

        return inertia("Laporan/PasienBelumGroup/Index", [
            'dataTable' => $paginatedData,
            'tglAwal' => $tgl_awal,
            'tglAkhir' => $tgl_akhir,
            'ruangan' => $ruangan,
            'queryParams' => request()->all(),
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
        $ruangan = $request->input('ruangan', '%');
        $dariTanggal = Carbon::parse($request->input('dari_tanggal'))->toDateTimeString();
        $sampaiTanggal = Carbon::parse($request->input('sampai_tanggal'))->endOfDay()->toDateTimeString();

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanPasienGrouping(?, ?, ?, ?, ?, ?, ?)', [$dariTanggal, $sampaiTanggal, 0, $ruangan, 3, 0, 0]);

        // Filter data seperti sebelumnya
        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->TGLMASUK) && $row->TGLMASUK !== '') ||
                (!is_null($row->TGLKELUAR) && $row->TGLKELUAR !== '') ||
                (!is_null($row->NOMOR) && $row->NOMOR !== '') ||
                (!is_null($row->NORM) && $row->NORM !== '') ||
                (!is_null($row->NOMORKARTU) && $row->NOMORKARTU !== '') ||
                (!is_null($row->NOSEP) && $row->NOSEP !== '') ||
                (!is_null($row->NAMAPASIEN) && $row->NAMAPASIEN !== '') ||
                (!is_null($row->LOS) && $row->LOS !== 0) ||
                (!is_null($row->TARIFRS) && $row->TARIFRS !== 0) ||
                (!is_null($row->DPJP) && $row->TARIFRS !== 0) ||
                (!is_null($row->UNITPELAYANAN) && $row->UNITPELAYANAN !== '')
            );
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($a->TGLMASUK, $b->TGLMASUK);
        });

        return inertia("Laporan/PasienBelumGroup/Print", [
            'data' => $filteredData,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}