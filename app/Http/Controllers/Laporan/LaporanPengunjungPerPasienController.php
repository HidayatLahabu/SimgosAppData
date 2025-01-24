<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterReferensiModel;

class LaporanPengunjungPerPasienController extends Controller
{
    public function index(Request $request)
    {
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        $query = DB::connection('mysql2')->table('pendaftaran.pendaftaran as pd')
            ->select([
                'pd.NOMOR as NOPEN',
                'p.NORM as NORM',
                DB::raw('master.getNamaLengkap(p.NORM) as NAMA_LENGKAP'),
                DB::raw("DATE_FORMAT(tk.MASUK, '%d-%m-%Y %H:%i:%s') as TGLTERIMA"),
                DB::raw("DATE_FORMAT(tk.KELUAR, '%d-%m-%Y %H:%i:%s') as TGLKELUAR"),
                'ref.DESKRIPSI as CARABAYAR',
                DB::raw('master.getNamaLengkapPegawai(dok.NIP) as DOKTER_REG'),
                'r.DESKRIPSI as UNITPELAYANAN',
                DB::raw("IF(DATE_FORMAT(p.TANGGAL, '%d-%m-%Y') = DATE_FORMAT(tk.MASUK, '%d-%m-%Y'), 'Baru', 'Lama') as STATUSPENGUNJUNG"),
            ])
            ->join('master.pasien as p', 'p.NORM', '=', 'pd.NORM')
            ->join('pendaftaran.tujuan_pasien as tp', 'pd.NOMOR', '=', 'tp.NOPEN')
            ->join('pendaftaran.kunjungan as tk', function ($join) {
                $join->on('pd.NOMOR', '=', 'tk.NOPEN')
                    ->on('tp.RUANGAN', '=', 'tk.RUANGAN');
            })
            ->leftJoin('pendaftaran.penjamin as pj', 'pd.NOMOR', '=', 'pj.NOPEN')
            ->leftJoin('master.referensi as ref', function ($join) {
                $join->on('pj.JENIS', '=', 'ref.ID')
                    ->where('ref.JENIS', '=', 10);
            })
            ->leftJoin('master.dokter as dok', 'tp.DOKTER', '=', 'dok.ID')
            ->leftJoin('master.ruangan as r', 'tp.RUANGAN', '=', 'r.ID')
            ->whereDate('tk.MASUK', '=', DB::raw('CURRENT_DATE'))
            ->whereIn('pd.STATUS', [1, 2])
            ->whereIn('tk.STATUS', [1, 2])
            ->groupBy([
                'pd.NOMOR',
                'p.NORM',
                'tk.MASUK',
                'tk.KELUAR',
                'ref.DESKRIPSI',
                'dok.NIP',
                'r.DESKRIPSI',
                'p.TANGGAL',
            ]);

        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(master.getNamaLengkap(p.NORM)) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(r.DESKRIPSI) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(p.NORM) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(master.getNamaLengkapPegawai(dok.NIP)) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(IF(DATE_FORMAT(p.TANGGAL, "%d-%m-%Y") = DATE_FORMAT(tk.MASUK, "%d-%m-%Y"), "Baru", "Lama")) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(ref.DESKRIPSI) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        $data = $query->orderByDesc('tk.MASUK')->paginate(5)->appends(request()->query());
        $dataArray = $data->toArray();

        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->whereIn('JENIS_KUNJUNGAN', [1])
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        $caraBayar = MasterReferensiModel::where('JENIS', 10)
            ->where('STATUS', 1)
            ->orderBy('ID')
            ->get();

        return inertia('Laporan/PengunjungPerPasien/Index', [
            'ruangan' => $ruangan,
            'caraBayar' => $caraBayar,
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all(),
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
        $ruangan  = $request->input('ruangan');
        $caraBayar = $request->input('caraBayar');
        $dariTanggal    = $request->input('dari_tanggal');
        $sampaiTanggal  = $request->input('sampai_tanggal');
        $dariTanggal = Carbon::parse($dariTanggal)->format('Y-m-d H:i:s');
        $sampaiTanggal = Carbon::parse($sampaiTanggal)->endOfDay()->format('Y-m-d H:i:s');

        $query = DB::connection('mysql2')->table('pendaftaran.pendaftaran as pd')
            ->select([
                'pd.NOMOR as NOPEN',
                'p.NORM as NORM',
                DB::raw('master.getNamaLengkap(p.NORM) as NAMA_LENGKAP'),
                DB::raw("DATE_FORMAT(tk.MASUK, '%d-%m-%Y %H:%i:%s') as TGLTERIMA"),
                DB::raw("DATE_FORMAT(tk.KELUAR, '%d-%m-%Y %H:%i:%s') as TGLKELUAR"),
                'ref.DESKRIPSI as CARABAYAR',
                DB::raw('master.getNamaLengkapPegawai(dok.NIP) as DOKTER_REG'),
                'r.DESKRIPSI as UNITPELAYANAN',
                DB::raw("IF(DATE_FORMAT(p.TANGGAL, '%d-%m-%Y') = DATE_FORMAT(tk.MASUK, '%d-%m-%Y'), 'Baru', 'Lama') as STATUSPENGUNJUNG"),
            ])
            ->join('master.pasien as p', 'p.NORM', '=', 'pd.NORM')
            ->join('pendaftaran.tujuan_pasien as tp', 'pd.NOMOR', '=', 'tp.NOPEN')
            ->join('pendaftaran.kunjungan as tk', function ($join) {
                $join->on('pd.NOMOR', '=', 'tk.NOPEN')
                    ->on('tp.RUANGAN', '=', 'tk.RUANGAN');
            })
            ->leftJoin('pendaftaran.penjamin as pj', 'pd.NOMOR', '=', 'pj.NOPEN')
            ->leftJoin('master.referensi as ref', function ($join) {
                $join->on('pj.JENIS', '=', 'ref.ID')
                    ->where('ref.JENIS', '=', 10);
            })
            ->leftJoin('master.dokter as dok', 'tp.DOKTER', '=', 'dok.ID')
            ->leftJoin('master.ruangan as r', 'tp.RUANGAN', '=', 'r.ID')
            ->whereIn('pd.STATUS', [1, 2])
            ->whereIn('tk.STATUS', [1, 2])
            ->groupBy([
                'pd.NOMOR',
                'p.NORM',
                'tk.MASUK',
                'tk.KELUAR',
                'ref.DESKRIPSI',
                'dok.NIP',
                'r.DESKRIPSI',
                'p.TANGGAL',
            ]);

        // Apply 'cara_bayar' filter if provided
        if ($ruangan) {
            $query->where('tp.RUANGAN', 'LIKE', $ruangan);
        }

        $cara_bayar = 0;
        // Apply 'cara_bayar' filter if provided
        if ($cara_bayar != 0) {
            $query->where('pj.JENIS', '=', $caraBayar);
        }

        // Filter berdasarkan tanggal
        $data = $query
            ->whereBetween('tk.MASUK', [$dariTanggal, $sampaiTanggal])
            ->get();

        return inertia("Laporan/PengunjungPerPasien/Print", [
            'data' => $data,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}