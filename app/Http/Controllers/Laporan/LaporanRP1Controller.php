<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class LaporanRP1Controller extends Controller
{
    public function index()
    {
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();
        $tgl_akhir = Carbon::now()->toDateString();

        // Panggil prosedur yang telah dibuat
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRP1(?, ?, ?, ?, ?, ?)', [$tgl_awal, $tgl_akhir, 0, '%', 3, 0]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->TANGGAL) && $row->TANGGAL !== '') ||
                ($row->AWAL ?? 0) > 0 ||
                ($row->MASUK ?? 0) > 0 ||
                ($row->PINDAHAN ?? 0) > 0 ||
                ($row->DIPINDAHKAN ?? 0) > 0 ||
                ($row->HIDUP ?? 0) > 0 ||
                ($row->MATIKURANG48 ?? 0) > 0 ||
                ($row->MATILEBIH48 ?? 0) > 0 ||
                ($row->SISA ?? 0) > 0 ||
                ($row->LD ?? 0) > 0 ||
                ($row->HP ?? 0) > 0 ||
                ($row->PAV ?? 0) > 0 ||
                ($row->VVIP ?? 0) > 0 ||
                ($row->VIP ?? 0) > 0 ||
                ($row->KLSI ?? 0) > 0 ||
                ($row->KLSII ?? 0) > 0 ||
                ($row->KLSIII ?? 0) > 0 ||
                ($row->KLSKHUSUS ?? 0) > 0
            );
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($b->TANGGAL, $a->TANGGAL);
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

        return inertia("Laporan/LaporanRP1/Index", [
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
        $data = DB::connection('mysql10')->select('CALL laporan.LaporanRP1(?, ?, ?, ?, ?, ?)', [$dariTanggal, $sampaiTanggal, 0, $ruangan, 3, 0]);

        $filteredData = array_values(array_filter($data, function ($row) {
            return (
                (!is_null($row->TANGGAL) && $row->TANGGAL !== '') ||
                ($row->AWAL ?? 0) > 0 ||
                ($row->MASUK ?? 0) > 0 ||
                ($row->PINDAHAN ?? 0) > 0 ||
                ($row->DIPINDAHKAN ?? 0) > 0 ||
                ($row->HIDUP ?? 0) > 0 ||
                ($row->MATIKURANG48 ?? 0) > 0 ||
                ($row->MATILEBIH48 ?? 0) > 0 ||
                ($row->LD ?? 0) > 0 ||
                ($row->HP ?? 0) > 0 ||
                ($row->PAV ?? 0) > 0 ||
                ($row->VVIP ?? 0) > 0 ||
                ($row->VIP ?? 0) > 0 ||
                ($row->KLSI ?? 0) > 0 ||
                ($row->KLSII ?? 0) > 0 ||
                ($row->KLSIII ?? 0) > 0 ||
                ($row->KLSKHUSUS ?? 0) > 0
            );
        }));

        // Urutkan hasil berdasarkan LAYANAN secara ascending
        usort($filteredData, function ($a, $b) {
            return strcmp($a->TANGGAL, $b->TANGGAL);
        });

        // Fetch ruangan description only if ruangan is provided
        if ($ruangan) {
            $ruangan = MasterRuanganModel::where('ID', $ruangan)->value('DESKRIPSI');
        }

        return inertia("Laporan/LaporanRP1/Print", [
            'data' => $filteredData,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
            'ruangan' => $ruangan,
        ]);
    }
}