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
use App\Models\MedicalrecordBatukModel;
use App\Models\MedicalrecordCpptModel;
use App\Models\MedicalrecordEdukasiEmergencyModel;
use App\Models\MedicalrecordEdukasiEndOfLifeModel;
use App\Models\MedicalrecordEdukasiPasienKeluargaModel;
use App\Models\MedicalrecordEndOfLifeModel;
use App\Models\MedicalrecordFaktorRisikoModel;
use App\Models\MedicalrecordFungsionalModel;
use App\Models\MedicalrecordJadwalKontrolModel;
use App\Models\MedicalrecordKeluhanUtamaModel;
use App\Models\MedicalrecordKondisiSosialModel;
use App\Models\MedicalrecordPemeriksaanAnusModel;
use App\Models\MedicalrecordPemeriksaanAsessmentMChatModel;
use App\Models\MedicalrecordPemeriksaanBibirModel;
use App\Models\MedicalrecordPemeriksaanCatClamsModel;
use App\Models\MedicalrecordPemeriksaanDadaModel;
use App\Models\MedicalrecordPemeriksaanEegModel;
use App\Models\MedicalrecordPemeriksaanEkgModel;
use App\Models\MedicalrecordPemeriksaanFaringModel;
use App\Models\MedicalrecordPemeriksaanGenitalModel;
use App\Models\MedicalrecordPemeriksaanGigiGeligiModel;
use App\Models\MedicalrecordPemeriksaanHidungModel;
use App\Models\MedicalrecordPemeriksaanJariKakiModel;
use App\Models\MedicalrecordPemeriksaanJariTanganModel;
use App\Models\MedicalrecordPemeriksaanKepalaModel;
use App\Models\MedicalrecordPemeriksaanKukuKakiModel;
use App\Models\MedicalrecordPemeriksaanKukuTanganModel;
use App\Models\MedicalrecordPemeriksaanLangitLangitModel;
use App\Models\MedicalrecordPemeriksaanLeherModel;
use App\Models\MedicalrecordPemeriksaanLenganAtasModel;
use App\Models\MedicalrecordPemeriksaanLenganBawahModel;
use App\Models\MedicalrecordPemeriksaanLidahModel;
use App\Models\MedicalrecordPemeriksaanMataModel;
use App\Models\MedicalrecordPemeriksaanPayudaraModel;
use App\Models\MedicalrecordPemeriksaanPersendianKakiModel;
use App\Models\MedicalrecordPemeriksaanPersendianTanganModel;
use App\Models\MedicalrecordPemeriksaanPerutModel;
use App\Models\MedicalrecordPemeriksaanPunggungModel;
use App\Models\MedicalrecordPemeriksaanRambutModel;
use App\Models\MedicalrecordPemeriksaanRavenTestModel;
use App\Models\MedicalrecordPemeriksaanSaluranCernahAtasModel;
use App\Models\MedicalrecordPemeriksaanSaluranCernahBawahModel;
use App\Models\MedicalrecordPemeriksaanTelingaModel;
use App\Models\MedicalrecordPemeriksaanTenggorokanModel;
use App\Models\MedicalrecordPemeriksaanTonsilModel;
use App\Models\MedicalrecordPemeriksaanTransfusiDarahModel;
use App\Models\MedicalrecordPemeriksaanTungkaiAtasModel;
use App\Models\MedicalrecordPemeriksaanTungkaiBawahModel;
use App\Models\MedicalrecordPenilaianFisikModel;
use App\Models\MedicalrecordPermasalahanGiziModel;
use App\Models\MedicalrecordRekonsiliasiDischargeModel;
use App\Models\MedicalrecordRekonsiliasiObatModel;
use App\Models\MedicalrecordRekonsiliasiTransferModel;
use App\Models\MedicalrecordRiwayatAlergiModel;
use App\Models\MedicalrecordRiwayatGynekologiModel;
use App\Models\MedicalrecordRiwayatLainnyaModel;
use App\Models\MedicalrecordRiwayatPemberianObatModel;
use App\Models\MedicalrecordRiwayatPenyakitKeluargaModel;
use App\Models\MedicalrecordRiwayatTuberkulosisModel;
use App\Models\MedicalrecordRppModel;
use App\Models\MedicalrecordStatusFungsionalModel;
use App\Models\MedicalrecordTandaVitalModel;

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
                DB::raw('
                    CASE 
                        WHEN DATE(kunjungan.MASUK) = DATE(pasien.TANGGAL) THEN 1 
                        ELSE 0 
                    END as STATUS_KUNJUNGAN
                '),
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
            ->firstOrFail();
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
            ->firstOrFail();
    }

    public function detail($id)
    {

        // Fetch kunjungan data using the new function
        $query = $this->getDetailKunjungan($id);

        //dd($query);

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

    private function getRelatedData($noKunjungan)
    {
        $triage = $this->medicalRecordController->getTriage($noKunjungan);
        $rekonsiliasiObatAdmisi = $this->medicalRecordController->getRekonsiliasiObatAdmisi($noKunjungan);
        $rekonsiliasiObatTransfer = $this->medicalRecordController->getRekonsiliasiObatTransfer($noKunjungan);
        $rekonsiliasiObatDischarge = $this->medicalRecordController->getRekonsiliasiObatDischarge($noKunjungan);
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
        $pemeriksaanUmum = $this->medicalRecordController->getPemeriksaanUmum($noKunjungan);
        $pemeriksaanFungsional = $this->medicalRecordController->getPemeriksaanFungsional($noKunjungan);
        $pemeriksaanFisik = $this->medicalRecordController->getPemeriksaanFisik($noKunjungan);
        $pemeriksaanKepala = $this->medicalRecordController->getPemeriksaanKepala($noKunjungan);
        $pemeriksaanMata = $this->medicalRecordController->getPemeriksaanMata($noKunjungan);
        $pemeriksaanTelinga = $this->medicalRecordController->getPemeriksaanTelinga($noKunjungan);
        $pemeriksaanHidung = $this->medicalRecordController->getPemeriksaanHidung($noKunjungan);
        $pemeriksaanRambut = $this->medicalRecordController->getPemeriksaanRambut($noKunjungan);
        $pemeriksaanBibir = $this->medicalRecordController->getPemeriksaanBibir($noKunjungan);
        $pemeriksaanGigiGeligi = $this->medicalRecordController->getPemeriksaanGigiGeligi($noKunjungan);
        $pemeriksaanLidah = $this->medicalRecordController->getPemeriksaanLidah($noKunjungan);
        $pemeriksaanLangitLangit = $this->medicalRecordController->getPemeriksaanLangitLangit($noKunjungan);
        $pemeriksaanLeher = $this->medicalRecordController->getPemeriksaanLeher($noKunjungan);
        $pemeriksaanTenggorokan = $this->medicalRecordController->getPemeriksaanTenggorokan($noKunjungan);
        $pemeriksaanTonsil = $this->medicalRecordController->getPemeriksaanTonsil($noKunjungan);
        $pemeriksaanDada = $this->medicalRecordController->getPemeriksaanDada($noKunjungan);
        $pemeriksaanPayudara = $this->medicalRecordController->getPemeriksaanPayudara($noKunjungan);
        $pemeriksaanPunggung = $this->medicalRecordController->getPemeriksaanPunggung($noKunjungan);
        $pemeriksaanPerut = $this->medicalRecordController->getPemeriksaanPerut($noKunjungan);
        $pemeriksaanGenital = $this->medicalRecordController->getPemeriksaanGenital($noKunjungan);
        $pemeriksaanAnus = $this->medicalRecordController->getPemeriksaanAnus($noKunjungan);
        $pemeriksaanLenganAtas = $this->medicalRecordController->getPemeriksaanLenganAtas($noKunjungan);
        $pemeriksaanLenganBawah = $this->medicalRecordController->getPemeriksaanLenganBawah($noKunjungan);
        $pemeriksaanJariTangan = $this->medicalRecordController->getPemeriksaanJariTangan($noKunjungan);
        $pemeriksaanKukuTangan = $this->medicalRecordController->getPemeriksaanKukuTangan($noKunjungan);
        $pemeriksaanPersendianTangan = $this->medicalRecordController->getPemeriksaanPersendianTangan($noKunjungan);
        $pemeriksaanTungkaiAtas = $this->medicalRecordController->getPemeriksaanTungkaiAtas($noKunjungan);
        $pemeriksaanTungkaiBawah = $this->medicalRecordController->getPemeriksaanTungkaiBawah($noKunjungan);
        $pemeriksaanJariKaki = $this->medicalRecordController->getPemeriksaanJariKaki($noKunjungan);
        $pemeriksaanKukuKaki = $this->medicalRecordController->getPemeriksaanKukuKaki($noKunjungan);
        $pemeriksaanPersendianKaki = $this->medicalRecordController->getPemeriksaanPersendianKaki($noKunjungan);
        $pemeriksaanFaring = $this->medicalRecordController->getPemeriksaanFaring($noKunjungan);
        $pemeriksaanSaluranCernahBawah = $this->medicalRecordController->getPemeriksaanSaluranCernahBawah($noKunjungan);
        $pemeriksaanSaluranCernahAtas = $this->medicalRecordController->getPemeriksaanSaluranCernahAtas($noKunjungan);
        $cppt = $this->medicalRecordController->getCppt($noKunjungan);
        $jadwalKontrol = $this->medicalRecordController->getJadwalKontrol($noKunjungan);

        return [
            'triage' => $triage ? $triage->id : null,
            'rekonsiliasiObatAdmisi' => $rekonsiliasiObatAdmisi ? $rekonsiliasiObatAdmisi->id : null,
            'rekonsiliasiObatTransfer' => $rekonsiliasiObatTransfer ? $rekonsiliasiObatTransfer->id : null,
            'rekonsiliasiObatDischarge' => $rekonsiliasiObatDischarge ? $rekonsiliasiObatDischarge->id : null,
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
            'pemeriksaanFungsional' => $pemeriksaanFungsional ? $pemeriksaanFungsional->id : null,
            'pemeriksaanFisik' => $pemeriksaanFisik ? $pemeriksaanFisik->id : null,
            'pemeriksaanKepala' => $pemeriksaanKepala ? $pemeriksaanKepala->id : null,
            'pemeriksaanMata' => $pemeriksaanMata ? $pemeriksaanMata->id : null,
            'pemeriksaanTelinga' => $pemeriksaanTelinga ? $pemeriksaanTelinga->id : null,
            'pemeriksaanHidung' => $pemeriksaanHidung ? $pemeriksaanHidung->id : null,
            'pemeriksaanRambut' => $pemeriksaanRambut ? $pemeriksaanRambut->id : null,
            'pemeriksaanBibir' => $pemeriksaanBibir ? $pemeriksaanBibir->id : null,
            'pemeriksaanGigiGeligi' => $pemeriksaanGigiGeligi ? $pemeriksaanGigiGeligi->id : null,
            'pemeriksaanLidah' => $pemeriksaanLidah ? $pemeriksaanLidah->id : null,
            'pemeriksaanLangitLangit' => $pemeriksaanLangitLangit ? $pemeriksaanLangitLangit->id : null,
            'pemeriksaanLeher' => $pemeriksaanLeher ? $pemeriksaanLeher->id : null,
            'pemeriksaanTenggorokan' => $pemeriksaanTenggorokan ? $pemeriksaanTenggorokan->id : null,
            'pemeriksaanTonsil' => $pemeriksaanTonsil ? $pemeriksaanTonsil->id : null,
            'pemeriksaanDada' => $pemeriksaanDada ? $pemeriksaanDada->id : null,
            'pemeriksaanPayudara' => $pemeriksaanPayudara ? $pemeriksaanPayudara->id : null,
            'pemeriksaanPunggung' => $pemeriksaanPunggung ? $pemeriksaanPunggung->id : null,
            'pemeriksaanPerut' => $pemeriksaanPerut ? $pemeriksaanPerut->id : null,
            'pemeriksaanGenital' => $pemeriksaanGenital ? $pemeriksaanGenital->id : null,
            'pemeriksaanAnus' => $pemeriksaanAnus ? $pemeriksaanAnus->id : null,
            'pemeriksaanLenganAtas' => $pemeriksaanLenganAtas ? $pemeriksaanLenganAtas->id : null,
            'pemeriksaanLenganBawah' => $pemeriksaanLenganBawah ? $pemeriksaanLenganBawah->id : null,
            'pemeriksaanJariTangan' => $pemeriksaanJariTangan ? $pemeriksaanJariTangan->id : null,
            'pemeriksaanKukuTangan' => $pemeriksaanKukuTangan ? $pemeriksaanKukuTangan->id : null,
            'pemeriksaanPersendianTangan' => $pemeriksaanPersendianTangan ? $pemeriksaanPersendianTangan->id : null,
            'pemeriksaanTungkaiAtas' => $pemeriksaanTungkaiAtas ? $pemeriksaanTungkaiAtas->id : null,
            'pemeriksaanTungkaiBawah' => $pemeriksaanTungkaiBawah ? $pemeriksaanTungkaiBawah->id : null,
            'pemeriksaanJariKaki' => $pemeriksaanJariKaki ? $pemeriksaanJariKaki->id : null,
            'pemeriksaanKukuKaki' => $pemeriksaanKukuKaki ? $pemeriksaanKukuKaki->id : null,
            'pemeriksaanPersendianKaki' => $pemeriksaanPersendianKaki ? $pemeriksaanPersendianKaki->id : null,
            'pemeriksaanFaring' => $pemeriksaanFaring ? $pemeriksaanFaring->id : null,
            'pemeriksaanSaluranCernahBawah' => $pemeriksaanSaluranCernahBawah ? $pemeriksaanSaluranCernahBawah->id : null,
            'pemeriksaanSaluranCernahAtas' => $pemeriksaanSaluranCernahAtas ? $pemeriksaanSaluranCernahAtas->id : null,
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

    public function rekonsiliasiObatAdmisi($id)
    {
        $query = MedicalrecordRekonsiliasiObatModel::getById($id);

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
        $idRekon     = $query->ID;

        $detailObat = DB::connection('mysql11')->table('medicalrecord.rekonsiliasi_obat_detil as rekonsiliasiObatDetail')
            ->select([
                'rekonsiliasiObatDetail.ID as id',
                'rekonsiliasiObatDetail.OBAT_DARI_LUAR as obatDariLuar',
                'rekonsiliasiObatDetail.DOSIS as dosis',
                'rekonsiliasiObatDetail.FREKUENSI as frekuensi',
                'rekonsiliasiObatDetail.RUTE as rute',
                'rekonsiliasiObatDetail.TINDAK_LANJUT as tindakLanjut',
                'rekonsiliasiObatDetail.PERUBAHAN_ATURAN_PAKAI as perubahanAturanPakai',
                'rekonsiliasiObatDetail.JENIS_REKONSILIASI as jenisRekonsiliasi',
                'rekonsiliasiObatDetail.STATUS as status',
            ])
            ->where('rekonsiliasiObatDetail.REKONSILIASI_OBAT', $idRekon)
            ->get();

        return inertia("Pendaftaran/Kunjungan/TableRekonObat", [
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
            'dataTable'        => $detailObat,
            'judulRme'         => 'REKONSILIASI OBAT ADMISI',
        ]);
    }

    public function rekonsiliasiObatTransfer($id)
    {
        $query = MedicalrecordRekonsiliasiTransferModel::getById($id);

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
        $idRekon     = $query->ID;

        $detailObat = DB::connection('mysql11')->table('medicalrecord.rekonsiliasi_transfer_detil as rekonsiliasiTransferDetail')
            ->select([
                'rekonsiliasiTransferDetail.ID as id',
                'rekonsiliasiTransferDetail.OBAT_DARI_LUAR as obatDariLuar',
                'rekonsiliasiTransferDetail.DOSIS as dosis',
                'rekonsiliasiTransferDetail.FREKUENSI as frekuensi',
                'rekonsiliasiTransferDetail.RUTE as rute',
                'rekonsiliasiTransferDetail.TINDAK_LANJUT as tindakLanjut',
                'rekonsiliasiTransferDetail.PERUBAHAN_ATURAN_PAKAI as perubahanAturanPakai',
                'rekonsiliasiTransferDetail.STATUS as status',
                'rekonsiliasiTransferDetail.INPUT_ASAL as inputAsal',
                'rekonsiliasiTransferDetail.REKONSILIASI_ADMISI_DETIL as rekonsiliasiAdmisiDetail',
                'rekonsiliasiTransferDetail.LAYANAN_FARMASI as layananFarmasi',
                'rekonsiliasiTransferDetail.REKONSILIASI_TRANSFER_DETIL as rekonsiliasiTransferDetail',
            ])
            ->where('rekonsiliasiTransferDetail.REKONSILIASI_TRANSFER', $idRekon)
            ->get();

        return inertia("Pendaftaran/Kunjungan/TableRekonObat", [
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
            'dataTable'        => $detailObat,
            'judulRme'         => 'REKONSILIASI OBAT TRANSFER',
        ]);
    }

    public function rekonsiliasiObatDischarge($id)
    {
        $query = MedicalrecordRekonsiliasiDischargeModel::getById($id);

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
        $idRekon     = $query->ID;

        $detailObat = DB::connection('mysql11')->table('medicalrecord.rekonsiliasi_discharge_detil as rekonsiliasiDischargeDetail')
            ->select([
                'rekonsiliasiDischargeDetail.ID as id',
                'rekonsiliasiDischargeDetail.OBAT_DARI_LUAR as obatDariLuar',
                'rekonsiliasiDischargeDetail.DOSIS as dosis',
                'rekonsiliasiDischargeDetail.FREKUENSI as frekuensi',
                'rekonsiliasiDischargeDetail.RUTE as rute',
                'rekonsiliasiDischargeDetail.TINDAK_LANJUT as tindakLanjut',
                'rekonsiliasiDischargeDetail.PERUBAHAN_ATURAN_PAKAI as perubahanAturanPakai',
                'rekonsiliasiDischargeDetail.STATUS as status',
                'rekonsiliasiDischargeDetail.INPUT_ASAL as inputAsal',
                'rekonsiliasiDischargeDetail.REKONSILIASI_ADMISI_DETIL as rekonsiliasiAdmisiDetail',
                'rekonsiliasiDischargeDetail.LAYANAN_FARMASI as layananFarmasi',
                'rekonsiliasiDischargeDetail.REKONSILIASI_TRANSFER_DETIL as rekonsiliasiTransferDetail',
            ])
            ->where('rekonsiliasiDischargeDetail.REKONSILIASI_DISCHARGE', $idRekon)
            ->get();

        return inertia("Pendaftaran/Kunjungan/TableRekonObat", [
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
            'dataTable'        => $detailObat,
            'judulRme'         => 'REKONSILIASI OBAT DISCHARGE',
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
        $query = MedicalrecordRiwayatPemberianObatModel::getById($id);

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
        $query = MedicalrecordRiwayatLainnyaModel::getById($id);

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
        $query = MedicalrecordFaktorRisikoModel::getById($id);

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
        $query = MedicalrecordRiwayatPenyakitKeluargaModel::getById($id);

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
        $query = MedicalrecordRiwayatTuberkulosisModel::getById($id);

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
        $query = MedicalrecordRiwayatGynekologiModel::getById($id);

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
        $query = MedicalrecordStatusFungsionalModel::getById($id);

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
        $query = MedicalrecordKondisiSosialModel::getById($id);

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
        $query = MedicalrecordEdukasiPasienKeluargaModel::getById($id);

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
        $query = MedicalrecordEdukasiEmergencyModel::getById($id);

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
        $query = MedicalrecordEdukasiEndOfLifeModel::getById($id);

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
        $query = MedicalrecordPermasalahanGiziModel::getById($id);

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
        $query = MedicalrecordBatukModel::getById($id);

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
        $query = MedicalrecordTandaVitalModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN UMUM TANDA VITAL',
        ]);
    }

    public function pemeriksaanFungsional($id)
    {
        // Fetch the specific data
        $query = MedicalrecordFungsionalModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN UMUM FUNGSIONAL',
        ]);
    }

    public function pemeriksaanFisik($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPenilaianFisikModel::getById($id);

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

    public function pemeriksaanKepala($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanKepalaModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN KEPALA',
        ]);
    }

    public function pemeriksaanMata($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanMataModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN MATA',
        ]);
    }

    public function pemeriksaanTelinga($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanTelingaModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN TELINGA',
        ]);
    }

    public function pemeriksaanHidung($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanHidungModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN HIDUNG',
        ]);
    }

    public function pemeriksaanRambut($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanRambutModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN RAMBUT',
        ]);
    }

    public function pemeriksaanBibir($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanBibirModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN BIBIR',
        ]);
    }

    public function pemeriksaanGigiGeligi($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanGigiGeligiModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN GIGI GELIGI',
        ]);
    }

    public function pemeriksaanLidah($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanLidahModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN LIDAH',
        ]);
    }

    public function pemeriksaanLangitLangit($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanLangitLangitModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN LANGIT LANGIT',
        ]);
    }

    public function pemeriksaanLeher($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanLeherModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN LEHER',
        ]);
    }

    public function pemeriksaanTenggorokan($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanTenggorokanModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN TENGGOROKAN',
        ]);
    }

    public function pemeriksaanTonsil($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanTonsilModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN TONSIL',
        ]);
    }

    public function pemeriksaanDada($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanDadaModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN DADA',
        ]);
    }
    public function pemeriksaanPayudara($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanPayudaraModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN PAYUDARA',
        ]);
    }

    public function pemeriksaanPunggung($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanPunggungModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN PUNGGUNG',
        ]);
    }

    public function pemeriksaanPerut($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanPerutModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN PERUT',
        ]);
    }

    public function pemeriksaanGenital($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanGenitalModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN GENITAL',
        ]);
    }

    public function pemeriksaanAnus($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanAnusModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN ANUS',
        ]);
    }

    public function pemeriksaanLenganAtas($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanLenganAtasModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN LENGAN ATAS',
        ]);
    }

    public function pemeriksaanLenganBawah($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanLenganBawahModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN LENGAN BAWAH',
        ]);
    }

    public function pemeriksaanJariTangan($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanJariTanganModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN JARI TANGAN',
        ]);
    }

    public function pemeriksaanKukuTangan($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanKukuTanganModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN KUKU TANGAN',
        ]);
    }

    public function pemeriksaanPersendianTangan($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanPersendianTanganModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN PERSENDIAN TANGAN',
        ]);
    }

    public function pemeriksaanTungkaiAtas($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanTungkaiAtasModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN TUNGKAI ATAS',
        ]);
    }

    public function pemeriksaanTungkaiBawah($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanTungkaiBawahModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN TUNGKAI BAWAH',
        ]);
    }

    public function pemeriksaanJariKaki($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanJariKakiModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN JARI KAKI',
        ]);
    }

    public function pemeriksaanKukuKaki($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanKukuKakiModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN KUKU KAKI',
        ]);
    }

    public function pemeriksaanPersendianKaki($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanPersendianKakiModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN PERSENDIAN KAKI',
        ]);
    }

    public function pemeriksaanFaring($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanFaringModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN FARING',
        ]);
    }

    public function pemeriksaanSaluranCernahBawah($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanSaluranCernahBawahModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN SALURAN CERNAH BAWAH',
        ]);
    }

    public function pemeriksaanSaluranCernahAtas($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanSaluranCernahAtasModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN SALURAN CERNAH ATAS',
        ]);
    }

    public function pemeriksaanEeg($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanEegModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN EEG',
        ]);
    }

    public function pemeriksaanEmg($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanEmgModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN EMG',
        ]);
    }

    public function pemeriksaanRavenTest($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanRavenTestModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN RAVEN TEST',
        ]);
    }

    public function pemeriksaanCatClams($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanCatClamsModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN CAT CLAMS',
        ]);
    }

    public function pemeriksaanTransfusiDarah($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanTransfusiDarahModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN TRANSFUSI DARAH',
        ]);
    }

    public function pemeriksaanAsessmentMChat($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanAsessmentMChatModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN ASESSMENT M CHAT',
        ]);
    }

    public function pemeriksaanEkg($id)
    {
        // Fetch the specific data
        $query = MedicalrecordPemeriksaanEkgModel::getById($id);

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
            'judulRme'         => 'PEMERIKSAAN EKG',
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
        if ($query->isEmpty()) {
            return redirect()->route('kunjungan.detail', $id)->with('error', 'Data pemeriksaan tidak ditemukan.');
        }

        return inertia("Pendaftaran/Kunjungan/TableCppt", [
            'dataTable'        => $query,
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
        $query = MedicalrecordCpptModel::getById($id);

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
        $query = MedicalrecordJadwalKontrolModel::getById($id);

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
