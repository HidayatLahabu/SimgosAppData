<?php

namespace App\Http\Controllers\Layanan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaboratoriumController extends Controller
{
    public function index()
    {
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        /**
         * 1) Ambil order_lab (ringan) sebagai paginator agar punya links() / toArray()['links']
         */
        $order = DB::connection('mysql7')
            ->table('layanan.order_lab as ol')
            ->select('ol.NOMOR', 'ol.TANGGAL', 'ol.KUNJUNGAN', 'ol.DOKTER_ASAL', 'ol.STATUS')
            ->when($searchSubject, function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(ol.NOMOR) LIKE ?', ["%{$searchSubject}%"])
                    ->orWhereRaw('LOWER(ol.NOMOR) LIKE ?', ["%{$searchSubject}%"]);
            })
            ->orderByDesc('ol.TANGGAL')
            ->paginate(5) // gunakan paginate agar kita punya total + links
            ->appends(request()->query());

        // Ambil arrays dari paginator collection (original)
        $collection = $order->getCollection();
        $nomorList     = $collection->pluck('NOMOR')->filter()->unique()->values()->all();
        $kunjunganList = $collection->pluck('KUNJUNGAN')->filter()->unique()->values()->all();
        $dokterList    = $collection->pluck('DOKTER_ASAL')->filter()->unique()->values()->all();

