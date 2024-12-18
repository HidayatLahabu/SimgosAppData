<?php

namespace App\Http\Controllers\Pendaftaran;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MasterRuanganModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MedicalrecordTriageModel;
use App\Http\Controllers\Pendaftaran\MedicalRecordController;
use App\Models\MedicalrecordAnamnesisDiperolehModel;
use App\Models\MedicalrecordAnamnesisModel;
use App\Models\MedicalrecordAsuhanKeperawatanModel;
use App\Models\MedicalrecordKeluhanUtamaModel;
use App\Models\MedicalrecordRiwayatAlergiModel;
use App\Models\MedicalrecordRppModel;

class KunjunganController extends Controller
{
    private $medicalRecordController;

    public function __construct(MedicalRecordController $medicalRecordController)
    {
        $this->medicalRecordController = $medicalRecordController;
    }

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
                    ->orWhereRaw('LOWER(pasien.NORM) LIKE ?', ['%' . $searchSubject . '%'])
                    ->orWhereRaw('LOWER(ruangan.DESKRIPSI) LIKE ?', ['%' . $searchSubject . '%']);
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

    protected function getKunjungan($noPendaftaran)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'kunjungan.NOPEN as pendaftaran',
                'kunjungan.MASUK as masuk',
                'kunjungan.KELUAR as keluar',
                'ruangan.DESKRIPSI as ruangan',
                'kunjungan.STATUS as status',
            ])
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->where('kunjungan.NOPEN', $noPendaftaran)
            ->get();
    }

    protected function getDetailKunjungan($id)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
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
    }

    public function getDataPasien($id)
    {
        // Ambil data pasien berdasarkan ID kunjungan
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomorKunjungan',
                'kunjungan.NOPEN as nomorPendaftaran',
                'pasien.NORM as norm',
                'pasien.NAMA as namaPasien',
                'ruangan.DESKRIPSI as ruangan',
                'kunjungan.MASUK as masuk',
                'kunjungan.KELUAR as keluar',
                'kunjungan.STATUS as status',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as dpjp'),
            ])
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pendaftaran.NORM', '=', 'pasien.NORM')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'kunjungan.RUANGAN')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'kunjungan.DPJP')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->where('kunjungan.NOMOR', $id)
            ->distinct()
            ->first();
    }

    public function detail($id)
    {

        // Fetch kunjungan data using the new function
        $query = $this->getDetailKunjungan($id);

        // Check if the record exists
        if (!$query) {
            // Handle the case where the encounter was not found
            return redirect()->route('kunjungan.index')->with('error', 'Data not found.');
        }

        //nomor pendaftaran
        $pendaftaran = $query->NOMOR_PENDAFTARAN;
        $noKunjungan = $query->NOMOR_KUNJUNGAN;

        // Fetch kunjungan data using the new function
        $kunjungan = $this->getKunjungan($pendaftaran);

        //get related data medicalrecord
        $relatedData = $this->getRelatedData($noKunjungan, $pendaftaran);

        return inertia("Pendaftaran/Kunjungan/Detail", array_merge([
            'detail' => $query,
            'nomorPendaftaran' => $pendaftaran,
            'dataKunjungan' => $kunjungan,
        ], $relatedData));
    }

    private function getRelatedData($noKunjungan, $pendaftaran)
    {
        $triage = $this->medicalRecordController->getTriage($noKunjungan);
        $askep = $this->medicalRecordController->getAskep($noKunjungan);
        $keluhanUtama = $this->medicalRecordController->getKeluhanUtama($noKunjungan);
        $anamnesisDiperoleh = $this->medicalRecordController->getAnamnesisDiperoleh($noKunjungan);
        $riwayatPenyakitSekarang = $this->medicalRecordController->getRiwayatPenyakitSekarang($noKunjungan);
        $riwayatPenyakitDahulu = $this->medicalRecordController->getRiwayatPenyakitDahulu($noKunjungan);
        $riwayatAlergi = $this->medicalRecordController->getRiwayatAlergi($noKunjungan);
        $riwayatPemberianObat = $this->medicalRecordController->getRiwayatPemberianObat($noKunjungan);
        $riwayatLainnya = $this->medicalRecordController->getRiwayatLainnya($noKunjungan);
        $faktorRisiko = $this->medicalRecordController->getFaktorRisiko($noKunjungan);
        $riwayatPenyakitKeluarga = $this->medicalRecordController->getRiwayatPenyakitKeluarga($noKunjungan);
        $riwayatTuberkulosis = $this->medicalRecordController->getRiwayatTuberkulosis($noKunjungan);
        $riwayatGinekologi = $this->medicalRecordController->getRiwayatGinekologi($noKunjungan);
        $statusFungsional = $this->medicalRecordController->getStatusFungsional($noKunjungan);
        $hubunganPsikososial = $this->medicalRecordController->getHubunganPsikososial($noKunjungan);
        $edukasiPasienKeluarga = $this->medicalRecordController->getEdukasiPasienKeluarga($noKunjungan);
        $edukasiEmergency = $this->medicalRecordController->getEdukasiEmergency($noKunjungan);
        $edukasiEndOfLife = $this->medicalRecordController->getEdukasiEndOfLife($noKunjungan);
        $skriningGiziAwal = $this->medicalRecordController->getSkriningGiziAwal($noKunjungan);
        $batuk = $this->medicalRecordController->getBatuk($noKunjungan);
        $cppt = $this->medicalRecordController->getCppt($noKunjungan);
        $pemeriksaanUmum = $this->medicalRecordController->getPemeriksaanUmum($noKunjungan);
        $pemeriksaanFisik = $this->medicalRecordController->getPemeriksaanFisik($noKunjungan);
        $jadwalKontrol = $this->medicalRecordController->getJadwalKontrol($noKunjungan);

        return [
            'triage' => $triage ? $triage->id : null,
            'askep' => $askep ? $askep->id : null,
            'keluhanUtama' => $keluhanUtama ? $keluhanUtama->id : null,
            'anamnesisDiperoleh' => $anamnesisDiperoleh ? $anamnesisDiperoleh->id : null,
            'riwayatPenyakitSekarang' => $riwayatPenyakitSekarang ? $riwayatPenyakitSekarang->id : null,
            'riwayatPenyakitDahulu' => $riwayatPenyakitDahulu ? $riwayatPenyakitDahulu->id : null,
            'riwayatAlergi' => $riwayatAlergi ? $riwayatAlergi->id : null,
            'riwayatPemberianObat' => $riwayatPemberianObat ? $riwayatPemberianObat->id : null,
            'riwayatLainnya' => $riwayatLainnya ? $riwayatLainnya->id : null,
            'faktorRisiko' => $faktorRisiko ? $faktorRisiko->id : null,
            'riwayatPenyakitKeluarga' => $riwayatPenyakitKeluarga ? $riwayatPenyakitKeluarga->id : null,
            'riwayatTuberkulosis' => $riwayatTuberkulosis ? $riwayatTuberkulosis->id : null,
            'riwayatGinekologi' => $riwayatGinekologi ? $riwayatGinekologi->id : null,
            'statusFungsional' => $statusFungsional ? $statusFungsional->id : null,
            'hubunganPsikososial' => $hubunganPsikososial ? $hubunganPsikososial->id : null,
            'edukasiPasienKeluarga' => $edukasiPasienKeluarga ? $edukasiPasienKeluarga->id : null,
            'edukasiEmergency' => $edukasiEmergency ? $edukasiEmergency->id : null,
            'edukasiEndOfLife' => $edukasiEndOfLife ? $edukasiEndOfLife->id : null,
            'skriningGiziAwal' => $skriningGiziAwal ? $skriningGiziAwal->id : null,
            'batuk' => $batuk ? $batuk->id : null,
            'pemeriksaanUmum' => $pemeriksaanUmum ? $pemeriksaanUmum->id : null,
            'pemeriksaanFisik' => $pemeriksaanFisik ? $pemeriksaanFisik->id : null,
            'cppt' => $cppt ? $cppt->id : null,
            'jadwalKontrol' => $jadwalKontrol ? $jadwalKontrol->id : null,
        ];
    }

    public function triage($id)
    {
        $query = MedicalrecordTriageModel::getById($id);

        if ($query) {
            $query->KEDATANGAN = implode(', ', json_decode($query->KEDATANGAN, true));
            $query->KASUS = implode(', ', json_decode($query->KASUS, true));
            $query->ANAMNESE = implode(', ', json_decode($query->ANAMNESE, true));
            $query->TANDA_VITAL = implode(', ', json_decode($query->TANDA_VITAL, true));
            $query->OBGYN = implode(', ', json_decode($query->OBGYN, true));
            $query->KEBUTUHAN_KHUSUS = implode(', ', json_decode($query->KEBUTUHAN_KHUSUS, true));
            $query->RESUSITASI = implode(', ', json_decode($query->RESUSITASI, true));
            $query->EMERGENCY = implode(', ', json_decode($query->EMERGENCY, true));
            $query->URGENT = implode(', ', json_decode($query->URGENT, true));
            $query->LESS_URGENT = implode(', ', json_decode($query->LESS_URGENT, true));
            $query->NON_URGENT = implode(', ', json_decode($query->NON_URGENT, true));
            $query->DOA = implode(', ', json_decode($query->DOA, true));
        }

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'TRIAGE',
        ]);
    }

    public function askep($id)
    {
        $query = MedicalrecordAsuhanKeperawatanModel::getById($id);

        if ($query) {
            $query->SUBJECKTIF = implode(', ', json_decode($query->SUBJECKTIF, true));
            $query->OBJEKTIF = implode(', ', json_decode($query->OBJEKTIF, true));
            $query->OBSERVASI = implode(', ', json_decode($query->OBSERVASI, true));
            $query->THEURAPEUTIC = implode(', ', json_decode($query->THEURAPEUTIC, true));
            $query->EDUKASI = implode(', ', json_decode($query->EDUKASI, true));
            $query->KOLABORASI = implode(', ', json_decode($query->KOLABORASI, true));
        }

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'ASUHAN KEPERAWATAN',
        ]);
    }

    public function keluhanUtama($id)
    {
        // Fetch the specific data
        $query = MedicalrecordKeluhanUtamaModel::getById($id);

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'KELUHAN UTAMA',
        ]);
    }

    public function anamnesisDiperoleh($id)
    {
        // Fetch the specific data
        $query = MedicalrecordAnamnesisDiperolehModel::getById($id);

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'ANAMNESIS DIPEROLEH',
        ]);
    }

    public function riwayatPenyakitSekarang($id)
    {
        // Fetch the specific data
        $query = MedicalrecordAnamnesisModel::getById($id);

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'RIWAYAT PENYAKIT SEKARANG',
        ]);
    }

    public function riwayatPenyakitDahulu($id)
    {
        // Fetch the specific data
        $query = MedicalrecordRppModel::getById($id);

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'RIWAYAT PERJALANAN PENYAKIT',
        ]);
    }

    public function riwayatAlergi($id)
    {
        // Fetch the specific data
        $query = MedicalrecordRiwayatAlergiModel::getById($id);

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'RIWAYAT ALERGI',
        ]);
    }

    public function riwayatPemberianObat($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.riwayat_pemberian_obat as riwayatPemberianObat')
            ->select([
                'riwayatPemberianObat.ID as ID',
                'riwayatPemberianObat.KUNJUNGAN as KUNJUNGAN',
                'riwayatPemberianObat.OBAT as OBAT',
                'riwayatPemberianObat.DOSIS as DOSIS',
                'frekuensiAturanResep.FREKUENSI as FREKUENSI',
                'frekuensiAturanResep.SIGNA1 as SIGNA1',
                'frekuensiAturanResep.SIGNA2 as SIGNA2',
                'frekuensiAturanResep.KETERANGAN as KETERANGAN',
                'ruteObat.DESKRIPSI as RUTE',
                'riwayatPemberianObat.LAMA_PENGGUNAAN as LAMA_PENGGUNAAN',
                'riwayatPemberianObat.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'riwayatPemberianObat.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'riwayatPemberianObat.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('master.frekuensi_aturan_resep as frekuensiAturanResep', 'frekuensiAturanResep.ID', '=', 'riwayatPemberianObat.FREKUENSI')
            ->leftJoin('master.referensi as ruteObat', function ($join) {
                $join->on('riwayatPemberianObat.RUTE', '=', 'ruteObat.ID')
                    ->where('ruteObat.JENIS', '=', 217);
            })
            ->where('riwayatPemberianObat.ID', $id)
            ->distinct()
            ->first();

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'RIWAYAT PEMBERIAN OBAT',
        ]);
    }

    public function riwayatLainnya($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.riwayat_lainnya as riwayatLainnya')
            ->select([
                'riwayatLainnya.ID as ID',
                'riwayatLainnya.KUNJUNGAN as KUNJUNGAN',
                'riwayatLainnya.MEROKOK as MEROKOK',
                'riwayatLainnya.TERPAPAR_ASAP_ROKOK as TERPAPAR_ASAP_ROKOK',
                'riwayatLainnya.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'riwayatLainnya.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'riwayatLainnya.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('riwayatLainnya.ID', $id)
            ->distinct()
            ->first();

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'RIWAYAT LAINNYA',
        ]);
    }

    public function faktorRisiko($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.faktor_risiko as faktorRisiko')
            ->select([
                'faktorRisiko.ID as ID',
                'faktorRisiko.KUNJUNGAN as KUNJUNGAN',
                'faktorRisiko.HIPERTENSI as HIPERTENSI',
                'faktorRisiko.DIABETES_MELITUS as DIABETES_MELITUS',
                'faktorRisiko.PENYAKIT_JANTUNG as PENYAKIT_JANTUNG',
                'faktorRisiko.ASMA as ASMA',
                'faktorRisiko.STROKE as STROKE',
                'faktorRisiko.LIVER as LIVER',
                'faktorRisiko.GINJAL as GINJAL',
                'faktorRisiko.PENYAKIT_KEGANASAN_DAN_HIV as PENYAKIT_KEGANASAN_DAN_HIV',
                'faktorRisiko.DISLIPIDEMIA as DISLIPIDEMIA',
                'faktorRisiko.GAGAL_JANTUNG as GAGAL_JANTUNG',
                'faktorRisiko.SERANGAN_JANTUNG as SERANGAN_JANTUNG',
                'faktorRisiko.ROKOK as ROKOK',
                'faktorRisiko.MINUM_ALKOHOL as MINUM_ALKOHOL',
                'faktorRisiko.MINUMAN_ALKOHOL as MINUMAN_ALKOHOL',
                'faktorRisiko.MEROKOK as MEROKOK',
                'faktorRisiko.BEGADANG as BEGADANG',
                'faktorRisiko.SEKS_BEBAS as SEKS_BEBAS',
                'faktorRisiko.NAPZA_TAMPA_RESEP_DOKTER as NAPZA_TAMPA_RESEP_DOKTER',
                'faktorRisiko.MAKAN_MANIS_BERLEBIHAN as MAKAN_MANIS_BERLEBIHAN',
                'faktorRisiko.PERILAKU_LGBT as PERILAKU_LGBT',
                'faktorRisiko.NOTIFIKASI_PASANGAN_NP as NOTIFIKASI_PASANGAN_NP',
                'faktorRisiko.PENYAKIT_LAIN as PENYAKIT_LAIN',
                'faktorRisiko.PERILAKU_LAIN as PERILAKU_LAIN',
                'faktorRisiko.PERNAH_DIRAWAT_TIDAK as PERNAH_DIRAWAT_TIDAK',
                'faktorRisiko.PERNAH_DIRAWAT_KAPAN as PERNAH_DIRAWAT_KAPAN',
                'faktorRisiko.PERNAH_DIRAWAT_DIMANA as PERNAH_DIRAWAT_DIMANA',
                'faktorRisiko.PERNAH_DIRAWAT_DIAGNOSIS as PERNAH_DIRAWAT_DIAGNOSIS',
                'faktorRisiko.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'faktorRisiko.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'faktorRisiko.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('faktorRisiko.ID', $id)
            ->distinct()
            ->first();

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'FAKTOR RISIKO',
        ]);
    }

    public function riwayatPenyakitKeluarga($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.riwayat_penyakit_keluarga as rpk')
            ->select([
                'rpk.ID as ID',
                'rpk.KUNJUNGAN as KUNJUNGAN',
                'rpk.HIPERTENSI as HIPERTENSI',
                'rpk.DIABETES_MELITUS as DIABETES_MELITUS',
                'rpk.PENYAKIT_JANTUNG as PENYAKIT_JANTUNG',
                'rpk.HIPERTENSI as HIPERTENSI',
                'rpk.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'rpk.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'rpk.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('rpk.ID', $id)
            ->distinct()
            ->first();

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'RIWAYAT PENYAKIT KELUARGA',
        ]);
    }

    public function riwayatTuberkulosis($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.riwayat_penyakit_tb as riwayatTuberkulosis')
            ->select([
                'riwayatTuberkulosis.ID as ID',
                'riwayatTuberkulosis.KUNJUNGAN as KUNJUNGAN',
                'riwayatTuberkulosis.RIWAYAT as RIWAYAT',
                'riwayatTuberkulosis.TAHUN as TAHUN',
                'riwayatTuberkulosis.BEROBAT as BEROBAT',
                'riwayatTuberkulosis.SPUTUM as SPUTUM',
                'riwayatTuberkulosis.TANGGAL_PEMERIKSAAN_SPUTUM as TANGGAL_PEMERIKSAAN_SPUTUM',
                'riwayatTuberkulosis.TEST_CEPAT_MOLEKULER as TEST_CEPAT_MOLEKULER',
                'riwayatTuberkulosis.TANGGAL_TEST_CEPAT as TANGGAL_TEST_CEPAT',
                'riwayatTuberkulosis.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'riwayatTuberkulosis.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'riwayatTuberkulosis.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('riwayatTuberkulosis.ID', $id)
            ->distinct()
            ->first();

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'RIWAYAT TUBERKULOSIS',
        ]);
    }

    public function riwayatGinekologi($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.riwayat_gynekologi as riwayatGynekologi')
            ->select([
                'riwayatGynekologi.ID as ID',
                'riwayatGynekologi.KUNJUNGAN as KUNJUNGAN',
                'riwayatGynekologi.INFERTILITAS as INFERTILITAS',
                'riwayatGynekologi.INFEKSI_VIRUS as INFEKSI_VIRUS',
                'riwayatGynekologi.PENYAKIT_MENULAR_SEKSUAL as PENYAKIT_MENULAR_SEKSUAL',
                'riwayatGynekologi.CERVISITIS_CRONIS as CERVISITIS_CRONIS',
                'riwayatGynekologi.ENDOMETRIOSIS as ENDOMETRIOSIS',
                'riwayatGynekologi.MYOMA as MYOMA',
                'riwayatGynekologi.POLIP_SERVIX as POLIP_SERVIX',
                'riwayatGynekologi.KANKER_KANDUNGAN as KANKER_KANDUNGAN',
                'riwayatGynekologi.MINUMAN_ALKOHOL as MINUMAN_ALKOHOL',
                'riwayatGynekologi.PERKOSAAN as PERKOSAAN',
                'riwayatGynekologi.OPERASI_KANDUNGAN as OPERASI_KANDUNGAN',
                'riwayatGynekologi.POST_COINTAL_BLEEDING as POST_COINTAL_BLEEDING',
                'riwayatGynekologi.FLOUR_ALBUS as FLOUR_ALBUS',
                'riwayatGynekologi.LAINYA as LAINYA',
                'riwayatGynekologi.KETERANGAN_LAINNYA as KETERANGAN_LAINNYA',
                'riwayatGynekologi.GATAL as GATAL',
                'riwayatGynekologi.BERBAU as BERBAU',
                'riwayatGynekologi.WARNAH as WARNAH',
                'riwayatGynekologi.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'riwayatGynekologi.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'riwayatGynekologi.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('riwayatGynekologi.ID', $id)
            ->distinct()
            ->first();

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'RIWAYAT GINEKOLOGI',
        ]);
    }

    public function statusFungsional($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.status_fungsional as statusFungsional')
            ->select([
                'statusFungsional.ID as ID',
                'statusFungsional.KUNJUNGAN as KUNJUNGAN',
                'statusFungsional.TANPA_ALAT_BANTU as TANPA_ALAT_BANTU',
                'statusFungsional.TONGKAT as TONGKAT',
                'statusFungsional.KURSI_RODA as KURSI_RODA',
                'statusFungsional.BRANKARD as BRANKARD',
                'statusFungsional.WALKER as WALKER',
                'statusFungsional.ALAT_BANTU as ALAT_BANTU',
                'statusFungsional.CACAT_TUBUH_TIDAK as CACAT_TUBUH_TIDAK',
                'statusFungsional.CACAT_TUBUH_YA as CACAT_TUBUH_YA',
                'statusFungsional.KET_CACAT_TUBUH as KET_CACAT_TUBUH',
                'statusFungsional.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'statusFungsional.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'statusFungsional.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('statusFungsional.ID', $id)
            ->distinct()
            ->first();

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'STATUS FUNGSIONAL',
        ]);
    }

    public function hubunganPsikososial($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.kondisi_sosial as kondisiSosial')
            ->select([
                'kondisiSosial.ID as ID',
                'kondisiSosial.KUNJUNGAN as KUNJUNGAN',
                'kondisiSosial.TIDAK_ADA_KELAINAN as TIDAK_ADA_KELAINAN',
                'kondisiSosial.MARAH as MARAH',
                'kondisiSosial.CEMAS as CEMAS',
                'kondisiSosial.TAKUT as TAKUT',
                'kondisiSosial.BUNUH_DIRI as BUNUH_DIRI',
                'kondisiSosial.SEDIH as SEDIH',
                'kondisiSosial.LAINNYA as LAINNYA',
                'kondisiSosial.STATUS_MENTAL as STATUS_MENTAL',
                'kondisiSosial.MASALAH_PERILAKU as MASALAH_PERILAKU',
                'kondisiSosial.PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA as PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA',
                'kondisiSosial.HUBUNGAN_PASIEN_DENGAN_KELUARGA as HUBUNGAN_PASIEN_DENGAN_KELUARGA',
                'kondisiSosial.TEMPAT_TINGGAL as TEMPAT_TINGGAL',
                'kondisiSosial.TEMPAT_TINGGAL_LAINNYA as TEMPAT_TINGGAL_LAINNYA',
                'kondisiSosial.KEBIASAAN_BERIBADAH_TERATUR as KEBIASAAN_BERIBADAH_TERATUR',
                'kondisiSosial.NILAI_KEPERCAYAAN as NILAI_KEPERCAYAAN',
                'kondisiSosial.NILAI_KEPERCAYAAN_DESKRIPSI as NILAI_KEPERCAYAAN_DESKRIPSI',
                'kondisiSosial.PENGAMBIL_KEPUTUSAN_DALAM_KELUARGA as PENGAMBIL_KEPUTUSAN_DALAM_KELUARGA',
                'kondisiSosial.PENGHASILAN_PERBULAN as PENGHASILAN_PERBULAN',
                'kondisiSosial.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawaiKondisiSosial.GELAR_DEPAN, " ", pegawaiKondisiSosial.NAMA, " ", pegawaiKondisiSosial.GELAR_BELAKANG) as KONDISI_SOSIAL_OLEH'),
                'kondisiSosial.STATUS as STATUS_KONDISI_SOSIAL',
                'hubunganPsikososial.KECEMASAN_PASIEN_ATAU_KERABAT as KECEMASAN_PASIEN_ATAU_KERABAT',
                'hubunganPsikososial.KETERANGAN_KECEMASAN_PASIEN_ATAU_KERABAT as KETERANGAN_KECEMASAN_PASIEN_ATAU_KERABAT',
                'hubunganPsikososial.KEBUTUHAN_DAN_DUKUNGAN_SPIRITUAL as KEBUTUHAN_DAN_DUKUNGAN_SPIRITUAL',
                'hubunganPsikososial.KETERANGAN_KEBUTUHAN_DAN_DUKUNGAN_SPIRITUAL as KETERANGAN_KEBUTUHAN_DAN_DUKUNGAN_SPIRITUAL',
                'hubunganPsikososial.DUKUNGAN_DARI_TIM as DUKUNGAN_DARI_TIM',
                'hubunganPsikososial.KETERANGAN_DUKUNGAN_DARI_TIM as KETERANGAN_DUKUNGAN_DARI_TIM',
                'hubunganPsikososial.INDIKASI_TRADISI_KEAGAMAAN as INDIKASI_TRADISI_KEAGAMAAN',
                'hubunganPsikososial.KETERANGAN_INDIKASI_TRADISI_KEAGAMAAN as KETERANGAN_INDIKASI_TRADISI_KEAGAMAAN',
                'hubunganPsikososial.INDIKASI_KEBUTUHAN_KHUSUS as INDIKASI_KEBUTUHAN_KHUSUS',
                'hubunganPsikososial.KETERANGAN_INDIKASI_KEBUTUHAN_KHUSUS as KETERANGAN_INDIKASI_KEBUTUHAN_KHUSUS',
                'hubunganPsikososial.PILIHAN_HIDUP_PASIEN as PILIHAN_HIDUP_PASIEN',
                'hubunganPsikososial.TANGGAL as PILIHAN_HIDUP_PASIEN',
                DB::raw('CONCAT(pegawaiHubunganPsikososial.GELAR_DEPAN, " ", pegawaiHubunganPsikososial.NAMA, " ", pegawaiHubunganPsikososial.GELAR_BELAKANG) as OLEH'),
                'hubunganPsikososial.STATUS as STATUS_END_OF_LIFE',
            ])
            ->leftJoin('aplikasi.pengguna as penggunaKondisiSosial', 'penggunaKondisiSosial.ID', '=', 'kondisiSosial.OLEH')
            ->leftJoin('master.pegawai as pegawaiKondisiSosial', 'pegawaiKondisiSosial.NIP', '=', 'penggunaKondisiSosial.NIP')
            ->leftJoin('medicalrecord.hubungan_psikososial_end_of_life as hubunganPsikososial', 'hubunganPsikososial.KUNJUNGAN', '=', 'kondisiSosial.KUNJUNGAN')
            ->leftJoin('aplikasi.pengguna as penggunaEndOfLife', 'penggunaEndOfLife.ID', '=', 'hubunganPsikososial.OLEH')
            ->leftJoin('master.pegawai as pegawaiHubunganPsikososial', 'pegawaiHubunganPsikososial.NIP', '=', 'penggunaEndOfLife.NIP')
            ->where('kondisiSosial.ID', $id)
            ->distinct()
            ->first();

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'HUBUNGAN STATUS PSIKOSOSIAL',
        ]);
    }

    public function edukasiPasienKeluarga($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.edukasi_pasien_keluarga as edukasiPasienKeluarga')
            ->select([
                'edukasiPasienKeluarga.ID as ID',
                'edukasiPasienKeluarga.KUNJUNGAN as KUNJUNGAN',
                'edukasiPasienKeluarga.KESEDIAAN as KESEDIAAN',
                'edukasiPasienKeluarga.HAMBATAN as HAMBATAN',
                'edukasiPasienKeluarga.HAMBATAN_PENDENGARAN as HAMBATAN_PENDENGARAN',
                'edukasiPasienKeluarga.HAMBATAN_PENGLIHATAN as HAMBATAN_PENGLIHATAN',
                'edukasiPasienKeluarga.HAMBATAN_KOGNITIF as HAMBATAN_KOGNITIF',
                'edukasiPasienKeluarga.HAMBATAN_FISIK as HAMBATAN_FISIK',
                'edukasiPasienKeluarga.HAMBATAN_BUDAYA as HAMBATAN_BUDAYA',
                'edukasiPasienKeluarga.HAMBATAN_EMOSI as HAMBATAN_EMOSI',
                'edukasiPasienKeluarga.HAMBATAN_BAHASA as HAMBATAN_BAHASA',
                'edukasiPasienKeluarga.HAMBATAN_LAINNYA as HAMBATAN_LAINNYA',
                'edukasiPasienKeluarga.PENERJEMAH as PENERJEMAH',
                'edukasiPasienKeluarga.EDUKASI_DIAGNOSA as EDUKASI_DIAGNOSA',
                'edukasiPasienKeluarga.EDUKASI_PENYAKIT as EDUKASI_PENYAKIT',
                'edukasiPasienKeluarga.EDUKASI_REHAB_MEDIK as EDUKASI_REHAB_MEDIK',
                'edukasiPasienKeluarga.EDUKASI_HKP as EDUKASI_HKP',
                'edukasiPasienKeluarga.EDUKASI_OBAT as EDUKASI_OBAT',
                'edukasiPasienKeluarga.EDUKASI_NYERI as EDUKASI_NYERI',
                'edukasiPasienKeluarga.EDUKASI_NUTRISI as EDUKASI_NUTRISI',
                'edukasiPasienKeluarga.EDUKASI_PENGGUNAAN_ALAT as EDUKASI_PENGGUNAAN_ALAT',
                'edukasiPasienKeluarga.EDUKASI_HAK_BERPARTISIPASI as EDUKASI_HAK_BERPARTISIPASI',
                'edukasiPasienKeluarga.EDUKASI_PROSEDURE_PENUNJANG as EDUKASI_PROSEDURE_PENUNJANG',
                'edukasiPasienKeluarga.EDUKASI_PEMBERIAN_INFORMED_CONSENT as EDUKASI_PEMBERIAN_INFORMED_CONSENT',
                'edukasiPasienKeluarga.EDUKASI_PENUNDAAN_PELAYANAN as EDUKASI_PENUNDAAN_PELAYANAN',
                'edukasiPasienKeluarga.EDUKASI_KELAMBATAN_PELAYANAN as EDUKASI_KELAMBATAN_PELAYANAN',
                'edukasiPasienKeluarga.EDUKASI_CUCI_TANGAN as EDUKASI_CUCI_TANGAN',
                'edukasiPasienKeluarga.EDUKASI_BAHAYA_MEROKO as EDUKASI_BAHAYA_MEROKO',
                'edukasiPasienKeluarga.EDUKASI_RUJUKAN_PASIEN as EDUKASI_RUJUKAN_PASIEN',
                'edukasiPasienKeluarga.EDUKASI_PERENCANAAN_PULANG as EDUKASI_PERENCANAAN_PULANG',
                'edukasiPasienKeluarga.STATUS_LAIN as STATUS_LAIN',
                'edukasiPasienKeluarga.DESKRIPSI_LAINYA as DESKRIPSI_LAINYA',
                'edukasiPasienKeluarga.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'edukasiPasienKeluarga.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'edukasiPasienKeluarga.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('edukasiPasienKeluarga.ID', $id)
            ->distinct()
            ->first();

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'EDUKASI PASIEN DAN KELUARGA',
        ]);
    }

    public function edukasiEmergency($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.edukasi_emergency as edukasiEmergency')
            ->select([
                'edukasiEmergency.ID as ID',
                'edukasiEmergency.KUNJUNGAN as KUNJUNGAN',
                'edukasiEmergency.EDUKASI as EDUKASI',
                'edukasiEmergency.KEMBALI_KE_UGD as KEMBALI_KE_UGD',
                'edukasiEmergency.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'edukasiEmergency.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'edukasiEmergency.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('edukasiEmergency.ID', $id)
            ->distinct()
            ->first();

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'EDUKASI EMERGENCY',
        ]);
    }

    public function edukasiEndOfLife($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.edukasi_end_of_life as edukasiEndOfLife')
            ->select([
                'edukasiEndOfLife.ID as ID',
                'edukasiEndOfLife.KUNJUNGAN as KUNJUNGAN',
                'edukasiEndOfLife.MENGETAHUI_DIAGNOSA as MENGETAHUI_DIAGNOSA',
                'edukasiEndOfLife.MENGETAHUI_PROGNOSIS as MENGETAHUI_PROGNOSIS',
                'edukasiEndOfLife.MENGETAHUI_TUJUAN_PERAWATAN as MENGETAHUI_TUJUAN_PERAWATAN',
                'edukasiEndOfLife.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'edukasiEndOfLife.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'edukasiEndOfLife.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('edukasiEndOfLife.ID', $id)
            ->distinct()
            ->first();

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'EDUKASI END OF LIFE',
        ]);
    }

    public function skriningGiziAwal($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.permasalahan_gizi as permasalahanGizi')
            ->select([
                'permasalahanGizi.ID as ID',
                'permasalahanGizi.KUNJUNGAN as KUNJUNGAN',
                'permasalahanGizi.BERAT_BADAN_SIGNIFIKAN as BERAT_BADAN_SIGNIFIKAN',
                'permasalahanGizi.PERUBAHAN_BERAT_BADAN as PERUBAHAN_BERAT_BADAN',
                'permasalahanGizi.INTAKE_MAKANAN as INTAKE_MAKANAN',
                'permasalahanGizi.KONDISI_KHUSUS as KONDISI_KHUSUS',
                'permasalahanGizi.SKOR as SKOR',
                'permasalahanGizi.STATUS_SKOR as STATUS_SKOR',
                'permasalahanGizi.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'permasalahanGizi.STATUS_VALIDASI as STATUS_VALIDASI',
                'permasalahanGizi.TANGGAL_VALIDASI as TANGGAL_VALIDASI',
                DB::raw('CONCAT(pegawaiValidasi.GELAR_DEPAN, " ", pegawaiValidasi.NAMA, " ", pegawaiValidasi.GELAR_BELAKANG) as USER_VALIDASI'),
                'permasalahanGizi.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'permasalahanGizi.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->leftJoin('aplikasi.pengguna as penggunaValidasi', 'penggunaValidasi.ID', '=', 'permasalahanGizi.USER_VALIDASI')
            ->leftJoin('master.pegawai as pegawaiValidasi', 'pegawaiValidasi.NIP', '=', 'penggunaValidasi.NIP')
            ->where('permasalahanGizi.ID', $id)
            ->distinct()
            ->first();

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'SKRINING GIZI AWAL',
        ]);
    }

    public function batuk($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.batuk as batuk')
            ->select([
                'batuk.ID as ID',
                'batuk.KUNJUNGAN as KUNJUNGAN',
                'batuk.DEMAM as DEMAM',
                'batuk.DEMAM_KETERANGAN as DEMAM_KETERANGAN',
                'batuk.BERKERINGAT_MALAM_HARI_TANPA_AKTIFITAS as BERKERINGAT_MALAM_HARI_TANPA_AKTIFITAS',
                'batuk.BERKERINGAT_MALAM_HARI_TANPA_AKTIFITAS_KETERANGAN as BERKERINGAT_MALAM_HARI_TANPA_AKTIFITAS_KETERANGAN',
                'batuk.BEPERGIAN_DARI_DAERAH_WABAH as BEPERGIAN_DARI_DAERAH_WABAH',
                'batuk.BEPERGIAN_DARI_DAERAH_WABAH_KETERANGAN as BEPERGIAN_DARI_DAERAH_WABAH_KETERANGAN',
                'batuk.PEMAKAIAN_OBAT_JANGKA_PANJANG as PEMAKAIAN_OBAT_JANGKA_PANJANG',
                'batuk.PEMAKAIAN_OBAT_JANGKA_PANJANG_KETERANGAN as PEMAKAIAN_OBAT_JANGKA_PANJANG_KETERANGAN',
                'batuk.BERAT_BADAN_TURUN_TANPA_SEBAB as BERAT_BADAN_TURUN_TANPA_SEBAB',
                'batuk.BERAT_BADAN_TURUN_TANPA_SEBAB_KETERANGAN as BERAT_BADAN_TURUN_TANPA_SEBAB_KETERANGAN',
                'batuk.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'batuk.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'batuk.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('batuk.ID', $id)
            ->distinct()
            ->first();

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'BATUK',
        ]);
    }

    public function pemeriksaanUmum($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.tanda_vital as tandaVital')
            ->select([
                'tandaVital.ID as ID',
                'tandaVital.KUNJUNGAN as KUNJUNGAN',
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
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'tandaVital.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('tandaVital.ID', $id)
            ->distinct()
            ->first();

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'PEMERIKSAAN UMUM',
        ]);
    }

    public function pemeriksaanFisik($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.penilaian_fisik as pemeriksaanFisik')
            ->select([
                'pemeriksaanFisik.ID as ID',
                'pemeriksaanFisik.KUNJUNGAN as KUNJUNGAN',
                'pemeriksaanFisik.DESKRIPSI as DESKRIPSI',
                'pemeriksaanFisik.TANGGAL as TANGGAL',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as OLEH'),
                'pemeriksaanFisik.STATUS as STATUS',
            ])
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'pemeriksaanFisik.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('pemeriksaanFisik.ID', $id)
            ->distinct()
            ->first();

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'PEMERIKSAAN FISIK',
        ]);
    }

    public function cppt($id)
    {
        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($id);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

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


        return inertia("Pendaftaran/Kunjungan/TableCppt", [
            'dataTable'         => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
        ]);
    }

    public function detailCppt($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.cppt as cppt')
            ->select([
                'cppt.ID as ID',
                'cppt.KUNJUNGAN as KUNJUNGAN',
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

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'CPPT',
        ]);
    }

    public function jadwalKontrol($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.jadwal_kontrol as jadwalKontrol')
            ->select([
                'jadwalKontrol.ID as ID',
                'jadwalKontrol.KUNJUNGAN as KUNJUNGAN',
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

        $noKunjungan = $query->KUNJUNGAN;

        // Panggil fungsi getDataPasien
        $dataPasien = $this->getDataPasien($noKunjungan);

        // Ambil data dari hasil query
        $pendaftaran = $dataPasien->nomorPendaftaran;
        $kunjungan   = $dataPasien->nomorKunjungan;
        $pasien      = $dataPasien->namaPasien;
        $norm        = $dataPasien->norm;
        $ruangan     = $dataPasien->ruangan;
        $status      = $dataPasien->status;
        $masuk       = $dataPasien->masuk;
        $keluar      = $dataPasien->keluar;
        $dpjp        = $dataPasien->dpjp;

        return inertia("Pendaftaran/Kunjungan/DetailRme", [
            'detail'           => $query,
            'nomorKunjungan'   => $kunjungan,
            'nomorPendaftaran' => $pendaftaran,
            'namaPasien'       => $pasien,
            'normPasien'       => $norm,
            'ruanganTujuan'    => $ruangan,
            'statusKunjungan'  => $status,
            'tanggalMasuk'     => $masuk,
            'tanggalKeluar'    => $keluar,
            'dpjp'             => $dpjp,
            'judulRme'         => 'JADWAL KONTROL',
        ]);
    }

    public function print(Request $request)
    {
        // Validasi input
        $request->validate([
            'ruangan' => 'nullable|string',
            'statusKunjungan' => 'nullable|integer|in:0,1,2',
            'pasien' => 'nullable|integer|in:1,2',
            'dari_tanggal' => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        // Ambil nilai input
        $ruangan = $request->input('ruangan');
        $statusKunjungan = $request->input('statusKunjungan');
        $pasien = $request->input('pasien');
        $dariTanggal = $request->input('dari_tanggal');
        $sampaiTanggal = $request->input('sampai_tanggal');
        $dariTanggal = Carbon::parse($dariTanggal)->format('Y-m-d H:i:s');
        $sampaiTanggal = Carbon::parse($sampaiTanggal)->endOfDay()->format('Y-m-d H:i:s');

        // Variabel default untuk label
        $namaRuangan = 'Semua Ruangan';
        $namaStatusKunjungan = 'Semua Status Aktifitas Kunjungan';
        $jenisPasien = 'Semua Status Kunjungan';

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
                ->value('DESKRIPSI');
        }

        // Filter berdasarkan jenis kunjungan
        if ($statusKunjungan === null) {
            $query->whereIn('kunjungan.STATUS', [0, 1, 2]);
        } elseif ($statusKunjungan == 1) {
            $query->where('kunjungan.STATUS', 1); // Sedang Dilayani
            $namaStatusKunjungan = 'Sedang Dilayani';
        } elseif ($statusKunjungan == 2) {
            $query->where('kunjungan.STATUS', 2); // Selesai
            $namaStatusKunjungan = 'Selesai';
        } else {
            $query->where('kunjungan.STATUS', 0); // Batal Kunjungan
            $namaStatusKunjungan = 'Batal Kunjungan';
        }

        // Filter berdasarkan pasien baru atau lama
        if ($pasien == 1) {
            // Pasien baru: TANGGAL pasien sama dengan tanggal kunjungan MASUK
            $query->whereRaw('DATE(pasien.TANGGAL) = DATE(kunjungan.MASUK)');
            $jenisPasien = 'Baru';
        } elseif ($pasien == 2) {
            // Pasien lama: TANGGAL pasien tidak sama dengan tanggal kunjungan MASUK
            $query->whereRaw('DATE(pasien.TANGGAL) != DATE(kunjungan.MASUK)');
            $jenisPasien = 'Lama';
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
            'jenisPasien' => $jenisPasien,
        ]);
    }
}