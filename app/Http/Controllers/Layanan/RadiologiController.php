<?php

namespace App\Http\Controllers\Layanan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RadiologiController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql7')->table('layanan.order_rad as orderRad')
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'orderRad.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'orderRad.DOKTER_ASAL')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('layanan.order_detil_rad as orderRadDetail', 'orderRadDetail.ORDER_ID', '=', 'orderRad.NOMOR')
            ->leftJoin('layanan.hasil_rad as hasilRad', 'hasilRad.TINDAKAN_MEDIS', '=', 'orderRadDetail.REF');

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(orderRad.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        $query->selectRaw('
            orderRad.NOMOR as nomor,
            orderRad.TANGGAL as tanggal,
            orderRad.KUNJUNGAN as kunjungan,
            master.getNamaLengkapPegawai(dokter.NIP) as orderOleh,
            pasien.NORM as norm,
            master.getNamaLengkap(pasien.NORM) as nama,
            kunjungan.STATUS as statusKunjungan,
            orderRad.STATUS as statusOrder,
            hasilRad.STATUS as statusHasil
        ');

        // Paginate the results
        $data = $query->orderByDesc('orderRad.TANGGAL')->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Layanan/Radiologi/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function filterByTime($filter)
    {
        // Mendapatkan istilah pencarian dari request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Membangun query dasar
        $baseQuery = DB::connection('mysql7')->table('layanan.order_rad as orderRad')
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'orderRad.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'orderRad.DOKTER_ASAL')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('layanan.order_detil_rad as orderRadDetail', 'orderRadDetail.ORDER_ID', '=', 'orderRad.NOMOR')
            ->leftJoin('layanan.hasil_rad as hasilRad', 'hasilRad.TINDAKAN_MEDIS', '=', 'orderRadDetail.REF');

        // Menerapkan filter waktu
        switch ($filter) {
            case 'hariIni':
                $baseQuery->whereDate('orderRad.TANGGAL', now()->format('Y-m-d'));
                $header = 'HARI INI';
                $text = 'PASIEN';
                break;

            case 'mingguIni':
                $baseQuery->whereBetween('orderRad.TANGGAL', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $header = 'MINGGU INI';
                $text = 'PASIEN';
                break;

            case 'bulanIni':
                $baseQuery->whereMonth('orderRad.TANGGAL', now()->month)
                    ->whereYear('orderRad.TANGGAL', now()->year);
                $header = 'BULAN INI';
                $text = 'PASIEN';
                break;

            case 'tahunIni':
                $baseQuery->whereYear('orderRad.TANGGAL', now()->year);
                $header = 'TAHUN INI';
                $text = 'PASIEN';
                break;

            default:
                abort(404, 'Filter tidak ditemukan');
        }

        // Membangun query data dengan grouping dan seleksi
        $dataQuery = clone $baseQuery;
        $dataQuery
            ->selectRaw('
                orderRad.NOMOR as nomor,
                orderRad.TANGGAL as tanggal,
                orderRad.KUNJUNGAN as kunjungan,
                master.getNamaLengkapPegawai(dokter.NIP) as orderOleh,
                pasien.NORM as norm,
                master.getNamaLengkap(pasien.NORM) as nama,
                kunjungan.STATUS as statusKunjungan,
                orderRad.STATUS as statusOrder,
                hasilRad.STATUS as statusHasil
            ');

        // Membangun query count
        $count = $baseQuery->distinct('orderRad.NOMOR')->count('orderRad.NOMOR');

        // Menambahkan filter pencarian jika ada
        if ($searchSubject) {
            $dataQuery->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(orderRad.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });

            // Perbarui count untuk pencarian
            $count = $baseQuery->distinct('orderRad.NOMOR')->count('orderRad.NOMOR');
        }

        // Mengambil data dengan paginasi
        $data = $dataQuery->orderByDesc('orderRad.TANGGAL')->paginate(5)->appends(request()->query());

        // Mengonversi data ke array
        $dataArray = $data->toArray();

        // Mengembalikan view Inertia dengan data yang dipaginate
        return inertia("Layanan/Radiologi/Index", [
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
        $queryDetail = DB::connection('mysql7')->table('layanan.order_rad as order')
            ->select(
                'order.NOMOR',
                'order.KUNJUNGAN',
                'order.TANGGAL',
                'pasien.NORM',
                DB::raw('master.getNamaLengkap(pasien.NORM) as NAMA'),
                DB::raw('master.getNamaLengkapPegawai(dokter.NIP) as DOKTER_ASAL'),
                'ruangan.DESKRIPSI as TUJUAN',
                'order.CITO',
                'pengguna.NAMA as OLEH',
                'order.ALASAN',
                'order.KETERANGAN',
                'order.STATUS_PUASA_PASIEN',
                'order.STATUS_ALERGI',
                'order.STATUS_KEHAMILAN',
                'order.STATUS',
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'order.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'order.DOKTER_ASAL')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'order.TUJUAN')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'order.OLEH')
            ->where('order.NOMOR', $id)
            ->firstOrFail();

        // Fetch data hasil lab (lab test results)
        $queryHasil = DB::connection('mysql7')->table('layanan.order_rad as orderRad')
            ->select(
                'hasil.ID as ID',
                'tindakan.NAMA as TINDAKAN',
                'hasil.TANGGAL',
                'hasil.KLINIS',
                'hasil.KESAN',
                'hasil.USUL',
                'hasil.HASIL',
                'hasil.BTK',
                'hasil.KRITIS',
                DB::raw('master.getNamaLengkapPegawai(dokter.NIP) as PENGGUNA'),
                'hasil.STATUS'
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.REF', '=', 'orderRad.NOMOR')
            ->leftJoin('layanan.tindakan_medis as tindakanMedis', 'tindakanMedis.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('layanan.hasil_rad as hasil', 'hasil.TINDAKAN_MEDIS', '=', 'tindakanMedis.ID')
            ->leftJoin('master.tindakan as tindakan', 'tindakan.ID', '=', 'tindakanMedis.TINDAKAN')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'hasil.DOKTER')
            ->leftJoin('aplikasi.pengguna as pengguna', 'hasil.OLEH', '=', 'pengguna.ID')
            ->where('orderRad.NOMOR', $id)
            ->where('hasil.STATUS', 2)
            ->distinct()
            ->get();

        // Error handling: No data found
        if (!$queryHasil) {
            return redirect()->route('layananRad.index')->with('error', 'Data tidak ditemukan.');
        }

        // Return both data to the view
        return inertia("Layanan/Radiologi/Detail", [
            'detail' => $queryDetail,
            'detailHasil' => $queryHasil,
        ]);
    }

    public function print(Request $request)
    {
        // Validasi input
        $request->validate([
            'jenisPenjamin'  => 'nullable|integer',
            'jenisKunjungan' => 'nullable|integer',
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        // Ambil nilai input
        $jenisPenjamin  = $request->input('jenisPenjamin');
        $jenisKunjungan = $request->input('jenisKunjungan');
        $dariTanggal    = $request->input('dari_tanggal');
        $sampaiTanggal  = $request->input('sampai_tanggal');
        $dariTanggal = Carbon::parse($dariTanggal)->format('Y-m-d H:i:s');
        $sampaiTanggal = Carbon::parse($sampaiTanggal)->endOfDay()->format('Y-m-d H:i:s');

        // Variable default
        $penjamin = 'Semua Penjamin';
        $kunjungan = 'Semua Kunjungan';

        // Query utama
        $query = DB::connection('mysql7')->table('layanan.hasil_rad as hasilRad')
            ->select([
                'hasilRad.ID as idHasil',
                'hasilRad.TANGGAL as tanggalHasil',
                'tindakan.NAMA as namaTindakan',
                'pendaftaran.NORM as norm',
                DB::raw('master.getNamaLengkap(pasien.NORM) as namaPasien'),
                DB::raw('master.getNamaLengkapPegawai(dokter.NIP) as pelaksana'),
                'ruangan.DESKRIPSI as ruangan'
            ])
            ->leftJoin('layanan.tindakan_medis as tindakanMedis', 'tindakanMedis.ID', '=', 'hasilRad.TINDAKAN_MEDIS')
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'tindakanMedis.KUNJUNGAN')
            ->leftJoin('master.tindakan as tindakan', 'tindakan.ID', '=', 'tindakanMedis.TINDAKAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->join('pendaftaran.penjamin as jenisPenjamin', 'jenisPenjamin.NOPEN', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'hasilRad.DOKTER')
            ->leftJoin('pendaftaran.tujuan_pasien as tujuanPasien', 'tujuanPasien.NOPEN', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'tujuanPasien.RUANGAN')
            ->whereBetween('hasilRad.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->orderBy('pasien.NAMA')
            ->orderBy('hasilRad.TANGGAL');

        if ($jenisPenjamin == 2) {
            $query->addSelect([
                'jenisPenjamin.NOMOR as nomorSEP',
                'kunjunganBpjs.tglSEP as tanggalSEP'
            ])
                ->join('bpjs.kunjungan as kunjunganBpjs', 'kunjunganBpjs.noSEP', '=', 'jenisPenjamin.NOMOR')
                ->whereNotNull('jenisPenjamin.NOMOR')
                ->where('jenisPenjamin.NOMOR', '!=', '')
                ->groupBy('kunjunganBpjs.tglSEP', 'hasilRad.ID', 'jenisPenjamin.NOMOR');;

            $penjamin = 'BPJS KESEHATAN';
        } else {
            $query->where('jenisPenjamin.NOMOR', '')->where('jenisPenjamin.JENIS', 1);
            $penjamin = 'Non BPJS KESEHATAN';
        }

        // Filter berdasarkan jenis kunjungan
        $kunjunganMap = [
            1 => 'Rawat Jalan',
            2 => 'Rawat Darurat',
            3 => 'Rawat Inap',
            7 => 'Hemodialisa',
            5 => 'Radiologi',
            6 => 'Bedan Sentral',
            12 => 'Kamar Bersalin'
        ];

        if (isset($kunjunganMap[$jenisKunjungan])) {
            $query->where('ruangan.JENIS_KUNJUNGAN', $jenisKunjungan);
            $kunjungan = $kunjunganMap[$jenisKunjungan];
        } else {
            $query->whereIn('ruangan.JENIS_KUNJUNGAN', [1, 2, 3, 7, 5, 6, 12]);
            $kunjungan = 'Semua Kunjungan';
        }

        $data = $query->cursor();

        // Kirim data ke frontend
        return inertia("Layanan/Radiologi/Print", [
            'data'              => $data,
            'dariTanggal'       => $dariTanggal,
            'sampaiTanggal'     => $sampaiTanggal,
            'jenisPenjamin'     => $penjamin,
            'jenisKunjungan'    => $kunjungan,
        ]);
    }
}