        /**
         * 2) Ambil relasi pasien/pendaftaran/kunjungan (batch)
         * Jika list kosong, skip query dan gunakan koleksi kosong.
         */
        $relasi = collect();
        if (!empty($kunjunganList)) {
            $relasi = DB::connection('mysql7')
                ->table('pendaftaran.kunjungan as k')
                ->leftJoin('pendaftaran.pendaftaran as p', 'p.NOMOR', '=', 'k.NOPEN')
                ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'p.NORM')
                ->whereIn('k.NOMOR', $kunjunganList)
                ->select(
                    'k.NOMOR as kunjungan',
                    'k.STATUS as statusKunjungan',
                    'pasien.NORM as norm',
                    'pasien.NAMA as namaPasien'
                )
                ->get()
                ->keyBy('kunjungan');
        }

        /**
         * 3) Ambil dokter (batch) => pluck NIP keyed by ID
         */
        $dokter = collect();
        if (!empty($dokterList)) {
            $dokter = DB::connection('mysql7')
                ->table('master.dokter as d')
                ->leftJoin('master.pegawai as p', 'p.NIP', '=', 'd.NIP')
                ->whereIn('d.ID', $dokterList)
                ->select(
                    'd.ID',
                    'd.NIP',
                    DB::raw('master.getNamaLengkapPegawai(d.NIP) AS namaLengkap')
                )
                ->get()
                ->keyBy('ID');
        }

        /**
         * 4) Ambil status hasil_lab per ORDER_ID (grouped)
         */
        $hasil = collect();
        if (!empty($nomorList)) {
            $hasil = DB::connection('mysql7')
                ->table('layanan.order_detil_lab as od')
                ->leftJoin('layanan.hasil_lab as h', 'h.TINDAKAN_MEDIS', '=', 'od.REF')
                ->whereIn('od.ORDER_ID', $nomorList)
                ->select('od.ORDER_ID', DB::raw('MIN(h.STATUS) as statusHasil'))
                ->groupBy('od.ORDER_ID')
                ->get()
                ->keyBy('ORDER_ID');
        }

        /**
         * 5) Gabungkan semua data — hasilnya collection (masih kecil: page size)
         *    Tetap pertahankan nama field yang frontend harapkan.
         */
        $merged = $collection->map(function ($row) use ($relasi, $dokter, $hasil) {
            $r  = $relasi[$row->KUNJUNGAN] ?? null;
            $hs = $hasil[$row->NOMOR]->statusHasil ?? null;

            // NOTE: saya masukkan orderOleh sebagai string kosong ketika tidak ada
            // Jika sebelumnya frontend mengharapkan hasil fungsi master.getNamaLengkapPegawai,
            // kita tidak bisa panggil function DB per row di PHP — jadi kita simpan null atau nip.
            $orderOleh = $dokter[$row->DOKTER_ASAL]->namaLengkap ?? null;

            return (object)[ // pakai object supaya bentuk mirip stdClass query result
                'nomor'           => $row->NOMOR,
                'tanggal'         => $row->TANGGAL,
                'kunjungan'       => $row->KUNJUNGAN,
                'orderOleh'       => $orderOleh,
                'norm'            => $r->norm ?? null,
                'nama'            => $r->namaPasien ?? null,
                'statusKunjungan' => $r->statusKunjungan ?? null,
                'statusOrder'     => $row->STATUS,
                'statusHasil'     => $hs,
            ];
        });

        /**
         * 6) Masukkan kembali collection hasil merge ke paginator
         *    LengthAwarePaginator memiliki method setCollection — paginate() mengembalikan LengthAwarePaginator
         */
        $order->setCollection($merged->values()); // values() agar index rapi

        /**
         * 7) Bentuk array links + data sama seperti sebelumnya untuk frontend
         */
        $orderArray = $order->toArray(); // sekarang 'data' berisi merged, 'links' ada

        $ruangan = DB::connection('mysql2')->table('master.ruangan as ruangan')
            ->select('ruangan.JENIS_KUNJUNGAN', 'ruangan.DESKRIPSI')
            ->where('ruangan.STATUS', 1)
            ->where('ruangan.JENIS', 3)
            ->where(function ($query) {
                $query->where('ruangan.DESKRIPSI', 'LIKE', '%jalan%')
                    ->orWhere('ruangan.DESKRIPSI', 'LIKE', '%poli%')
                    ->orWhere('ruangan.DESKRIPSI', 'LIKE', '%inap%')
                    ->orWhere('ruangan.DESKRIPSI', 'LIKE', '%darurat%')
                    ->orWhere('ruangan.DESKRIPSI', 'LIKE', '%vk%')
                    ->orWhere('ruangan.DESKRIPSI', 'LIKE', '%bersalin%')
                    ->orWhere('ruangan.DESKRIPSI', 'LIKE', '%laboratorium%');
            })
            ->orderBy('ruangan.JENIS_KUNJUNGAN')
            ->get();

        return inertia("Layanan/Laboratorium/Index", [
            'ruangan' => $ruangan,
            'dataTable' => [
                'data'  => $orderArray['data'],
                'links' => $orderArray['links'],
            ],
            'queryParams' => request()->all(),
        ]);
    }

    public function filterByTime($filter)
    {
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Base query (untuk data)
        $baseQuery = DB::connection('mysql7')->table('layanan.order_lab as orderLab')
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'orderLab.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'orderLab.DOKTER_ASAL')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('layanan.order_detil_lab as orderDetail', 'orderDetail.ORDER_ID', '=', 'orderLab.NOMOR')
            ->leftJoin('layanan.hasil_lab as hasil', 'hasil.TINDAKAN_MEDIS', '=', 'orderDetail.REF');

        // Filter waktu
        switch ($filter) {
            case 'hariIni':
                $baseQuery->whereDate('orderLab.TANGGAL', now()->format('Y-m-d'));
                $header = 'HARI INI';
                $text = 'PASIEN';
                break;

            case 'mingguIni':
                $baseQuery->whereBetween('orderLab.TANGGAL', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $header = 'MINGGU INI';
                $text = 'PASIEN';
                break;

            case 'bulanIni':
                $baseQuery->whereMonth('orderLab.TANGGAL', now()->month)
                    ->whereYear('orderLab.TANGGAL', now()->year);
                $header = 'BULAN INI';
                $text = 'PASIEN';
                break;

            case 'tahunIni':
                $baseQuery->whereYear('orderLab.TANGGAL', now()->year);
                $header = 'TAHUN INI';
                $text = 'PASIEN';
                break;

            default:
                abort(404, 'Filter tidak ditemukan');
        }

        // Query data (clone)
        $dataQuery = clone $baseQuery;

        $dataQuery->selectRaw('
        orderLab.NOMOR as nomor,
        MIN(orderLab.TANGGAL) as tanggal,
        MIN(orderLab.KUNJUNGAN) as kunjungan,
        master.getNamaLengkapPegawai(dokter.NIP) as orderOleh,
        MIN(pasien.NORM) as norm,                
        master.getNamaLengkap(pasien.NORM) as nama,
        MIN(kunjungan.STATUS) as statusKunjungan,
        MIN(orderLab.STATUS) as statusOrder,
        MIN(hasil.STATUS) as statusHasil
    ')
            ->groupBy('orderLab.NOMOR');

        // Search
        if ($searchSubject) {
            $dataQuery->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ["%{$searchSubject}%"])
                    ->orWhereRaw('LOWER(orderLab.NOMOR) LIKE ?', ["%{$searchSubject}%"])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ["%{$searchSubject}%"]);
            });
        }

        // Count (lebih cepat, tidak include join berat)
        $countQuery = DB::connection('mysql7')->table('layanan.order_lab as orderLab');

        // Filter waktu untuk count
        switch ($filter) {
            case 'hariIni':
                $countQuery->whereDate('orderLab.TANGGAL', now()->format('Y-m-d'));
                break;
            case 'mingguIni':
                $countQuery->whereBetween('orderLab.TANGGAL', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                break;
            case 'bulanIni':
                $countQuery->whereMonth('orderLab.TANGGAL', now()->month)
                    ->whereYear('orderLab.TANGGAL', now()->year);
                break;
            case 'tahunIni':
                $countQuery->whereYear('orderLab.TANGGAL', now()->year);
                break;
        }

        // Search untuk count
        if ($searchSubject) {
            $countQuery->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'orderLab.KUNJUNGAN')
                ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
                ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM');

            $countQuery->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ["%{$searchSubject}%"])
                    ->orWhereRaw('LOWER(orderLab.NOMOR) LIKE ?', ["%{$searchSubject}%"])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ["%{$searchSubject}%"]);
            });
        }

        $count = $countQuery->distinct('orderLab.NOMOR')->count('orderLab.NOMOR');

        // Pagination
        $data = $dataQuery->orderByDesc('orderLab.TANGGAL')
            ->paginate(5)
            ->appends(request()->query());

        $dataArray = $data->toArray();

        return inertia("Layanan/Laboratorium/Index", [
            'dataTable' => [
                'data'  => $dataArray['data'],
                'links' => $dataArray['links'], // aman untuk React
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
        $queryDetail = DB::connection('mysql7')->table('layanan.order_lab as order')
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
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'order.TUJUAN')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'order.OLEH')
            ->where('order.NOMOR', $id)
            ->firstOrFail();

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
            ->where('hasilLab.HASIL', '!=', '')
            ->get();

        // Fetch data catatan (main lab order details)
        $catatanID = $queryDetail->NOMOR;
        $queryCatatan = DB::connection('mysql7')->table('pendaftaran.kunjungan as kunjungan')
            ->select(
                'catatan.KUNJUNGAN',
                'catatan.TANGGAL',
                'catatan.CATATAN',
                DB::raw('master.getNamaLengkapPegawai(dokter.NIP) as DOKTER_LAB'),
                'catatan.STATUS',
            )
            ->leftJoin('layanan.catatan_hasil_lab as catatan', 'catatan.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('layanan.order_lab as order', 'order.NOMOR', '=', 'kunjungan.REF')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'catatan.DOKTER')
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

    public function hasil()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql7')->table('layanan.hasil_lab as hasil')
            ->leftJoin('layanan.tindakan_medis as tindakanMedis', 'hasil.TINDAKAN_MEDIS', '=', 'tindakanMedis.ID')
            ->leftJoin('master.tindakan as tindakanLab', 'tindakanMedis.TINDAKAN', '=', 'tindakanLab.ID')
            ->leftJoin('master.parameter_tindakan_lab as parameter', 'hasil.PARAMETER_TINDAKAN', '=', 'parameter.ID')
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'tindakanMedis.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'kunjungan.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM');

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(kunjungan.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(hasil.ID) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Group by 'nomor' and select the first occurrence for other fields
        $query->selectRaw('
            hasil.TANGGAL as tanggal,
            hasil.ID as idHasil,
            kunjungan.NOMOR as kunjungan,
            pendaftaran.NORM as norm,
            master.getNamaLengkap(pasien.NORM) as namaPasien,
            pasien.JENIS_KELAMIN as kelamin,
            tindakanLab.NAMA as tindakan,
            parameter.PARAMETER as parameter,
            hasil.HASIL as hasil,
            hasil.SATUAN as satuan,
            hasil.STATUS as status
        ');

        // Paginate the results
        $data = $query->orderByDesc('hasil.TANGGAL')->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Layanan/Laboratorium/Hasil", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function hasilRekap(Request $request)
    {
        $dari = $request->input('dari_tanggal');
        $sampai = $request->input('sampai_tanggal');

        $rekap = DB::connection('mysql7')->table('layanan.hasil_lab as hasil')
            ->leftJoin('layanan.tindakan_medis as tindakanMedis', 'hasil.TINDAKAN_MEDIS', '=', 'tindakanMedis.ID')
            ->leftJoin('master.tindakan as tindakanLab', 'tindakanMedis.TINDAKAN', '=', 'tindakanLab.ID')
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'tindakanMedis.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'kunjungan.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->when($dari && $sampai, function ($q) use ($dari, $sampai) {
                $q->whereBetween(DB::raw('DATE(hasil.TANGGAL)'), [$dari, $sampai]);
            })
            ->selectRaw('
            pasien.JENIS_KELAMIN as kelamin,
            tindakanLab.NAMA as tindakan,
            COUNT(*) as jumlah
        ')
            ->groupBy('pasien.JENIS_KELAMIN', 'tindakanLab.NAMA')
            ->get();

        // Pivot hasil jadi per tindakan
        $rekapPivot = [];
        foreach ($rekap as $row) {
            $tindakan = $row->tindakan ?? 'Tidak diketahui';
            if (!isset($rekapPivot[$tindakan])) {
                $rekapPivot[$tindakan] = [
                    'tindakan' => $tindakan,
                    'laki' => 0,
                    'perempuan' => 0,
                    'total' => 0,
                ];
            }

            if ($row->kelamin == 1 || strtoupper($row->kelamin) == 'L') {
                $rekapPivot[$tindakan]['laki'] += $row->jumlah;
            } elseif ($row->kelamin == 2 || strtoupper($row->kelamin) == 'P') {
                $rekapPivot[$tindakan]['perempuan'] += $row->jumlah;
            }
            $rekapPivot[$tindakan]['total'] += $row->jumlah;
        }

        return inertia("Layanan/Laboratorium/PrintRekap", [
            'rekap' => array_values($rekapPivot),
            'dari' => $dari,
            'sampai' => $sampai,
        ]);
    }


    public function catatan()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql7')->table('layanan.catatan_hasil_lab as catatan')
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'catatan.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'kunjungan.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.dokter as dokter', 'catatan.DOKTER', '=', 'dokter.ID');

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(kunjungan.NOMOR) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Group by 'nomor' and select the first occurrence for other fields
        $query->selectRaw('
            catatan.TANGGAL as tanggal,
            kunjungan.NOMOR as kunjungan,
            pendaftaran.NORM as norm,
            master.getNamaLengkap(pasien.NORM) as namaPasien,
            catatan.CATATAN as catatan,
            master.getNamaLengkapPegawai(dokter.NIP) as dokter
        ');


        // Paginate the results
        $data = $query->orderByDesc('catatan.TANGGAL')->paginate(10)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Return Inertia view with paginated data
        return inertia("Layanan/Laboratorium/Catatan", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'queryParams' => request()->all()
        ]);
    }

    public function print(Request $request)
    {
        // Validasi input
        $request->validate([
            'jenisPenjamin'  => 'nullable|integer',
            'ruangan' => 'nullable|integer',
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        // Ambil nilai input
        $jenisPenjamin  = $request->input('jenisPenjamin');
        $jenisKunjungan = $request->input('ruangan');
        $dariTanggal    = $request->input('dari_tanggal');
        $sampaiTanggal  = $request->input('sampai_tanggal');
        $dariTanggal = Carbon::parse($dariTanggal)->format('Y-m-d H:i:s');
        $sampaiTanggal = Carbon::parse($sampaiTanggal)->endOfDay()->format('Y-m-d H:i:s');

        // Variable default untuk label
        $penjamin = 'Semua Penjamin';
        $kunjungan = 'Semua Kunjungan';

        $query = DB::connection('mysql7')->table('layanan.hasil_lab as hasilLab')
            ->select([
                'hasilLab.ID as idHasil',
                'hasilLab.TANGGAL as tanggalHasil',
                'tindakan.NAMA as namaTindakan',
                'hasilLab.HASIL as hasil',
                'hasilLab.SATUAN as satuan',
                'pendaftaran.NORM as norm',
                DB::raw('master.getNamaLengkap(pasien.NORM) as namaPasien'),
                DB::raw('master.getNamaLengkapPegawai(dokter.NIP) as pelaksana'),
                'parameterTindakan.PARAMETER as parameterTindakan',
                'ruangan.DESKRIPSI as ruangan'
            ])
            ->join('layanan.tindakan_medis as tindakanMedis', 'tindakanMedis.ID', '=', 'hasilLab.TINDAKAN_MEDIS')
            ->join('master.parameter_tindakan_lab as parameterTindakan', 'parameterTindakan.ID', '=', 'hasilLab.PARAMETER_TINDAKAN')
            ->join('aplikasi.pengguna as dokter', 'dokter.ID', '=', 'hasilLab.OLEH')
            ->join('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'tindakanMedis.KUNJUNGAN')
            ->join('master.tindakan as tindakan', 'tindakan.ID', '=', 'tindakanMedis.TINDAKAN')
            ->join('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->join('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->join('pendaftaran.penjamin as jenisPenjamin', 'jenisPenjamin.NOPEN', '=', 'kunjungan.NOPEN')
            ->join('pendaftaran.tujuan_pasien as tujuanPasien', 'tujuanPasien.NOPEN', '=', 'kunjungan.NOPEN')
            ->join('master.ruangan as ruangan', 'ruangan.ID', '=', 'tujuanPasien.RUANGAN')
            ->whereBetween('hasilLab.TANGGAL', [$dariTanggal, $sampaiTanggal])
            ->where('hasilLab.HASIL', '!=', '')
            ->where('tindakanMedis.STATUS', 1)
            ->where('tindakan.STATUS', 1)
            ->where('tindakan.JENIS', 8)
            ->where('parameterTindakan.STATUS', 1)
            ->where('hasilLab.STATUS', 1)
            ->groupBy('hasilLab.ID', 'parameterTindakan.PARAMETER', 'jenisPenjamin.NOMOR')
            ->orderBy('pasien.NAMA')
            ->orderBy('hasilLab.TANGGAL');

        // Tambahan untuk BPJS
        if ($jenisPenjamin == 2) {
            $query->addSelect([
                'jenisPenjamin.NOMOR as nomorSEP',
                'kunjunganBpjs.tglSEP as tanggalSEP'
            ])
                ->join('bpjs.kunjungan as kunjunganBpjs', 'kunjunganBpjs.noSEP', '=', 'jenisPenjamin.NOMOR')
                ->whereNotNull('jenisPenjamin.NOMOR')
                ->where('jenisPenjamin.NOMOR', '!=', '')
                ->groupBy('kunjunganBpjs.tglSEP');;

            $penjamin = 'BPJS KESEHATAN';
        } elseif ($jenisPenjamin == 1) {
            $query->where('jenisPenjamin.NOMOR', '')->where('jenisPenjamin.JENIS', 1);
            $penjamin = 'Non BPJS KESEHATAN';
        } else {
            $query->where('jenisPenjamin.JENIS', '>', 0);
            $penjamin = 'Semua Penjamin';
        }

        if ($jenisKunjungan > 0) {
            $query->where('ruangan.JENIS_KUNJUNGAN', $jenisKunjungan);

            $ruangan = DB::connection('mysql2')->table('master.ruangan as ruangan')
                ->select('ruangan.DESKRIPSI as namaRuangan')
                ->where('ruangan.STATUS', 1)
                ->where('ruangan.JENIS', 3)
                ->where('ruangan.JENIS_KUNJUNGAN', $jenisKunjungan)
                ->where(function ($query) {
                    $query->where('ruangan.DESKRIPSI', 'LIKE', '%jalan%')
                        ->orWhere('ruangan.DESKRIPSI', 'LIKE', '%poli%')
                        ->orWhere('ruangan.DESKRIPSI', 'LIKE', '%inap%')
                        ->orWhere('ruangan.DESKRIPSI', 'LIKE', '%darurat%')
                        ->orWhere('ruangan.DESKRIPSI', 'LIKE', '%vk%')
                        ->orWhere('ruangan.DESKRIPSI', 'LIKE', '%bersalin%')
                        ->orWhere('ruangan.DESKRIPSI', 'LIKE', '%laboratorium%');
                })
                ->first();

            $kunjungan = $ruangan->namaRuangan;
        } else {
            $query->where('ruangan.JENIS_KUNJUNGAN', '>', 0);
        }

        $data = $query->cursor();

        // Kirim data ke frontend menggunakan Inertia
        return inertia("Layanan/Laboratorium/Print", [
            'data'              => $data,
            'dariTanggal'       => $dariTanggal,
            'sampaiTanggal'     => $sampaiTanggal,
            'jenisPenjamin'     => $penjamin,
            'jenisKunjungan'    => $kunjungan,
        ]);
    }
}
