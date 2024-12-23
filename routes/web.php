<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\StafController;
use App\Http\Controllers\Layanan\ResepController;
use App\Http\Controllers\Master\DokterController;
use App\Http\Controllers\Master\PasienController;
use App\Http\Controllers\Layanan\PulangController;
use App\Http\Controllers\Master\PegawaiController;
use App\Http\Controllers\Master\PerawatController;
use App\Http\Controllers\Master\RuanganController;
use App\Http\Controllers\Bpjs\PesertaBpjController;
use App\Http\Controllers\Inventory\OrderController;
use App\Http\Controllers\Inventory\StockController;
use App\Http\Controllers\Logs\BridgeLogsController;
use App\Http\Controllers\Master\TindakanController;
use App\Http\Controllers\Inventory\BarangController;
use App\Http\Controllers\Logs\PenggunaLogController;
use App\Http\Controllers\Master\ReferensiController;
use App\Http\Controllers\Bpjs\PengajuanSepController;
use App\Http\Controllers\Layanan\RadiologiController;
use App\Http\Controllers\Satusehat\AllergyController;
use App\Http\Controllers\Satusehat\ConsentController;
use App\Http\Controllers\Satusehat\PatientController;
use App\Http\Controllers\Bpjs\KunjunganBpjsController;
use App\Http\Controllers\Logs\PenggunaAksesController;
use App\Http\Controllers\Pendaftaran\KonsulController;
use App\Http\Controllers\Pendaftaran\MutasiController;
use App\Http\Controllers\Satusehat\CarePlanController;
use App\Http\Controllers\Satusehat\LocationController;
use App\Http\Controllers\Satusehat\SpecimenController;
use App\Http\Controllers\Bpjs\RencanaKontrolController;
use App\Http\Controllers\Inventory\TransaksiController;
use App\Http\Controllers\Laporan\LaporanRl12Controller;
use App\Http\Controllers\Laporan\LaporanRl51Controller;
use App\Http\Controllers\Satusehat\ConditionController;
use App\Http\Controllers\Satusehat\EncounterController;
use App\Http\Controllers\Satusehat\ProcedureController;
use App\Http\Controllers\Bpjs\MonitoringRekonController;
use App\Http\Controllers\Inventory\PenerimaanController;
use App\Http\Controllers\Inventory\PengirimanController;
use App\Http\Controllers\Inventory\PermintaanController;
use App\Http\Controllers\Layanan\LaboratoriumController;
use App\Http\Controllers\Logs\PenggunaRequestController;
use App\Http\Controllers\Satusehat\MedicationController;
use App\Http\Controllers\Pendaftaran\KunjunganController;
use App\Http\Controllers\Satusehat\BarangToBzaController;
use App\Http\Controllers\Satusehat\CompositionController;
use App\Http\Controllers\Satusehat\ObservationController;
use App\Http\Controllers\Master\TindakanRuanganController;
use App\Http\Controllers\Satusehat\ImagingStudyController;
use App\Http\Controllers\Satusehat\OrganizationController;
use App\Http\Controllers\Satusehat\PractitionerController;
use App\Http\Controllers\Inventory\BarangRuanganController;
use App\Http\Controllers\Pendaftaran\PendaftaranController;
use App\Http\Controllers\Satusehat\ServiceRequestController;
use App\Http\Controllers\Satusehat\TindakanToLoincController;
use App\Http\Controllers\Pendaftaran\AntrianRuanganController;
use App\Http\Controllers\Pendaftaran\MedicalRecordController;
use App\Http\Controllers\Pendaftaran\ReservasiController;
use App\Http\Controllers\Satusehat\ConditionHasilPaController;
use App\Http\Controllers\Satusehat\DiagnosticReportController;
use App\Http\Controllers\Satusehat\MedicationRequestController;
use App\Http\Controllers\Satusehat\MedicationDispanseController;
use App\Http\Controllers\Satusehat\ConditionPenilaianTumorController;
use App\Http\Controllers\Satusehat\SinkronisasiController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'hospitalName' => env('HOSPITAL_NAME', 'Default Hospital Name'),
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('satusehat')->namespace('App\Http\Controllers\Satusehat')->group(function () {
        Route::get('sinkronisasi', [SinkronisasiController::class, 'index'])->name('sinkronisasi.index');

        Route::get('organization', [OrganizationController::class, 'index'])->name('organization.index');
        Route::get('organization/detail/{id}', [OrganizationController::class, 'detail'])->name('organization.detail');

        Route::get('location', [LocationController::class, 'index'])->name('location.index');
        Route::get('location/detail/{id}', [LocationController::class, 'detail'])->name('location.detail');

        Route::get('patient', [PatientController::class, 'index'])->name('patient.index');
        Route::get('patient/detail/{id}', [PatientController::class, 'detail'])->name('patient.detail');
        Route::get('/patient/{filter}', [PatientController::class, 'filterByTime'])
            ->name('patient.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('practitioner', [PractitionerController::class, 'index'])->name('practitioner.index');
        Route::get('practitioner/detail/{id}', [PractitionerController::class, 'detail'])->name('practitioner.detail');

        Route::get('encounter', [EncounterController::class, 'index'])->name('encounter.index');
        Route::get('encounter/detail/{id}', [EncounterController::class, 'detail'])->name('encounter.detail');
        Route::get('/encounter/{filter}', [EncounterController::class, 'filterByTime'])
            ->name('encounter.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('condition', [ConditionController::class, 'index'])->name('condition.index');
        Route::get('condition/detail/{id}', [ConditionController::class, 'detail'])->name('condition.detail');
        Route::get('/condition/{filter}', [ConditionController::class, 'filterByTime'])
            ->name('condition.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('observation', [ObservationController::class, 'index'])->name('observation.index');
        Route::get('observation/detail/{id}', [ObservationController::class, 'detail'])->name('observation.detail');
        Route::get('/observation/{filter}', [ObservationController::class, 'filterByTime'])
            ->name('observation.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('procedure', [ProcedureController::class, 'index'])->name('procedure.index');
        Route::get('procedure/detail/{id}', [ProcedureController::class, 'detail'])->name('procedure.detail');

        Route::get('composition', [CompositionController::class, 'index'])->name('composition.index');
        Route::get('composition/detail/{id}', [CompositionController::class, 'detail'])->name('composition.detail');
        Route::get('/composition/{filter}', [CompositionController::class, 'filterByTime'])
            ->name('composition.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('consent', [ConsentController::class, 'index'])->name('consent.index');
        Route::get('consent/detail/{id}', [ConsentController::class, 'detail'])->name('consent.detail');
        Route::get('/consent/{filter}', [ConsentController::class, 'filterByTime'])
            ->name('consent.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('diagnosticReport', [DiagnosticReportController::class, 'index'])->name('diagnosticReport.index');
        Route::get('diagnosticReport/detail/{id}', [DiagnosticReportController::class, 'detail'])->name('diagnosticReport.detail');
        Route::get('/diagnosticReport/{filter}', [DiagnosticReportController::class, 'filterByTime'])
            ->name('diagnosticReport.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('medication', [MedicationController::class, 'index'])->name('medication.index');
        Route::get('medication/detail/{id}', [MedicationController::class, 'detail'])->name('medication.detail');

        Route::get('medicationDispanse', [MedicationDispanseController::class, 'index'])->name('medicationDispanse.index');
        Route::get('medicationDispanse/detail/{id}', [MedicationDispanseController::class, 'detail'])->name('medicationDispanse.detail');

        Route::get('medicationRequest', [MedicationRequestController::class, 'index'])->name('medicationRequest.index');
        Route::get('medicationRequest/detail/{id}', [MedicationRequestController::class, 'detail'])->name('medicationRequest.detail');

        Route::get('serviceRequest', [ServiceRequestController::class, 'index'])->name('serviceRequest.index');
        Route::get('serviceRequest/detail/{id}', [ServiceRequestController::class, 'detail'])->name('serviceRequest.detail');
        Route::get('/serviceRequest/{filter}', [ServiceRequestController::class, 'filterByTime'])
            ->name('serviceRequest.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('specimen', [SpecimenController::class, 'index'])->name('specimen.index');
        Route::get('specimen/detail/{id}', [SpecimenController::class, 'detail'])->name('specimen.detail');
        Route::get('/specimen/{filter}', [SpecimenController::class, 'filterByTime'])
            ->name('specimen.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('allergy', [AllergyController::class, 'index'])->name('allergy.index');
        Route::get('allergy/detail/{id}', [AllergyController::class, 'detail'])->name('allergy.detail');
        Route::get('/allergy/{filter}', [AllergyController::class, 'filterByTime'])
            ->name('allergy.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('barangBza', [BarangToBzaController::class, 'index'])->name('barangBza.index');
        Route::get('barangBza/detail/{id}', [BarangToBzaController::class, 'detail'])->name('barangBza.detail');

        Route::get('carePlan', [CarePlanController::class, 'index'])->name('carePlan.index');
        Route::get('carePlan/detail/{id}', [CarePlanController::class, 'detail'])->name('carePlan.detail');
        Route::get('/carePlan/{filter}', [CarePlanController::class, 'filterByTime'])
            ->name('carePlan.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('conditionPa', [ConditionHasilPaController::class, 'index'])->name('conditionPa.index');
        Route::get('conditionPa/detail/{id}', [ConditionHasilPaController::class, 'detail'])->name('conditionPa.detail');

        Route::get('conditionTumor', [ConditionPenilaianTumorController::class, 'index'])->name('conditionTumor.index');
        Route::get('conditionTumor/detail/{id}', [ConditionPenilaianTumorController::class, 'detail'])->name('conditionTumor.detail');

        Route::get('imagingStudy', [ImagingStudyController::class, 'index'])->name('imagingStudy.index');
        Route::get('/imagingStudy/{filter}', [ImagingStudyController::class, 'filterByTime'])
            ->name('imagingStudy.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('tindakanToLoinc', [TindakanToLoincController::class, 'index'])->name('tindakanToLoinc.index');
        Route::get('tindakanToLoinc/detail/{id}', [TindakanToLoincController::class, 'detail'])->name('tindakanToLoinc.detail');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('inventory')->namespace('App\Http\Controllers\Inventory')->group(function () {
        Route::get('daftarBarang', [BarangController::class, 'index'])->name('daftarBarang.index');

        Route::get('orderBarang', [OrderController::class, 'index'])->name('orderBarang.index');

        Route::get('penerimaanBarang', [PenerimaanController::class, 'index'])->name('penerimaanBarang.index');

        Route::get('pengirimanBarang', [PengirimanController::class, 'index'])->name('pengirimanBarang.index');

        Route::get('permintaanBarang', [PermintaanController::class, 'index'])->name('permintaanBarang.index');

        Route::get('barangRuangan', [BarangRuanganController::class, 'index'])->name('barangRuangan.index');

        Route::get('stockBarang', [StockController::class, 'index'])->name('stockBarang.index');
        Route::get('stockBarang/list/{id}', [StockController::class, 'list'])->name('stockBarang.list');

        Route::get('transaksiBarang', [TransaksiController::class, 'index'])->name('transaksiBarang.index');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('master')->namespace('App\Http\Controllers\Master')->group(function () {
        Route::get('dokter', [DokterController::class, 'index'])->name('dokter.index');

        Route::get('pasien', [PasienController::class, 'index'])->name('pasien.index');
        Route::get('pasien/detail/{id}', [PasienController::class, 'detail'])->name('pasien.detail');

        Route::get('pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');

        Route::get('perawat', [PerawatController::class, 'index'])->name('perawat.index');

        Route::get('referensi', [ReferensiController::class, 'index'])->name('referensi.index');

        Route::get('ruangan', [RuanganController::class, 'index'])->name('ruangan.index');

        Route::get('staf', [StafController::class, 'index'])->name('staf.index');

        Route::get('tindakan', [TindakanController::class, 'index'])->name('tindakan.index');

        Route::get('tindakanRuangan', [TindakanRuanganController::class, 'index'])->name('tindakanRuangan.index');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('pendaftaran')->namespace('App\Http\Controllers\Pendaftaran')->group(function () {
        Route::get('pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
        Route::get('pendaftaran/detail/{id}', [PendaftaranController::class, 'detail'])->name('pendaftaran.detail');
        Route::get('pendaftaran/kunjungan/{id}', [PendaftaranController::class, 'kunjungan'])->name('pendaftaran.kunjungan');
        Route::get('pendaftaran/pasien/{id}', [PendaftaranController::class, 'pasien'])->name('pendaftaran.pasien');
        Route::get('pendaftaran/bpjs/{id}', [PendaftaranController::class, 'bpjs'])->name('pendaftaran.bpjs');
        Route::get('/pendaftaran/{filter}', [PendaftaranController::class, 'filterByTime'])
            ->name('pendaftaran.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('kunjungan', [KunjunganController::class, 'index'])->name('kunjungan.index');
        Route::get('kunjungan/detail/{id}', [KunjunganController::class, 'detail'])->name('kunjungan.detail');
        Route::get('/kunjungan/{filter}', [KunjunganController::class, 'filterByTime'])
            ->name('kunjungan.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');
        Route::get('/kunjungan-print', [KunjunganController::class, 'print'])->name('kunjungan.print');

        Route::get('kunjungan/triage/{id}', [KunjunganController::class, 'triage'])->name('kunjungan.triage');

        Route::get('kunjungan/rekonsiliasiObatAdmisi/{id}', [KunjunganController::class, 'rekonsiliasiObatAdmisi'])->name('kunjungan.rekonsiliasiObatAdmisi');
        Route::get('kunjungan/rekonsiliasiObatTransfer/{id}', [KunjunganController::class, 'rekonsiliasiObatTransfer'])->name('kunjungan.rekonsiliasiObatTransfer');
        Route::get('kunjungan/rekonsiliasiObatDischarge/{id}', [KunjunganController::class, 'rekonsiliasiObatDischarge'])->name('kunjungan.rekonsiliasiObatDischarge');

        Route::get('kunjungan/askep/{id}', [KunjunganController::class, 'askep'])->name('kunjungan.askep');

        Route::get('kunjungan/keluhanUtama/{id}', [KunjunganController::class, 'keluhanUtama'])->name('kunjungan.keluhanUtama');

        Route::get('kunjungan/anamnesisDiperoleh/{id}', [KunjunganController::class, 'anamnesisDiperoleh'])->name('kunjungan.anamnesisDiperoleh');

        Route::get('kunjungan/riwayatPenyakitSekarang/{id}', [KunjunganController::class, 'riwayatPenyakitSekarang'])->name('kunjungan.riwayatPenyakitSekarang');
        Route::get('kunjungan/riwayatPenyakitDahulu/{id}', [KunjunganController::class, 'riwayatPenyakitDahulu'])->name('kunjungan.riwayatPenyakitDahulu');
        Route::get('kunjungan/riwayatAlergi/{id}', [KunjunganController::class, 'riwayatAlergi'])->name('kunjungan.riwayatAlergi');
        Route::get('kunjungan/riwayatPemberianObat/{id}', [KunjunganController::class, 'riwayatPemberianObat'])->name('kunjungan.riwayatPemberianObat');
        Route::get('kunjungan/riwayatLainnya/{id}', [KunjunganController::class, 'riwayatLainnya'])->name('kunjungan.riwayatLainnya');
        Route::get('kunjungan/faktorRisiko/{id}', [KunjunganController::class, 'faktorRisiko'])->name('kunjungan.faktorRisiko');
        Route::get('kunjungan/riwayatPenyakitKeluarga/{id}', [KunjunganController::class, 'riwayatPenyakitKeluarga'])->name('kunjungan.riwayatPenyakitKeluarga');
        Route::get('kunjungan/riwayatTuberkulosis/{id}', [KunjunganController::class, 'riwayatTuberkulosis'])->name('kunjungan.riwayatTuberkulosis');
        Route::get('kunjungan/riwayatGinekologi/{id}', [KunjunganController::class, 'riwayatGinekologi'])->name('kunjungan.riwayatGinekologi');
        Route::get('kunjungan/statusFungsional/{id}', [KunjunganController::class, 'statusFungsional'])->name('kunjungan.statusFungsional');
        Route::get('kunjungan/hubunganPsikososial/{id}', [KunjunganController::class, 'hubunganPsikososial'])->name('kunjungan.hubunganPsikososial');

        Route::get('kunjungan/edukasiPasienKeluarga/{id}', [KunjunganController::class, 'edukasiPasienKeluarga'])->name('kunjungan.edukasiPasienKeluarga');
        Route::get('kunjungan/edukasiEmergency/{id}', [KunjunganController::class, 'edukasiEmergency'])->name('kunjungan.edukasiEmergency');
        Route::get('kunjungan/edukasiEndOfLife/{id}', [KunjunganController::class, 'edukasiEndOfLife'])->name('kunjungan.edukasiEndOfLife');

        Route::get('kunjungan/skriningGiziAwal/{id}', [KunjunganController::class, 'skriningGiziAwal'])->name('kunjungan.skriningGiziAwal');

        Route::get('kunjungan/batuk/{id}', [KunjunganController::class, 'batuk'])->name('kunjungan.batuk');

        Route::get('kunjungan/pemeriksaanUmum/{id}', [KunjunganController::class, 'pemeriksaanUmum'])->name('kunjungan.pemeriksaanUmum');
        Route::get('kunjungan/pemeriksaanFungsional/{id}', [KunjunganController::class, 'pemeriksaanFungsional'])->name('kunjungan.pemeriksaanFungsional');
        Route::get('kunjungan/pemeriksaanFisik/{id}', [KunjunganController::class, 'pemeriksaanFisik'])->name('kunjungan.pemeriksaanFisik');
        Route::get('kunjungan/pemeriksaanKepala/{id}', [KunjunganController::class, 'pemeriksaanKepala'])->name('kunjungan.pemeriksaanKepala');
        Route::get('kunjungan/pemeriksaanMata/{id}', [KunjunganController::class, 'pemeriksaanMata'])->name('kunjungan.pemeriksaanMata');
        Route::get('kunjungan/pemeriksaanTelinga/{id}', [KunjunganController::class, 'pemeriksaanTelinga'])->name('kunjungan.pemeriksaanTelinga');
        Route::get('kunjungan/pemeriksaanRambut/{id}', [KunjunganController::class, 'pemeriksaanRambut'])->name('kunjungan.pemeriksaanRambut');
        Route::get('kunjungan/pemeriksaanBibir/{id}', [KunjunganController::class, 'pemeriksaanBibir'])->name('kunjungan.pemeriksaanBibir');
        Route::get('kunjungan/pemeriksaanGigiGeligi/{id}', [KunjunganController::class, 'pemeriksaanGigiGeligi'])->name('kunjungan.pemeriksaanGigiGeligi');
        Route::get('kunjungan/pemeriksaanLidah/{id}', [KunjunganController::class, 'pemeriksaanLidah'])->name('kunjungan.pemeriksaanLidah');
        Route::get('kunjungan/pemeriksaanLangitLangit/{id}', [KunjunganController::class, 'pemeriksaanLangitLangit'])->name('kunjungan.pemeriksaanLangitLangit');
        Route::get('kunjungan/pemeriksaanLeher/{id}', [KunjunganController::class, 'pemeriksaanLeher'])->name('kunjungan.pemeriksaanLeher');
        Route::get('kunjungan/pemeriksaanTenggorokan/{id}', [KunjunganController::class, 'pemeriksaanTenggorokan'])->name('kunjungan.pemeriksaanTenggorokan');
        Route::get('kunjungan/pemeriksaanTonsil/{id}', [KunjunganController::class, 'pemeriksaanTonsil'])->name('kunjungan.pemeriksaanTonsil');
        Route::get('kunjungan/pemeriksaanDada/{id}', [KunjunganController::class, 'pemeriksaanDada'])->name('kunjungan.pemeriksaanDada');
        Route::get('kunjungan/pemeriksaanPayudara/{id}', [KunjunganController::class, 'pemeriksaanPayudara'])->name('kunjungan.pemeriksaanPayudara');
        Route::get('kunjungan/pemeriksaanPunggung/{id}', [KunjunganController::class, 'pemeriksaanPunggung'])->name('kunjungan.pemeriksaanPunggung');
        Route::get('kunjungan/pemeriksaanPerut/{id}', [KunjunganController::class, 'pemeriksaanPerut'])->name('kunjungan.pemeriksaanPerut');
        Route::get('kunjungan/pemeriksaanGenital/{id}', [KunjunganController::class, 'pemeriksaanGenital'])->name('kunjungan.pemeriksaanGenital');
        Route::get('kunjungan/pemeriksaanAnus/{id}', [KunjunganController::class, 'pemeriksaanAnus'])->name('kunjungan.pemeriksaanAnus');
        Route::get('kunjungan/pemeriksaanLenganAtas/{id}', [KunjunganController::class, 'pemeriksaanLenganAtas'])->name('kunjungan.pemeriksaanLenganAtas');
        Route::get('kunjungan/pemeriksaanLenganBawah/{id}', [KunjunganController::class, 'pemeriksaanLenganBawah'])->name('kunjungan.pemeriksaanLenganBawah');
        Route::get('kunjungan/pemeriksaanJariTangan/{id}', [KunjunganController::class, 'pemeriksaanJariTangan'])->name('kunjungan.pemeriksaanJariTangan');
        Route::get('kunjungan/pemeriksaanKukuTangan/{id}', [KunjunganController::class, 'pemeriksaanKukuTangan'])->name('kunjungan.pemeriksaanKukuTangan');
        Route::get('kunjungan/pemeriksaanPersediaanTangan/{id}', [KunjunganController::class, 'pemeriksaanPersediaanTangan'])->name('kunjungan.pemeriksaanPersediaanTangan');
        Route::get('kunjungan/pemeriksaanTungkaiAtas/{id}', [KunjunganController::class, 'pemeriksaanTungkaiAtas'])->name('kunjungan.pemeriksaanTungkaiAtas');
        Route::get('kunjungan/pemeriksaanTungkaiBawah/{id}', [KunjunganController::class, 'pemeriksaanTungkaiBawah'])->name('kunjungan.pemeriksaanTungkaiBawah');
        Route::get('kunjungan/pemeriksaanJariKaki/{id}', [KunjunganController::class, 'pemeriksaanLenganAtas'])->name('kunjungan.pemeriksaanLenganAtas');
        Route::get('kunjungan/pemeriksaanKukuKaki/{id}', [KunjunganController::class, 'pemeriksaanKukuKaki'])->name('kunjungan.pemeriksaanKukuKaki');
        Route::get('kunjungan/pemeriksaanPersendianKaki/{id}', [KunjunganController::class, 'pemeriksaanPersendianKaki'])->name('kunjungan.pemeriksaanPersendianKaki');
        Route::get('kunjungan/pemeriksaanFaring/{id}', [KunjunganController::class, 'pemeriksaanFaring'])->name('kunjungan.pemeriksaanFaring');
        Route::get('kunjungan/pemeriksaanKukuKaki/{id}', [KunjunganController::class, 'pemeriksaanKukuKaki'])->name('kunjungan.pemeriksaanKukuKaki');
        Route::get('kunjungan/pemeriksaanSaluranCernahBawah/{id}', [KunjunganController::class, 'pemeriksaanSaluranCernahBawah'])->name('kunjungan.pemeriksaanSaluranCernahBawah');
        Route::get('kunjungan/pemeriksaanSaluranCernahAtas/{id}', [KunjunganController::class, 'pemeriksaanSaluranCernahAtas'])->name('kunjungan.pemeriksaanSaluranCernahAtas');

        Route::get('kunjungan/pemeriksaanEeg/{id}', [KunjunganController::class, 'pemeriksaanEeg'])->name('kunjungan.pemeriksaanEeg');
        Route::get('kunjungan/pemeriksaanEmg/{id}', [KunjunganController::class, 'pemeriksaanEmg'])->name('kunjungan.pemeriksaanEmg');
        Route::get('kunjungan/pemeriksaanRavenTest/{id}', [KunjunganController::class, 'pemeriksaanRavenTest'])->name('kunjungan.pemeriksaanRavenTest');
        Route::get('kunjungan/pemeriksaanCatClams/{id}', [KunjunganController::class, 'pemeriksaanCatClams'])->name('kunjungan.pemeriksaanCatClams');
        Route::get('kunjungan/pemeriksaanTransfusiDarah/{id}', [KunjunganController::class, 'pemeriksaanTransfusiDarah'])->name('kunjungan.pemeriksaanTransfusiDarah');
        Route::get('kunjungan/pemeriksaanAsessmentMChat/{id}', [KunjunganController::class, 'pemeriksaanAsessmentMChat'])->name('kunjungan.pemeriksaanAsessmentMChat');
        Route::get('kunjungan/pemeriksaanEkg/{id}', [KunjunganController::class, 'pemeriksaanEkg'])->name('kunjungan.pemeriksaanEkg');

        Route::get('kunjungan/penilaianFisik/{id}', [KunjunganController::class, 'penilaianFisik'])->name('kunjungan.penilaianFisik');
        Route::get('kunjungan/penilaianNyeri/{id}', [KunjunganController::class, 'penilaianNyeri'])->name('kunjungan.penilaianNyeri');
        Route::get('kunjungan/penilaianStatusPediatrik/{id}', [KunjunganController::class, 'penilaianStatusPediatrik'])->name('kunjungan.penilaianStatusPediatrik');
        Route::get('kunjungan/penilaianDiagnosis/{id}', [KunjunganController::class, 'penilaianDiagnosis'])->name('kunjungan.penilaianDiagnosis');
        Route::get('kunjungan/penilaianSkalaMorse/{id}', [KunjunganController::class, 'penilaianSkalaMorse'])->name('kunjungan.penilaianSkalaMorse');
        Route::get('kunjungan/penilaianSkalaHumptyDumpty/{id}', [KunjunganController::class, 'penilaianSkalaHumptyDumpty'])->name('kunjungan.penilaianSkalaHumptyDumpty');
        Route::get('kunjungan/penilaianEpfra/{id}', [KunjunganController::class, 'penilaianEpfra'])->name('kunjungan.penilaianEpfra');
        Route::get('kunjungan/penilaianGetupGo/{id}', [KunjunganController::class, 'penilaianGetupGo'])->name('kunjungan.penilaianGetupGo');
        Route::get('kunjungan/penilaianDekubitus/{id}', [KunjunganController::class, 'penilaianDekubitus'])->name('kunjungan.penilaianDekubitus');
        Route::get('kunjungan/penilaianBallanceCairan/{id}', [KunjunganController::class, 'penilaianBallanceCairan'])->name('kunjungan.penilaianBallanceCairan');

        Route::get('kunjungan/diagnosa/{id}', [KunjunganController::class, 'diagnosa'])->name('kunjungan.diagnosa');
        Route::get('kunjungan/detailDiagnosa/{id}', [KunjunganController::class, 'detailDiagnosa'])->name('kunjungan.detailDiagnosa');

        Route::get('kunjungan/cppt/{id}', [KunjunganController::class, 'cppt'])->name('kunjungan.cppt');
        Route::get('kunjungan/detailCppt/{id}', [KunjunganController::class, 'detailCppt'])->name('kunjungan.detailCppt');

        Route::get('kunjungan/jadwalKontrol/{id}', [KunjunganController::class, 'jadwalKontrol'])->name('kunjungan.jadwalKontrol');

        Route::get('kunjungan/laboratorium/{id}', [KunjunganController::class, 'laboratorium'])->name('kunjungan.laboratorium');

        Route::get('kunjungan/radiologi/{id}', [KunjunganController::class, 'radiologi'])->name('kunjungan.radiologi');
        Route::get('kunjungan/radiologi/{id}', [KunjunganController::class, 'radiologi'])->name('kunjungan.radiologi');

        Route::get('konsul', [KonsulController::class, 'index'])->name('konsul.index');
        Route::get('konsul/detail/{id}', [KonsulController::class, 'detail'])->name('konsul.detail');
        Route::get('/konsul/{filter}', [KonsulController::class, 'filterByTime'])
            ->name('konsul.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');
        Route::get('/konsul-print', [KonsulController::class, 'print'])->name('konsul.print');

        Route::get('mutasi', [MutasiController::class, 'index'])->name('mutasi.index');
        Route::get('mutasi/detail/{id}', [MutasiController::class, 'detail'])->name('mutasi.detail');
        Route::get('/mutasi/{filter}', [MutasiController::class, 'filterByTime'])
            ->name('mutasi.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');
        Route::get('/mutasi-print', [MutasiController::class, 'print'])->name('mutasi.print');

        Route::get('antrian', [AntrianRuanganController::class, 'index'])->name('antrian.index');
        Route::get('antrian/detail/{id}', [AntrianRuanganController::class, 'detail'])->name('antrian.detail');
        Route::get('/antrian/{filter}', [AntrianRuanganController::class, 'filterByStatus'])
            ->name('antrian.filterByStatus')
            ->where('filter', 'batal|belumDiterima|diterima');
        Route::get('/antrian/{filterKunjungan}', [AntrianRuanganController::class, 'filterByKunjungan'])
            ->name('antrian.filterByKunjungan')
            ->where('filterKunjungan', 'rajal|ranap|darurat');

        Route::get('reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');
        Route::get('reservasi/detail/{id}', [ReservasiController::class, 'detail'])->name('reservasi.detail');
        Route::get('/reservasi/{filter}', [ReservasiController::class, 'filterByStatus'])
            ->name('reservasi.filterByStatus')
            ->where('filter', 'batal|reservasi|selesai');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('bpjs')->namespace('App\Http\Controllers\Bpjs')->group(function () {
        Route::get('pesertaBpjs', [PesertaBpjController::class, 'index'])->name('pesertaBpjs.index');
        Route::get('pesertaBpjs/detail/{id}', [PesertaBpjController::class, 'detail'])->name('pesertaBpjs.detail');

        Route::get('kunjunganBpjs', [KunjunganBpjsController::class, 'index'])->name('kunjunganBpjs.index');
        Route::get('kunjunganBpjs/detail/{id}', [KunjunganBpjsController::class, 'detail'])->name('kunjunganBpjs.detail');
        Route::get('/kunjunganBpjs/{filter}', [KunjunganBpjsController::class, 'filterByTime'])
            ->name('kunjunganBpjs.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('pengajuanSep', [PengajuanSepController::class, 'index'])->name('pengajuanSep.index');
        Route::get('pengajuanSep/detail/{id}', [PengajuanSepController::class, 'detail'])->name('pengajuanSep.detail');

        Route::get('rekonBpjs', [RencanaKontrolController::class, 'index'])->name('rekonBpjs.index');
        Route::get('rekonBpjs/detail/{id}', [RencanaKontrolController::class, 'detail'])->name('rekonBpjs.detail');

        Route::get('monitoringRekon', [MonitoringRekonController::class, 'index'])->name('monitoringRekon.index');
        Route::get('monitoringRekon/detail/{id}', [MonitoringRekonController::class, 'detail'])->name('monitoringRekon.detail');
        Route::get('/monitoringRekon/{filter}', [MonitoringRekonController::class, 'filterByTime'])
            ->name('monitoringRekon.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');
    });
});

//layanan
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('layanan')->namespace('App\Http\Controllers\Layanan')->group(function () {
        Route::get('layananLab', [LaboratoriumController::class, 'index'])->name('layananLab.index');
        Route::get('layananLab/detail/{id}', [LaboratoriumController::class, 'detail'])->name('layananLab.detail');
        Route::get('/layananLab-print', [LaboratoriumController::class, 'print'])->name('layananLab.print');
        Route::get('/layananLab/{filter}', [LaboratoriumController::class, 'filterByTime'])
            ->name('layananLab.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('layananRad', [RadiologiController::class, 'index'])->name('layananRad.index');
        Route::get('layananRad/detail/{id}', [RadiologiController::class, 'detail'])->name('layananRad.detail');
        Route::get('/layananRad-print', [RadiologiController::class, 'print'])->name('layananRad.print');
        Route::get('/layananRad/{filter}', [RadiologiController::class, 'filterByTime'])
            ->name('layananRad.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('layananResep', [ResepController::class, 'index'])->name('layananResep.index');

        Route::get('layananPulang', [PulangController::class, 'index'])->name('layananPulang.index');
        Route::get('layananPulang/detail/{id}', [PulangController::class, 'detail'])->name('layananPulang.detail');
        Route::get('/layananPulang-print', [PulangController::class, 'print'])->name('layananPulang.print');
        Route::get('/layananPulang/{filter}', [PulangController::class, 'filterByTime'])
            ->name('layananPulang.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');
    });
});

//logs
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('logs')->namespace('App\Http\Controllers\Logs')->group(function () {
        Route::get('logsBridge', [BridgeLogsController::class, 'index'])->name('logsBridge.index');

        Route::get('logsAkses', [PenggunaAksesController::class, 'index'])->name('logsAkses.index');

        Route::get('logsRequest', [PenggunaRequestController::class, 'index'])->name('logsRequest.index');

        Route::get('logsPengguna', [PenggunaLogController::class, 'index'])->name('logsPengguna.index');
    });
});

//laporan
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('laporan')->namespace('App\Http\Controllers\Laporan')->group(function () {
        Route::get('laporanRl12', [LaporanRl12Controller::class, 'index'])->name('laporanRl12.index');
        Route::get('laporanRl51', [LaporanRl51Controller::class, 'index'])->name('laporanRl51.index');
    });
});


require __DIR__ . '/auth.php';
