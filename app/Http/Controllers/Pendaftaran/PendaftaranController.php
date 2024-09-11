<?php

namespace App\Http\Controllers\Pendaftaran;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select(
                'pendaftaran.NOMOR as nomor',
                'pendaftaran.NORM as norm',
                'pasien.NAMA as nama',
                'kip.ALAMAT as alamat',
                'pendaftaran.TANGGAL as tanggal',
                'pendaftaran.STATUS as status',
            )
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.kartu_identitas_pasien as kip', 'pendaftaran.NORM', '=', 'kip.NORM')
            ->leftJoin('bpjs.peserta as peserta', 'pendaftaran.NORM', '=', 'peserta.norm')
            ->where('pasien.STATUS', 1);

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('pendaftaran.NOMOR')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Pendaftaran/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function detail($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')
            ->table('pendaftaran.pendaftaran as pendaftaran')
            ->select([
                'pendaftaran.NOMOR as NOMOR',
                'pendaftaran.NORM as NORM',
                'pasien.NAMA as NAMA_PASIEN',
                'pasien.TEMPAT_LAHIR as TEMPAT_LAHIR',
                'pasien.TANGGAL_LAHIR as TANGGAL_LAHIR',
                'kartu_asuransi_pasien.NOMOR as NOMOR_ASURANSI',
                'agama.DESKRIPSI as AGAMA',
                'kelamin.DESKRIPSI as JENIS_KELAMIN',
                'pendidikan.DESKRIPSI as PENDIDIKAN',
                'wilayah.DESKRIPSI as WILAYAH',
                'diagnosa_masuk.ICD as DIAGNOSA_MASUK_ICD',
                'mrconso.STR as DIAGNOSA_MASUK_STR',
                'penanggung_jawab_pasien.NAMA as PENANGGUNG_JAWAB_PASIEN',
                'ruangan.DESKRIPSI as RUANGAN_TUJUAN',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as DPJP'),
                'pendaftaran.STATUS as STATUS_PASIEN',
                'surat_rujukan_pasien.NOMOR as NOMOR_RUJUKAN_PASIEN',
                'surat_rujukan_pasien.TANGGAL as TANGGAL_RUJUKAN_PASIEN',
                'ppk.NAMA as FASKES_PERUJUK',
                'pengantar_pasien.NAMA as PENGANTAR_PASIEN',
                'penjamin.NOMOR as NOMOR_PENJAMIN',
                'jenis_peserta_penjamin.DESKRIPSI as JENIS_PESERTA_PENJAMIN',
                'penjamin.NO_SURAT as NOMOR_SURAT_PENJAMIN',
                'dpjp.nama as DPJP_PENJAMIN',
                'kecelakaan.NOMOR as KECELAKAAN_NOMOR',
                'kecelakaan_jenis.DESKRIPSI as JENIS_KECELAKAAN',
                'kecelakaan.NO_LP as LAPORAN_POLISI',
                'kecelakaan.LOKASI as LOKASI_KECELAKAAN',
                'kecelakaan.TANGGAL_KEJADIAN as TANGGAL_KEJADIAN',
            ])
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.kartu_asuransi_pasien as kartu_asuransi_pasien', 'pasien.NORM', '=', 'kartu_asuransi_pasien.NORM')
            ->leftJoin('master.referensi as kelamin', function ($join) {
                $join->on('pasien.JENIS_KELAMIN', '=', 'kelamin.ID')
                    ->where('kelamin.JENIS', '=', 2);
            })
            ->leftJoin('master.referensi as agama', function ($join) {
                $join->on('agama.ID', '=', 'pasien.AGAMA')
                    ->where('agama.JENIS', '=', 1);
            })
            ->leftJoin('master.referensi as pendidikan', function ($join) {
                $join->on('pendidikan.ID', '=', 'pasien.PENDIDIKAN')
                    ->where('pendidikan.JENIS', '=', 3);
            })
            ->leftJoin('master.wilayah as wilayah', 'wilayah.ID', '=', 'pasien.WILAYAH')
            ->leftJoin('pendaftaran.penanggung_jawab_pasien as penanggung_jawab_pasien', 'penanggung_jawab_pasien.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('pendaftaran.tujuan_pasien as tujuan_pasien', 'tujuan_pasien.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'tujuan_pasien.RUANGAN')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'tujuan_pasien.DOKTER')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('master.diagnosa_masuk as diagnosa_masuk', 'diagnosa_masuk.ID', '=', 'pendaftaran.DIAGNOSA_MASUK')
            ->leftJoin('master.mrconso as mrconso', 'mrconso.CODE', '=', 'diagnosa_masuk.ICD')
            ->leftJoin('pendaftaran.surat_rujukan_pasien as surat_rujukan_pasien', 'surat_rujukan_pasien.ID', '=', 'pendaftaran.RUJUKAN')
            ->leftJoin('master.ppk as ppk', 'ppk.ID', '=', 'surat_rujukan_pasien.PPK')
            ->leftJoin('pendaftaran.pengantar_pasien as pengantar_pasien', 'pengantar_pasien.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('pendaftaran.penjamin as penjamin', 'penjamin.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('master.jenis_peserta_penjamin as jenis_peserta_penjamin', 'jenis_peserta_penjamin.ID', '=', 'penjamin.JENIS_PESERTA')
            ->leftJoin('bpjs.dpjp as dpjp', 'dpjp.kode', '=', 'penjamin.DPJP')
            ->leftJoin('pendaftaran.kecelakaan as kecelakaan', 'kecelakaan.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('master.referensi as kecelakaan_jenis', function ($join) {
                $join->on('kecelakaan_jenis.ID', '=', 'kecelakaan.JENIS')
                    ->where('kecelakaan_jenis.JENIS', '=', 212);
            })
            ->where('pendaftaran.NOMOR', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('pendaftaran.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Pendaftaran/Pendaftaran/Detail", [
            'detail' => $query,
        ]);
    }
}
