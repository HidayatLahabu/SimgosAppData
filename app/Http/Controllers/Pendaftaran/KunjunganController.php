<?php

namespace App\Http\Controllers\Pendaftaran;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterRuanganModel;
use App\Models\PendaftaranKunjunganModel;

class KunjunganController extends Controller
{
    public function index()
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select(
                'kunjungan.NOMOR as nomor',
                'pasien.NAMA as nama',
                'pasien.NORM as norm',
                'ruangan.DESKRIPSI as ruangan',
                'kunjungan.MASUK as masuk',
                'kunjungan.KELUAR as keluar',
                'kunjungan.STATUS as status',
            )
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->where('pasien.STATUS', 1);

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(kunjungan.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('kunjungan.MASUK')->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Hitung rata-rata
        $rataRata = $this->rataRata();

        $ruangan = MasterRuanganModel::where('JENIS', 5)
            ->whereIn('JENIS_KUNJUNGAN', [1, 2, 3, 4, 5])
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Kunjungan/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'rataRata' => $rataRata, // Pass rata-rata data to frontend
            'ruangan' => $ruangan,
            'queryParams' => request()->all()
        ]);
    }

    protected function rataRata()
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->selectRaw('
            ROUND(COUNT(*) / COUNT(DISTINCT DATE(kunjungan.MASUK))) AS rata_rata_per_hari,
            ROUND(COUNT(*) / COUNT(DISTINCT WEEK(kunjungan.MASUK, 1))) AS rata_rata_per_minggu,
            ROUND(COUNT(*) / COUNT(DISTINCT DATE_FORMAT(kunjungan.MASUK, "%Y-%m"))) AS rata_rata_per_bulan,
            ROUND(COUNT(*) / COUNT(DISTINCT YEAR(kunjungan.MASUK))) AS rata_rata_per_tahun
        ')
            ->whereIn('STATUS', [1, 2])
            ->first();
    }

    public function filterByTime($filter)
    {
        // Get the search term from the request
        $searchSubject = request('search') ? strtolower(request('search')) : null;

        // Start building the query using the query builder
        $query = DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select(
                'kunjungan.NOMOR as nomor',
                'pasien.NAMA as nama',
                'pasien.NORM as norm',
                'ruangan.DESKRIPSI as ruangan',
                'kunjungan.MASUK as masuk',
                'kunjungan.KELUAR as keluar',
                'kunjungan.STATUS as status',
            )
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->where('pasien.STATUS', 1);

        // Clone query for count calculation
        $countQuery = clone $query;

        switch ($filter) {
            case 'hariIni':
                $query->whereDate('kunjungan.MASUK', now()->format('Y-m-d'));
                $countQuery->whereDate('kunjungan.MASUK', now()->format('Y-m-d'));
                $header = 'HARI INI';
                break;

            case 'mingguIni':
                $query->whereBetween('kunjungan.MASUK', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $countQuery->whereBetween('kunjungan.MASUK', [
                    now()->startOfWeek()->format('Y-m-d'),
                    now()->endOfWeek()->format('Y-m-d')
                ]);
                $header = 'MINGGU INI';
                break;

            case 'bulanIni':
                $query->whereMonth('kunjungan.MASUK', now()->month)
                    ->whereYear('kunjungan.MASUK', now()->year);
                $countQuery->whereMonth('kunjungan.MASUK', now()->month)
                    ->whereYear('kunjungan.MASUK', now()->year);
                $header = 'BULAN INI';
                break;

            case 'tahunIni':
                $query->whereYear('kunjungan.MASUK', now()->year);
                $countQuery->whereYear('kunjungan.MASUK', now()->year);
                $header = 'TAHUN INI';
                break;

            default:
                abort(404, 'Filter not found');
        }

        // Get count
        $count = $countQuery->count();

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pendaftaran.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pendaftaran.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('pendaftaran.NOMOR')->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Add search filter if provided
        if ($searchSubject) {
            $query->where(function ($q) use ($searchSubject) {
                $q->whereRaw('LOWER(pasien.NAMA) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(kunjungan.NOMOR) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%']);
            });
        }

        // Paginate the results
        $data = $query->orderByDesc('kunjungan.MASUK')->paginate(5)->appends(request()->query());

        // Convert data to array
        $dataArray = $data->toArray();

        // Hitung rata-rata
        $rataRata = $this->rataRata();

        // Return Inertia view with paginated data
        return inertia("Pendaftaran/Kunjungan/Index", [
            'dataTable' => [
                'data' => $dataArray['data'], // Only the paginated data
                'links' => $dataArray['links'], // Pagination links
            ],
            'rataRata' => $rataRata, // Pass rata-rata data to frontend
            'queryParams' => request()->all(),
            'header' => $header,
            'totalCount' => number_format($count, 0, ',', '.'),
        ]);
    }

    public function detail($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as NOMOR_KUNJUNGAN',
                'kunjungan.NOPEN as NOMOR_PENDAFTARAN',
                'pasien.NORM as NORM',
                'pasien.NAMA as NAMA_PASIEN',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as DPJP'),
                'ruangan.DESKRIPSI as RUANGAN_TUJUAN',
                'ruang_kamar.KAMAR as KAMAR_TUJUAN',
                'ruang_kamar_tidur.TEMPAT_TIDUR as TEMPAT_TIDUR',
                'kunjungan.MASUK as TANGGAL_MASUK',
                'kunjungan.KELUAR as TANGGAL_KELUAR',
                'kunjungan.REF as REF',
                'penerima_kunjungan.NAMA as DITERIMA_OLEH',
                'kunjungan.BARU as STATUS_KUNJUNGAN',
                'kunjungan.TITIPAN as TITIPAN',
                'kunjungan.TITIPAN_KELAS as TITIPAN_KELAS',
                'kunjungan.STATUS as STATUS_AKTIFITAS_KUNJUNGAN',
                'final_kunjungan.NAMA as FINAL_HASIL_OLEH',
                'kunjungan.FINAL_HASIL_TANGGAL as FINAL_HASIL_TANGGAL',
                'perubahan_tanggal_kunjungan.TANGGAL_LAMA as TANGGAL_KUNJUNGAN_LAMA',
                'perubahan_tanggal_kunjungan.TANGGAL_BARU as TANGGAL_KUNJUNGAN_BARU',
                'perubahan_oleh.NAMA as PERUBAHAN_KUNJUNGAN_OLEH',
                'perubahan_tanggal_kunjungan.STATUS as STATUS_PERUBAHAN_KUNJUNGAN',
            ])
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->leftJoin('master.ruang_kamar as ruang_kamar', 'ruang_kamar.RUANGAN', '=', 'kunjungan.RUANGAN')
            ->leftJoin('master.ruang_kamar_tidur as ruang_kamar_tidur', 'ruang_kamar_tidur.ID', '=', 'kunjungan.RUANG_KAMAR_TIDUR')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'kunjungan.DPJP')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('master.diagnosa_masuk as diagnosa_masuk', 'diagnosa_masuk.ID', '=', 'pendaftaran.DIAGNOSA_MASUK')
            ->leftJoin('aplikasi.pengguna as penerima_kunjungan', 'penerima_kunjungan.ID', '=', 'kunjungan.DITERIMA_OLEH')
            ->leftJoin('aplikasi.pengguna as final_kunjungan', 'final_kunjungan.ID', '=', 'kunjungan.FINAL_HASIL_OLEH')
            ->leftJoin('pendaftaran.perubahan_tanggal_kunjungan AS perubahan_tanggal_kunjungan', 'perubahan_tanggal_kunjungan.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('aplikasi.pengguna AS perubahan_oleh', 'perubahan_oleh.ID', '=', 'perubahan_tanggal_kunjungan.OLEH')
            ->where('kunjungan.NOMOR', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('kunjungan.index')->with('error', 'Data not found.');
        }

        //nomor pendaftaran
        $noPendaftaran = $query->NOMOR_PENDAFTARAN;

        // Fetch kunjungan data using the new function
        $kunjungan = $this->getKunjungan($noPendaftaran);

        // Return Inertia view with the encounter data
        return inertia("Pendaftaran/Kunjungan/Detail", [
            'detail'            => $query,
            'dataKunjungan'     => $kunjungan,
            'nomorPendaftaran'  => $noPendaftaran,
        ]);
    }

    protected function getKunjungan($noPendaftaran)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'kunjungan.NOPEN as pendaftaran',
                'kunjungan.MASUK as masuk',
                'kunjungan.KELUAR as keluar',
                'ruangan.DESKRIPSI as ruangan',
            ])
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->where('kunjungan.NOPEN', $noPendaftaran)
            ->get();
    }

    public function tableRme($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomorKunjungan',
                'kunjungan.NOPEN as nomorPendaftaran',
                'pasien.NORM as norm',
                'pasien.NAMA as namaPasien',
            ])
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->where('kunjungan.NOMOR', $id)
            ->distinct()
            ->first();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('kunjungan.detail', $id)->with('error', 'Data not found.');
        }

        // Get nomor pendaftaran
        $pendaftaran = $query->nomorPendaftaran;
        $kunjungan   = $query->nomorKunjungan;
        $pasien      = $query->namaPasien;
        $norm        = $query->norm;

        // Fetch diagnosa data
        $diagnosa = $this->getDiagnosa($pendaftaran);
        $diagnosaId = $diagnosa ? $diagnosa->id : null;

        // Fetch anamnesis data
        $anamnesis = $this->getAnamnesis($kunjungan);
        $anamnesisId = $anamnesis ? $anamnesis->id : null;

        // Fetch asuhan keperawatan data
        $askep = $this->getAskep($kunjungan);
        $askepId = $askep ? $askep->id : null;

        // Fetch CPPT data
        $cppt = $this->getCppt($kunjungan);
        $cpptId = $cppt ? $cppt->id : null;

        // Fetch jadwal kontrol data
        $jadwalKontrol = $this->getJadwalKontrol($kunjungan);
        $jadwalKontrolId = $jadwalKontrol ? $jadwalKontrol->id : null;

        // Fetch tanda vital data
        $tandaVital = $this->getTandaVital($kunjungan);
        $tandaVitalId = $tandaVital ? $tandaVital->id : null;

        return inertia("Pendaftaran/Kunjungan/TableRme", [
            'dataTable'         => $query,
            'nomorKunjungan'    => $kunjungan,
            'nomorPendaftaran'  => $pendaftaran,
            'namaPasien'        => $pasien,
            'normPasien'        => $norm,
            'diagnosa'          => $diagnosaId,
            'anamnesis'         => $anamnesisId,
            'askep'             => $askepId,
            'cppt'              => $cpptId,
            'jadwalKontrol'     => $jadwalKontrolId,
            'tandaVital'        => $tandaVitalId,
        ]);
    }

    protected function getDiagnosa($noPendaftaran)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'diagnosa.NOPEN as id',
            ])
            ->leftJoin('medicalrecord.diagnosa as diagnosa', 'diagnosa.NOPEN', '=', 'kunjungan.NOPEN')
            ->where('kunjungan.NOPEN', $noPendaftaran)
            ->first();
    }

    public function diagnosa($id)
    {

        $dataKunjungan = DB::connection('mysql5')->table('medicalrecord.diagnosa as diagnosa')
            ->select([
                'kunjungan.NOMOR as nomorKunjungan',
                'kunjungan.NOPEN as nomorPendaftaran',
                'pasien.NORM as norm',
                'pasien.NAMA as namaPasien',
            ])
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOPEN', '=', 'diagnosa.NOPEN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'diagnosa.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->where('diagnosa.NOPEN', $id)
            ->distinct()
            ->first();

        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.diagnosa as diagnosa')
            ->select([
                'diagnosa.NOPEN as pendaftaran',
                'diagnosa.ID as id',
                'diagnosa.TANGGAL as tanggal',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as oleh'),
                'diagnosa.STATUS as status',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'diagnosa.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('diagnosa.NOPEN', $id)
            ->get();

        // Get nomor pendaftaran
        $nomorPendaftaran = $dataKunjungan->nomorPendaftaran;
        $nomorKunjungan   = $dataKunjungan->nomorKunjungan;
        $namaPasien       = $dataKunjungan->namaPasien;
        $normPasien       = $dataKunjungan->norm;

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('kunjungan.detail', $nomorKunjungan)->with('error', 'Data not found.');
        }

        return inertia("Pendaftaran/Kunjungan/TableDiagnosa", [
            'dataTable'         => $query,
            'nomorKunjungan'    => $nomorKunjungan,
            'nomorPendaftaran'  => $nomorPendaftaran,
            'namaPasien'        => $namaPasien,
            'normPasien'        => $normPasien,
        ]);
    }

    public function detailDiagnosa($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.diagnosa as diagnosa')
            ->select([
                'kunjungan.NOPEN as NOMOR_PENDAFTARAN',
                'kunjungan.NOMOR as NOMOR_KUNJUNGAN',
                'pasien.NORM as NORM',
                'pasien.NAMA as NAMA_PASIEN',
                'diagnosa.ID as ID',
                'diagnosa.KODE as KODE',
                'diagnosa.DIAGNOSA as DIAGNOSA',
                'diagnosa.UTAMA as UTAMA',
                'diagnosa.INACBG as INACBG',
                'diagnosa.BARU as BARU',
                'diagnosa.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'diagnosa.STATUS as STATUS',
                'diagnosa.INA_GROUPER as INA_GROUPER',
            ])
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'diagnosa.NOPEN')
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOPEN', '=', 'pendaftaran.NOMOR')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'diagnosa.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('diagnosa.ID', $id)
            ->distinct()
            ->first();

        $kunjungan = $query->NOMOR_KUNJUNGAN;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'            => $query,
            'nomorKunjungan'    => $kunjungan,
            'judulRme'          => 'DIAGNOSA',
        ]);
    }

    protected function getAnamnesis($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'anamnesis.ID as id',
            ])
            ->leftJoin('medicalrecord.anamnesis as anamnesis', 'anamnesis.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function anamnesis($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.anamnesis as anamnesis')
            ->select([
                'kunjungan.NOPEN as NOMOR_PENDAFTARAN',
                'kunjungan.NOMOR as NOMOR_KUNJUNGAN',
                'pasien.NORM as NORM',
                'pasien.NAMA as NAMA_PASIEN',
                'anamnesis.ID as ID',
                'anamnesis.DESKRIPSI as DESKRIPSI',
                'rpp.DESKRIPSI as RPP',
                'keluhanUtama.DESKRIPSI as KELUHAN_UTAMA',
                'anamnesis.RPS as RPS',
                'anamnesis.RPT as RPT',
                'anamnesis.RPK as RPK',
                'anamnesis.RL as RL',
                'riwayatAlergi.JENIS as RIWAYAT_ALERGI_JENIS',
                'riwayatAlergi.DESKRIPSI as RIWAYAT_ALERGI_DESKRIPSI',
                'riwayatAlergi.KODE_REFERENSI as RIWAYAT_ALERGI_KODE_REFERENSI',
                'anamnesis.REAKSI_ALERGI as REAKSI_ALERGI',
                'anamnesisDiperoleh.AUTOANAMNESIS as AUTOANAMNESIS',
                'anamnesisDiperoleh.ALLOANAMNESIS as ALLOANAMNESIS',
                'anamnesisDiperoleh.DARI as DARI',
                'anamnesis.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'anamnesis.STATUS as STATUS',
            ])
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'anamnesis.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('medicalrecord.rpp as rpp', 'rpp.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('medicalrecord.keluhan_utama as keluhanUtama', 'keluhanUtama.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('medicalrecord.anamnesis_diperoleh as anamnesisDiperoleh', 'anamnesisDiperoleh.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('medicalrecord.riwayat_alergi as riwayatAlergi', 'riwayatAlergi.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'anamnesis.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('anamnesis.ID', $id)
            ->distinct()
            ->first();

        $kunjungan = $query->NOMOR_KUNJUNGAN;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'            => $query,
            'nomorKunjungan'    => $kunjungan,
            'judulRme'          => 'ANAMNESIS',
        ]);
    }

    protected function getAskep($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'askep.ID as id',
            ])
            ->leftJoin('medicalrecord.asuhan_keperawatan as askep', 'askep.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function askep($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.asuhan_keperawatan as askep')
            ->select([
                'kunjungan.NOPEN as NOMOR_PENDAFTARAN',
                'kunjungan.NOMOR as NOMOR_KUNJUNGAN',
                'pasien.NORM as NORM',
                'pasien.NAMA as NAMA_PASIEN',
                'askep.ID as ID',
                'askep.SUBJECKTIF as SUBJECKTIF',
                'askep.OBJEKTIF as OBJEKTIF',
                'askep.SUBJECT_MINOR as SUBJECT_MINOR',
                'askep.OBJECT_MINOR as OBJECT_MINOR',
                'askep.FAKTOR_RESIKO as FAKTOR_RESIKO',
                'askep.DIAGNOSA as DIAGNOSA',
                'askep.DESK_DIAGNOSA as DESK_DIAGNOSA',
                'askep.LAMA_INTEVENSI as LAMA_INTEVENSI',
                'askep.JENIS_LAMA_INTERVENSI as JENIS_LAMA_INTERVENSI',
                'askep.TUJUAN as TUJUAN',
                'askep.DESK_TUJUAN as DESK_TUJUAN',
                'askep.KRITERIA_HASIL as KRITERIA_HASIL',
                'askep.INTERVENSI as INTERVENSI',
                'askep.DESK_INTERVENSI as DESK_INTERVENSI',
                'askep.OBSERVASI as OBSERVASI',
                'askep.THEURAPEUTIC as THEURAPEUTIC',
                'askep.EDUKASI as EDUKASI',
                'askep.KOLABORASI as KOLABORASI',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'askep.USER_VERIFIKASI as USER_VERIFIKASI',
                'askep.TANGGAL_VERIFIKASI as TANGGAL_VERIFIKASI',
                'askep.TANGGAL_INPUT as TANGGAL_INPUT',
                'askep.TANGGAL as TANGGAL',
                'askep.STATUS as STATUS',
            ])
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'askep.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'askep.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('askep.ID', $id)
            ->distinct()
            ->first();

        $kunjungan = $query->NOMOR_KUNJUNGAN;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'            => $query,
            'nomorKunjungan'    => $kunjungan,
            'judulRme'          => 'ASUHAN KEPERAWATAN',
        ]);
    }

    protected function getCppt($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'cppt.KUNJUNGAN as id',
            ])
            ->leftJoin('medicalrecord.cppt as cppt', 'cppt.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function cppt($id)
    {
        $dataKunjungan = DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomorKunjungan',
                'kunjungan.NOPEN as nomorPendaftaran',
                'pasien.NORM as norm',
                'pasien.NAMA as namaPasien',
            ])
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->where('kunjungan.NOMOR', $id)
            ->distinct()
            ->first();

        // Fetch the specific data
        $query = DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'cppt.KUNJUNGAN as kunjungan',
                'cppt.ID as id',
                'cppt.TANGGAL as tanggal',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as oleh'),
                'cppt.STATUS as status',
            ])
            ->leftJoin('medicalrecord.cppt as cppt', 'cppt.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'cppt.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('kunjungan.NOMOR', $id)
            ->get();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('kunjungan.detail', $id)->with('error', 'Data not found.');
        }

        // Get nomor pendaftaran
        $nomorPendaftaran = $dataKunjungan->nomorPendaftaran;
        $nomorKunjungan   = $dataKunjungan->nomorKunjungan;
        $namaPasien       = $dataKunjungan->namaPasien;
        $normPasien       = $dataKunjungan->norm;

        return inertia("Pendaftaran/Kunjungan/TableCppt", [
            'dataTable'         => $query,
            'nomorKunjungan'    => $nomorKunjungan,
            'nomorPendaftaran'  => $nomorPendaftaran,
            'namaPasien'        => $namaPasien,
            'normPasien'        => $normPasien,
        ]);
    }

    public function detailCppt($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.cppt as cppt')
            ->select([
                'kunjungan.NOPEN as NOMOR_PENDAFTARAN',
                'cppt.KUNJUNGAN as NOMOR_KUNJUNGAN',
                'pasien.NORM as NORM',
                'pasien.NAMA as NAMA_PASIEN',
                'cppt.ID as ID',
                'cppt.TANGGAL as TANGGAL',
                'cppt.SUBYEKTIF as SUBYEKTIF',
                'cppt.OBYEKTIF as OBYEKTIF',
                'cppt.ASSESMENT as ASSESMENT',
                'cppt.PLANNING as PLANNING',
                'cppt.INSTRUKSI as INSTRUKSI',
                'cppt.TULIS as TULIS',
                'cppt.JENIS as JENIS',
                DB::raw('CONCAT(tenagaMedis.GELAR_DEPAN, " ", tenagaMedis.NAMA, " ", tenagaMedis.GELAR_BELAKANG) as TENAGA_MEDIS'),
                'cppt.STATUS_TBAK as STATUS_TBAK',
                'cppt.STATUS_SBAR as STATUS_SBAR',
                'cppt.BACA as BACA',
                'cppt.KONFIRMASI as KONFIRMASI',
                'cppt.ADIME as ADIME',
                'cppt.DOKTER_TBAK_OR_SBAR as DOKTER_TBAK_OR_SBAR',
                'cppt.RENCANA_PULANG as RENCANA_PULANG',
                'cppt.TANGGAL_RENCANA_PULANG as TANGGAL_RENCANA_PULANG',
                'subDevisi.DESKRIPSI as SUB_DEVISI',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'cppt.VERIFIKASI as VERIFIKASI',
                'cppt.STATUS as STATUS',
            ])
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'cppt.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'cppt.TENAGA_MEDIS')
            ->leftJoin('master.pegawai as tenagaMedis', 'tenagaMedis.NIP', '=', 'dokter.NIP')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'cppt.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('master.referensi as subDevisi', function ($join) {
                $join->on('subDevisi.ID', '=', 'cppt.SUB_DEVISI')
                    ->where('subDevisi.JENIS', '=', 26);
            })
            ->where('cppt.ID', $id)
            ->distinct()
            ->first();

        // Gunakan strip_tags untuk data dari tag HTML
        if ($query) {
            $query->SUBYEKTIF = html_entity_decode(strip_tags($query->SUBYEKTIF));
            $query->OBYEKTIF = html_entity_decode(strip_tags($query->OBYEKTIF));
            $query->ASSESMENT = html_entity_decode(strip_tags($query->ASSESMENT));
            $query->PLANNING = html_entity_decode(strip_tags($query->PLANNING));
            $query->INSTRUKSI = html_entity_decode(strip_tags($query->INSTRUKSI));
        }

        $kunjungan = $query->NOMOR_KUNJUNGAN;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'            => $query,
            'nomorKunjungan'    => $kunjungan,
            'judulRme'          => 'CPPT',
        ]);
    }

    protected function getJadwalKontrol($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'jadwalKontrol.ID as id',
            ])
            ->leftJoin('medicalrecord.jadwal_kontrol as jadwalKontrol', 'jadwalKontrol.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function jadwalKontrol($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.jadwal_kontrol as jadwalKontrol')
            ->select([
                'kunjungan.NOPEN as NOMOR_PENDAFTARAN',
                'kunjungan.NOMOR as NOMOR_KUNJUNGAN',
                'pasien.NORM as NORM',
                'pasien.NAMA as NAMA_PASIEN',
                'jadwalKontrol.ID as ID',
                'jadwalKontrol.NOMOR as NOMOR',
                'jadwalKontrol.NOMOR_REFERENSI as NOMOR_REFERENSI',
                'jadwalKontrol.NOMOR_ANTRIAN as NOMOR_ANTRIAN',
                'jadwalKontrol.NOMOR_BOOKING as NOMOR_BOOKING',
                'tujuan.DESKRIPSI as TUJUAN',
                DB::raw('CONCAT(dokter.GELAR_DEPAN, " ", dokter.NAMA, " ", dokter.GELAR_BELAKANG) as DOKTER'),
                'jadwalKontrol.TANGGAL as TANGGAL',
                'jadwalKontrol.JAM as JAM',
                'jadwalKontrol.DESKRIPSI as DESKRIPSI',
                'ruangan.DESKRIPSI as RUANGAN',
                'jadwalKontrol.BERULANG as BERULANG',
                'jadwalKontrol.DIBUAT_TANGGAL as DIBUAT_TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'jadwalKontrol.STATUS as STATUS',
            ])
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'jadwalKontrol.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as tenagaMedis', 'tenagaMedis.ID', '=', 'jadwalKontrol.DOKTER')
            ->leftJoin('master.pegawai as dokter', 'dokter.NIP', '=', 'tenagaMedis.NIP')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'jadwalKontrol.RUANGAN')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'jadwalKontrol.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('master.referensi as tujuan', function ($join) {
                $join->on('tujuan.ID', '=', 'jadwalKontrol.TUJUAN')
                    ->where('tujuan.JENIS', '=', 26);
            })
            ->where('jadwalKontrol.ID', $id)
            ->distinct()
            ->first();

        $kunjungan = $query->NOMOR_KUNJUNGAN;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'            => $query,
            'nomorKunjungan'    => $kunjungan,
            'judulRme'          => 'JADWAL KONTROL',
        ]);
    }

    protected function getTandaVital($noKunjungan)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'tandaVital.KUNJUNGAN as id',
            ])
            ->leftJoin('medicalrecord.tanda_vital as tandaVital', 'tandaVital.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->where('kunjungan.NOMOR', $noKunjungan)
            ->first();
    }

    public function tandaVital($id)
    {
        $dataKunjungan = DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomorKunjungan',
                'kunjungan.NOPEN as nomorPendaftaran',
                'pasien.NORM as norm',
                'pasien.NAMA as namaPasien',
            ])
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->where('kunjungan.NOMOR', $id)
            ->distinct()
            ->first();

        // Fetch the specific data
        $query = DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'tandaVital.KUNJUNGAN as kunjungan',
                'tandaVital.ID as id',
                'tandaVital.TANGGAL as tanggal',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as oleh'),
                'tandaVital.STATUS as status',
            ])
            ->leftJoin('medicalrecord.tanda_vital as tandaVital', 'tandaVital.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'tandaVital.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('kunjungan.NOMOR', $id)
            ->get();

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('kunjungan.detail', $id)->with('error', 'Data not found.');
        }

        // Get nomor pendaftaran
        $nomorPendaftaran = $dataKunjungan->nomorPendaftaran;
        $nomorKunjungan   = $dataKunjungan->nomorKunjungan;
        $namaPasien       = $dataKunjungan->namaPasien;
        $normPasien       = $dataKunjungan->norm;

        return inertia("Pendaftaran/Kunjungan/TableVital", [
            'dataTable'         => $query,
            'nomorKunjungan'    => $nomorKunjungan,
            'nomorPendaftaran'  => $nomorPendaftaran,
            'namaPasien'        => $namaPasien,
            'normPasien'        => $normPasien,
        ]);
    }

    public function detailTandaVital($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.tanda_vital as tandaVital')
            ->select([
                'kunjungan.NOPEN as NOMOR_PENDAFTARAN',
                'tandaVital.KUNJUNGAN as NOMOR_KUNJUNGAN',
                'pasien.NORM as NORM',
                'pasien.NAMA as NAMA_PASIEN',
                'tandaVital.ID as ID',
                'tandaVital.KEADAAN_UMUM as KEADAAN_UMUM',
                'tandaVital.KESADARAN as KESADARAN',
                'tandaVital.SISTOLIK as SISTOLIK',
                'tandaVital.DISTOLIK as DISTOLIK',
                'tandaVital.FREKUENSI_NADI as FREKUENSI_NADI',
                'tandaVital.FREKUENSI_NAFAS as FREKUENSI_NAFAS',
                'tandaVital.SUHU as SUHU',
                'tandaVital.SATURASI_O2 as SATURASI_O2',
                'tandaVital.MOTORIK as MOTORIK',
                'tandaVital.GCS as GCS',
                'tandaVital.EWSS as EWSS',
                'tandaVital.UMUR as UMUR',
                'tandaVital.PEWSS as PEWSS',
                'tandaVital.TINGKAT_KESADARAN as TINGKAT_KESADARAN',
                'tandaVital.WAKTU_PEMERIKSAAN as WAKTU_PEMERIKSAAN',
                'tandaVital.ALAT_BANTU_NAFAS as ALAT_BANTU_NAFAS',
                'tandaVital.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'tandaVital.STATUS as STATUS',
            ])
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOMOR', '=', 'tandaVital.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'tandaVital.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('tandaVital.ID', $id)
            ->distinct()
            ->first();

        $kunjungan = $query->NOMOR_KUNJUNGAN;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'            => $query,
            'nomorKunjungan'    => $kunjungan,
            'judulRme'          => 'TANDA VITAL',
        ]);
    }

    public function print(Request $request)
    {
        // Validasi input
        $request->validate([
            'ruangan' => 'nullable|string',
            'statusKunjungan' => 'nullable|integer|in:0,1,2',
            'dari_tanggal' => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        // Ambil nilai input
        $ruangan = $request->input('ruangan');
        $statusKunjungan = $request->input('statusKunjungan');
        $dariTanggal = $request->input('dari_tanggal');
        $sampaiTanggal = $request->input('sampai_tanggal');
        $dariTanggal = Carbon::parse($dariTanggal)->format('Y-m-d H:i:s');
        $sampaiTanggal = Carbon::parse($sampaiTanggal)->endOfDay()->format('Y-m-d H:i:s');

        // Variabel default untuk label
        $namaRuangan = 'Semua Ruangan';
        $namaStatusKunjungan = 'Semua Status Kunjungan';

        // Query utama
        $query = DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select(
                'kunjungan.NOMOR as nomor',
                'pasien.NAMA as nama',
                'pasien.NORM as norm',
                'ruangan.DESKRIPSI as ruangan',
                'kunjungan.MASUK as masuk',
                'kunjungan.KELUAR as keluar',
                'kunjungan.STATUS as status'
            )
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->where('pasien.STATUS', 1);

        // Filter berdasarkan ruangan
        if (!empty($ruangan)) {
            $query->where('ruangan.ID', $ruangan);

            // Mendapatkan nama ruangan yang sesuai dari hasil query
            $namaRuangan = DB::connection('mysql5')->table('master.ruangan')
                ->where('ID', $ruangan)
                ->value('DESKRIPSI'); // Ambil nilai DESKRIPSI dari tabel ruangan
        }

        // Filter berdasarkan jenis kunjungan
        if ($statusKunjungan == 1) {
            $query->where('kunjungan.STATUS', 1); // Exclude Rawat Jalan
            $namaStatusKunjungan = 'Sedang Dilayani';
        } elseif ($statusKunjungan == 2) {
            $query->where('kunjungan.STATUS', 2); // Exclude Rawat Jalan
            $namaStatusKunjungan = 'Selesai';
        } else {
            $query->where('kunjungan.STATUS', 0); // Exclude Rawat Jalan
            $namaStatusKunjungan = 'Batal Kunjungan';
        }

        // Filter berdasarkan tanggal
        $data = $query->whereBetween('kunjungan.MASUK', [$dariTanggal, $sampaiTanggal])
            ->orderBy('kunjungan.MASUK')
            ->get();

        // Kirim data ke frontend menggunakan Inertia
        return inertia("Pendaftaran/Kunjungan/Print", [
            'data' => $data,
            'dariTanggal' => $dariTanggal,
            'sampaiTanggal' => $sampaiTanggal,
            'namaRuangan' => $namaRuangan,
            'namaStatusKunjungan' => $namaStatusKunjungan,
        ]);
    }
}
