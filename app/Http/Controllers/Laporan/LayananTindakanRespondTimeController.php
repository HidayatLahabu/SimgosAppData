<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class LayananTindakanRespondTimeController extends Controller
{
    public function index()
    {
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();
        $tgl_akhir = Carbon::now()->toDateString();
        $search = request('search') ? strtolower(request('search')) : null;

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanResponTime(?, ?, ?, ?, ?, ?)', [$tgl_awal, $tgl_akhir, '%', 0, 0, 0]);

        // // Filter data seperti sebelumnya
        // $filteredData = array_values(array_filter($data, function ($row) {
        //     return (
        //         (!is_null($row->NORM) && $row->NORM !== '') ||
        //         (!is_null($row->NAMALENGKAP) && $row->NAMALENGKAP !== '') ||
        //         (!is_null($row->RUANGAN) && $row->RUANGAN !== '') ||
        //         (!is_null($row->NAMATINDAKAN) && $row->NAMATINDAKAN !== '') ||
        //         (!is_null($row->TGL_ORDER) && $row->TGL_ORDER !== '') ||
        //         (!is_null($row->TGL_TERIMA) && $row->TGL_TERIMA !== '') ||
        //         (!is_null($row->TGL_HASIL) && $row->TGL_HASIL !== '') ||
        //         (!is_null($row->SELISIH1) && $row->SELISIH1 !== '') ||
        //         (!is_null($row->SELISIH2) && $row->SELISIH2 !== '') ||
        //         (!is_null($row->SELISIH3) && $row->SELISIH3 !== '')
        //     );
        // }));

        // Filter data sesuai dengan kondisi pencarian dan tetap mempertahankan data yang tidak kosong
        $filteredData = array_values(array_filter($data, function ($row) use ($search) {
            $hasValue = (
                (!is_null($row->NORM) && $row->NORM !== '') ||
                (!is_null($row->NAMALENGKAP) && $row->NAMALENGKAP !== '') ||
                (!is_null($row->RUANGAN) && $row->RUANGAN !== '') ||
                (!is_null($row->NAMATINDAKAN) && $row->NAMATINDAKAN !== '') ||
                (!is_null($row->TGL_ORDER) && $row->TGL_ORDER !== '') ||
                (!is_null($row->TGL_TERIMA) && $row->TGL_TERIMA !== '') ||
                (!is_null($row->TGL_HASIL) && $row->TGL_HASIL !== '') ||
                (!is_null($row->SELISIH1) && $row->SELISIH1 !== '') ||
                (!is_null($row->SELISIH2) && $row->SELISIH2 !== '') ||
                (!is_null($row->SELISIH3) && $row->SELISIH3 !== '')
            );

            if (!$search) {
                return $hasValue;
            }

            $matchesSearch = (
                (isset($row->NORM) && stripos($row->NORM, $search) !== false) ||
                (isset($row->NAMALENGKAP) && stripos($row->NAMALENGKAP, $search) !== false) ||
                (isset($row->RUANGAN) && stripos($row->RUANGAN, $search) !== false) ||
                (isset($row->NAMATINDAKAN) && stripos($row->NAMATINDAKAN, $search) !== false)
            );

            return $matchesSearch && $hasValue;
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($b->TGL_ORDER, $a->TGL_ORDER);
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

        return inertia("Laporan/TindakanRespondTime/Index", [
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
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanResponTime(?, ?, ?, ?, ?, ?)', [$dariTanggal, $sampaiTanggal, $ruangan, 0, 0, 0]);

        // Filter data seperti sebelumnya
        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->NORM) && $row->NORM !== '') ||
                (!is_null($row->NAMALENGKAP) && $row->NAMALENGKAP !== '') ||
                (!is_null($row->RUANGAN) && $row->RUANGAN !== '') ||
                (!is_null($row->NAMATINDAKAN) && $row->NAMATINDAKAN !== '') ||
                (!is_null($row->TGL_ORDER) && $row->TGL_ORDER !== '') ||
                (!is_null($row->TGL_TERIMA) && $row->TGL_TERIMA !== '') ||
                (!is_null($row->TGL_HASIL) && $row->TGL_HASIL !== '') ||
                (!is_null($row->SELISIH1) && $row->SELISIH1 !== '') ||
                (!is_null($row->SELISIH2) && $row->SELISIH2 !== '') ||
                (!is_null($row->SELISIH3) && $row->SELISIH3 !== '')
            );
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($a->TGL_ORDER, $b->TGL_ORDER);
        });


        return inertia("Laporan/TindakanRespondTime/Print", [
            'data' => $filteredData,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}