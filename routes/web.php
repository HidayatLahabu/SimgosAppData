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
use App\Http\Controllers\Chart\ChartBpjsController;
use App\Http\Controllers\Inventory\OrderController;
use App\Http\Controllers\Inventory\StockController;
use App\Http\Controllers\Logs\BridgeLogsController;
use App\Http\Controllers\Master\TindakanController;
use App\Http\Controllers\Inventory\BarangController;
use App\Http\Controllers\Logs\PenggunaLogController;
use App\Http\Controllers\Master\ReferensiController;
use App\Http\Controllers\Bpjs\BatalControlController;
use App\Http\Controllers\Bpjs\PengajuanSepController;
use App\Http\Controllers\Layanan\RadiologiController;
use App\Http\Controllers\Satusehat\AllergyController;
use App\Http\Controllers\Satusehat\ConsentController;
use App\Http\Controllers\Satusehat\PatientController;
use App\Http\Controllers\Bpjs\KunjunganBpjsController;
use App\Http\Controllers\Chart\ChartLayananController;
use App\Http\Controllers\Laporan\LaporanRP1Controller;
use App\Http\Controllers\Logs\PenggunaAksesController;
use App\Http\Controllers\Medicalrecord\CpptController;
use App\Http\Controllers\Pendaftaran\KonsulController;
use App\Http\Controllers\Pendaftaran\MutasiController;
use App\Http\Controllers\Satusehat\CarePlanController;
use App\Http\Controllers\Satusehat\LocationController;
use App\Http\Controllers\Satusehat\SpecimenController;
use App\Http\Controllers\Bpjs\RencanaKontrolController;
use App\Http\Controllers\Inventory\TransaksiController;
use App\Http\Controllers\Laporan\LamaDirawatController;
use App\Http\Controllers\Laporan\LaporanRl12Controller;
use App\Http\Controllers\Laporan\LaporanRl31Controller;
use App\Http\Controllers\Laporan\LaporanRl32Controller;
use App\Http\Controllers\Laporan\LaporanRl51Controller;
use App\Http\Controllers\Laporan\PasienMasukController;
use App\Http\Controllers\Medicalrecord\AskepController;
use App\Http\Controllers\Satusehat\ConditionController;
use App\Http\Controllers\Satusehat\EncounterController;
use App\Http\Controllers\Satusehat\ProcedureController;
use App\Http\Controllers\Bpjs\MonitoringRekonController;
use App\Http\Controllers\Chart\ChartLaporanController;
use App\Http\Controllers\Inventory\PenerimaanController;
use App\Http\Controllers\Inventory\PengirimanController;
use App\Http\Controllers\Inventory\PermintaanController;
use App\Http\Controllers\Laporan\LaporanRl314Controller;
use App\Http\Controllers\Laporan\LaporanRl315Controller;
use App\Http\Controllers\Layanan\LaboratoriumController;
use App\Http\Controllers\Logs\PenggunaRequestController;
use App\Http\Controllers\Satusehat\MedicationController;
use App\Http\Controllers\Laporan\HariPerawatanController;
use App\Http\Controllers\Laporan\PasienDirawatController;
use App\Http\Controllers\Pendaftaran\KunjunganController;
use App\Http\Controllers\Pendaftaran\ReservasiController;
use App\Http\Controllers\Satusehat\BarangToBzaController;
use App\Http\Controllers\Satusehat\CompositionController;
use App\Http\Controllers\Satusehat\ObservationController;
use App\Http\Controllers\Chart\ChartPendaftaranController;
use App\Http\Controllers\Laporan\KunjunganRekapController;
use App\Http\Controllers\Laporan\RespondTimeIgdController;
use App\Http\Controllers\Master\TindakanRuanganController;
use App\Http\Controllers\Satusehat\ImagingStudyController;
use App\Http\Controllers\Satusehat\OrganizationController;
use App\Http\Controllers\Satusehat\PractitionerController;
use App\Http\Controllers\Satusehat\SinkronisasiController;
use App\Http\Controllers\Inventory\BarangRuanganController;
use App\Http\Controllers\Laporan\PasienMeninggalController;
use App\Http\Controllers\Laporan\PengunjungRekapController;
use App\Http\Controllers\Medicalrecord\AnamnesisController;
use App\Http\Controllers\Pendaftaran\PendaftaranController;
use App\Http\Controllers\Laporan\KunjunganPerHariController;
use App\Http\Controllers\Laporan\KunjunganPerUnitController;
use App\Http\Controllers\Satusehat\ServiceRequestController;
use App\Http\Controllers\Laporan\KegiatanRawatInapController;
use App\Http\Controllers\Laporan\PasienKeluarRanapController;
use App\Http\Controllers\Laporan\PengunjungPerHariController;
use App\Http\Controllers\Laporan\RekapPasienKeluarController;
use App\Http\Controllers\Satusehat\TindakanToLoincController;
use App\Http\Controllers\Laporan\KunjunganCaraBayarController;
use App\Http\Controllers\Laporan\KunjunganPerPasienController;
use App\Http\Controllers\Laporan\LaporanRL31PerUnitController;
use App\Http\Controllers\Pendaftaran\AntrianRuanganController;
use App\Http\Controllers\Satusehat\ConditionHasilPaController;
use App\Http\Controllers\Satusehat\DiagnosticReportController;
use App\Http\Controllers\Laporan\PasienBelumGroupingController;
use App\Http\Controllers\Laporan\PasienKeluarDaruratController;
use App\Http\Controllers\Laporan\PengunjungCaraBayarController;
use App\Http\Controllers\Laporan\PengunjungPerPasienController;
use App\Http\Controllers\Medicalrecord\JadwalKontrolController;
use App\Http\Controllers\Satusehat\MedicationRequestController;
use App\Http\Controllers\Informasi\InformasiKunjunganController;
use App\Http\Controllers\Informasi\InformasiPenunjangController;
use App\Http\Controllers\Informasi\StatistikKunjunganController;
use App\Http\Controllers\Laporan\LayananTindakanRekapController;
use App\Http\Controllers\Laporan\PasienMeninggalRekapController;
use App\Http\Controllers\Laporan\PengunjungBelumGroupController;
use App\Http\Controllers\Satusehat\MedicationDispanseController;
use App\Http\Controllers\Chart\ChartStatistikKunjunganController;
use App\Http\Controllers\Informasi\InformasiPengunjungController;
use App\Http\Controllers\Laporan\LayananTindakanPasienController;
use App\Http\Controllers\Laporan\WaktuTungguRegistrasiController;
use App\Http\Controllers\Laporan\LayananTindakanLabGroupController;
use App\Http\Controllers\Laporan\LayananTindakanRadGroupController;
use App\Http\Controllers\Laporan\MonitoringKegiatanRekapController;
use App\Http\Controllers\Laporan\MonitoringStatusKegiatanController;
use App\Http\Controllers\Satusehat\ConditionPenilaianTumorController;
use App\Http\Controllers\Laporan\LayananTindakanRespondTimeController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'hospitalName' => env('HOSPITAL_NAME', 'Default Hospital Name'),
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

    Route::prefix('pendaftaran')->namespace('App\Http\Controllers\Pendaftaran')->group(function () {
        Route::get('/', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
        Route::get('/detail/{id}', [PendaftaranController::class, 'detail'])->name('pendaftaran.detail');
        Route::get('/filter/{filter}', [PendaftaranController::class, 'filterByTime'])
            ->name('pendaftaran.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');
    });

    Route::prefix('kunjungan')->namespace('App\Http\Controllers\Pendaftaran')->group(function () {
        Route::get('/', [KunjunganController::class, 'index'])->name('kunjungan.index');
        Route::get('/detail/{id}', [KunjunganController::class, 'detail'])->name('kunjungan.detail');
        Route::get('/filter/{filter}', [KunjunganController::class, 'filterByTime'])
            ->name('kunjungan.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');
        Route::get('/print', [KunjunganController::class, 'print'])->name('kunjungan.print');

        Route::get('/triage/{id}', [KunjunganController::class, 'triage'])->name('kunjungan.triage');

        Route::get('/rekonsiliasiObatAdmisi/{id}', [KunjunganController::class, 'rekonsiliasiObatAdmisi'])->name('kunjungan.rekonsiliasiObatAdmisi');
        Route::get('/rekonsiliasiObatTransfer/{id}', [KunjunganController::class, 'rekonsiliasiObatTransfer'])->name('kunjungan.rekonsiliasiObatTransfer');
        Route::get('/rekonsiliasiObatDischarge/{id}', [KunjunganController::class, 'rekonsiliasiObatDischarge'])->name('kunjungan.rekonsiliasiObatDischarge');

        Route::get('/askep/{id}', [KunjunganController::class, 'askep'])->name('kunjungan.askep');

        Route::get('/keluhanUtama/{id}', [KunjunganController::class, 'keluhanUtama'])->name('kunjungan.keluhanUtama');

        Route::get('/anamnesisDiperoleh/{id}', [KunjunganController::class, 'anamnesisDiperoleh'])->name('kunjungan.anamnesisDiperoleh');

        Route::get('/riwayatPenyakitSekarang/{id}', [KunjunganController::class, 'riwayatPenyakitSekarang'])->name('kunjungan.riwayatPenyakitSekarang');
        Route::get('/riwayatPenyakitDahulu/{id}', [KunjunganController::class, 'riwayatPenyakitDahulu'])->name('kunjungan.riwayatPenyakitDahulu');
        Route::get('/riwayatAlergi/{id}', [KunjunganController::class, 'riwayatAlergi'])->name('kunjungan.riwayatAlergi');
        Route::get('/riwayatPemberianObat/{id}', [KunjunganController::class, 'riwayatPemberianObat'])->name('kunjungan.riwayatPemberianObat');
        Route::get('/riwayatLainnya/{id}', [KunjunganController::class, 'riwayatLainnya'])->name('kunjungan.riwayatLainnya');
        Route::get('/faktorRisiko/{id}', [KunjunganController::class, 'faktorRisiko'])->name('kunjungan.faktorRisiko');
        Route::get('/riwayatPenyakitKeluarga/{id}', [KunjunganController::class, 'riwayatPenyakitKeluarga'])->name('kunjungan.riwayatPenyakitKeluarga');
        Route::get('/riwayatTuberkulosis/{id}', [KunjunganController::class, 'riwayatTuberkulosis'])->name('kunjungan.riwayatTuberkulosis');
        Route::get('/riwayatGinekologi/{id}', [KunjunganController::class, 'riwayatGinekologi'])->name('kunjungan.riwayatGinekologi');
        Route::get('/statusFungsional/{id}', [KunjunganController::class, 'statusFungsional'])->name('kunjungan.statusFungsional');
        Route::get('/hubunganPsikososial/{id}', [KunjunganController::class, 'hubunganPsikososial'])->name('kunjungan.hubunganPsikososial');

        Route::get('/edukasiPasienKeluarga/{id}', [KunjunganController::class, 'edukasiPasienKeluarga'])->name('kunjungan.edukasiPasienKeluarga');
        Route::get('/edukasiEmergency/{id}', [KunjunganController::class, 'edukasiEmergency'])->name('kunjungan.edukasiEmergency');
        Route::get('/edukasiEndOfLife/{id}', [KunjunganController::class, 'edukasiEndOfLife'])->name('kunjungan.edukasiEndOfLife');

        Route::get('/skriningGiziAwal/{id}', [KunjunganController::class, 'skriningGiziAwal'])->name('kunjungan.skriningGiziAwal');

        Route::get('/batuk/{id}', [KunjunganController::class, 'batuk'])->name('kunjungan.batuk');

        Route::get('/pemeriksaanUmum/{id}', [KunjunganController::class, 'pemeriksaanUmum'])->name('kunjungan.pemeriksaanUmum');
        Route::get('/pemeriksaanFungsional/{id}', [KunjunganController::class, 'pemeriksaanFungsional'])->name('kunjungan.pemeriksaanFungsional');
        Route::get('/pemeriksaanFisik/{id}', [KunjunganController::class, 'pemeriksaanFisik'])->name('kunjungan.pemeriksaanFisik');
        Route::get('/pemeriksaanKepala/{id}', [KunjunganController::class, 'pemeriksaanKepala'])->name('kunjungan.pemeriksaanKepala');
        Route::get('/pemeriksaanMata/{id}', [KunjunganController::class, 'pemeriksaanMata'])->name('kunjungan.pemeriksaanMata');
        Route::get('/pemeriksaanTelinga/{id}', [KunjunganController::class, 'pemeriksaanTelinga'])->name('kunjungan.pemeriksaanTelinga');
        Route::get('/pemeriksaanRambut/{id}', [KunjunganController::class, 'pemeriksaanRambut'])->name('kunjungan.pemeriksaanRambut');
        Route::get('/pemeriksaanBibir/{id}', [KunjunganController::class, 'pemeriksaanBibir'])->name('kunjungan.pemeriksaanBibir');
        Route::get('/pemeriksaanGigiGeligi/{id}', [KunjunganController::class, 'pemeriksaanGigiGeligi'])->name('kunjungan.pemeriksaanGigiGeligi');
        Route::get('/pemeriksaanLidah/{id}', [KunjunganController::class, 'pemeriksaanLidah'])->name('kunjungan.pemeriksaanLidah');
        Route::get('/pemeriksaanLangitLangit/{id}', [KunjunganController::class, 'pemeriksaanLangitLangit'])->name('kunjungan.pemeriksaanLangitLangit');
        Route::get('/pemeriksaanLeher/{id}', [KunjunganController::class, 'pemeriksaanLeher'])->name('kunjungan.pemeriksaanLeher');
        Route::get('/pemeriksaanTenggorokan/{id}', [KunjunganController::class, 'pemeriksaanTenggorokan'])->name('kunjungan.pemeriksaanTenggorokan');
        Route::get('/pemeriksaanTonsil/{id}', [KunjunganController::class, 'pemeriksaanTonsil'])->name('kunjungan.pemeriksaanTonsil');
        Route::get('/pemeriksaanDada/{id}', [KunjunganController::class, 'pemeriksaanDada'])->name('kunjungan.pemeriksaanDada');
        Route::get('/pemeriksaanPayudara/{id}', [KunjunganController::class, 'pemeriksaanPayudara'])->name('kunjungan.pemeriksaanPayudara');
        Route::get('/pemeriksaanPunggung/{id}', [KunjunganController::class, 'pemeriksaanPunggung'])->name('kunjungan.pemeriksaanPunggung');
        Route::get('/pemeriksaanPerut/{id}', [KunjunganController::class, 'pemeriksaanPerut'])->name('kunjungan.pemeriksaanPerut');
        Route::get('/pemeriksaanGenital/{id}', [KunjunganController::class, 'pemeriksaanGenital'])->name('kunjungan.pemeriksaanGenital');
        Route::get('/pemeriksaanAnus/{id}', [KunjunganController::class, 'pemeriksaanAnus'])->name('kunjungan.pemeriksaanAnus');
        Route::get('/pemeriksaanLenganAtas/{id}', [KunjunganController::class, 'pemeriksaanLenganAtas'])->name('kunjungan.pemeriksaanLenganAtas');
        Route::get('/pemeriksaanLenganBawah/{id}', [KunjunganController::class, 'pemeriksaanLenganBawah'])->name('kunjungan.pemeriksaanLenganBawah');
        Route::get('/pemeriksaanJariTangan/{id}', [KunjunganController::class, 'pemeriksaanJariTangan'])->name('kunjungan.pemeriksaanJariTangan');
        Route::get('/pemeriksaanKukuTangan/{id}', [KunjunganController::class, 'pemeriksaanKukuTangan'])->name('kunjungan.pemeriksaanKukuTangan');
        Route::get('/pemeriksaanPersediaanTangan/{id}', [KunjunganController::class, 'pemeriksaanPersediaanTangan'])->name('kunjungan.pemeriksaanPersediaanTangan');
        Route::get('/pemeriksaanTungkaiAtas/{id}', [KunjunganController::class, 'pemeriksaanTungkaiAtas'])->name('kunjungan.pemeriksaanTungkaiAtas');
        Route::get('/pemeriksaanTungkaiBawah/{id}', [KunjunganController::class, 'pemeriksaanTungkaiBawah'])->name('kunjungan.pemeriksaanTungkaiBawah');
        Route::get('/pemeriksaanJariKaki/{id}', [KunjunganController::class, 'pemeriksaanLenganAtas'])->name('kunjungan.pemeriksaanLenganAtas');
        Route::get('/pemeriksaanKukuKaki/{id}', [KunjunganController::class, 'pemeriksaanKukuKaki'])->name('kunjungan.pemeriksaanKukuKaki');
        Route::get('/pemeriksaanPersendianKaki/{id}', [KunjunganController::class, 'pemeriksaanPersendianKaki'])->name('kunjungan.pemeriksaanPersendianKaki');
        Route::get('/pemeriksaanFaring/{id}', [KunjunganController::class, 'pemeriksaanFaring'])->name('kunjungan.pemeriksaanFaring');
        Route::get('/pemeriksaanKukuKaki/{id}', [KunjunganController::class, 'pemeriksaanKukuKaki'])->name('kunjungan.pemeriksaanKukuKaki');
        Route::get('/pemeriksaanSaluranCernahBawah/{id}', [KunjunganController::class, 'pemeriksaanSaluranCernahBawah'])->name('kunjungan.pemeriksaanSaluranCernahBawah');
        Route::get('/pemeriksaanSaluranCernahAtas/{id}', [KunjunganController::class, 'pemeriksaanSaluranCernahAtas'])->name('kunjungan.pemeriksaanSaluranCernahAtas');

        Route::get('/pemeriksaanEeg/{id}', [KunjunganController::class, 'pemeriksaanEeg'])->name('kunjungan.pemeriksaanEeg');
        Route::get('/pemeriksaanEmg/{id}', [KunjunganController::class, 'pemeriksaanEmg'])->name('kunjungan.pemeriksaanEmg');
        Route::get('/pemeriksaanRavenTest/{id}', [KunjunganController::class, 'pemeriksaanRavenTest'])->name('kunjungan.pemeriksaanRavenTest');
        Route::get('/pemeriksaanCatClams/{id}', [KunjunganController::class, 'pemeriksaanCatClams'])->name('kunjungan.pemeriksaanCatClams');
        Route::get('/pemeriksaanTransfusiDarah/{id}', [KunjunganController::class, 'pemeriksaanTransfusiDarah'])->name('kunjungan.pemeriksaanTransfusiDarah');
        Route::get('/pemeriksaanAsessmentMChat/{id}', [KunjunganController::class, 'pemeriksaanAsessmentMChat'])->name('kunjungan.pemeriksaanAsessmentMChat');
        Route::get('/pemeriksaanEkg/{id}', [KunjunganController::class, 'pemeriksaanEkg'])->name('kunjungan.pemeriksaanEkg');

        Route::get('/penilaianFisik/{id}', [KunjunganController::class, 'penilaianFisik'])->name('kunjungan.penilaianFisik');
        Route::get('/penilaianNyeri/{id}', [KunjunganController::class, 'penilaianNyeri'])->name('kunjungan.penilaianNyeri');
        Route::get('/penilaianStatusPediatrik/{id}', [KunjunganController::class, 'penilaianStatusPediatrik'])->name('kunjungan.penilaianStatusPediatrik');
        Route::get('/penilaianDiagnosis/{id}', [KunjunganController::class, 'penilaianDiagnosis'])->name('kunjungan.penilaianDiagnosis');
        Route::get('/penilaianSkalaMorse/{id}', [KunjunganController::class, 'penilaianSkalaMorse'])->name('kunjungan.penilaianSkalaMorse');
        Route::get('/penilaianSkalaHumptyDumpty/{id}', [KunjunganController::class, 'penilaianSkalaHumptyDumpty'])->name('kunjungan.penilaianSkalaHumptyDumpty');
        Route::get('/penilaianEpfra/{id}', [KunjunganController::class, 'penilaianEpfra'])->name('kunjungan.penilaianEpfra');
        Route::get('/penilaianGetupGo/{id}', [KunjunganController::class, 'penilaianGetupGo'])->name('kunjungan.penilaianGetupGo');
        Route::get('/penilaianDekubitus/{id}', [KunjunganController::class, 'penilaianDekubitus'])->name('kunjungan.penilaianDekubitus');
        Route::get('/penilaianBallanceCairan/{id}', [KunjunganController::class, 'penilaianBallanceCairan'])->name('kunjungan.penilaianBallanceCairan');

        Route::get('/diagnosa/{id}', [KunjunganController::class, 'diagnosa'])->name('kunjungan.diagnosa');
        Route::get('/detailDiagnosa/{id}', [KunjunganController::class, 'detailDiagnosa'])->name('kunjungan.detailDiagnosa');

        Route::get('/rencanaTerapi/{id}', [KunjunganController::class, 'rencanaTerapi'])->name('kunjungan.rencanaTerapi');

        Route::get('/jadwalKontrol/{id}', [KunjunganController::class, 'jadwalKontrol'])->name('kunjungan.jadwalKontrol');

        Route::get('/perencanaanRawatInap/{id}', [KunjunganController::class, 'perencanaanRawatInap'])->name('kunjungan.perencanaanRawatInap');

        Route::get('/dischargePlanningSkrining/{id}', [KunjunganController::class, 'dischargePlanningSkrining'])->name('kunjungan.dischargePlanningSkrining');

        Route::get('/dischargePlanningFaktorRisiko/{id}', [KunjunganController::class, 'dischargePlanningFaktorRisiko'])->name('kunjungan.dischargePlanningFaktorRisiko');

        Route::get('/cppt/{id}', [KunjunganController::class, 'cppt'])->name('kunjungan.cppt');
        Route::get('/detailCppt/{id}', [KunjunganController::class, 'detailCppt'])->name('kunjungan.detailCppt');

        Route::get('/pemantauanHDIntradialitik/{id}', [KunjunganController::class, 'pemantauanHDIntradialitik'])->name('kunjungan.pemantauanHDIntradialitik');

        Route::get('/tindakanAbci/{id}', [KunjunganController::class, 'tindakanAbci'])->name('kunjungan.tindakanAbci');

        Route::get('/tindakanMmpi/{id}', [KunjunganController::class, 'tindakanMmpi'])->name('kunjungan.tindakanMmpi');

        Route::get('/laboratorium/{id}', [KunjunganController::class, 'laboratorium'])->name('kunjungan.laboratorium');

        Route::get('/radiologi/{id}', [KunjunganController::class, 'radiologi'])->name('kunjungan.radiologi');
    });

    Route::prefix('konsul')->namespace('App\Http\Controllers\Pendaftaran')->group(function () {
        Route::get('/', [KonsulController::class, 'index'])->name('konsul.index');
        Route::get('/detail/{id}', [KonsulController::class, 'detail'])->name('konsul.detail');
        Route::get('/filter/{filter}', [KonsulController::class, 'filterByTime'])
            ->name('konsul.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');
        Route::get('/print', [KonsulController::class, 'print'])->name('konsul.print');
    });

    Route::prefix('mutasi')->namespace('App\Http\Controllers\Pendaftaran')->group(function () {
        Route::get('/', [MutasiController::class, 'index'])->name('mutasi.index');
        Route::get('/detail/{id}', [MutasiController::class, 'detail'])->name('mutasi.detail');
        Route::get('/filter/{filter}', [MutasiController::class, 'filterByTime'])
            ->name('mutasi.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');
        Route::get('/print', [MutasiController::class, 'print'])->name('mutasi.print');
    });

    Route::prefix('antrian')->namespace('App\Http\Controllers\Pendaftaran')->group(function () {
        Route::get('/', [AntrianRuanganController::class, 'index'])->name('antrian.index');
        Route::get('/detail/{id}', [AntrianRuanganController::class, 'detail'])->name('antrian.detail');
        Route::get('/filter/{filter}', [AntrianRuanganController::class, 'filterByStatus'])
            ->name('antrian.filterByStatus')
            ->where('filter', 'batal|belumDiterima|diterima');
        Route::get('/filter/{filterKunjungan}', [AntrianRuanganController::class, 'filterByKunjungan'])
            ->name('antrian.filterByKunjungan')
            ->where('filterKunjungan', 'rajal|ranap|darurat');
    });

    Route::prefix('reservasi')->namespace('App\Http\Controllers\Pendaftaran')->group(function () {
        Route::get('/', [ReservasiController::class, 'index'])->name('reservasi.index');
        Route::get('/detail/{id}', [ReservasiController::class, 'detail'])->name('reservasi.detail');
        Route::get('/filter/{filter}', [ReservasiController::class, 'filterByStatus'])
            ->name('reservasi.filterByStatus')
            ->where('filter', 'batal|reservasi|selesai');
    });

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
        Route::get('/rekonBpjs-print', [RencanaKontrolController::class, 'print'])->name('rekonBpjs.print');
        Route::get('/rekonBpjs/{filter}', [RencanaKontrolController::class, 'filterByTime'])
            ->name('rekonBpjs.filterByTime')
            ->where('filter', 'hariIni');

        Route::get('monitoringRekon', [MonitoringRekonController::class, 'index'])->name('monitoringRekon.index');
        Route::get('monitoringRekon/detail/{id}', [MonitoringRekonController::class, 'detail'])->name('monitoringRekon.detail');
        Route::get('/monitoringRekon-print', [MonitoringRekonController::class, 'print'])->name('monitoringRekon.print');
        Route::get('/monitoringRekon/{filter}', [MonitoringRekonController::class, 'filterByTime'])
            ->name('monitoringRekon.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('batalKontrol', [BatalControlController::class, 'index'])->name('batalKontrol.index');
        Route::get('/batalKontrol-print', [BatalControlController::class, 'print'])->name('batalKontrol.print');
        Route::get('/batalKontrol/{filter}', [BatalControlController::class, 'filterByTime'])
            ->name('batalKontrol.filterByTime')
            ->where('filter', 'hariIni');
    });

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

    Route::prefix('layananLab')->namespace('App\Http\Controllers\Layanan')->group(function () {
        Route::get('/', [LaboratoriumController::class, 'index'])->name('layananLab.index');
        Route::get('/detail/{id}', [LaboratoriumController::class, 'detail'])->name('layananLab.detail');
        Route::get('/print', [LaboratoriumController::class, 'print'])->name('layananLab.print');
        Route::get('/hasil', [LaboratoriumController::class, 'hasil'])->name('layananLab.hasil');
        Route::get('/catatan', [LaboratoriumController::class, 'catatan'])->name('layananLab.catatan');
        Route::get('/filter/{filter}', [LaboratoriumController::class, 'filterByTime'])
            ->name('layananLab.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');
    });

    Route::prefix('layananRad')->namespace('App\Http\Controllers\Layanan')->group(function () {
        Route::get('/', [RadiologiController::class, 'index'])->name('layananRad.index');
        Route::get('/detail/{id}', [RadiologiController::class, 'detail'])->name('layananRad.detail');
        Route::get('/print', [RadiologiController::class, 'print'])->name('layananRad.print');
        Route::get('/hasil', [RadiologiController::class, 'hasil'])->name('layananRad.hasil');
        Route::get('/filter/{filter}', [RadiologiController::class, 'filterByTime'])
            ->name('layananRad.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');
    });

    Route::prefix('layananPulang')->namespace('App\Http\Controllers\Layanan')->group(function () {
        Route::get('/', [PulangController::class, 'index'])->name('layananPulang.index');
        Route::get('/detail/{id}', [PulangController::class, 'detail'])->name('layananPulang.detail');
        Route::get('/print', [PulangController::class, 'print'])->name('layananPulang.print');
        Route::get('/filter/{filter}', [PulangController::class, 'filterByTime'])
            ->name('layananPulang.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');
    });

    Route::prefix('layananResep')->namespace('App\Http\Controllers\Layanan')->group(function () {
        Route::get('/', [ResepController::class, 'index'])->name('layananResep.index');
    });

    Route::prefix('logs')->namespace('App\Http\Controllers\Logs')->group(function () {
        Route::get('logsBridge', [BridgeLogsController::class, 'index'])->name('logsBridge.index');

        Route::get('logsAkses', [PenggunaAksesController::class, 'index'])->name('logsAkses.index');

        Route::get('logsRequest', [PenggunaRequestController::class, 'index'])->name('logsRequest.index');

        Route::get('logsPengguna', [PenggunaLogController::class, 'index'])->name('logsPengguna.index');
    });

    Route::prefix('medicalrecord')->namespace('App\Http\Controllers\Medicalrecord')->group(function () {
        Route::get('anamnesis', [AnamnesisController::class, 'index'])->name('anamnesis.index');
        Route::get('anamnesis/detail/{id}', [AnamnesisController::class, 'detail'])->name('anamnesis.detail');
        Route::get('anamnesis/filter/{filter}', [AnamnesisController::class, 'filterByTime'])
            ->name('anamnesis.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('askep', [AskepController::class, 'index'])->name('askep.index');
        Route::get('askep/detail/{id}', [AskepController::class, 'detail'])->name('askep.detail');
        Route::get('askep/filter/{filter}', [AskepController::class, 'filterByTime'])
            ->name('askep.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('cppt', [CpptController::class, 'index'])->name('cppt.index');
        Route::get('cppt/detail/{id}', [CpptController::class, 'detail'])->name('cppt.detail');
        Route::get('cppt/filter/{filter}', [CpptController::class, 'filterByTime'])
            ->name('cppt.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');

        Route::get('jadwalKontrol', [JadwalKontrolController::class, 'index'])->name('jadwalKontrol.index');
        Route::get('jadwalKontrol/detail/{id}', [JadwalKontrolController::class, 'detail'])->name('jadwalKontrol.detail');
        Route::get('jadwalKontrol/filter/{filter}', [JadwalKontrolController::class, 'filterByTime'])
            ->name('jadwalKontrol.filterByTime')
            ->where('filter', 'hariIni|mingguIni|bulanIni|tahunIni');
    });

    Route::prefix('informasi')->namespace('App\Http\Controllers\Informasi')->group(function () {
        Route::get('statistikKunjungan', [StatistikKunjunganController::class, 'index'])->name('statistikKunjungan.index');
        Route::get('/statistikKunjungan-print', [StatistikKunjunganController::class, 'print'])->name('statistikKunjungan.print');
        Route::get('kunjungan', [InformasiKunjunganController::class, 'index'])->name('informasiKunjungan.index');
        Route::get('/kunjungan-print', [InformasiKunjunganController::class, 'print'])->name('informasiKunjungan.print');
        Route::get('pengunjung', [InformasiPengunjungController::class, 'index'])->name('informasiPengunjung.index');
        Route::get('/pengunjung-print', [InformasiPengunjungController::class, 'print'])->name('informasiPengunjung.print');
        Route::get('penunjang', [InformasiPenunjangController::class, 'index'])->name('informasiPenunjang.index');
        Route::get('/penunjang-print', [InformasiPenunjangController::class, 'print'])->name('informasiPenunjang.print');
    });

    Route::prefix('laporan')->namespace('App\Http\Controllers\Laporan')->group(function () {
        Route::get('laporanRl12', [LaporanRl12Controller::class, 'index'])->name('laporanRl12.index');
        Route::get('/laporanRl12-print', [LaporanRl12Controller::class, 'print'])->name('laporanRl12.print');

        Route::get('laporanRl31', [LaporanRl31Controller::class, 'index'])->name('laporanRl31.index');
        Route::get('/laporanRl31-print', [LaporanRl31Controller::class, 'print'])->name('laporanRl31.print');

        Route::get('laporanRl32', [LaporanRl32Controller::class, 'index'])->name('laporanRl32.index');
        Route::get('/laporanRl32-print', [LaporanRl32Controller::class, 'print'])->name('laporanRl32.print');

        Route::get('laporanRl314', [LaporanRl314Controller::class, 'index'])->name('laporanRl314.index');
        Route::get('/laporanRl314-print', [LaporanRl314Controller::class, 'print'])->name('laporanRl314.print');

        Route::get('laporanRl315', [LaporanRl315Controller::class, 'index'])->name('laporanRl315.index');
        Route::get('/laporanRl315-print', [LaporanRl315Controller::class, 'print'])->name('laporanRl315.print');

        Route::get('laporanRl51', [LaporanRl51Controller::class, 'index'])->name('laporanRl51.index');
        Route::get('/laporanRl51-print', [LaporanRl51Controller::class, 'print'])->name('laporanRl51.print');

        Route::get('pengunjungPerPasien', [PengunjungPerPasienController::class, 'index'])->name('pengunjungPerPasien.index');
        Route::get('/pengunjungPerPasien-print', [PengunjungPerPasienController::class, 'print'])->name('pengunjungPerPasien.print');

        Route::get('pengunjungPerHari', [PengunjungPerHariController::class, 'index'])->name('pengunjungPerHari.index');
        Route::get('/pengunjungPerHari-print', [PengunjungPerHariController::class, 'print'])->name('pengunjungPerHari.print');

        Route::get('pengunjungCaraBayar', [PengunjungCaraBayarController::class, 'index'])->name('pengunjungCaraBayar.index');
        Route::get('/pengunjungCaraBayar-print', [PengunjungCaraBayarController::class, 'print'])->name('pengunjungCaraBayar.print');

        Route::get('pengunjungRekap', [PengunjungRekapController::class, 'index'])->name('pengunjungRekap.index');
        Route::get('/pengunjungRekap-print', [PengunjungRekapController::class, 'print'])->name('pengunjungRekap.print');

        Route::get('pengunjungBelumGroup', [PengunjungBelumGroupController::class, 'index'])->name('pengunjungBelumGroup.index');
        Route::get('/pengunjungBelumGroup-print', [PengunjungBelumGroupController::class, 'print'])->name('pengunjungBelumGroup.print');

        Route::get('pengunjungWaktuTunggu', [WaktuTungguRegistrasiController::class, 'index'])->name('pengunjungWaktuTunggu.index');
        Route::get('/pengunjungWaktuTunggu-print', [WaktuTungguRegistrasiController::class, 'print'])->name('pengunjungWaktuTunggu.print');

        Route::get('kunjunganPerPasien', [KunjunganPerPasienController::class, 'index'])->name('kunjunganPerPasien.index');
        Route::get('/kunjunganPerPasien-print', [KunjunganPerPasienController::class, 'print'])->name('kunjunganPerPasien.print');

        Route::get('kunjunganPerHari', [KunjunganPerHariController::class, 'index'])->name('kunjunganPerHari.index');
        Route::get('/kunjunganPerHari-print', [KunjunganPerHariController::class, 'print'])->name('kunjunganPerHari.print');

        Route::get('kunjunganCaraBayar', [KunjunganCaraBayarController::class, 'index'])->name('kunjunganCaraBayar.index');
        Route::get('/kunjunganCaraBayar-print', [KunjunganCaraBayarController::class, 'print'])->name('kunjunganCaraBayar.print');

        Route::get('kunjunganPerUnit', [KunjunganPerUnitController::class, 'index'])->name('kunjunganPerUnit.index');
        Route::get('/kunjunganPerUnit-print', [KunjunganPerUnitController::class, 'print'])->name('kunjunganPerUnit.print');

        Route::get('kunjunganRekap', [KunjunganRekapController::class, 'index'])->name('kunjunganRekap.index');
        Route::get('/kunjunganRekap-print', [KunjunganRekapController::class, 'print'])->name('kunjunganRekap.print');

        Route::get('tindakanPasien', [LayananTindakanPasienController::class, 'index'])->name('tindakanPasien.index');
        Route::get('/tindakanPasien-print', [LayananTindakanPasienController::class, 'print'])->name('tindakanPasien.print');

        Route::get('tindakanRekap', [LayananTindakanRekapController::class, 'index'])->name('tindakanRekap.index');
        Route::get('/tindakanRekap-print', [LayananTindakanRekapController::class, 'print'])->name('tindakanRekap.print');

        Route::get('tindakanLabGroup', [LayananTindakanLabGroupController::class, 'index'])->name('tindakanLabGroup.index');
        Route::get('/tindakanLabGroup-print', [LayananTindakanLabGroupController::class, 'print'])->name('tindakanLabGroup.print');

        Route::get('tindakanRadGroup', [LayananTindakanRadGroupController::class, 'index'])->name('tindakanRadGroup.index');
        Route::get('/tindakanRadGroup-print', [LayananTindakanRadGroupController::class, 'print'])->name('tindakanRadGroup.print');

        Route::get('tindakanRespondTime', [LayananTindakanRespondTimeController::class, 'index'])->name('tindakanRespondTime.index');
        Route::get('/tindakanRespondTime-print', [LayananTindakanRespondTimeController::class, 'print'])->name('tindakanRespondTime.print');

        Route::get('kegiatanPasienMasuk', [PasienMasukController::class, 'index'])->name('kegiatanPasienMasuk.index');
        Route::get('/kegiatanPasienMasuk-print', [PasienMasukController::class, 'print'])->name('kegiatanPasienMasuk.print');

        Route::get('kegiatanPasienKeluarRanap', [PasienKeluarRanapController::class, 'index'])->name('kegiatanPasienKeluarRanap.index');
        Route::get('/kegiatanPasienKeluarRanap-print', [PasienKeluarRanapController::class, 'print'])->name('kegiatanPasienKeluarRanap.print');

        Route::get('kegiatanPasienKeluarDarurat', [PasienKeluarDaruratController::class, 'index'])->name('kegiatanPasienKeluarDarurat.index');
        Route::get('/kegiatanPasienKeluarDarurat-print', [PasienKeluarDaruratController::class, 'print'])->name('kegiatanPasienKeluarDarurat.print');

        Route::get('kegiatanPasienMeninggal', [PasienMeninggalController::class, 'index'])->name('kegiatanPasienMeninggal.index');
        Route::get('/kegiatanPasienMeninggal-print', [PasienMeninggalController::class, 'print'])->name('kegiatanPasienMeninggal.print');

        Route::get('kegiatanPasienMeninggalRekap', [PasienMeninggalRekapController::class, 'index'])->name('kegiatanPasienMeninggalRekap.index');
        Route::get('/kegiatanPasienMeninggalRekap-print', [PasienMeninggalRekapController::class, 'print'])->name('kegiatanPasienMeninggalRekap.print');

        Route::get('lamaDirawat', [LamaDirawatController::class, 'index'])->name('lamaDirawat.index');
        Route::get('/lamaDirawat-print', [LamaDirawatController::class, 'print'])->name('lamaDirawat.print');

        Route::get('hariPerawatan', [HariPerawatanController::class, 'index'])->name('hariPerawatan.index');
        Route::get('/hariPerawatan-print', [HariPerawatanController::class, 'print'])->name('hariPerawatan.print');

        Route::get('pasienDirawat', [PasienDirawatController::class, 'index'])->name('pasienDirawat.index');
        Route::get('/pasienDirawat-print', [PasienDirawatController::class, 'print'])->name('pasienDirawat.print');

        Route::get('kegiatanRawatInap', [KegiatanRawatInapController::class, 'index'])->name('kegiatanRawatInap.index');
        Route::get('/kegiatanRawatInap-print', [KegiatanRawatInapController::class, 'print'])->name('kegiatanRawatInap.print');

        Route::get('laporanRP1', [LaporanRP1Controller::class, 'index'])->name('laporanRP1.index');
        Route::get('/laporanRP1-print', [LaporanRP1Controller::class, 'print'])->name('laporanRP1.print');

        Route::get('pasienBelumGrouping', [PasienBelumGroupingController::class, 'index'])->name('pasienBelumGrouping.index');
        Route::get('/pasienBelumGrouping-print', [PasienBelumGroupingController::class, 'print'])->name('pasienBelumGrouping.print');

        Route::get('rekapPasienKeluarRi', [RekapPasienKeluarController::class, 'index'])->name('rekapPasienKeluarRi.index');
        Route::get('/rekapPasienKeluarRi-print', [RekapPasienKeluarController::class, 'print'])->name('rekapPasienKeluarRi.print');

        Route::get('rekapPasienKeluarRi', [RekapPasienKeluarController::class, 'index'])->name('rekapPasienKeluarRi.index');
        Route::get('/rekapPasienKeluarRi-print', [RekapPasienKeluarController::class, 'print'])->name('rekapPasienKeluarRi.print');

        Route::get('kegiatanRiPerUnit', [LaporanRL31PerUnitController::class, 'index'])->name('kegiatanRiPerUnit.index');
        Route::get('/kegiatanRiPerUnit-print', [LaporanRL31PerUnitController::class, 'print'])->name('kegiatanRiPerUnit.print');

        Route::get('respondTimeIgd', [RespondTimeIgdController::class, 'index'])->name('respondTimeIgd.index');
        Route::get('/respondTimeIgd-print', [RespondTimeIgdController::class, 'print'])->name('respondTimeIgd.print');

        Route::get('monitoringKegiatan', [MonitoringStatusKegiatanController::class, 'index'])->name('monitoringKegiatan.index');
        Route::get('/monitoringKegiatan-print', [MonitoringStatusKegiatanController::class, 'print'])->name('monitoringKegiatan.print');

        Route::get('monitoringKegiatan', [MonitoringStatusKegiatanController::class, 'index'])->name('monitoringKegiatan.index');
        Route::get('/monitoringKegiatan-print', [MonitoringStatusKegiatanController::class, 'print'])->name('monitoringKegiatan.print');

        Route::get('monitoringKegiatanRekap', [MonitoringKegiatanRekapController::class, 'index'])->name('monitoringKegiatanRekap.index');
        Route::get('/monitoringKegiatanRekap-print', [MonitoringKegiatanRekapController::class, 'print'])->name('monitoringKegiatanRekap.print');
    });

    Route::prefix('chart')->namespace('App\Http\Controllers\Chart')->group(function () {
        Route::get('chartPendaftaran', [ChartPendaftaranController::class, 'index'])->name('chartPendaftaran.index');
        Route::get('chartBpjs', [ChartBpjsController::class, 'index'])->name('chartBpjs.index');
        Route::get('chartLayanan', [ChartLayananController::class, 'index'])->name('chartLayanan.index');
        Route::get('chartInformasi', [ChartStatistikKunjunganController::class, 'index'])->name('chartInformasi.index');
        Route::get('chartLaporan', [ChartLaporanController::class, 'index'])->name('chartLaporan.index');
    });
});

require __DIR__ . '/auth.php';