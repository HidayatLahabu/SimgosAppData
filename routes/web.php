<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\StafController;
use App\Http\Controllers\Master\DokterController;
use App\Http\Controllers\Master\PasienController;
use App\Http\Controllers\Master\PegawaiController;
use App\Http\Controllers\Master\PerawatController;
use App\Http\Controllers\Master\RuanganController;
use App\Http\Controllers\Inventory\OrderController;
use App\Http\Controllers\Inventory\StockController;
use App\Http\Controllers\Master\TindakanController;
use App\Http\Controllers\Inventory\BarangController;
use App\Http\Controllers\Master\ReferensiController;
use App\Http\Controllers\Satusehat\ConsentController;
use App\Http\Controllers\Satusehat\PatientController;
use App\Http\Controllers\Satusehat\LocationController;
use App\Http\Controllers\Satusehat\SpecimenController;
use App\Http\Controllers\Inventory\TransaksiController;
use App\Http\Controllers\Satusehat\ConditionController;
use App\Http\Controllers\Satusehat\EncounterController;
use App\Http\Controllers\Satusehat\ProcedureController;
use App\Http\Controllers\Inventory\PenerimaanController;
use App\Http\Controllers\Inventory\PengirimanController;
use App\Http\Controllers\Inventory\PermintaanController;
use App\Http\Controllers\Satusehat\MedicationController;
use App\Http\Controllers\Satusehat\CompositionController;
use App\Http\Controllers\Satusehat\ObservationController;
use App\Http\Controllers\Satusehat\OrganizationController;
use App\Http\Controllers\Satusehat\PractitionerController;
use App\Http\Controllers\Inventory\BarangRuanganController;
use App\Http\Controllers\Satusehat\ServiceRequestController;
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

        Route::get('location', [LocationController::class, 'index'])->name('location.index');

        Route::get('patient', [PatientController::class, 'index'])->name('patient.index');

        Route::get('practitioner', [PractitionerController::class, 'index'])->name('practitioner.index');

        Route::get('encounter', [EncounterController::class, 'index'])->name('encounter.index');

        Route::get('condition', [ConditionController::class, 'index'])->name('condition.index');

        Route::get('observation', [ObservationController::class, 'index'])->name('observation.index');

        Route::get('procedure', [ProcedureController::class, 'index'])->name('procedure.index');

        Route::get('composition', [CompositionController::class, 'index'])->name('composition.index');

        Route::get('consent', [ConsentController::class, 'index'])->name('consent.index');

        Route::get('diagnosticReport', [DiagnosticReportController::class, 'index'])->name('diagnosticReport.index');

        Route::get('medication', [MedicationController::class, 'index'])->name('medication.index');

        Route::get('medicationDispanse', [MedicationDispanseController::class, 'index'])->name('medicationDispanse.index');

        Route::get('medicationRequest', [MedicationRequestController::class, 'index'])->name('medicationRequest.index');

        Route::get('serviceRequest', [ServiceRequestController::class, 'index'])->name('serviceRequest.index');

        Route::get('specimen', [SpecimenController::class, 'index'])->name('specimen.index');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('inventory')->namespace('App\Http\Controllers\Inventory')->group(function () {
        Route::get('barang', [BarangController::class, 'index'])->name('barang.index');

        Route::get('order', [OrderController::class, 'index'])->name('order.index');

        Route::get('penerimaan', [PenerimaanController::class, 'index'])->name('penerimaan.index');

        Route::get('pengiriman', [PengirimanController::class, 'index'])->name('pengiriman.index');

        Route::get('permintaan', [PermintaanController::class, 'index'])->name('permintaan.index');

        Route::get('barangRuangan', [BarangRuanganController::class, 'index'])->name('barangRuangan.index');

        Route::get('stock', [StockController::class, 'index'])->name('stock.index');
        Route::get('stock/list/{id}', [StockController::class, 'list'])->name('stock.list');

        Route::get('transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
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
    });
});


require __DIR__ . '/auth.php';
