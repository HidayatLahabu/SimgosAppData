<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterReferensiModel;
use Illuminate\Pagination\LengthAwarePaginator;

class RespondTimeIgdController extends Controller
{
    public function index()
    {
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();
        $tgl_akhir = Carbon::now()->toDateString();
        $search = request('search') ? strtolower(request('search')) : null;

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanResponTimeIGD(?, ?, ?, ?, ?, ?)', [$tgl_awal, $tgl_akhir, 0, '%', 3, 0]);

        // Filter data sesuai dengan kondisi pencarian dan tetap mempertahankan data yang tidak kosong
        $filteredData = array_values(array_filter($data, function ($row) use ($search) {
            $hasValue = (
                (!is_null($row->NORM) && $row->NORM !== '') ||
                (!is_null($row->NAMALENGKAP) && $row->NAMALENGKAP !== '') ||
                (!is_null($row->RUANGAN_TRIASE) && $row->RUANGAN_TRIASE !== '') ||
                (!is_null($row->TGL_REK_TRIASE) && $row->TGL_REK_TRIASE !== '') ||
                (!is_null($row->TGL_KELUAR_IRD) && $row->TGL_KELUAR_IRD !== '') ||
                (!is_null($row->SELISIH6) && $row->SELISIH6 !== '') ||
                (!is_null($row->RUANGAN_IRNA) && $row->RUANGAN_IRNA !== '') ||
                (!is_null($row->TGL_REK_IRNA) && $row->TGL_REG_IRNA !== '') ||
                (!is_null($row->TGL_TERIMA_RUANGAN) && $row->TGL_TERIMA_RUANGAN !== '') ||
                (!is_null($row->SELISIH2) && $row->SELISIH2 !== 0)
            );

            if (!$search) {
                return $hasValue;
            }

            $matchesSearch = (
                (isset($row->NORM) && stripos($row->NORM, $search) !== false) ||
                (isset($row->NAMALENGKAP) && stripos($row->NAMALENGKAP, $search) !== false)
            );

            return $matchesSearch && $hasValue;
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($b->TGL_REG_TRIASE, $a->TGL_REG_TRIASE);
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

        return inertia("Laporan/RespondTimeIgd/Index", [
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
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanResponTimeIGD(?, ?, ?, ?, ?, ?)', [$dariTanggal, $sampaiTanggal, 0, $ruangan, 3, $caraBayar]);

        // Filter data seperti sebelumnya
        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->NORM) && $row->NORM !== '') ||
                (!is_null($row->NAMALENGKAP) && $row->NAMALENGKAP !== '') ||
                (!is_null($row->RUANGAN_TRIASE) && $row->RUANGAN_TRIASE !== '') ||
                (!is_null($row->TGL_REK_TRIASE) && $row->TGL_REK_TRIASE !== '') ||
                (!is_null($row->TGL_KELUAR_IRD) && $row->TGL_KELUAR_IRD !== '') ||
                (!is_null($row->SELISIH6) && $row->SELISIH6 !== '') ||
                (!is_null($row->RUANGAN_IRNA) && $row->RUANGAN_IRNA !== '') ||
                (!is_null($row->TGL_REK_IRNA) && $row->TGL_REG_IRNA !== '') ||
                (!is_null($row->TGL_TERIMA_RUANGAN) && $row->TGL_TERIMA_RUANGAN !== '') ||
                (!is_null($row->SELISIH2) && $row->SELISIH2 !== 0)
            );
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($a->TGL_REG_TRIASE, $b->TGL_REG_TRIASE);
        });

        return inertia("Laporan/RespondTimeIgd/Print", [
            'data' => $filteredData,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}