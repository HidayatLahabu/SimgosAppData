<?php

namespace App\Http\Controllers\Api\Lis;

use App\Http\Controllers\Controller;
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
                DB::raw('DATE(pasien.TANGGAL_LAHIR) as birth_date'),
                DB::raw("
                CASE pasien.JENIS_KELAMIN
                    WHEN 1 THEN 'M'
                    WHEN 2 THEN 'F'
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

            // 🔥 BATASI 30 HARI TERAKHIR
            ->where('ol.TANGGAL', '>=', now()->subDays(30))

            ->orderByDesc('ol.TANGGAL')
            ->distinct()
            ->get();

        return response()->json([
            'status' => 'success',
            'count'  => $data->count(),
            'data'   => $data,
        ]);
    }

    public function show(string $orderId)
    {
        $data = DB::connection('mysql7')
            ->table('layanan.order_detil_lab as od')
            ->select(
                'ol.NOMOR as no_lab_order',
                'parameter.PARAMETER as parameter_code',
                'tindakan.NAMA as parameter_name',
                'hl.HASIL as result_value',
                'hl.SATUAN as unit',
                'hl.NILAI_NORMAL as reference_range',
                DB::raw("
                CASE 
                    WHEN hl.STATUS = 1 THEN 'N'
                    WHEN hl.STATUS = 2 THEN 'H'
                    ELSE NULL
                END as flag
            "),
                'ol.TANGGAL as created_at'
            )
            ->join('layanan.order_lab as ol', 'ol.NOMOR', '=', 'od.ORDER_ID')
            ->leftJoin('master.tindakan as tindakan', 'tindakan.ID', '=', 'od.TINDAKAN')
            ->leftJoin('layanan.hasil_lab as hl', 'hl.TINDAKAN_MEDIS', '=', 'od.REF')
            ->leftJoin(
                'master.parameter_tindakan_lab as parameter',
                'parameter.ID',
                '=',
                'hl.PARAMETER_TINDAKAN'
            )
            ->where('ol.NOMOR', $orderId)
            ->whereNotNull('hl.HASIL')
            ->where('hl.HASIL', '!=', '')
            ->orderBy('parameter.PARAMETER')
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lab order tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'no_lab_order' => $orderId,
            'items' => $data
        ]);
    }
}
