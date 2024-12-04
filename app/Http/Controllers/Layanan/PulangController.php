<?php

namespace App\Http\Controllers\Layanan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterReferensiModel;

class PulangController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql7')->table('layanan.pasien_pulang as pulang')
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

        // Group by 'pulang.ID' and select aggregated fields
        $query->selectRaw('
            pulang.ID as id,
            MIN(pulang.TANGGAL) as tanggal,
            MIN(pulang.KUNJUNGAN) as kunjungan,
            MIN(pegawai.NAMA) as dokter,
            MIN(pegawai.GELAR_DEPAN) as gelarDepan,
            MIN(pegawai.GELAR_BELAKANG) as gelarBelakang,
            MIN(pasien.NORM) as norm,
            MIN(pasien.NAMA) as nama,
            MIN(refCara.DESKRIPSI) as cara,
            MIN(refKeadaan.DESKRIPSI) as keadaan
        ')
            ->groupBy('pulang.ID');

        // Paginate the results
        $data = $query->orderByDesc('pulang.TANGGAL')->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        //get data referensi pasien pulang
        $keadaanPulang = MasterReferensiModel::where('JENIS', 46)->get();

        // Return Inertia view with paginated data
        return inertia("Layanan/Pulang/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all(),
            'keadaanPulang' => $keadaanPulang,
        ]);
    }

    public function filterByTime($filter)
    {
        // Mendapatkan istilah pencarian dari request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Membangun query dasar
        $baseQuery = DB::connection('mysql7')->table('layanan.pasien_pulang as pulang')
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

        // Menerapkan filter waktu
        switch ($filter) {
            case 'hariIni':
                $baseQuery->whereDate('pulang.TANGGAL', now()->format('Y-m-d'));
                $header = 'HARI INI';
                $text = 'PASIEN';
                break;

            case 'mingguIni':
                $baseQuery->whereBetween('pulang.TANGGAL', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $header = 'MINGGU INI';
                $text = 'PASIEN';
                break;

            case 'bulanIni':
                $baseQuery->whereMonth('pulang.TANGGAL', now()->month)
                    ->whereYear('pulang.TANGGAL', now()->year);
                $header = 'BULAN INI';
                $text = 'PASIEN';
                break;

            case 'tahunIni':
                $baseQuery->whereYear('pulang.TANGGAL', now()->year);
                $header = 'TAHUN INI';
                $text = 'PASIEN';
                break;

            default:
                abort(404, 'Filter tidak ditemukan');
        }

        // Membangun query data dengan grouping dan seleksi
        $dataQuery = clone $baseQuery;
        $dataQuery->selectRaw('
            pulang.ID as id,
            MIN(pulang.TANGGAL) as tanggal,
            MIN(pulang.KUNJUNGAN) as kunjungan,
            MIN(pegawai.NAMA) as dokter,
            MIN(pegawai.GELAR_DEPAN) as gelarDepan,
            MIN(pegawai.GELAR_BELAKANG) as gelarBelakang,
            MIN(pasien.NORM) as norm,
            MIN(pasien.NAMA) as nama,
            MIN(refCara.DESKRIPSI) as cara,
            MIN(refKeadaan.DESKRIPSI) as keadaan
        ')
            ->groupBy('pulang.ID');

        // Membangun query count
        $count = $baseQuery->distinct('pulang.ID')->count('pulang.ID');

        // Menambahkan filter pencarian jika ada
        if ($searchSubject) {
            $dataQuery->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pulang.KUNJUNGAN) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });

            // Perbarui count untuk pencarian
            $count = $baseQuery->distinct('pulang.ID')->count('pulang.ID');
        }

        // Mengambil data dengan paginasi
        $data = $dataQuery->orderByDesc('pulang.TANGGAL')->paginate(5)->appends(request()->query());

        // Mengonversi data ke array
        $dataArray = $data->toArray();

        // Mengembalikan view Inertia dengan data yang dipaginate
        return inertia("Layanan/Pulang/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Hanya data yang dipaginate
                'links' => $dataArray['links'], // Tautan paginasi
            ],
            'queryParams' => request()->all(),
            'header' => $header,
            'totalCount' => $count,
            'text' => $text,
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

    // public function print(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'jenisPenjamin'  => 'nullable|integer|in:1,2',
    //         'keadaanPulang'  => 'nullable|integer',
    //         'dari_tanggal'   => 'required|date',
    //         'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
    //     ]);

    //     // Ambil nilai input
    //     $jenisPenjamin  = $request->input('jenisPenjamin');
    //     $keadaanPulang  = $request->input('keadaanPulang');
    //     $dariTanggal    = $request->input('dari_tanggal');
    //     $sampaiTanggal  = $request->input('sampai_tanggal');

    //     // Variable default untuk label
    //     $penjamin = 'Semua Penjamin';
    //     $keadaan  = 'Semua Keadaan Pulang';

    //     // Query utama
    //     $query = DB::connection('mysql7')->table('layanan.pasien_pulang as pulang')
    //         ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'pulang.NOPEN')
    //         ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
    //         ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'pulang.DOKTER')
    //         ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
    //         ->leftJoin('master.referensi as refKeadaan', function ($join) {
    //             $join->on('refKeadaan.ID', '=', 'pulang.KEADAAN')
    //                 ->where('refKeadaan.JENIS', '=', 46);
    //         })
    //         ->selectRaw('
    //             pulang.ID as idPulang,
    //             MIN(pulang.TANGGAL) as tanggal,
    //             MIN(pulang.KUNJUNGAN) as kunjungan,
    //             MIN(pegawai.NAMA) as dokter,
    //             MIN(pegawai.GELAR_DEPAN) as gelarDepan,
    //             MIN(pegawai.GELAR_BELAKANG) as gelarBelakang,
    //             MIN(pasien.NORM) as norm,
    //             MIN(pasien.NAMA) as namaPasien,
    //             MIN(refKeadaan.DESKRIPSI) as keadaan
    //     ')
    //         ->groupBy('pulang.ID');

    //     // Filter berdasarkan jenis penjamin
    //     if ($jenisPenjamin) {
    //         $query->leftJoin('pendaftaran.penjamin as jenisPenjamin', 'jenisPenjamin.NOPEN', '=', 'pulang.NOPEN');
    //         if ($jenisPenjamin == 1) {
    //             $query->where('jenisPenjamin.JENIS', 1)->where('jenisPenjamin.NOMOR', '=', ''); // Non BPJS
    //             $penjamin = 'Non BPJS';
    //         } elseif ($jenisPenjamin == 2) {
    //             $query->where(function ($query) {
    //                 $query->where('jenisPenjamin.JENIS', 2)
    //                     ->whereNotNull('jenisPenjamin.NOMOR')
    //                     ->where('jenisPenjamin.NOMOR', '!=', '');
    //             }); // BPJS
    //             $penjamin = 'BPJS';
    //         }
    //     }

    //     // Filter berdasarkan keadaan pulang
    //     if ($keadaanPulang) {
    //         $query->where('pulang.KEADAAN', '=', $keadaanPulang);
    //         $keadaan = DB::connection('mysql7')->table('master.referensi')
    //             ->where('ID', $keadaanPulang)
    //             ->where('JENIS', '=', 46)
    //             ->value('DESKRIPSI');
    //     }

    //     // Filter berdasarkan tanggal
    //     $data = $query->whereBetween('pulang.TANGGAL', [$dariTanggal, $sampaiTanggal])
    //         ->orderBy('pulang.TANGGAL')
    //         ->get();

    //     // Kirim data ke frontend menggunakan Inertia
    //     return inertia("Layanan/Pulang/Print", [
    //         'data'          => $data,
    //         'dariTanggal'   => $dariTanggal,
    //         'sampaiTanggal' => $sampaiTanggal,
    //         'jenisPenjamin' => $penjamin,
    //         'keadaanPulang' => $keadaan,
    //     ]);
    // }

    public function print(Request $request)
    {
        // Validasi input
        $request->validate([
            'jenisPenjamin'  => 'nullable|integer|in:1,2',
            'keadaanPulang'  => 'nullable|integer',
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        // Ambil nilai input
        $jenisPenjamin  = $request->input('jenisPenjamin');
        $keadaanPulang  = $request->input('keadaanPulang');
        $dariTanggal    = $request->input('dari_tanggal');
        $sampaiTanggal  = $request->input('sampai_tanggal');

        // Variable default untuk label
        $penjamin = 'Semua Penjamin';
        $keadaan  = 'Semua Keadaan Pulang';

        // Query utama
        $query = DB::connection('mysql7')->table('layanan.pasien_pulang as pulang')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'pulang.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'pulang.DOKTER')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('master.referensi as refKeadaan', function ($join) {
                $join->on('refKeadaan.ID', '=', 'pulang.KEADAAN')
                    ->where('refKeadaan.JENIS', '=', 46);
            })
            ->selectRaw('
                pulang.ID as idPulang,
                pulang.TANGGAL as tanggal,
                pulang.KUNJUNGAN as kunjungan,
                pegawai.NAMA as dokter,
                pegawai.GELAR_DEPAN as gelarDepan,
                pegawai.GELAR_BELAKANG as gelarBelakang,
                pasien.NORM as norm,
                pasien.NAMA as namaPasien,
                refKeadaan.DESKRIPSI as keadaan
            ');

        // Filter berdasarkan jenis penjamin
        if ($jenisPenjamin) {
            $query->leftJoin('pendaftaran.penjamin as jenisPenjamin', 'jenisPenjamin.NOPEN', '=', 'pulang.NOPEN');

            // BPJS
            if ($jenisPenjamin == 2) {
                $query->where(function ($query) {
                    $query->where('jenisPenjamin.JENIS', 2)
                        ->whereNotNull('jenisPenjamin.NOMOR')
                        ->where('jenisPenjamin.NOMOR', '!=', '');
                });
                $penjamin = 'BPJS KESEHATAN';

                // Tambahkan NOMOR untuk BPJS sebagai 'nomorSEP'
                $query->addSelect('jenisPenjamin.NOMOR as nomorSEP');
            }

            // Non-BPJS
            if ($jenisPenjamin == 1) {
                $query->where('jenisPenjamin.JENIS', 1)
                    ->where('jenisPenjamin.NOMOR', '=', ''); // Non BPJS
                $penjamin = 'Non BPJS KESEHATAN';
            }
        }

        // Filter berdasarkan keadaan pulang
        if ($keadaanPulang) {
            $query->where('pulang.KEADAAN', '=', $keadaanPulang);
            $keadaan = DB::connection('mysql7')->table('master.referensi')
                ->where('ID', $keadaanPulang)
                ->where('JENIS', '=', 46)
                ->value('DESKRIPSI');
        }

        // Filter berdasarkan tanggal
        $data = $query->whereBetween('pulang.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->orderBy('pulang.TANGGAL')
            ->get();

        // Kirim data ke frontend menggunakan Inertia
        return inertia("Layanan/Pulang/Print", [
            'data'          => $data,
            'dariTanggal'   => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
            'jenisPenjamin' => $penjamin,
            'keadaanPulang' => $keadaan,
        ]);
    }
}