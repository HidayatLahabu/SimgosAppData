<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterReferensiModel;

class LayananTindakanRekapController extends Controller
{
    public function index()
    {
        $tgl_awal = Carbon::now()->startOfYear()->toDateString();
        $tgl_akhir = Carbon::now()->toDateString();

        $query = DB::connection('mysql7')->table('layanan.tindakan_medis as tindakanMedis')
            ->select([
                'ruangan.DESKRIPSI as UNITPELAYANAN',
                'tindakan.NAMA as NAMATINDAKAN',
                DB::raw('COUNT(tindakanMedis.TINDAKAN) as JUMLAH'),
                DB::raw('SUM(IF(ruanganTujuan.JENIS_KUNJUNGAN=3,1,0)) as RI'),
                DB::raw('SUM(IF(ruanganTujuan.JENIS_KUNJUNGAN=2,1,0)) as RD'),
                DB::raw('SUM(IF(ruanganTujuan.JENIS_KUNJUNGAN NOT IN (3,2),1,0)) as RJ'),
                DB::raw('SUM(IF(penjamin.JENIS=1,1,0)) as UMUM'),
                DB::raw('SUM(IF(penjamin.JENIS=2,1,0)) as BPJS'),
                DB::raw('SUM(IF(penjamin.JENIS NOT IN (1,2),1,0)) as IKS')
            ])
            ->leftJoin('master.tindakan as tindakan', 'tindakanMedis.TINDAKAN', '=', 'tindakan.ID')
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'tindakanMedis.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'kunjungan.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('pendaftaran.penjamin as penjamin', 'pendaftaran.NOMOR', '=', 'penjamin.NOPEN')
            ->leftJoin('pendaftaran.tujuan_pasien as tujuanPasien', 'pendaftaran.NOMOR', '=', 'tujuanPasien.NOPEN')
            ->leftJoin('master.ruangan as ruangan', function ($join) {
                $join->on('kunjungan.RUANGAN', '=', 'ruangan.ID')->where('ruangan.JENIS', '=', 5);
            })
            ->leftJoin('master.ruangan as ruanganTujuan', function ($join) {
                $join->on('tujuanPasien.RUANGAN', '=', 'ruanganTujuan.ID')->where('ruanganTujuan.JENIS', '=', 5);
            })
            ->leftJoin('pembayaran.rincian_tagihan as rincianTagihan', 'tindakanMedis.ID', '=', 'rincianTagihan.REF_ID')
            ->whereIn('tindakanMedis.STATUS', [1, 2])
            ->where('rincianTagihan.STATUS', '!=', 0)
            ->whereBetween('tindakanMedis.TANGGAL', [$tgl_awal, $tgl_akhir])
            ->where('kunjungan.RUANGAN', 'LIKE', '%')
            ->groupBy('tindakanMedis.TINDAKAN', 'ruangan.DESKRIPSI')
            ->orderBy('tindakan.NAMA')
            ->get();

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'RJ' => 0,
            'RD' => 0,
            'RI' => 0,
            'JUMLAH' => 0,
            'UMUM' => 0,
            'BPJS' => 0,
            'IKS' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($query as $row) {
            $total['RJ'] += $row->RJ;
            $total['RD'] += $row->RD;
            $total['RI'] += $row->RI;
            $total['JUMLAH'] += $row->JUMLAH;
            $total['UMUM'] += $row->UMUM;
            $total['BPJS'] += $row->BPJS;
            $total['IKS'] += $row->IKS;
        }

        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        $caraBayar = MasterReferensiModel::where('JENIS', 10)
            ->where('STATUS', 1)
            ->orderBy('ID')
            ->get();

        return inertia("Laporan/TindakanRekap/Index", [
            'dataTable' => $query,
            'total' => $total,
            'tglAwal' => $tgl_awal,
            'tglAkhir' => $tgl_akhir,
            'ruangan' => $ruangan,
            'caraBayar' => $caraBayar,
        ]);
    }

