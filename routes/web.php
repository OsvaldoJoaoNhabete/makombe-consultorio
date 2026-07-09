<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\Portal\PatientPortalController;
use App\Http\Controllers\Portal\PatientRegistrationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DoctorDashboardController;

// Página inicial
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Login Staff
Route::middleware('guest')->group(function () {
    Route::get('/login', [StaffLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StaffLoginController::class, 'login'])->name('staff.login');
});

Route::post('/logout', [StaffLoginController::class, 'logout'])->middleware('auth')->name('staff.logout');

// Portal Paciente - Público
Route::get('/portal/login', [PatientPortalController::class, 'showLogin'])->name('patient.login');
Route::post('/portal/login', [PatientPortalController::class, 'login'])->name('patient.login.post');

// Registo Paciente
Route::get('/pacientes/registo', [PatientRegistrationController::class, 'showRegistrationForm'])->name('patient.register');
Route::post('/pacientes/registo', [PatientRegistrationController::class, 'register'])->name('patient.register.post');
Route::get('/pacientes/registo/sucesso', [PatientRegistrationController::class, 'registerSuccess'])->name('patient.register.success');
Route::get('/termos', [PatientRegistrationController::class, 'showTerms'])->name('patient.terms');
Route::get('/privacidade', [PatientRegistrationController::class, 'showPrivacy'])->name('patient.privacy');

// Portal Paciente - Protegido
Route::middleware(['web', 'patient.auth'])->prefix('portal')->name('patient.')->group(function () {
    Route::get('/paciente', [PatientPortalController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [PatientPortalController::class, 'logout'])->name('logout');
});

// Admin - Protegido
Route::middleware(['auth', 'verified', 'role:Administrador|Gerente|Medico|Enfermeiro|Atendente|Financeiro'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::middleware('role:Administrador|Gerente|Medico')->prefix('medico')->name('doctor.')->group(function () {
        Route::get('/', [DoctorDashboardController::class, 'index'])->name('index');
        Route::get('/{id}', [DoctorDashboardController::class, 'show'])->name('show');
        Route::get('/{id}/atender', [DoctorDashboardController::class, 'attend'])->name('attend');
        Route::post('/{id}/atender', [DoctorDashboardController::class, 'storeAttendance'])->name('storeAttendance');
        Route::post('/{id}/concluir', [DoctorDashboardController::class, 'complete'])->name('complete');
        Route::post('/{id}/cancelar', [DoctorDashboardController::class, 'cancel'])->name('cancel');
        Route::post('/{id}/video/iniciar', [DoctorDashboardController::class, 'startVideoCall'])->name('video.start');
    });
});