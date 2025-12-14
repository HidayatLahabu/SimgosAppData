<?php

namespace App\Http\Controllers\Api\Lis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderLabDetailController extends Controller
{
    public function index()
    {
        $data = DB::connection('mysql7')
            ->table('layanan.order_detil_lab as od')
            ->select(
                'ol.NOMOR as no_lab_order',
                'pasien.NORM as no_rm',
                DB::raw('master.getNamaLengkap(pasien.NORM) as patient_name'),
                'pasien.TGL_LAHIR as birth_date',
                DB::raw("
                CASE pasien.JK
                    WHEN 'L' THEN 'M'
                    WHEN 'P' THEN 'F'
                    ELSE NULL
                END as sex
            "),
                DB::raw('NULL as doctor'),
                DB::raw('NULL as analyst'),
                DB::raw("'NEW' as status"),
                'ol.TANGGAL as created_at'
            )
            ->join('layanan.order_lab as ol', 'ol.NOMOR', '=', 'od.ORDER_ID')
            ->leftJoin('pendaftaran.kunjungan as k', 'k.NOMOR', '=', 'ol.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as p', 'p.NOMOR', '=', 'k.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'p.NORM')
            ->whereNotNull('od.ORDER_ID')
            ->orderByDesc('ol.TANGGAL')
            ->distinct()
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $data,
        ]);
    }

    public function show(string $orderId)
    {
        $data = DB::connection('mysql7')
            ->table('layanan.order_detil_lab as od')
            ->select(
                'ol.NOMOR as order_id',
                'ol.TANGGAL as tanggal_order',
                'k.NOMOR as kunjungan',
                'pasien.NORM as norm',
                DB::raw('master.getNamaLengkap(pasien.NORM) as nama_pasien'),
                'tindakan.NAMA as tindakan',
                'parameter.PARAMETER as parameter',
                'hl.HASIL',
                'hl.NILAI_NORMAL',
                'hl.SATUAN',
                'hl.STATUS as status_hasil'
            )
            ->join('layanan.order_lab as ol', 'ol.NOMOR', '=', 'od.ORDER_ID')
            ->leftJoin('pendaftaran.kunjungan as k', 'k.NOMOR', '=', 'ol.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as p', 'p.NOMOR', '=', 'k.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'p.NORM')
            ->leftJoin('master.tindakan as tindakan', 'tindakan.ID', '=', 'od.TINDAKAN')
            ->leftJoin('layanan.hasil_lab as hl', 'hl.TINDAKAN_MEDIS', '=', 'od.REF')
            ->leftJoin('master.parameter_tindakan_lab as parameter', 'parameter.ID', '=', 'hl.PARAMETER_TINDAKAN')
            ->where('ol.NOMOR', $orderId)
            ->orderBy('parameter.PARAMETER')
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'order_id' => $orderId,
            'data' => $data
        ]);
    }
}
