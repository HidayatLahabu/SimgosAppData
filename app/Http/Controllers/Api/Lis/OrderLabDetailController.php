<?php

namespace App\Http\Controllers\Api\Lis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderLabDetailController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = DB::connection('mysql7')
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
            ->whereNotNull('hl.HASIL')
            ->where('hl.HASIL', '!=', '')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('ol.NOMOR', 'LIKE', "%{$search}%")
                        ->orWhere('pasien.NORM', 'LIKE', "%{$search}%")
                        ->orWhereRaw('LOWER(tindakan.NAMA) LIKE ?', ['%' . strtolower($search) . '%']);
                });
            })
            ->orderByDesc('ol.TANGGAL')
            ->paginate(7);

        return response()->json([
            'status' => 'success',
            'meta' => [
                'current_page' => $query->currentPage(),
                'per_page'     => $query->perPage(),
                'total'        => $query->total(),
            ],
            'data' => $query->items(),
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