    public function print(Request $request)
    {
        $request->validate([
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
            'ruangan'  => 'nullable|integer',
            'caraBayar' => 'nullable|integer',
        ]);

        // Ambil nilai input
        $ruangan  = $request->input('ruangan');
        $caraBayar = $request->input('caraBayar');
        $dariTanggal = Carbon::parse($request->input('dari_tanggal'))->startOfDay()->format('Y-m-d H:i:s');
        $sampaiTanggal = Carbon::parse($request->input('sampai_tanggal'))->endOfDay()->format('Y-m-d H:i:s');

        $query = DB::connection('mysql7')->table('layanan.tindakan_medis as tindakanMedis')
            ->select([
                'ruangan.DESKRIPSI as UNITPELAYANAN',
                'tindakan.NAMA as NAMATINDAKAN',
                DB::raw('COUNT(tindakanMedis.TINDAKAN) as JUMLAH'),
                DB::raw('SUM(IF(ruanganTujuan.JENIS_KUNJUNGAN=3,1,0)) as RI'),
                DB::raw('SUM(IF(ruanganTujuan.JENIS_KUNJUNGAN=2,1,0)) as RD'),
                DB::raw('SUM(IF(ruanganTujuan.JENIS_KUNJUNGAN NOT IN (3,2),1,0)) as RJ'),
                DB::raw('SUM(IF(penjamin.JENIS=1,1,0)) as UMUM'),
                DB::raw('SUM(IF(penjamin.JENIS=2,1,0)) as BPJS'),
                DB::raw('SUM(IF(penjamin.JENIS NOT IN (1,2),1,0)) as IKS')
            ])
            ->leftJoin('master.tindakan as tindakan', 'tindakanMedis.TINDAKAN', '=', 'tindakan.ID')
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'tindakanMedis.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'kunjungan.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('pendaftaran.penjamin as penjamin', 'pendaftaran.NOMOR', '=', 'penjamin.NOPEN')
            ->leftJoin('pendaftaran.tujuan_pasien as tujuanPasien', 'pendaftaran.NOMOR', '=', 'tujuanPasien.NOPEN')
            ->leftJoin('master.ruangan as ruangan', function ($join) {
                $join->on('kunjungan.RUANGAN', '=', 'ruangan.ID')->where('ruangan.JENIS', '=', 5);
            })
            ->leftJoin('master.ruangan as ruanganTujuan', function ($join) {
                $join->on('tujuanPasien.RUANGAN', '=', 'ruanganTujuan.ID')->where('ruanganTujuan.JENIS', '=', 5);
            })
            ->leftJoin('pembayaran.rincian_tagihan as rincianTagihan', 'tindakanMedis.ID', '=', 'rincianTagihan.REF_ID')
            ->whereIn('tindakanMedis.STATUS', [1, 2])
            ->where('rincianTagihan.STATUS', '!=', 0)
            ->groupBy('tindakanMedis.TINDAKAN', 'ruangan.DESKRIPSI');

        if ($ruangan) {
            $query->where('kunjungan.RUANGAN', '=', $ruangan);
        }

        if ($caraBayar) {
            $query->where('penjamin.JENIS', '=', $caraBayar);
        }

        // Filter berdasarkan tanggal
        $data = $query
            ->whereBetween('tindakanMedis.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->orderBy('tindakan.NAMA')
            ->get();

        // Inisialisasi variabel untuk menyimpan total
        $total = [
            'RJ' => 0,
            'RD' => 0,
            'RI' => 0,
            'JUMLAH' => 0,
            'UMUM' => 0,
            'BPJS' => 0,
            'IKS' => 0,
        ];

        // Loop melalui hasil query dan jumlahkan nilai
        foreach ($data as $row) {
            $total['RJ'] += $row->RJ;
            $total['RD'] += $row->RD;
            $total['RI'] += $row->RI;
            $total['JUMLAH'] += $row->JUMLAH;
            $total['UMUM'] += $row->UMUM;
            $total['BPJS'] += $row->BPJS;
            $total['IKS'] += $row->IKS;
        }

        return inertia("Laporan/TindakanRekap/Print", [
            'data' => $data,
            'total' => $total,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
        ]);
    }
}
