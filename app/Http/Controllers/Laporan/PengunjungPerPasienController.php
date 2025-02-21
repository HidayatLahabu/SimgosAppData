<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterReferensiModel;

class PengunjungPerPasienController extends Controller
{
    public function index()
    {
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        $query = DB::connection('mysql2')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select([
                'pendaftaran.NOMOR as NOPEN',
                'pasien.NORM as NORM',
                DB::raw('master.getNamaLengkap(pasien.NORM) as NAMA_LENGKAP'),
                DB::raw("DATE_FORMAT(pendaftaran.TANGGAL, '%d-%m-%Y %H:%i:%s') as TGLREG"),
                DB::raw("DATE_FORMAT(kunjungan.MASUK, '%d-%m-%Y %H:%i:%s') as TGLMASUK"),
                'referensi.DESKRIPSI as CARABAYAR',
                DB::raw('master.getNamaLengkapPegawai(dokter.NIP) as DOKTER_REG'),
                'ruangan.DESKRIPSI as UNITPELAYANAN',
                DB::raw("IF(DATE_FORMAT(pasien.TANGGAL, '%d-%m-%Y') = DATE_FORMAT(pendaftaran.TANGGAL, '%d-%m-%Y'), 'Baru', 'Lama') as STATUSPENGUNJUNG"),
            ])
            ->join('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->join('pendaftaran.tujuan_pasien as tujuanPasien', 'pendaftaran.NOMOR', '=', 'tujuanPasien.NOPEN')
            ->join('pendaftaran.kunjungan as kunjungan', function ($join) {
                $join->on('pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
                    ->on('tujuanPasien.RUANGAN', '=', 'kunjungan.RUANGAN');
            })
            ->leftJoin('pendaftaran.penjamin as penjamin', 'pendaftaran.NOMOR', '=', 'penjamin.NOPEN')
            ->leftJoin('master.referensi as referensi', function ($join) {
                $join->on('penjamin.JENIS', '=', 'referensi.ID')
                    ->where('referensi.JENIS', '=', 10);
            })
            ->leftJoin('master.dokter as dokter', 'tujuanPasien.DOKTER', '=', 'dokter.ID')
            ->leftJoin('master.ruangan as ruangan', 'tujuanPasien.RUANGAN', '=', 'ruangan.ID')
            ->whereDate('pendaftaran.TANGGAL', '=', DB::raw('CURRENT_DATE'))
            ->whereIn('pendaftaran.STATUS', [1, 2])
            ->groupBy([
                'pendaftaran.NOMOR',
                'pasien.NORM',
                'kunjungan.MASUK',
                'kunjungan.KELUAR',
                'referensi.DESKRIPSI',
                'dokter.NIP',
                'ruangan.DESKRIPSI',
                'pendaftaran.TANGGAL',
            ]);

        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(master.getNamaLengkap(pasien.NORM)) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(ruangan.DESKRIPSI) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pendaftaran.NORM) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(master.getNamaLengkapPegawai(dokter.NIP)) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(IF(DATE_FORMAT(pasien.TANGGAL, "%d-%m-%Y") = DATE_FORMAT(pendaftaran.TANGGAL, "%d-%m-%Y"), "Baru", "Lama")) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(referensi.DESKRIPSI) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        $data = $query->orderByDesc('pendaftaran.TANGGAL')->paginate(5)->appends(request()->query());
        $dataArray = $data->toArray();

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

        return inertia('Laporan/PengunjungPerPasien/Index', [
            'ruangan' => $ruangan,
            'caraBayar' => $caraBayar,
            'dokter' =>  $dokter,
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
            'dokter' => 'nullable|integer',
        ]);

        // Ambil nilai input
        $ruangan  = $request->input('ruangan');
        $caraBayar = $request->input('caraBayar');
        $dokter = $request->input('dokter');
        $dariTanggal = Carbon::parse($request->input('dari_tanggal'))->toDateTimeString();
        $sampaiTanggal = Carbon::parse($request->input('sampai_tanggal'))->endOfDay()->toDateTimeString();

        $query = DB::connection('mysql2')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select([
                'pendaftaran.NOMOR as NOPEN',
                'pasien.NORM as NORM',
                DB::raw('master.getNamaLengkap(pasien.NORM) as NAMA_LENGKAP'),
                DB::raw("DATE_FORMAT(pendaftaran.TANGGAL, '%d-%m-%Y %H:%i:%s') as TGLREG"),
                DB::raw("DATE_FORMAT(kunjungan.MASUK, '%d-%m-%Y %H:%i:%s') as TGLMASUK"),
                'referensi.DESKRIPSI as CARABAYAR',
                DB::raw('master.getNamaLengkapPegawai(dokter.NIP) as DOKTER_REG'),
                'ruangan.DESKRIPSI as UNITPELAYANAN',
                DB::raw("IF(DATE_FORMAT(pasien.TANGGAL, '%d-%m-%Y') = DATE_FORMAT(pendaftaran.TANGGAL, '%d-%m-%Y'), 'Baru', 'Lama') as STATUSPENGUNJUNG"),
            ])
            ->join('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->join('pendaftaran.tujuan_pasien as tujuanPasien', 'pendaftaran.NOMOR', '=', 'tujuanPasien.NOPEN')
            ->join('pendaftaran.kunjungan as kunjungan', function ($join) {
                $join->on('pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
                    ->on('tujuanPasien.RUANGAN', '=', 'kunjungan.RUANGAN');
            })
            ->leftJoin('pendaftaran.penjamin as penjamin', 'pendaftaran.NOMOR', '=', 'penjamin.NOPEN')
            ->leftJoin('master.referensi as referensi', function ($join) {
                $join->on('penjamin.JENIS', '=', 'referensi.ID')
                    ->where('referensi.JENIS', '=', 10);
            })
            ->leftJoin('master.dokter as dokter', 'tujuanPasien.DOKTER', '=', 'dokter.ID')
            ->leftJoin('master.ruangan as ruangan', 'tujuanPasien.RUANGAN', '=', 'ruangan.ID')
            ->whereIn('pendaftaran.STATUS', [1, 2])
            ->groupBy([
                'pendaftaran.NOMOR',
                'pasien.NORM',
                'kunjungan.MASUK',
                'kunjungan.KELUAR',
                'referensi.DESKRIPSI',
                'dokter.NIP',
                'ruangan.DESKRIPSI',
                'pendaftaran.TANGGAL',
            ]);

        if ($ruangan) {
            $query->where('tujuanPasien.RUANGAN', 'LIKE', $ruangan);
        }

        if ($caraBayar) {
            $query->where('penjamin.JENIS', '=', $caraBayar);
        }

        if ($dokter) {
            $query->where('tujuanPasien.DOKTER', '=', $dokter);
        }

        // Filter berdasarkan tanggal
        $data = $query
            ->whereBetween('pendaftaran.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->orderBy('pendaftaran.TANGGAL')
            ->get();

        return inertia("Laporan/PengunjungPerPasien/Print", [
            'data' => $data,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}