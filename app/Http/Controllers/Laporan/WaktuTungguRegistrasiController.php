<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class WaktuTungguRegistrasiController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters from request
        $searchTerm = request('search') ? strtolower(request('search')) : null;
        $ruangan = '1021101';
        $perPage = 5;

        // Retrieve the report data
        $reportData = $this->getLaporan($ruangan, $perPage, $searchTerm);
        $dataArray = $reportData->toArray();

        $averageWaitData = $this->getRataRata($ruangan, $perPage, $searchTerm);

        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->whereIn('JENIS_KUNJUNGAN', [1])
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        $dokter = DB::connection('mysql2')->table('master.dokter as dokter')
            ->select([
                'dokter.ID',
                'dokter.NIP',
                DB::raw('master.getNamaLengkapPegawai(dokter.NIP) as DOKTER')
            ])
            ->leftJoin('master.pegawai as pegawai', 'dokter.NIP', '=', 'pegawai.NIP')
            ->leftJoin('master.dokter_ruangan as dpjpRuangan', 'dokter.ID', '=', 'dpjpRuangan.DOKTER')
            ->leftJoin('master.ruangan as ruangan', 'dpjpRuangan.RUANGAN', '=', 'ruangan.ID')
            ->where('dokter.STATUS', 1)
            ->whereNotNull('pegawai.NAMA')
            ->where('ruangan.JENIS_KUNJUNGAN', 1)
            ->where('ruangan.STATUS', 1)
            ->where('ruangan.JENIS', 5)
            ->where('ruangan.DESKRIPSI', 'NOT LIKE', '%Umum%')
            ->get();

        // Return the data to the Inertia.js view
        return inertia('Laporan/WaktuTunggu/Index', [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => $request->all(),
            'averageWaitData' => $averageWaitData,
            'ruangan' => $ruangan,
            'dokter' =>  $dokter,
        ]);
    }

    public function getLaporan($ruangan, $perPage = 5, $searchTerm = null)
    {
        $vRuangan = $ruangan . '%';

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select([
                'pasien.NORM as NORM',
                DB::raw("CONCAT(master.getNamaLengkap(pasien.NORM)) as NAMALENGKAP"),
                'pendaftaran.NOMOR as NOPEN',
                'ruangan.DESKRIPSI as UNITPELAYANAN',
                DB::raw("master.getNamaLengkapPegawai(dokter.NIP) as DOKTER_REG"),
                DB::raw("DATE_FORMAT(pendaftaran.TANGGAL, '%d-%m-%Y %H:%i:%s') as TGLREG"),
                DB::raw("DATE_FORMAT(kunjungan.MASUK, '%d-%m-%Y %H:%i:%s') as TGLTERIMA"),
                DB::raw("CONCAT(DATEDIFF(kunjungan.MASUK, pendaftaran.TANGGAL), ' hari ', DATE_FORMAT(TIMEDIFF(kunjungan.MASUK, pendaftaran.TANGGAL), '%H:%i:%s')) as SELISIH"),
                DB::raw("DATEDIFF(kunjungan.MASUK, pendaftaran.TANGGAL) * 86400 + TIME_TO_SEC(TIMEDIFF(kunjungan.MASUK, pendaftaran.TANGGAL)) as SELISIH_DETIK"),
            ])
            ->join('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->join('pendaftaran.tujuan_pasien as tujuanPasien', 'pendaftaran.NOMOR', '=', 'tujuanPasien.NOPEN')
            ->join('pendaftaran.kunjungan as kunjungan', function ($join) {
                $join->on('pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
                    ->on('tujuanPasien.RUANGAN', '=', 'kunjungan.RUANGAN');
            })
            ->join('master.ruangan as ruangan', function ($join) {
                $join->on('tujuanPasien.RUANGAN', '=', 'ruangan.ID')
                    ->where('ruangan.JENIS', '=', 5)
                    ->where('ruangan.JENIS_KUNJUNGAN', 1);
            })
            ->leftJoin('master.dokter as dokter', 'tujuanPasien.DOKTER', '=', 'dokter.ID')
            ->whereIn('pendaftaran.STATUS', [1])
            ->whereNull('kunjungan.REF')
            ->whereIn('kunjungan.STATUS', [1, 2])
            ->where('tujuanPasien.RUANGAN', 'LIKE', $vRuangan);

        // Add search filter if provided
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('pasien.NORM', 'LIKE', "%{$searchTerm}%")
                    ->orWhere(DB::raw("CONCAT(master.getNamaLengkap(pasien.NORM))"), 'LIKE', "%{$searchTerm}%")
                    ->orWhere('pendaftaran.NOMOR', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('ruangan.DESKRIPSI', 'LIKE', "%{$searchTerm}%")
                    ->orWhere(DB::raw("master.getNamaLengkapPegawai(dokter.NIP)"), 'LIKE', "%{$searchTerm}%");
            });
        }

        $data = $query->orderBy('pendaftaran.TANGGAL', 'desc')->paginate($perPage)->appends(request()->query());

        // Return paginated data
        return $data;
    }

    public function getRataRata($ruangan, $perPage = 5, $searchTerm = null)
    {
        $vRuangan = $ruangan . '%';

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select([
                'ruangan.DESKRIPSI as UNITPELAYANAN',
                DB::raw("master.getNamaLengkapPegawai(dok.NIP) as DOKTER_REG"),
                DB::raw("AVG(TIMESTAMPDIFF(SECOND, pendaftaran.TANGGAL, kunjungan.MASUK)) as AVERAGE_SELSIH"),
                DB::raw("COUNT(tujuanPasien.NOPEN) as JUMLAH_PASIEN"),
                DB::raw("MONTH(kunjungan.MASUK) as BULAN"),
                DB::raw("YEAR(kunjungan.MASUK) as TAHUN"),
            ])
            ->join('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->join('pendaftaran.tujuan_pasien as tujuanPasien', 'pendaftaran.NOMOR', '=', 'tujuanPasien.NOPEN')
            ->join('pendaftaran.kunjungan as kunjungan', function ($join) {
                $join->on('pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
                    ->on('tujuanPasien.RUANGAN', '=', 'kunjungan.RUANGAN');
            })
            ->join('master.ruangan as ruangan', function ($join) {
                $join->on('tujuanPasien.RUANGAN', '=', 'ruangan.ID')
                    ->where('ruangan.JENIS', '=', 5)
                    ->where('ruangan.JENIS_KUNJUNGAN', 1);
            })
            ->leftJoin('master.dokter as dok', 'tujuanPasien.DOKTER', '=', 'dok.ID')
            ->whereIn('pendaftaran.STATUS', [1])
            ->whereNull('kunjungan.REF')
            ->whereIn('kunjungan.STATUS', [1, 2])
            ->where('tujuanPasien.RUANGAN', 'LIKE', $vRuangan);

        // Add search filter if provided
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('ruangan.DESKRIPSI', 'LIKE', "%{$searchTerm}%")
                    ->orWhere(DB::raw("master.getNamaLengkapPegawai(dok.NIP)"), 'LIKE', "%{$searchTerm}%");
            });
        }

        // Apply ordering and pagination
        $data = $query->groupBy(
            DB::raw('MONTH(kunjungan.MASUK)'),
            DB::raw('YEAR(kunjungan.MASUK)'),
            'dok.NIP',
            'ruangan.DESKRIPSI'
        )
            ->orderBy(DB::raw('YEAR(kunjungan.MASUK)'), 'desc') // Order by year descending
            ->orderBy(DB::raw('MONTH(kunjungan.MASUK)'), 'desc') // Order by month descending
            ->orderBy(DB::raw("AVG(TIMESTAMPDIFF(SECOND, pendaftaran.TANGGAL, kunjungan.MASUK))"), 'asc')
            ->paginate($perPage)
            ->appends(request()->query());

        // Return paginated data
        return $data;
    }

    public function print(Request $request)
    {
        // Validasi input
        $request->validate([
            'ruangan'  => 'nullable|integer',
            'dokter' => 'nullable|integer',
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        // Ambil nilai input
        $ruangan  = $request->input('ruangan');
        $dokter = $request->input('dokter');
        $dariTanggal    = $request->input('dari_tanggal');
        $sampaiTanggal  = $request->input('sampai_tanggal');
        $dariTanggal = Carbon::parse($dariTanggal)->format('Y-m-d H:i:s');
        $sampaiTanggal = Carbon::parse($sampaiTanggal)->endOfDay()->format('Y-m-d H:i:s');

        // Variable default untuk label
        $namaRuangan = 'Semua Ruangan';
        $namaDokter = 'Semua Dokter';

        // Query utama
        $vRuangan = $ruangan . '%';

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.pendaftaran as pendaftaran')
            ->select([
                'pasien.NORM as NORM',
                DB::raw("CONCAT(master.getNamaLengkap(pasien.NORM)) as NAMALENGKAP"),
                'pendaftaran.NOMOR as NOPEN',
                'ruangan.DESKRIPSI as UNITPELAYANAN',
                DB::raw("master.getNamaLengkapPegawai(dokter.NIP) as DOKTER_REG"),
                DB::raw("DATE_FORMAT(pendaftaran.TANGGAL, '%d-%m-%Y %H:%i:%s') as TGLREG"),
                DB::raw("DATE_FORMAT(kunjungan.MASUK, '%d-%m-%Y %H:%i:%s') as TGLTERIMA"),
                DB::raw("CONCAT(DATEDIFF(kunjungan.MASUK, pendaftaran.TANGGAL), ' hari ', DATE_FORMAT(TIMEDIFF(kunjungan.MASUK, pendaftaran.TANGGAL), '%H:%i:%s')) as SELISIH"),
                DB::raw("DATEDIFF(kunjungan.MASUK, pendaftaran.TANGGAL) * 86400 + TIME_TO_SEC(TIMEDIFF(kunjungan.MASUK, pendaftaran.TANGGAL)) as SELISIH_DETIK"),
            ])
            ->join('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->join('pendaftaran.tujuan_pasien as tujuanPasien', 'pendaftaran.NOMOR', '=', 'tujuanPasien.NOPEN')
            ->join('pendaftaran.kunjungan as kunjungan', function ($join) {
                $join->on('pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
                    ->on('tujuanPasien.RUANGAN', '=', 'kunjungan.RUANGAN');
            })
            ->join('master.ruangan as ruangan', function ($join) {
                $join->on('tujuanPasien.RUANGAN', '=', 'ruangan.ID')
                    ->where('ruangan.JENIS', '=', 5);
            })
            ->leftJoin('master.dokter as dokter', 'tujuanPasien.DOKTER', '=', 'dokter.ID')
            ->whereIn('pendaftaran.STATUS', [1])
            ->whereNull('kunjungan.REF')
            ->whereIn('kunjungan.STATUS', [1, 2]);

        // Apply 'cara_bayar' filter if provided
        if ($ruangan) {
            $query->where('tujuanPasien.RUANGAN', 'LIKE', $vRuangan);
        }

        if ($dokter) {
            $query->where('dokter.NIP', $dokter);
        }

        // Filter berdasarkan tanggal
        $data = $query
            ->whereBetween('pendaftaran.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->get();

        // Kirim data ke frontend menggunakan Inertia
        return inertia("Laporan/WaktuTunggu/Print", [
            'data' => $data,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
            'namaRuangan' => $namaRuangan,
            'namaDokter' => $namaDokter,
        ]);
    }
}