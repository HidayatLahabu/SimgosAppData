<?php

namespace App\Http\Controllers\Layanan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PulangController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql7')->table('layanan.pasien_pulang as pulang')
            ->select(
                'pulang.ID as id',
                'pulang.KUNJUNGAN as kunjungan',
                'pulang.TANGGAL as tanggal',
                'pegawai.NAMA as dokter',
                'pegawai.GELAR_DEPAN as gelarDepan',
                'pegawai.GELAR_BELAKANG as gelarBelakang',
                'pasien.NORM as norm',
                'pasien.NAMA as nama',
                'refCara.DESKRIPSI as cara',
                'refKeadaan.DESKRIPSI as keadaan'
            )
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'pulang.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'pulang.DOKTER')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('bpjs.peserta as peserta', 'pasien.NORM', '=', 'peserta.norm')
            ->leftJoin('master.referensi as refCara', function ($join) {
                $join->on('refCara.ID', '=', 'pulang.CARA')
                    ->where('refCara.JENIS', '=', 45);
            })
            ->leftJoin('master.referensi as refKeadaan', function ($join) {
                $join->on('refKeadaan.ID', '=', 'pulang.KEADAAN')
                    ->where('refKeadaan.JENIS', '=', 46);
            });

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pulang.KUNJUNGAN) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('pulang.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Layanan/Pulang/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function detail($id)
    {
        // Fetch data utama (main lab order details)
        $queryDetail = DB::connection('mysql7')->table('layanan.pasien_pulang as pulang')
            ->select(
                'pulang.ID',
                'pulang.KUNJUNGAN as NOMOR_KUNJUNGAN',
                'pulang.NOPEN as NOMOR_PENDAFTARAN',
                'pulang.TANGGAL as TANGGAL',
                'pasien.NORM',
                'pasien.NAMA as NAMA_PASIEN',
                'refCara.DESKRIPSI as CARA',
                'refKeadaan.DESKRIPSI as KEADAAN',
                'pulang.DIAGNOSA as DIAGNOSA',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as DOKTER'),
                'pengguna.NAMA as OLEH',
                'pulang.STATUS as STATUS',
            )
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'pulang.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'pulang.DOKTER')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'pulang.OLEH')
            ->leftJoin('master.referensi as refCara', function ($join) {
                $join->on('refCara.ID', '=', 'pulang.CARA')
                    ->where('refCara.JENIS', '=', 45);
            })
            ->leftJoin('master.referensi as refKeadaan', function ($join) {
                $join->on('refKeadaan.ID', '=', 'pulang.KEADAAN')
                    ->where('refKeadaan.JENIS', '=', 46);
            })
            ->where('pulang.ID', $id)
            ->first();

        $kunjungan = $queryDetail->NOMOR_KUNJUNGAN;

        $queryMeninggal = DB::connection('mysql7')->table('layanan.pasien_meninggal as meninggal')
            ->select(
                'meninggal.ID',
                'meninggal.KUNJUNGAN as NOMOR_KUNJUNGAN',
                'meninggal.TANGGAL as TANGGAL',
                'meninggal.NOMOR as NOMOR_SURAT_KETERANGAN',
                'meninggal.DIAGNOSA',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as DOKTER'),
                'pemulasaran.DESKRIPSI as RENCANA_PEMULASARAN',
                'meninggal.KEADAAN_YANG_MENGAKIBATKAN_KEMATIAN',
                'meninggal.PENYAKIT_SEBAP_KEMATIAN',
                'meninggal.PENYAKIT_SEBAP_KEMATIAN_LAIN',
                'meninggal.PENYAKIT_LAIN_MEMPENGARUHI_KEMATIAN',
                'meninggal.MULAI_SAKIT',
                'meninggal.AKHIR_SAKIT',
                'meninggal.MACAM_RUDAPAKSA',
                'meninggal.CARA_RUDAPAKSA',
                'meninggal.KERUSAKAN_TUBUH',
                'meninggal.JANIN_LAHIR_MATI',
                'meninggal.SEBAP_KELAHIRAN_MATI',
                'meninggal.PERISTIWA_PERSALINAN',
                'meninggal.PERISTIWA_KEHAMILAN',
                'meninggal.DILAKUKAN_OPERASI',
                'bahasa.DESKRIPSI as BAHASA',
                'meninggal.STATUS_VERIFIKASI',
                DB::raw('CONCAT(verifikator.GELAR_DEPAN, " ", verifikator.NAMA, " ", verifikator.GELAR_BELAKANG) as VERIFIKATOR'),
                'operasi.DESKRIPSI as JENIS_OPERASI',
                'meninggal.CATATAN_VERIFIKASI',
                'pengguna.NAMA as OLEH',
                'meninggal.STATUS',
            )
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'meninggal.DOKTER')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('master.pegawai as verifikator', 'verifikator.NIP', '=', 'meninggal.VERIFIKATOR')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'meninggal.OLEH')
            ->leftJoin('master.referensi as pemulasaran', function ($join) {
                $join->on('pemulasaran.ID', '=', 'meninggal.RENCANA_PEMULASARAN')
                    ->where('pemulasaran.JENIS', '=', 251);
            })
            ->leftJoin('master.referensi as bahasa', function ($join) {
                $join->on('bahasa.ID', '=', 'meninggal.BAHASA')
                    ->where('bahasa.JENIS', '=', 177);
            })
            ->leftJoin('master.referensi as operasi', function ($join) {
                $join->on('operasi.ID', '=', 'meninggal.JENIS_OPERASI')
                    ->where('bahasa.JENIS', '=', 177);
            })
            ->where('meninggal.KUNJUNGAN', $kunjungan)
            ->first();

        // Ensure detailMeninggal is not null
        $queryMeninggal = $queryMeninggal ? (array) $queryMeninggal : [];

        // Error handling: No data found
        if (!$queryDetail) {
            return redirect()->route('layananPulang.index')->with('error', 'Data tidak ditemukan.');
        }

        // Return both data to the view
        return inertia("Layanan/Pulang/Detail", [
            'detail' => $queryDetail,
            'detailMeninggal' => $queryMeninggal,
        ]);
    }
}