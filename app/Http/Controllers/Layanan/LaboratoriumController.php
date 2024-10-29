<?php

namespace App\Http\Controllers\Layanan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LaboratoriumController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('nama') ? strtolower(request('nama')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql7')->table('layanan.order_lab as orderLab')
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'orderLab.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'orderLab.DOKTER_ASAL')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('master.kartu_identitas_pasien as kip', 'pasien.NORM', '=', 'kip.NORM')
            ->leftJoin('bpjs.peserta as peserta', 'kip.NOMOR', '=', 'peserta.nik')
            ->leftJoin('layanan.order_detil_lab as orderDetail', 'orderDetail.ORDER_ID', '=', 'orderLab.NOMOR')
            ->leftJoin('layanan.hasil_lab as hasil', 'hasil.TINDAKAN_MEDIS', '=', 'orderDetail.REF');

        // Add search filter if provided
        if ($searchSubject) {
            $query->whereRaw('LOWER(pasien.nama) LIKE ?', ['%' . $searchSubject . '%']);
        }

        // Group by 'nomor' and select the first occurrence for other fields
        $query->selectRaw('
                orderLab.NOMOR as nomor,
                MIN(orderLab.TANGGAL) as tanggal,
                MIN(orderLab.KUNJUNGAN) as kunjungan,
                MIN(pegawai.NAMA) as dokter,
                MIN(pegawai.GELAR_DEPAN) as gelarDepan,
                MIN(pegawai.GELAR_BELAKANG) as gelarBelakang,
                MIN(pasien.NORM) as norm,
                MIN(pasien.NAMA) as nama,
                MIN(peserta.noKartu) as noKartu,
                MIN(orderLab.STATUS) as statusKunjungan,
                MIN(hasil.STATUS) as statusHasil
            ')
            ->groupBy('orderLab.NOMOR');

        // Paginate the results
        $data = $query->orderByDesc('tanggal')->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Layanan/Laboratorium/Index", [
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
        $queryDetail = DB::connection('mysql7')->table('layanan.order_lab as order')
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
                'order.ADA_PENGANTAR_PA',
                'order.NOMOR_SPESIMEN',
                'order.SPESIMEN_KLINIS_ASAL_SUMBER',
                'order.SPESIMEN_KLINIS_CARA_PENGAMBILAN',
                'order.SPESIMEN_KLINIS_WAKTU_PENGAMBILAN',
                'order.SPESIMEN_KLINIS_KONDISI_PENGAMBILAN',
                'order.SPESIMEN_KLINIS_JUMLAH',
                'order.SPESIMEN_KLINIS_VOLUME',
                'order.FIKSASI_WAKTU',
                'order.FIKSASI_CAIRAN',
                'order.FIKSASI_VOLUME_CAIRAN',
                'order.SPESIMEN_KLINIS_PETUGAS_PENGAMBIL',
                'order.SPESIMEN_KLINIS_PETUGAS_PENGANTAR',
                'order.STATUS_PUASA_PASIEN',
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

        $queryHasil = DB::connection('mysql7')->table('layanan.order_detil_lab as orderDetail')
            ->select(
                'orderDetail.ORDER_ID',
                'tindakan.NAMA as TINDAKAN',
                'parameter.PARAMETER as PARAMETER',
                'hasilLab.HASIL',
                'hasilLab.NILAI_NORMAL',
                'hasilLab.SATUAN',
                'hasilLab.STATUS'
            )
            ->leftJoin('master.tindakan as tindakan', 'tindakan.ID', '=', 'orderDetail.TINDAKAN')
            ->leftJoin('layanan.hasil_lab as hasilLab', 'hasilLab.TINDAKAN_MEDIS', '=', 'orderDetail.REF')
            ->leftJoin('master.parameter_tindakan_lab as parameter', 'parameter.ID', '=', 'hasilLab.PARAMETER_TINDAKAN')
            ->where('orderDetail.ORDER_ID', $id)
            ->get();

        // Fetch data catatan (main lab order details)
        $catatanID = $queryDetail->NOMOR;
        $queryCatatan = DB::connection('mysql7')->table('pendaftaran.kunjungan as kunjungan')
            ->select(
                'catatan.KUNJUNGAN',
                'catatan.TANGGAL',
                'catatan.CATATAN',
                DB::raw('CONCAT(dokterLab.GELAR_DEPAN, " ", dokterLab.NAMA, " ", dokterLab.GELAR_BELAKANG) as DOKTER_LAB'),
                'catatan.STATUS',
            )
            ->leftJoin('layanan.catatan_hasil_lab as catatan', 'catatan.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('layanan.order_lab as order', 'order.NOMOR', '=', 'kunjungan.REF')
            ->leftJoin('master.dokter as pegawaiLab', 'pegawaiLab.ID', '=', 'catatan.DOKTER')
            ->leftJoin('master.pegawai as dokterLab', 'dokterLab.NIP', '=', 'pegawaiLab.NIP')
            ->where('kunjungan.REF', $catatanID)
            ->first();

        // Error handling: No data found
        if (!$queryDetail) {
            return redirect()->route('layananLab.index')->with('error', 'Data tidak ditemukan.');
        }

        // Return both data to the view
        return inertia("Layanan/Laboratorium/Detail", [
            'detail' => $queryDetail,
            'detailHasil' => $queryHasil,
            'detailCatatan' => $queryCatatan,
        ]);
    }

    public function print(Request $request)
    {
        // Retrieve input values
        $jenisPasien    = $request->input('jenisPasien');
        $orderBy        = $request->input('orderBy');
        $dariTanggal    = $request->input('dari_tanggal');
        $sampaiTanggal  = $request->input('sampai_tanggal');

        // Prepare the base query
        $query = DB::connection('mysql7')->table('layanan.order_lab as orderLab')
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'orderLab.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.kartu_identitas_pasien as kip', 'pasien.NORM', '=', 'kip.NORM')
            ->leftJoin('bpjs.peserta as peserta', 'kip.NOMOR', '=', 'peserta.nik')
            ->leftJoin('pendaftaran.kunjungan as kunjunganCatatan', 'kunjunganCatatan.REF', '=', 'orderLab.NOMOR')
            ->leftJoin('layanan.catatan_hasil_lab as catatanHasil', 'catatanHasil.KUNJUNGAN', '=', 'kunjunganCatatan.NOMOR')
            ->leftJoin('layanan.order_detil_lab as orderDetailLab', 'orderDetailLab.ORDER_ID', '=', 'orderLab.NOMOR')
            ->leftJoin('layanan.hasil_lab as hasilLab', 'hasilLab.TINDAKAN_MEDIS', '=', 'orderDetailLab.REF')
            ->leftJoin('master.tindakan as tindakan', 'tindakan.ID', '=', 'orderDetailLab.TINDAKAN')
            ->leftJoin('master.tarif_tindakan as tarif', 'tarif.TINDAKAN', '=', 'tindakan.ID')
            ->selectRaw('
                orderLab.NOMOR as nomor,
                orderLab.TANGGAL as tanggal,
                pasien.NORM as norm,
                pasien.NAMA as nama,
                peserta.noKartu as noKartu,
                orderLab.STATUS as statusKunjungan,
                tindakan.NAMA as tindakan,
                tarif.TARIF as tarif,
                catatanHasil.STATUS as statusHasil
            ');

        // Filter based on patient type
        $pasien = 'Semua Jenis Pasien';
        if ($jenisPasien == 1) {
            $query->whereNotNull('peserta.noKartu'); // pasien BPJS
            $pasien = 'BPJS';
        } elseif ($jenisPasien == 2) {
            $query->whereNull('peserta.noKartu'); // pasien non-BPJS
            $pasien = 'Umum';
        }

        if ($orderBy == 1) {
            $query->orderBy('pasien.NAMA')->orderBy('orderLab.TANGGAL');
        } elseif ($orderBy == 2) {
            $query->orderBy('orderLab.TANGGAL')->orderBy('pasien.NAMA');
        }

        // Filter by date range
        if ($dariTanggal && $sampaiTanggal) {
            $query->whereBetween('orderLab.TANGGAL', [$dariTanggal, $sampaiTanggal]);
        }

        // Execute the query and retrieve the data
        $data = $query->where('orderLab.STATUS', 2)
            ->where('catatanHasil.STATUS', 1)
            ->where('tindakan.STATUS', 1)
            ->distinct()
            ->get();

        // Map through the collection to set the statusHasil for each item
        $data = $data->map(function ($item) {
            // Set statusHasil based on the value of hasil.STATUS
            $item->statusHasil = $item->statusHasil == 1 ? 'Final' : 'Belum ada Hasil';
            return $item;
        });

        // Return data to the frontend using Inertia
        return inertia("Layanan/Laboratorium/Print", [
            'data' => $data,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
            'jenisPasien' => $pasien,
        ]);
    }
}