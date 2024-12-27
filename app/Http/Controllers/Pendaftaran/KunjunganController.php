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
use App\Models\MedicalrecordDischargePlanningFaktorRisikoModel;
use App\Models\MedicalrecordDischargePlanningSkriningModel;
use App\Models\MedicalrecordEdukasiEmergencyModel;
use App\Models\MedicalrecordEdukasiEndOfLifeModel;
use App\Models\MedicalrecordEdukasiPasienKeluargaModel;
use App\Models\MedicalrecordFaktorRisikoModel;
use App\Models\MedicalrecordFungsionalModel;
use App\Models\MedicalrecordJadwalKontrolModel;
use App\Models\MedicalrecordKeluhanUtamaModel;
use App\Models\MedicalrecordKondisiSosialModel;
use App\Models\MedicalrecordPemantauanHDIntradialitikModel;
use App\Models\MedicalrecordPemeriksaanAnusModel;
use App\Models\MedicalrecordPemeriksaanAsessmentMChatModel;
use App\Models\MedicalrecordPemeriksaanBibirModel;
use App\Models\MedicalrecordPemeriksaanCatClamsModel;
use App\Models\MedicalrecordPemeriksaanDadaModel;
use App\Models\MedicalrecordPemeriksaanEegModel;
use App\Models\MedicalrecordPemeriksaanEkgModel;
use App\Models\MedicalrecordPemeriksaanFaringModel;
use App\Models\MedicalrecordPemeriksaanFisikModel;
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
use App\Models\MedicalrecordPenilaianBallanceCairanModel;
use App\Models\MedicalrecordPenilaianDekubitusModel;
use App\Models\MedicalrecordPenilaianDiagnosisModel;
use App\Models\MedicalrecordPenilaianEpfraModel;
use App\Models\MedicalrecordPenilaianFisikModel;
use App\Models\MedicalrecordPenilaianGetupGoModel;
use App\Models\MedicalrecordPenilaianNyeriModel;
use App\Models\MedicalrecordPenilaianSkalaHumptyDumptyModel;
use App\Models\MedicalrecordPenilaianSkalaMorseModel;
use App\Models\MedicalrecordPenilaianStatusPediatrikModel;
use App\Models\MedicalrecordPerencanaanRawatInapModel;
use App\Models\MedicalrecordPermasalahanGiziModel;
use App\Models\MedicalrecordRekonsiliasiDischargeModel;
use App\Models\MedicalrecordRekonsiliasiObatModel;
use App\Models\MedicalrecordRekonsiliasiTransferModel;
use App\Models\MedicalrecordRencanaTerapiModel;
use App\Models\MedicalrecordRiwayatAlergiModel;
use App\Models\MedicalrecordRiwayatGynekologiModel;
use App\Models\MedicalrecordRiwayatLainnyaModel;
use App\Models\MedicalrecordRiwayatPemberianObatModel;
use App\Models\MedicalrecordRiwayatPenyakitKeluargaModel;
use App\Models\MedicalrecordRiwayatTuberkulosisModel;
use App\Models\MedicalrecordRppModel;
use App\Models\MedicalrecordStatusFungsionalModel;
use App\Models\MedicalrecordTandaVitalModel;
use App\Models\MedicalrecordTindakanAbciModel;
use App\Models\MedicalrecordTindakanMmpiModel;

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

    protected function rataRata()
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->selectRaw('
            ROUND(COUNT(*) / COUNT(DISTINCT DATE(kunjungan.MASUK))) AS rata_rata_per_hari,
            ROUND(COUNT(*) / COUNT(DISTINCT WEEK(kunjungan.MASUK, 1))) AS rata_rata_per_minggu,
            ROUND(SUM(CASE WHEN kunjungan.MASUK IS NOT NULL THEN 1 ELSE 0 END) / COUNT(DISTINCT DATE_FORMAT(kunjungan.MASUK, "%Y-%m"))) AS rata_rata_per_bulan,
            ROUND(COUNT(*) / COUNT(DISTINCT YEAR(kunjungan.MASUK))) AS rata_rata_per_tahun
        ')
            ->whereIn('kunjungan.STATUS', [1, 2])
            ->where('kunjungan.MASUK', '>', '0000-00-00')
            ->whereNotNull('kunjungan.MASUK')
            ->whereYear('kunjungan.MASUK', now()->year)
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
                'kunjungan.RUANGAN as ID_RUANGAN',
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

    public function getDiagnosa($id)
    {
        return DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
            ->select([
                'kunjungan.NOMOR as nomor',
                'diagnosa.NOPEN as id',
            ])
            ->leftJoin('medicalrecord.diagnosa as diagnosa', 'diagnosa.NOPEN', '=', 'kunjungan.NOPEN')
            ->where('kunjungan.NOMOR', $id)
            ->first();
    }

    public function getDetailLab($id)
    {
        // Fetch data utama (main lab order details)
        $queryDetail = DB::connection('mysql7')->table('layanan.order_lab as orderLab')
            ->select(
                'orderLab.*',
                'pasien.NORM',
                'pasien.NAMA',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as DOKTER_ASAL'),
                'ruangan.DESKRIPSI as TUJUAN',
                'pengguna.NAMA as OLEH',
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.REF', '=', 'orderLab.NOMOR')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'orderLab.DOKTER_ASAL')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'orderLab.TUJUAN')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'orderLab.OLEH')
            ->where('orderLab.NOMOR', $id)
            ->firstOrFail();

        // Error handling: No data found
        if (!$queryDetail) {
            return redirect()->route('kunjungan.index')->with('error', 'Data tidak ditemukan.');
        }

        // Return both data to the view
        return $queryDetail;
    }

    public function getHasilLab($id)
    {

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

        // Return both data to the view
        return $queryHasil;
    }

    public function getCatatanLab($id)
    {

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
            ->where('kunjungan.REF', $id)
            ->first();

        // Return both data to the view
        return $queryCatatan;
    }

    public function getDetailRad($id)
    {
        // Fetch data utama (main lab order details)
        $queryDetail = DB::connection('mysql7')->table('pendaftaran.kunjungan as kunjungan')
            ->select(
                'orderRad.*',
                'pasien.NORM',
                'pasien.NAMA',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as DOKTER_ASAL'),
                'ruangan.DESKRIPSI as TUJUAN',
                'pengguna.NAMA as OLEH',
            )
            ->leftJoin('layanan.tindakan_medis as tindakanMedis', 'tindakanMedis.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('pendaftaran.pendaftaran as pendaftaran', 'pendaftaran.NOMOR', '=', 'kunjungan.NOPEN')
            ->leftJoin('master.pasien as pasien', 'pasien.NORM', '=', 'pendaftaran.NORM')
            ->leftJoin('layanan.order_detil_rad as orderRadDetail', 'orderRadDetail.REF', '=', 'tindakanMedis.ID')
            ->leftJoin('layanan.order_rad as orderRad', 'orderRad.NOMOR', '=', 'orderRadDetail.ORDER_ID')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'orderRad.DOKTER_ASAL')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('master.ruangan as ruangan', 'ruangan.ID', '=', 'orderRad.TUJUAN')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'orderRad.OLEH')
            ->where('kunjungan.NOMOR', $id)
            ->firstOrFail();

        // Error handling: No data found
        if (!$queryDetail) {
            return redirect()->route('kunjungan.index')->with('error', 'Data tidak ditemukan.');
        }

        // Return both data to the view
        return $queryDetail;
    }

    public function getHasilRad($id)
    {
        // Fetch data utama (main lab order details)
        // Fetch data hasil lab (lab test results)
        $queryHasil = DB::connection('mysql7')->table('layanan.order_rad as orderRad')
            ->select(
                'hasil.ID as ID',
                'tindakan.NAMA as TINDAKAN',
                'hasil.TANGGAL',
                'hasil.KLINIS',
                'hasil.KESAN',
                'hasil.USUL',
                'hasil.HASIL',
                'hasil.BTK',
                'hasil.KRITIS',
                'pengguna.NAMA as PENGGUNA',
                'hasil.STATUS'
            )
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.REF', '=', 'orderRad.NOMOR')
            ->leftJoin('layanan.tindakan_medis as tindakanMedis', 'tindakanMedis.KUNJUNGAN', '=', 'kunjungan.NOMOR')
            ->leftJoin('layanan.hasil_rad as hasil', 'hasil.TINDAKAN_MEDIS', '=', 'tindakanMedis.ID')
            ->leftJoin('master.tindakan as tindakan', 'tindakan.ID', '=', 'tindakanMedis.TINDAKAN')
            ->leftJoin('master.dokter as dokter', 'dokter.ID', '=', 'hasil.DOKTER')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'dokter.NIP')
            ->leftJoin('aplikasi.pengguna as pengguna', 'hasil.OLEH', '=', 'pengguna.ID')
            ->where('orderRad.NOMOR', $id)
            ->where('hasil.STATUS', 2)
            ->distinct()
            ->get();

        // Error handling: No data found
        if (!$queryHasil) {
            return redirect()->route('kunjungan.index')->with('error', 'Data tidak ditemukan.');
        }

        // Return both data to the view
        return $queryHasil;

        dd($queryHasil);
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
        $refKunjungan = $query->REF;

        // Fetch kunjungan data using the new function
        $kunjungan = $this->getKunjungan($pendaftaran);

        //get related data medicalrecord
        $relatedData = $this->getRelatedData($noKunjungan);

        // Fetch diagnosa data
        $diagnosa = $this->getDiagnosa($noKunjungan);
        $diagnosaId = $diagnosa ? $diagnosa->id : null;

        $idRuangan = $query->ID_RUANGAN;

        // //check if kunjungan laboratorium
        $jenisKunjungan = MasterRuanganModel::where('ID', $idRuangan)->value('JENIS_KUNJUNGAN');

        //jika kunjungan laboratorium
        if ($jenisKunjungan == 4) {

            //get detail lab berdasarkan ref kunjungan
            $detailLab = $this->getDetailLab($refKunjungan);

            //get hasil lab berdasarkan ref kunjungan
            $hasilLab = $this->getHasilLab($refKunjungan);

            //get catatan lab berdasarkan ref kunjungan
            $catatanLab = $this->getCatatanLab($refKunjungan);

            return inertia("Pendaftaran/Kunjungan/DetailLab", [
                'detailLab' => $detailLab,
                'detailHasilLab' => $hasilLab,
                'detailCatatanLab' => $catatanLab,
            ]);
        } elseif ($jenisKunjungan == 5) {
            //get detail lab berdasarkan ref kunjungan
            $detailRad = $this->getDetailRad($noKunjungan);

            $orderRadNomor = $detailRad->NOMOR;

            //get hasil lab berdasarkan ref kunjungan
            $hasilRad = $this->getHasilRad($orderRadNomor);

            return inertia("Pendaftaran/Kunjungan/DetailRad", [
                'detailRad' => $detailRad,
                'detailHasilRad' => $hasilRad,
            ]);
        } else {
            return inertia("Pendaftaran/Kunjungan/Detail", array_merge([
                'detail' => $query,
                'nomorPendaftaran' => $pendaftaran,
                'dataKunjungan' => $kunjungan,
                'diagnosa' => $diagnosaId,
            ], $relatedData));
        }
    }

    private function getRelatedData($noKunjungan)
    {
        $methods = [
            'getTriage' => 'triage',
            'getRekonsiliasiObatAdmisi' => 'rekonsiliasiObatAdmisi',
            'getRekonsiliasiObatTransfer' => 'rekonsiliasiObatTransfer',
            'getRekonsiliasiObatDischarge' => 'rekonsiliasiObatDischarge',
            'getAskep' => 'askep',
            'getKeluhanUtama' => 'keluhanUtama',
            'getAnamnesisDiperoleh' => 'anamnesisDiperoleh',
            'getRiwayatPenyakitSekarang' => 'riwayatPenyakitSekarang',
            'getRiwayatPenyakitDahulu' => 'riwayatPenyakitDahulu',
            'getRiwayatAlergi' => 'riwayatAlergi',
            'getRiwayatPemberianObat' => 'riwayatPemberianObat',
            'getRiwayatLainnya' => 'riwayatLainnya',
            'getFaktorRisiko' => 'factoRisiko',
            'getRiwayatPenyakitKeluarga' => 'riwayatPenyakitKeluarga',
            'getRiwayatTuberkulosis' => 'riwayatTuberkulosis',
            'getRiwayatGinekologi' => 'riwayatGinekologi',
            'getStatusFungsional' => 'statusFungsional',
            'getHubunganPsikososial' => 'hubunganPsikososial',
            'getEdukasiPasienKeluarga' => 'edukasiPasienKeluarga',
            'getEdukasiEmergency' => 'edukasiEmergency',
            'getEdukasiEndOfLife' => 'edukasiEndOfLife',
            'getSkriningGiziAwal' => 'skriningGiziAwal',
            'getBatuk' => 'batuk',
            'getPemeriksaanUmum' => 'pemeriksaanUmum',
            'getPemeriksaanFungsional' => 'pemeriksaanFungsional',
            'getPemeriksaanFisik' => 'pemeriksaanFisik',
            'getPemeriksaanKepala' => 'pemeriksaanKepala',
            'getPemeriksaanMata' => 'pemeriksaanMata',
            'getPemeriksaanTelinga' => 'pemeriksaanTelinga',
            'getPemeriksaanHidung' => 'pemeriksaanHidung',
            'getPemeriksaanRambut' => 'pemeriksaanRambut',
            'getPemeriksaanBibir' => 'pemeriksaanBibir',
            'getPemeriksaanGigiGeligi' => 'pemeriksaanGigiGeligi',
            'getPemeriksaanLidah' => 'pemeriksaanLidah',
            'getPemeriksaanLangitLangit' => 'pemeriksaanLangitLangit',
            'getPemeriksaanLeher' => 'pemeriksaanLeher',
            'getPemeriksaanTenggorokan' => 'pemeriksaanTenggorokan',
            'getPemeriksaanTonsil' => 'pemeriksaanTonsil',
            'getPemeriksaanDada' => 'pemeriksaanDada',
            'getPemeriksaanPayudara' => 'pemeriksaanPayudara',
            'getPemeriksaanPunggung' => 'pemeriksaanPunggung',
            'getPemeriksaanPerut' => 'pemeriksaanPerut',
            'getPemeriksaanGenital' => 'pemeriksaanGenital',
            'getPemeriksaanAnus' => 'pemeriksaanAnus',
            'getPemeriksaanLenganAtas' => 'pemeriksaanLenganAtas',
            'getPemeriksaanLenganBawah' => 'pemeriksaanLenganBawah',
            'getPemeriksaanJariTangan' => 'pemeriksaanJariTangan',
            'getPemeriksaanKukuTangan' => 'pemeriksaanKukuTangan',
            'getPemeriksaanPersendianTangan' => 'pemeriksaanPersendianTangan',
            'getPemeriksaanTungkaiAtas' => 'pemeriksaanTungkaiAtas',
            'getPemeriksaanTungkaiBawah' => 'pemeriksaanTungkaiBawah',
            'getPemeriksaanJariKaki' => 'pemeriksaanJariKaki',
            'getPemeriksaanKukuKaki' => 'pemeriksaanKukuKaki',
            'getPemeriksaanPersendianKaki' => 'pemeriksaanPersendianKaki',
            'getPemeriksaanFaring' => 'pemeriksaanFaring',
            'getPemeriksaanSaluranCernahBawah' => 'pemeriksaanSaluranCernahBawah',
            'getPemeriksaanSaluranCernahAtas' => 'pemeriksaanSaluranCernahAtas',
            'getPemeriksaanEeg' => 'pemeriksaanEeg',
            'getPemeriksaanEmg' => 'pemeriksaanEmg',
            'getPemeriksaanRavenTest' => 'pemeriksaanRavenTest',
            'getPemeriksaanCatClams' => 'pemeriksaanCatClams',
            'getPemeriksaanTransfusiDarah' => 'pemeriksaanTransfusiDarah',
            'getPemeriksaanAsessmentMChat' => 'pemeriksaanAsessmentMChat',
            'getPemeriksaanEkg' => 'pemeriksaanEkg',
            'getPenilaianFisik' => 'penilaianFisik',
            'getPenilaianNyeri' => 'penilaianNyeri',
            'getPenilaianStatusPediatrik' => 'penilaianStatusPediatrik',
            'getPenilaianDiagnosis' => 'penilaianDiagnosis',
            'getPenilaianSkalaMorse' => 'penilaianSkalaMorse',
            'getPenilaianSkalaHumptyDumpty' => 'penilaianSkalaHumptyDumpty',
            'getPenilaianEpfra' => 'penilaianEpfra',
            'getPenilaianGetupGo' => 'penilaianGetupGo',
            'getPenilaianDekubitus' => 'penilaianDekubitus',
            'getPenilaianBallanceCairan' => 'penilaianBallanceCairan',
            'getRencanaTerapi' => 'rencanaTerapi',
            'getJadwalKontrol' => 'jadwalKontrol',
            'getPerencanaanRawatInap' => 'perencanaanRawatInap',
            'getDischargePlanningSkrining' => 'dischargePlanningSkrining',
            'getDischargePlanningFaktorRisiko' => 'dischargePlanningFaktorRisiko',
            'getCppt' => 'cppt',
            'getPemantauanHDIntradialitik' => 'pemantauanHDIntradialitik',
            'getTindakanAbci' => 'tindakanAbci',
            'getTindakanMmpi' => 'tindakanMmpi',
        ];

        $result = [];

        foreach ($methods as $method => $key) {
            $data = $this->medicalRecordController->$method($noKunjungan);
            $result[$key] = $data ? $data->id : null;
        }

        return $result;
    }

    public function diagnosa($id)
    {
        // Panggil fungsi getDataPasien
        $dataPasien = DB::connection('mysql5')->table('pendaftaran.kunjungan as kunjungan')
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
            ->where('kunjungan.NOPEN', $id)
            ->distinct()
            ->firstOrFail();

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
                'diagnosa.NOPEN as pendaftaran',
                'diagnosa.ID as id',
                'diagnosa.TANGGAL as tanggal',
                DB::raw('CONCAT(pegawai.GELAR_DEPAN, " ", pegawai.NAMA, " ", pegawai.GELAR_BELAKANG) as oleh'),
                'diagnosa.STATUS as status',
            ])
            ->leftJoin('medicalrecord.diagnosa as diagnosa', 'diagnosa.NOPEN', '=', 'kunjungan.NOPEN')
            ->leftJoin('aplikasi.pengguna as pengguna', 'pengguna.ID', '=', 'diagnosa.OLEH')
            ->leftJoin('master.pegawai as pegawai', 'pegawai.NIP', '=', 'pengguna.NIP')
            ->where('kunjungan.NOPEN', $id)
            ->get();

        // Check if the record exists
        if ($query->isEmpty()) {
            return redirect()->route('kunjungan.detail', $id)->with('error', 'Data pemeriksaan tidak ditemukan.');
        }

        return inertia("Pendaftaran/Kunjungan/TableDiagnosa", [
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

    public function detailDiagnosa($id)
    {
        // Fetch the specific data
        $query = DB::connection('mysql5')->table('medicalrecord.diagnosa as diagnosa')
            ->select([
                'diagnosa.ID as ID',
                'diagnosa.NOPEN as NOMOR_PENDAFTARAN',
                'kunjungan.NOMOR as NOMOR_KUNJUNGAN',
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
            ->leftJoin('pendaftaran.kunjungan as kunjungan', 'kunjungan.NOPEN', '=', 'diagnosa.NOPEN')
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

    public function detailRme($id, $model, $judulRme)
    {
        // Fetch the specific data using dynamic model
        $query = $model::getById($id);

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
            'judulRme'         => $judulRme,
        ]);
    }

    public function triage($id)
    {
        return $this->detailRme($id, MedicalrecordTriageModel::class, 'TRIAGE');
    }

    public function askep($id)
    {
        return $this->detailRme($id, MedicalrecordAsuhanKeperawatanModel::class, 'ASKEP');
    }

    public function keluhanUtama($id)
    {
        return $this->detailRme($id, MedicalrecordKeluhanUtamaModel::class, 'KELUHAN UTAMA');
    }

    public function anamnesisDiperoleh($id)
    {
        return $this->detailRme($id, MedicalrecordAnamnesisDiperolehModel::class, 'ANAMNESIS DIPEROLEH');
    }

    public function riwayatPenyakitSekarang($id)
    {
        return $this->detailRme($id, MedicalrecordAnamnesisModel::class, 'RIWAYAT PENYAKIT SEKARANG');
    }

    public function riwayatPenyakitDahulu($id)
    {
        return $this->detailRme($id, MedicalrecordRppModel::class, 'RIWAYAT PERJALANAN PENYAKIT');
    }

    public function riwayatAlergi($id)
    {
        return $this->detailRme($id, MedicalrecordRiwayatAlergiModel::class, 'RIWAYAT ALERGI');
    }

    public function riwayatPemberianObat($id)
    {
        return $this->detailRme($id, MedicalrecordRiwayatPemberianObatModel::class, 'RIWAYAT PEMBERIAN OBAT');
    }

    public function riwayatLainnya($id)
    {
        return $this->detailRme($id, MedicalrecordRiwayatLainnyaModel::class, 'RIWAYAT LAINNYA');
    }

    public function faktorRisiko($id)
    {
        return $this->detailRme($id, MedicalrecordFaktorRisikoModel::class, 'FAKTOR RISIKO');
    }

    public function riwayatPenyakitKeluarga($id)
    {
        return $this->detailRme($id, MedicalrecordRiwayatPenyakitKeluargaModel::class, 'RIWAYAT PENYAKIT KELUARGA');
    }

    public function riwayatTuberkulosis($id)
    {
        return $this->detailRme($id, MedicalrecordRiwayatTuberkulosisModel::class, 'RIWAYAT TUBERKULOSIS');
    }

    public function riwayatGinekologi($id)
    {
        return $this->detailRme($id, MedicalrecordRiwayatGynekologiModel::class, 'RIWAYAT GINEKOLOGI');
    }

    public function statusFungsional($id)
    {
        return $this->detailRme($id, MedicalrecordStatusFungsionalModel::class, 'STATUS FUNGSIONAL');
    }

    public function hubunganPsikososial($id)
    {
        return $this->detailRme($id, MedicalrecordKondisiSosialModel::class, 'HUBUNGAN PSIKOSOSIAL');
    }

    public function edukasiPasienKeluarga($id)
    {
        return $this->detailRme($id, MedicalrecordEdukasiPasienKeluargaModel::class, 'EDUKASI PASIEN DAN KELUARGA');
    }

    public function edukasiEmergency($id)
    {
        return $this->detailRme($id, MedicalrecordEdukasiEmergencyModel::class, 'EDUKASI EMERGENCY');
    }

    public function edukasiEndOfLife($id)
    {
        return $this->detailRme($id, MedicalrecordEdukasiEndOfLifeModel::class, 'EDUKASI END OF LIFE');
    }

    public function skriningGiziAwal($id)
    {
        return $this->detailRme($id, MedicalrecordPermasalahanGiziModel::class, 'SKRINING GIZI AWAL');
    }

    public function batuk($id)
    {
        return $this->detailRme($id, MedicalrecordBatukModel::class, 'BATUK');
    }

    public function pemeriksaanUmum($id)
    {
        return $this->detailRme($id, MedicalrecordTandaVitalModel::class, 'PEMERIKSAAN UMUM TANDA VITAL');
    }

    public function pemeriksaanFungsional($id)
    {
        return $this->detailRme($id, MedicalrecordFungsionalModel::class, 'PEMERIKSAAN UMUM FUNGSIONAL');
    }

    public function pemeriksaanFisik($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanFisikModel::class, 'PEMERIKSAAN FISIK');
    }

    public function pemeriksaanKepala($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanKepalaModel::class, 'PEMERIKSAAN KEPALA');
    }

    public function pemeriksaanMata($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanMataModel::class, 'PEMERIKSAAN MATA');
    }

    public function pemeriksaanTelinga($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanTelingaModel::class, 'PEMERIKSAAN TELINGA');
    }

    public function pemeriksaanHidung($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanHidungModel::class, 'PEMERIKSAAN HIDUNG');
    }

    public function pemeriksaanRambut($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanRambutModel::class, 'PEMERIKSAAN RAMBUT');
    }

    public function pemeriksaanBibir($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanBibirModel::class, 'PEMERIKSAAN BIBIR');
    }

    public function pemeriksaanGigiGeligi($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanGigiGeligiModel::class, 'PEMERIKSAAN GIGI GELIGI');
    }

    public function pemeriksaanLidah($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanLidahModel::class, 'PEMERIKSAAN LIDAH');
    }

    public function pemeriksaanLangitLangit($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanLangitLangitModel::class, 'PEMERIKSAAN LANGIT-LANGIT');
    }

    public function pemeriksaanLeher($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanLeherModel::class, 'PEMERIKSAAN LEHER');
    }

    public function pemeriksaanTenggorokan($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanTenggorokanModel::class, 'PEMERIKSAAN TENGGOROKAN');
    }

    public function pemeriksaanTonsil($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanTonsilModel::class, 'PEMERIKSAAN TONSIL');
    }

    public function pemeriksaanDada($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanDadaModel::class, 'PEMERIKSAAN DADA');
    }

    public function pemeriksaanPayudara($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanPayudaraModel::class, 'PEMERIKSAAN PAYUDARA');
    }

    public function pemeriksaanPunggung($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanPunggungModel::class, 'PEMERIKSAAN PUNGGUNG');
    }

    public function pemeriksaanPerut($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanPerutModel::class, 'PEMERIKSAAN PERUT');
    }

    public function pemeriksaanGenital($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanGenitalModel::class, 'PEMERIKSAAN GENITAL');
    }

    public function pemeriksaanAnus($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanAnusModel::class, 'PEMERIKSAAN ANUS');
    }

    public function pemeriksaanLenganAtas($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanLenganAtasModel::class, 'PEMERIKSAAN LENGAN ATAS');
    }

    public function pemeriksaanLenganBawah($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanLenganBawahModel::class, 'PEMERIKSAAN LENGAN BAWAH');
    }

    public function pemeriksaanJariTangan($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanJariTanganModel::class, 'PEMERIKSAAN JARI TANGAN');
    }

    public function pemeriksaanKukuTangan($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanKukuTanganModel::class, 'PEMERIKSAAN KUKU TANGAN');
    }

    public function pemeriksaanPersendianTangan($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanPersendianTanganModel::class, 'PEMERIKSAAN PERSENDIAN TANGAN');
    }

    public function pemeriksaanTungkaiAtas($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanTungkaiAtasModel::class, 'PEMERIKSAAN TUNGKAI ATAS');
    }

    public function pemeriksaanTungkaiBawah($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanTungkaiBawahModel::class, 'PEMERIKSAAN TUNGKAI BAWAH');
    }

    public function pemeriksaanJariKaki($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanJariKakiModel::class, 'PEMERIKSAAN JARI KAKI');
    }

    public function pemeriksaanKukuKaki($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanKukuKakiModel::class, 'PEMERIKSAAN KUKU KAKI');
    }

    public function pemeriksaanPersendianKaki($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanPersendianKakiModel::class, 'PEMERIKSAAN PERSENDIAN KAKI');
    }

    public function pemeriksaanFaring($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanFaringModel::class, 'PEMERIKSAAN FARING');
    }

    public function pemeriksaanSaluranCernahBawah($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanSaluranCernahBawahModel::class, 'PEMERIKSAAN SALURAN CERNAH BAWAH');
    }

    public function pemeriksaanSaluranCernahAtas($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanSaluranCernahAtasModel::class, 'PEMERIKSAAN SALURAN CERNAH ATAS');
    }

    public function pemeriksaanEeg($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanEegModel::class, 'PEMERIKSAAN EEG');
    }

    public function pemeriksaanEmg($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanEmgModel::class, 'PEMERIKSAAN EMG');
    }

    public function pemeriksaanRavenTest($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanRavenTestModel::class, 'PEMERIKSAAN RAVEN TEST');
    }

    public function pemeriksaanCatClams($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanCatClamsModel::class, 'PEMERIKSAAN CAT CLAMS');
    }

    public function pemeriksaanTransfusiDarah($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanTransfusiDarahModel::class, 'PEMERIKSAAN TRANSFUSI DARAH');
    }

    public function pemeriksaanAsessmentMChat($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanAsessmentMChatModel::class, 'PEMERIKSAAN ASESSMENT M CHAT');
    }

    public function pemeriksaanEkg($id)
    {
        return $this->detailRme($id, MedicalrecordPemeriksaanEkgModel::class, 'PEMERIKSAAN EKG');
    }

    public function penilaianFisik($id)
    {
        return $this->detailRme($id, MedicalrecordPenilaianFisikModel::class, 'PENILAIAN FISIK');
    }

    public function penilaianNyeri($id)
    {
        return $this->detailRme($id, MedicalrecordPenilaianNyeriModel::class, 'PENILAIAN NYERI');
    }

    public function penilaianStatusPediatrik($id)
    {
        return $this->detailRme($id, MedicalrecordPenilaianStatusPediatrikModel::class, 'PENILAIAN STATUS PEDIATRIK');
    }

    public function penilaianDiagnosis($id)
    {
        return $this->detailRme($id, MedicalrecordPenilaianDiagnosisModel::class, 'PENILAIAN DIAGNOSIS');
    }

    public function penilaianSkalaMorse($id)
    {
        return $this->detailRme($id, MedicalrecordPenilaianSkalaMorseModel::class, 'PENILAIAN SKALA MORSE');
    }

    public function penilaianSkalaHumptyDumpty($id)
    {
        return $this->detailRme($id, MedicalrecordPenilaianSkalaHumptyDumptyModel::class, 'PENILAIAN SKALA HUMPTY DUMPTY');
    }

    public function penilaianEpfra($id)
    {
        return $this->detailRme($id, MedicalrecordPenilaianEpfraModel::class, 'PENILAIAN EPFRA');
    }

    public function penilaianGetupGo($id)
    {
        return $this->detailRme($id, MedicalrecordPenilaianGetupGoModel::class, 'PENILAIAN GET UP AND GO');
    }

    public function penilaianDekubitus($id)
    {
        return $this->detailRme($id, MedicalrecordPenilaianDekubitusModel::class, 'PENILAIAN DEKUBITUS');
    }

    public function penilaianBallanceCairan($id)
    {
        return $this->detailRme($id, MedicalrecordPenilaianBallanceCairanModel::class, 'PENILAIAN BALANCE CAIRAN');
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
        return $this->detailRme($id, MedicalrecordCpptModel::class, 'CPPT');
    }

    public function rencanaTerapi($id)
    {
        return $this->detailRme($id, MedicalrecordRencanaTerapiModel::class, 'RENCANA TERAPI');
    }

    public function jadwalKontrol($id)
    {
        return $this->detailRme($id, MedicalrecordJadwalKontrolModel::class, 'JADWAL KONTROL');
    }

    public function perencanaanRawatInap($id)
    {
        return $this->detailRme($id, MedicalrecordPerencanaanRawatInapModel::class, 'PERENCANAAN RAWAT INAP');
    }

    public function dischargePlanningSkrining($id)
    {
        return $this->detailRme($id, MedicalrecordDischargePlanningSkriningModel::class, 'DISCHARGE PLANNING SKRINING');
    }

    public function dischargePlanningFaktorRisiko($id)
    {
        return $this->detailRme($id, MedicalrecordDischargePlanningFaktorRisikoModel::class, 'DISCHARGE PLANNING FAKTOR RISIKO');
    }

    public function pemantuanHDIntradialitik($id)
    {
        return $this->detailRme($id, MedicalrecordPemantauanHDIntradialitikModel::class, 'PEMANTAUAN HD INTRADIALITIK');
    }

    public function tindakanAbci($id)
    {
        return $this->detailRme($id, MedicalrecordTindakanAbciModel::class, 'TINDAKAN ABC I');
    }

    public function tindakanMmpi($id)
    {
        return $this->detailRme($id, MedicalrecordTindakanMmpiModel::class, 'TINDAKAN MMPI');
    }
}
