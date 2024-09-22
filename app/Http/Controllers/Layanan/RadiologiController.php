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
            ->select(
                'orderRad.NOMOR as nomor',
                'orderRad.TANGGAL as tanggal',
                'orderRad.KUNJUNGAN as kunjungan',
                'pegawai.NAMA as dokter',
                'pegawai.GELAR_DEPAN as gelarDepan',
                'pegawai.GELAR_BELAKANG as gelarBelakang',
                'pasien.NORM as norm',
                'pasien.NAMA as nama',
                'peserta.noKartu'
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'orderRad.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'orderRad.DOKTER_ASAL')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('bpjs.peserta as peserta', 'pasien.NORM', '=', 'peserta.norm');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pasien.nama) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Paginate the results
        $data = $query->orderByDesc('orderRad.TANGGAL')->paginate(10)->appends(request()->query());

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
        $queryHasil = DB::connection('mysql7')->table('layanan.order_detil_rad as orderDetail')
            ->select(
                'orderDetail.ORDER_ID',
                'tindakan.NAMA as TINDAKAN',
                'hasil.TANGGAL',
                'hasil.KLINIS',
                'hasil.KESAN',
                'hasil.USUL',
                'hasil.HASIL',
                'hasil.BTK',
                'hasil.KRITIS',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as DOKTER_ASAL'),
                'pengguna.NAMA as PENGGUNA',
                'hasil.STATUS'
            )
            ->leftJoin('layanan.hasil_rad as hasil', 'hasil.TINDAKAN_MEDIS', '=', 'orderDetail.REF')
            ->leftJoin('master.tindakan as tindakan', 'tindakan.ID', '=', 'orderDetail.TINDAKAN')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'hasil.DOKTER')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('aplikasi.pengguna as pengguna', 'hasil.OLEH', '=', 'pengguna.ID')
            ->where('orderDetail.ORDER_ID', $id)
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
}
