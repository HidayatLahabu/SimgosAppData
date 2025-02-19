<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterReferensiModel;

class LayananTindakanPasienController extends Controller
{
    public function index()
    {

        // Tanggal awal tahun
        $tgl_awal = Carbon::now()->startOfYear()->format('Y-m-d 00:00:00');

        // Tanggal hari ini
        $tgl_akhir = Carbon::now()->endOfDay()->format('Y-m-d 23:59:59');

        $searchSubject = request('search') ? strtolower(request('search')) : null;

        $query = DB::connection('mysql7')->table('layanan.tindakan_medis as tindakanMedis')
            ->select([
                'kunjungan.NOMOR as KUNJUNGAN',
                'pendaftaran.NORM as NORM',
                DB::raw('master.getNamaLengkap(pasien.NORM) as NAMA'),
                DB::raw("DATE_FORMAT(kunjungan.MASUK, '%d-%m-%Y %H:%i:%s') as TGLMASUK"),
                DB::raw("DATE_FORMAT(tindakanMedis.TANGGAL, '%d-%m-%Y %H:%i:%s') as TGLTINDAKAN"),
                'ruanganKunjungan.DESKRIPSI as RUANGAN',
                'caraBayar.DESKRIPSI as CARABAYAR',
                'tindakan.NAMA as TINDAKAN',
                DB::raw('master.getNamaLengkapPegawai(pegawai.NIP) as DOKTER')
            ])
            ->leftJoin('master.tindakan as tindakan', 'tindakanMedis.TINDAKAN', '=', 'tindakan.ID')
            ->leftJoin('layanan.petugas_tindakan_medis as petugas', function ($join) {
                $join->on('tindakanMedis.ID', '=', 'petugas.TINDAKAN_MEDIS')
                    ->where('petugas.JENIS', '=', 1)
                    ->where('petugas.KE', '=', 1);
            })
            ->leftJoin('master.dokter as dokter', 'petugas.MEDIS', '=', 'dokter.ID')
            ->leftJoin('master.pegawai as pegawai', 'dokter.NIP', '=', 'pegawai.NIP')
            ->join('pendaftaran.kunjungan as kunjungan', 'tindakanMedis.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->join('pendaftaran.pendaftaran as pendaftaran', 'kunjungan.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('pendaftaran.penjamin as penjamin', 'pendaftaran.NOMOR', '=', 'penjamin.NOPEN')
            ->leftJoin('master.referensi as caraBayar', function ($join) {
                $join->on('penjamin.JENIS', '=', 'caraBayar.ID')
                    ->where('caraBayar.JENIS', '=', 10);
            })
            ->leftJoin('master.ruangan as ruanganKunjungan', function ($join) {
                $join->on('kunjungan.RUANGAN', '=', 'ruanganKunjungan.ID')
                    ->where('ruanganKunjungan.JENIS', '=', 5);
            })
            ->leftJoin('master.referensi as jenisKunjungan', function ($join) {
                $join->on('ruanganKunjungan.JENIS_KUNJUNGAN', '=', 'jenisKunjungan.ID')
                    ->where('jenisKunjungan.JENIS', '=', 15);
            })
            ->join('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->whereIn('tindakanMedis.STATUS', [1, 2])
            ->whereBetween('tindakanMedis.TANGGAL', [$tgl_awal, $tgl_akhir])
            ->whereNotNull('kunjungan.RUANGAN')
            ->whereNotNull('petugas.MEDIS');

        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(ruanganKunjungan.DESKRIPSI) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(tindakan.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(master.getNamaLengkapPegawai(dokter.NIP)) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pendaftaran.NORM) LIKE ?', ['%' . $searchSubject . '%']);;
            });
        }

        $data = $query->orderByDesc('tindakanMedis.TANGGAL')
            ->paginate(5)
            ->appends(request()->query());

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
                DB::raw('master.getNamaLengkapPegawai(dokter.NIP) as DOKTER')
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
            ->get();

        return inertia('Laporan/TindakanPasien/Index', [
            'ruangan' => $ruangan,
            'caraBayar' => $caraBayar,
            'dokter' =>  $dokter,
            'tglAwal' => $tgl_awal,
            'tglAkhir' => $tgl_akhir,
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

        $query = DB::connection('mysql7')->table('layanan.tindakan_medis as tindakanMedis')
            ->select([
                'tindakanMedis.ID as KUNJUNGAN',
                'pendaftaran.NORM as NORM',
                DB::raw('master.getNamaLengkap(pasien.NORM) as NAMA'),
                DB::raw("DATE_FORMAT(kunjungan.MASUK, '%d-%m-%Y %H:%i:%s') as TGLMASUK"),
                DB::raw("DATE_FORMAT(tindakanMedis.TANGGAL, '%d-%m-%Y %H:%i:%s') as TGLTINDAKAN"),
                'ruanganKunjungan.DESKRIPSI as RUANGAN',
                'caraBayar.DESKRIPSI as CARABAYAR',
                'tindakan.NAMA as TINDAKAN',
                DB::raw('master.getNamaLengkapPegawai(pegawai.NIP) as DOKTER')
            ])
            ->leftJoin('master.tindakan as tindakan', 'tindakanMedis.TINDAKAN', '=', 'tindakan.ID')
            ->leftJoin('layanan.petugas_tindakan_medis as petugas', function ($join) {
                $join->on('tindakanMedis.ID', '=', 'petugas.TINDAKAN_MEDIS')
                    ->where('petugas.JENIS', '=', 1)
                    ->where('petugas.KE', '=', 1);
            })
            ->leftJoin('master.dokter as dokter', 'petugas.MEDIS', '=', 'dokter.ID')
            ->leftJoin('master.pegawai as pegawai', 'dokter.NIP', '=', 'pegawai.NIP')
            ->join('pendaftaran.kunjungan as kunjungan', 'tindakanMedis.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->join('pendaftaran.pendaftaran as pendaftaran', 'kunjungan.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('pendaftaran.penjamin as penjamin', 'pendaftaran.NOMOR', '=', 'penjamin.NOPEN')
            ->leftJoin('master.referensi as caraBayar', function ($join) {
                $join->on('penjamin.JENIS', '=', 'caraBayar.ID')
                    ->where('caraBayar.JENIS', '=', 10);
            })
            ->leftJoin('master.ruangan as ruanganKunjungan', function ($join) {
                $join->on('kunjungan.RUANGAN', '=', 'ruanganKunjungan.ID')
                    ->where('ruanganKunjungan.JENIS', '=', 5);
            })
            ->leftJoin('master.referensi as jenisKunjungan', function ($join) {
                $join->on('ruanganKunjungan.JENIS_KUNJUNGAN', '=', 'jenisKunjungan.ID')
                    ->where('jenisKunjungan.JENIS', '=', 15);
            })
            ->join('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->where('tindakanMedis.STATUS', 1)
            ->whereNotNull('kunjungan.RUANGAN')
            ->whereNotNull('petugas.MEDIS')
            ->groupBy('tindakanMedis.ID', 'caraBayar.DESKRIPSI', 'pegawai.NIP');

        if ($ruangan) {
            $query->where('kunjungan.RUANGAN', 'LIKE', $ruangan);
        }

        if ($caraBayar) {
            $query->where('penjamin.JENIS', '=', $caraBayar);
        }

        // Filter berdasarkan tanggal
        $data = $query
            ->whereBetween('tindakanMedis.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->orderByDesc('pendaftaran.TANGGAL')
            ->get();

        return inertia("Laporan/TindakanPasien/Print", [
            'data' => $data,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}