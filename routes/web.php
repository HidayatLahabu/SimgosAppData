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
use App\Http\Controllers\Master\ReferensiController;
use App\Http\Controllers\Bpjs\PengajuanSepController;
use App\Http\Controllers\Bpjs\RujukanMasukController;
use App\Http\Controllers\Layanan\RadiologiController;
use App\Http\Controllers\Satusehat\ConsentController;
use App\Http\Controllers\Satusehat\PatientController;
use App\Http\Controllers\Bpjs\KunjunganBpjsController;
use App\Http\Controllers\Logs\PenggunaAksesController;
use App\Http\Controllers\Pendaftaran\KonsulController;
use App\Http\Controllers\Pendaftaran\MutasiController;
use App\Http\Controllers\Satusehat\LocationController;
use App\Http\Controllers\Satusehat\SpecimenController;
use App\Http\Controllers\Bpjs\RencanaKontrolController;
use App\Http\Controllers\Inventory\TransaksiController;
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
use App\Http\Controllers\Pendaftaran\ReservasiController;
use App\Http\Controllers\Satusehat\CompositionController;
use App\Http\Controllers\Satusehat\ObservationController;
use App\Http\Controllers\Master\TindakanRuanganController;
use App\Http\Controllers\Satusehat\OrganizationController;
use App\Http\Controllers\Satusehat\PractitionerController;
use App\Http\Controllers\Inventory\BarangRuanganController;
use App\Http\Controllers\Pendaftaran\PendaftaranController;
use App\Http\Controllers\Satusehat\ServiceRequestController;
use App\Http\Controllers\Pendaftaran\AntrianRuanganController;
use App\Http\Controllers\Satusehat\DiagnosticReportController;
use App\Http\Controllers\Satusehat\MedicationRequestController;
use App\Http\Controllers\Satusehat\MedicationDispanseController;

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
        Route::get('organization', [OrganizationController::class, 'index'])->name('organization.index');
        Route::get('organization/detail/{id}', [OrganizationController::class, 'detail'])->name('organization.detail');

        Route::get('location', [LocationController::class, 'index'])->name('location.index');
        Route::get('location/detail/{id}', [LocationController::class, 'detail'])->name('location.detail');

        Route::get('patient', [PatientController::class, 'index'])->name('patient.index');
        Route::get('patient/detail/{id}', [PatientController::class, 'detail'])->name('patient.detail');

        Route::get('practitioner', [PractitionerController::class, 'index'])->name('practitioner.index');
        Route::get('practitioner/detail/{id}', [PractitionerController::class, 'detail'])->name('practitioner.detail');

        Route::get('encounter', [EncounterController::class, 'index'])->name('encounter.index');
        Route::get('encounter/detail/{id}', [EncounterController::class, 'detail'])->name('encounter.detail');

        Route::get('condition', [ConditionController::class, 'index'])->name('condition.index');
        Route::get('condition/detail/{id}', [ConditionController::class, 'detail'])->name('condition.detail');

        Route::get('observation', [ObservationController::class, 'index'])->name('observation.index');
        Route::get('observation/detail/{id}', [ObservationController::class, 'detail'])->name('observation.detail');

        Route::get('procedure', [ProcedureController::class, 'index'])->name('procedure.index');
        Route::get('procedure/detail/{id}', [ProcedureController::class, 'detail'])->name('procedure.detail');

        Route::get('composition', [CompositionController::class, 'index'])->name('composition.index');
        Route::get('composition/detail/{id}', [CompositionController::class, 'detail'])->name('composition.detail');

        Route::get('consent', [ConsentController::class, 'index'])->name('consent.index');
        Route::get('consent/detail/{id}', [ConsentController::class, 'detail'])->name('consent.detail');

        Route::get('diagnosticReport', [DiagnosticReportController::class, 'index'])->name('diagnosticReport.index');
        Route::get('diagnosticReport/detail/{id}', [DiagnosticReportController::class, 'detail'])->name('diagnosticReport.detail');

        Route::get('medication', [MedicationController::class, 'index'])->name('medication.index');
        Route::get('medication/detail/{id}', [MedicationController::class, 'detail'])->name('medication.detail');

        Route::get('medicationDispanse', [MedicationDispanseController::class, 'index'])->name('medicationDispanse.index');
        Route::get('medicationDispanse/detail/{id}', [MedicationDispanseController::class, 'detail'])->name('medicationDispanse.detail');

        Route::get('medicationRequest', [MedicationRequestController::class, 'index'])->name('medicationRequest.index');
        Route::get('medicationRequest/detail/{id}', [MedicationRequestController::class, 'detail'])->name('medicationRequest.detail');

        Route::get('serviceRequest', [ServiceRequestController::class, 'index'])->name('serviceRequest.index');
        Route::get('serviceRequest/detail/{id}', [ServiceRequestController::class, 'detail'])->name('serviceRequest.detail');

        Route::get('specimen', [SpecimenController::class, 'index'])->name('specimen.index');
        Route::get('specimen/detail/{id}', [SpecimenController::class, 'detail'])->name('specimen.detail');
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

        Route::get('kunjungan', [KunjunganController::class, 'index'])->name('kunjungan.index');
        Route::get('kunjungan/detail/{id}', [KunjunganController::class, 'detail'])->name('kunjungan.detail');

        Route::get('konsul', [KonsulController::class, 'index'])->name('konsul.index');

        Route::get('mutasi', [MutasiController::class, 'index'])->name('mutasi.index');

        Route::get('reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');

        Route::get('antrian', [AntrianRuanganController::class, 'index'])->name('antrian.index');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('bpjs')->namespace('App\Http\Controllers\Bpjs')->group(function () {
        Route::get('pesertaBpjs', [PesertaBpjController::class, 'index'])->name('pesertaBpjs.index');

        Route::get('kunjunganBpjs', [KunjunganBpjsController::class, 'index'])->name('kunjunganBpjs.index');

        Route::get('pengajuanSep', [PengajuanSepController::class, 'index'])->name('pengajuanSep.index');

        Route::get('rekonBpjs', [RencanaKontrolController::class, 'index'])->name('rekonBpjs.index');

        Route::get('monitoringRekon', [MonitoringRekonController::class, 'index'])->name('monitoringRekon.index');

        Route::get('rujukanBpjs', [RujukanMasukController::class, 'index'])->name('rujukanBpjs.index');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('layanan')->namespace('App\Http\Controllers\Layanan')->group(function () {
        Route::get('layananLab', [LaboratoriumController::class, 'index'])->name('layananLab.index');

        Route::get('layananRad', [RadiologiController::class, 'index'])->name('layananRad.index');

        Route::get('layananResep', [ResepController::class, 'index'])->name('layananResep.index');

        Route::get('layananPulang', [PulangController::class, 'index'])->name('layananPulang.index');

        Route::get('layananTindakan', [TindakanMedisController::class, 'index'])->name('layananTindakan.index');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('logs')->namespace('App\Http\Controllers\Logs')->group(function () {
        Route::get('logsBridge', [BridgeLogsController::class, 'index'])->name('logsBridge.index');

        Route::get('logsAkses', [PenggunaAksesController::class, 'index'])->name('logsAkses.index');

        Route::get('logsRequest', [PenggunaRequestController::class, 'index'])->name('logsRequest.index');
    });
});


require __DIR__ . '/auth.php';