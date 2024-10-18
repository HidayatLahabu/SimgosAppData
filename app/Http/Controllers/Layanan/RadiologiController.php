<?php

namespace App\Http\Controllers\Layanan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RadiologiController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql7')->table('layanan.order_rad as orderRad')
            ->selectRaw('
            orderRad.NOMOR as nomor,
            MIN(orderRad.TANGGAL) as tanggal,
            MIN(orderRad.KUNJUNGAN) as kunjungan,
            orderRad.STATUS as statusKunjungan,
            MIN(pegawai.NAMA) as dokter,
            MIN(pegawai.GELAR_DEPAN) as gelarDepan,
            MIN(pegawai.GELAR_BELAKANG) as gelarBelakang,
            MIN(pasien.NORM) as norm,
            MIN(pasien.NAMA) as nama,
            MIN(peserta.noKartu) as noKartu,
            MIN(hasil.STATUS) as statusHasil
        ')
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.REF', '=', 'orderRad.NOMOR')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('layanan.tindakan_medis as tindakanMedis', 'tindakanMedis.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('layanan.hasil_rad as hasil', 'hasil.TINDAKAN_MEDIS', '=', 'tindakanMedis.ID')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'orderRad.DOKTER_ASAL')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('master.kartu_identitas_pasien as kip', 'pasien.NORM', '=', 'kip.NORM')
            ->leftJoin('bpjs.peserta as peserta', 'kip.NOMOR', '=', 'peserta.nik');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pasien.nama) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Group by 'nomor'
        $query->groupBy('orderRad.NOMOR');

        // Paginate the results
        $data = $query->orderByDesc('tanggal')->paginate(5)->appends(request()->query());

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

    public function detail($id)
    {
        // Fetch data utama (main lab order details)
        $queryDetail = DB::connection('mysql7')->table('layanan.order_rad as order')
            ->select(
                'order.NOMOR',
                'order.KUNJUNGAN',
                'order.TANGGAL',
                'pasien.NORM',
                'pasien.NAMA',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as DOKTER_ASAL'),
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
            ->first();

        // Fetch data hasil lab (lab test results)
        $queryHasil = DB::connection('mysql7')->table('layanan.order_rad as orderRad')
            ->select(
                'hasil.ID as HASIL_ID',
                'tindakan.NAMA as TINDAKAN',
                'hasil.TANGGAL',
                'hasil.KLINIS',
                'hasil.KESAN',
                'hasil.USUL',
                'hasil.HASIL',
                'hasil.BTK',
                'hasil.KRITIS',
                'pengguna.NAMA as PENGGUNA',
                'hasil.STATUS'
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.REF', '=', 'orderRad.NOMOR')
            ->leftJoin('layanan.tindakan_medis as tindakanMedis', 'tindakanMedis.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('layanan.hasil_rad as hasil', 'hasil.TINDAKAN_MEDIS', '=', 'tindakanMedis.ID')
            ->leftJoin('master.tindakan as tindakan', 'tindakan.ID', '=', 'tindakanMedis.TINDAKAN')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'hasil.DOKTER')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('aplikasi.pengguna as pengguna', 'hasil.OLEH', '=', 'pengguna.ID')
            ->where('orderRad.NOMOR', $id)
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
        // Retrieve input values
        $jenisPasien  = $request->input('jenisPasien');
        $dariTanggal  = $request->input('dari_tanggal');
        $sampaiTanggal = $request->input('sampai_tanggal');

        // Prepare the base query
        $query = DB::connection('mysql7')->table('layanan.order_rad as orderRad')
            ->join('pendaftaran.kunjungan as kunjunganPasien', 'kunjunganPasien.NOMOR', '=', 'orderRad.KUNJUNGAN')
            ->join('pendaftaran.pendaftaran as pendaftaranPasien', 'pendaftaranPasien.NOMOR', '=', 'kunjunganPasien.NOPEN')
            ->join('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaranPasien.NORM')
            ->join('master.kartu_identitas_pasien as identitas', 'pasien.NORM', '=', 'identitas.NORM')
            ->join('bpjs.peserta as bpjs', 'bpjs.nik', '=', 'identitas.NOMOR')
            ->join('pendaftaran.kunjungan as kunjunganTindakan', 'kunjunganTindakan.REF', '=', 'orderRad.NOMOR')
            ->join('layanan.tindakan_medis as tindakanMedis', 'tindakanMedis.KUNJUNGAN', '=', 'kunjunganTindakan.NOMOR')
            ->join('layanan.hasil_rad as hasil', 'hasil.TINDAKAN_MEDIS', '=', 'tindakanMedis.ID')
            ->join('master.tindakan as masterTindakan', 'masterTindakan.ID', '=', 'tindakanMedis.TINDAKAN')
            ->join('master.dokter as dokter', 'dokter.ID', '=', 'hasil.DOKTER')
            ->join('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP');

        // Select relevant columns
        $query->selectRaw('
                orderRad.NOMOR as nomor,
                orderRad.TANGGAL as tanggal,
                orderRad.KUNJUNGAN as kunjungan,
                pasien.NORM as norm,
                pasien.NAMA as nama,
                bpjs.noKartu as noKartu,
                pegawai.NAMA as dokter,
                masterTindakan.NAMA as namaTindakan
            ')
            ->groupBy('orderRad.NOMOR', 'orderRad.TANGGAL', 'orderRad.KUNJUNGAN', 'pasien.NORM', 'pasien.NAMA', 'bpjs.noKartu', 'hasil.DOKTER', 'masterTindakan.NAMA');

        // Filter based on patient type
        $pasien = 'Semua Jenis Pasien';
        if ($jenisPasien == 1) {
            $query->whereNotNull('bpjs.noKartu'); // pasien BPJS
            $pasien = 'BPJS';
        } elseif ($jenisPasien == 2) {
            $query->whereNull('bpjs.noKartu'); // pasien non-BPJS
            $pasien = 'Umum';
        }

        // Execute the query and retrieve the data
        $data = $query->where('orderRad.STATUS', 1)
            ->orWhere('orderRad.STATUS', 2)
            ->whereBetween('orderRad.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->get();

        // Return data to the frontend using Inertia
        return inertia("Layanan/Radiologi/Print", [
            'data' => $data,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
            'jenisPasien' => $pasien,
        ]);
    }
}
