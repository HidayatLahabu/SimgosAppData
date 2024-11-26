<?php

namespace App\Http\Controllers\Pendaftaran;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranKunjunganModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

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
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pendaftaran.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pendaftaran.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
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
                'pendaftaran.NOMOR as NOMOR_PENDAFTARAN',
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
                'pendaftaran.PAKET as PAKET',
                'pendaftaran.BERAT_BAYI as BERAT_BAYI',
                'pendaftaran.PANJANG_BAYI as PANJANG_BAYI',
                'pendaftaran.CITO as CITO',
                'pendaftaran.RESIKO_JATUH as RESIKO_JATUH',
                'pendaftaran.LOKASI_DITEMUKAN as LOKASI_DITEMUKAN',
                'pendaftaran.TANGGAL_DITEMUKAN as TANGGAL_DITEMUKAN',
                'pendaftaran.JAM_LAHIR as JAM_LAHIR',
                'pendaftaran.CONSENT_SATUSEHAT as CONSENT_SATUSEHAT',
                'pendaftaran.PAKET as PAKET',
                'pengguna.NAMA as OLEH',
                'pendaftaran.STATUS as STATUS_PENDAFTARAN',
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
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'pendaftaran.OLEH')
            ->where('pendaftaran.NOMOR', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('pendaftaran.index')->with('error', 'Data not found.');
        }

        //nomor pendaftaran
        $noPendaftaran = $query->NOMOR_PENDAFTARAN;
        // Fetch kunjungan data using the new function
        $kunjungan = $this->getKunjungan($noPendaftaran);

        // Return Inertia view with the encounter data
        return inertia("Pendaftaran/Pendaftaran/Detail", [
            'detail'            => $query,
            'dataKunjungan'     => $kunjungan,
            'nomorPendaftaran'  => $noPendaftaran,
        ]);
    }

    //get data table untuk detail kunjungan
    protected function getKunjungan($noPendaftaran)
    {
        return DB::connection('mysql5')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select([
                'kunjungan.NOMOR as nomor',
                'kunjungan.NOPEN as pendaftaran',
                'kunjungan.MASUK as masuk',
                'kunjungan.KELUAR as keluar',
                'ruangan.DESKRIPSI as ruangan',
            ])
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->where('pendaftaran.NOMOR', $noPendaftaran)
            ->get();
    }

    public function kunjungan($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOPEN as NOMOR_PENDAFTARAN',
                'kunjungan.NOMOR as NOMOR_KUNJUNGAN',
                'pasien.NORM as NORM',
                'pasien.NAMA as NAMA_PASIEN',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as DPJP'),
                'ruangan.DESKRIPSI as RUANGAN_TUJUAN',
                'ruang_kamar.KAMAR as KAMAR_TUJUAN',
                'ruang_kamar_tidur.TEMPAT_TIDUR as TEMPAT_TIDUR',
                'kunjungan.MASUK as TANGGAL_MASUK',
                'kunjungan.KELUAR as TANGGAL_KELUAR',
                'kunjungan.REF as REF',
                'penerima_kunjungan.NAMA as DITERIMA_OLEH',
                'kunjungan.BARU as STATUS_KUNJUNGAN',
                'kunjungan.TITIPAN as TITIPAN',
                'kunjungan.TITIPAN_KELAS as TITIPAN_KELAS',
                'kunjungan.STATUS as STATUS_AKTIFITAS_KUNJUNGAN',
                DB::raw('IFNULL(final_kunjungan.NAMA, "") as FINAL_HASIL_OLEH'),
                'kunjungan.FINAL_HASIL_TANGGAL as FINAL_HASIL_TANGGAL',
                'perubahan_tanggal_kunjungan.TANGGAL_LAMA as TANGGAL_KUNJUNGAN_LAMA',
                'perubahan_tanggal_kunjungan.TANGGAL_BARU as TANGGAL_KUNJUNGAN_BARU',
                'perubahan_oleh.NAMA as PERUBAHAN_KUNJUNGAN_OLEH',
                'perubahan_tanggal_kunjungan.STATUS as STATUS_PERUBAHAN_KUNJUNGAN',
            ])
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->leftJoin('master.ruang_kamar as ruang_kamar', 'ruang_kamar.RUANGAN', '=', 'kunjungan.RUANGAN')
            ->leftJoin('master.ruang_kamar_tidur as ruang_kamar_tidur', 'ruang_kamar_tidur.ID', '=', 'kunjungan.RUANG_KAMAR_TIDUR')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'kunjungan.DPJP')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('master.diagnosa_masuk as diagnosa_masuk', 'diagnosa_masuk.ID', '=', 'pendaftaran.DIAGNOSA_MASUK')
            ->leftJoin('aplikasi.pengguna as penerima_kunjungan', 'penerima_kunjungan.ID', '=', 'kunjungan.DITERIMA_OLEH')
            ->leftJoin('aplikasi.pengguna as final_kunjungan', 'final_kunjungan.ID', '=', 'kunjungan.FINAL_HASIL_OLEH')
            ->leftJoin('pendaftaran.perubahan_tanggal_kunjungan AS perubahan_tanggal_kunjungan', 'perubahan_tanggal_kunjungan.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('aplikasi.pengguna AS perubahan_oleh', 'perubahan_oleh.ID', '=', 'perubahan_tanggal_kunjungan.OLEH')
            ->where('kunjungan.NOPEN', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('pendaftaran.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Pendaftaran/Pendaftaran/Kunjungan", [
            'detail' => $query,
        ]);
    }

    public function pasien($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select([
                'pendaftaran.NOMOR as NOMOR_PENDAFTARAN',
                'pasien.NORM as NORM',
                'pasien.GELAR_DEPAN AS GELAR_DEPAN',
                'pasien.NAMA as NAMA',
                'pasien.GELAR_BELAKANG as GELAR_BELAKANG',
                'pasien.TEMPAT_LAHIR as TEMPAT_LAHIR',
                'pasien.TANGGAL_LAHIR as TANGGAL_LAHIR',
                'pasien.JENIS_KELAMIN as JENIS_KELAMIN',
                'pasien.ALAMAT as ALAMAT',
                'pasien.RT as RT',
                'pasien.RW as RW',
                'wilayah.DESKRIPSI as WILAYAH',
                'pasien.KODEPOS as KODEPOS',
                'agama.DESKRIPSI as AGAMA',
                'kelamin.DESKRIPSI as JENIS_KELAMIN',
                'pendidikan.DESKRIPSI as PENDIDIKAN',
                'perkawinan.DESKRIPSI as STATUS_PERKAWINAN',
                'golonganDarah.DESKRIPSI as GOLONGAN_DARAH',
                'kewarganegaraan.DESKRIPSI as KEWARGANEGARAAN',
                'pasien.SUKU as SUKU',
                'pasien.TIDAK_DIKENAL as TIDAK_DIKENAL',
                'bahasa.DESKRIPSI as BAHASA',
                'pasien.LOCK_AKSES as LOCK_AKSES',
                'identitas.NOMOR as KARTU_IDENTITAS',
                'asuransi.NOMOR as ASURANSI_PASIEN',
                'kontak_pasien.NOMOR as KONTAK_PASIEN',
                'keluarga.NAMA as NAMA_KELUARGA',
                'keluarga.ALAMAT as ALAMAT_KELUARGA',
                'kontak_keluarga.NOMOR as KONTAK_KELUARGA',
                'pengguna.NAMA as INPUT_OLEH',
                'pasien.TANGGAL as TANGGAL_INPUT',
            ])
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.wilayah as wilayah', 'wilayah.ID', '=', 'pasien.WILAYAH')
            ->leftJoin('master.negara as kewarganegaraan', 'kewarganegaraan.ID', '=', 'pasien.KEWARGANEGARAAN')
            ->leftJoin('master.kartu_identitas_pasien as identitas', 'identitas.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.kartu_asuransi_pasien as asuransi', 'asuransi.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.kontak_pasien as kontak_pasien', 'kontak_pasien.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.keluarga_pasien as keluarga', 'keluarga.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.kontak_keluarga_pasien as kontak_keluarga', 'kontak_keluarga.NORM', '=', 'pasien.NORM')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'pasien.OLEH')
            ->leftJoin('master.referensi as kelamin', function ($join) {
                $join->on('kelamin.ID', '=', 'pasien.JENIS_KELAMIN')
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
            ->leftJoin('master.referensi as pekerjaan', function ($join) {
                $join->on('pekerjaan.ID', '=', 'pasien.PEKERJAAN')
                    ->where('pendidikan.JENIS', '=', 4);
            })
            ->leftJoin('master.referensi as perkawinan', function ($join) {
                $join->on('perkawinan.ID', '=', 'pasien.STATUS_PERKAWINAN')
                    ->where('perkawinan.JENIS', '=', 5);
            })
            ->leftJoin('master.referensi as golonganDarah', function ($join) {
                $join->on('golonganDarah.ID', '=', 'pasien.GOLONGAN_DARAH')
                    ->where('golonganDarah.JENIS', '=', 6);
            })
            ->leftJoin('master.referensi as bahasa', function ($join) {
                $join->on('bahasa.ID', '=', 'pasien.BAHASA')
                    ->where('bahasa.JENIS', '=', 177);
            })
            ->where('pendaftaran.NORM', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('pendaftaran.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Pendaftaran/Pendaftaran/Pasien", [
            'detail' => $query,
        ]);
    }

    public function bpjs($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select([
                'pendaftaran.NOMOR as NOMOR_PENDAFTARAN',
                'bpjs.*',
            ])
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.kartu_identitas_pasien as identitas', 'identitas.NORM', '=', 'pasien.NORM')
            ->leftJoin('bpjs.peserta as bpjs', 'bpjs.nik', '=', 'identitas.NOMOR')
            ->where('pendaftaran.NORM', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('pendaftaran.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Pendaftaran/Pendaftaran/Bpjs", [
            'detail' => $query,
        ]);
    }

    public function filterByTime($filter)
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Determine the date filter based on the type
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

        switch ($filter) {
            case 'hariIni':
                $query->whereDate('pendaftaran.TANGGAL', now()->format('Y-m-d'));
                $header = 'HARI INI';
                break;

            case 'mingguIni':
                $query->whereBetween('pendaftaran.TANGGAL', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $header = 'MINGGU INI';
                break;

            case 'bulanIni':
                $query->whereMonth('pendaftaran.TANGGAL', now()->month)
                    ->whereYear('pendaftaran.TANGGAL', now()->year);
                $header = 'BULAN INI';
                break;

            case 'tahunIni':
                $query->whereYear('pendaftaran.TANGGAL', now()->year);
                $header = 'TAHUN INI';
                break;

            default:
                abort(404, 'Filter not found');
        }

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pendaftaran.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pendaftaran.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
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
            'queryParams' => request()->all(),
            'header' => $header,
        ]);
    }
}