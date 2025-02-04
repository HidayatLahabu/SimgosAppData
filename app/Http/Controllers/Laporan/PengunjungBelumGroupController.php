<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterReferensiModel;
use Illuminate\Pagination\LengthAwarePaginator;

class PengunjungBelumGroupController extends Controller
{

    public function index()
    {
        $TGLAWAL = Carbon::now()->startOfMonth()->toDateString();
        $TGLAKHIR = Carbon::now()->toDateString();
        $RUANGAN = '%';
        $LAPORAN = 7;
        $CARABAYAR = 0;

        $data = DB::connection('mysql10')->select(
            'CALL laporan.LaporanPengunjungBelumGrouping(?, ?, ?, ?, ?)',
            [$TGLAWAL, $TGLAKHIR, $RUANGAN, $LAPORAN, $CARABAYAR]
        );

        $dataCollection = collect($data);

        $perPage = 5;
        $currentPage = request()->get('page', 1);
        $currentItems = $dataCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        // Buat paginator manual
        $paginatedData = new LengthAwarePaginator(
            $currentItems,
            $dataCollection->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Ambil data referensi
        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        $caraBayar = MasterReferensiModel::where('JENIS', 10)
            ->where('STATUS', 1)
            ->orderBy('ID')
            ->get();

        $dokter = DB::connection('mysql2')->table('master.dokter as dokter')
            ->select([
                'dokter.ID',
                'dokter.NIP',
                DB::raw('CONCAT(
                    IFNULL(pegawai.GELAR_DEPAN, ""), " ",
                    pegawai.NAMA, " ",
                    IFNULL(pegawai.GELAR_BELAKANG, "")
                ) as DOKTER'),
                'ruangan.DESKRIPSI as RUANGAN',
            ])
            ->leftJoin('master.pegawai as pegawai', 'dokter.NIP', '=', 'pegawai.NIP')
            ->leftJoin('master.dokter_ruangan as dpjpRuangan', 'dokter.ID', '=', 'dpjpRuangan.DOKTER')
            ->leftJoin('master.ruangan as ruangan', 'dpjpRuangan.RUANGAN', '=', 'ruangan.ID')
            ->where('dokter.STATUS', 1)
            ->whereNotNull('pegawai.NAMA')
            ->where('ruangan.JENIS_KUNJUNGAN', 1)
            ->where('ruangan.STATUS', 1)
            ->where('ruangan.JENIS', 5)
            ->where('ruangan.DESKRIPSI', 'NOT LIKE', '%Umum%')
            ->orderBy('pegawai.NAMA')
            ->get();

        return inertia("Laporan/PasienBelumGroup/Index", [
            'dataTable' => $paginatedData,
            'ruangan' => $ruangan,
            'caraBayar' => $caraBayar,
            'dokter' =>  $dokter,
            'tglAwal' => $TGLAWAL,
            'tglAkhir' => $TGLAKHIR,
        ]);
    }

    public function print(Request $request)
    {
        $request->validate([
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
            'ruangan'  => 'nullable|integer',
            'caraBayar' => 'nullable|integer',
        ]);

        // Ambil nilai input
        $dariTanggal    = $request->input('dari_tanggal');
        $sampaiTanggal  = $request->input('sampai_tanggal');
        $ruangan = $request->input('ruangan') ?: '%';
        $caraBayar = $request->input('caraBayar') ?: 0;
        $laporan = 7;

        $data = DB::connection('mysql10')->select(
            'CALL laporan.LaporanPengunjungBelumGrouping(?, ?, ?, ?, ?)',
            [$dariTanggal, $sampaiTanggal, $ruangan, $laporan, $caraBayar]
        );


        return inertia("Laporan/PasienBelumGroup/Print", [
            'items' => $data,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}