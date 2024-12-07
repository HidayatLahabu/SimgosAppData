<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PasienController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql2')->table('master.pasien as pasien')
            ->select(
                'pasien.NORM as norm',
                'pasien.NAMA as nama',
                'pasien.TANGGAL_LAHIR as tanggal',
                'kip.ALAMAT as alamat',
                'kip.NOMOR as nik',
                'peserta.noKartu as bpjs',
                'pasien.TANGGAL as terdaftar'
            )
            ->leftJoin('master.kartu_identitas_pasien as kip', 'pasien.NORM', '=', 'kip.NORM')
            ->leftJoin('bpjs.peserta as peserta', 'kip.NOMOR', '=', 'peserta.nik')
            ->where('pasien.STATUS', 1);

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('pasien.NORM')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Master/Pasien/Index", [
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
        $query = DB::connection('mysql2')
            ->table('master.pasien as pasien')
            ->select([
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
            ->where('pasien.NORM', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('pasien.index')->with('error', 'Data not found.');
        }

        // Return Inertia view with the encounter data
        return inertia("Master/Pasien/Detail", [
            'detail' => $query,
        ]);
    }
}