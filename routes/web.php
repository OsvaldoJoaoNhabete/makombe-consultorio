<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\Portal\PatientPortalController;
use App\Http\Controllers\Portal\PatientRegistrationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DoctorDashboardController;
use App\Http\Controllers\Admin\PatientManageController;
use App\Http\Controllers\Admin\ConsultationManageController;
use App\Http\Controllers\Admin\QuoteManageController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\UserManageController;
use App\Http\Controllers\Admin\InsuranceManageController;
use App\Http\Controllers\Admin\ManagerialReportsController;
use App\Models\Report;

// Página inicial
Route::get('/', function () { return view('welcome'); })->name('welcome');

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

    // Pacientes
    Route::prefix('pacientes')->name('patients.')->group(function () {
        Route::get('/', [PatientManageController::class, 'index'])->name('index');
        Route::get('/criar', [PatientManageController::class, 'create'])->name('create');
        Route::post('/', [PatientManageController::class, 'store'])->name('store');
        Route::get('/{id}', [PatientManageController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [PatientManageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PatientManageController::class, 'update'])->name('update');
        Route::post('/{id}/toggle-status', [PatientManageController::class, 'toggleStatus'])->name('toggleStatus');
        Route::delete('/{id}', [PatientManageController::class, 'destroy'])->name('destroy');
    });

    // Consultas
    Route::prefix('consultas')->name('consultations.')->group(function () {
        Route::get('/', [ConsultationManageController::class, 'index'])->name('index');
        Route::get('/criar', [ConsultationManageController::class, 'create'])->name('create');
        Route::post('/', [ConsultationManageController::class, 'store'])->name('store');
        Route::get('/{id}', [ConsultationManageController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [ConsultationManageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ConsultationManageController::class, 'update'])->name('update');
        Route::post('/{id}/concluir', [ConsultationManageController::class, 'complete'])->name('complete');
        Route::post('/{id}/cancelar', [ConsultationManageController::class, 'cancel'])->name('cancel');
        Route::delete('/{id}', [ConsultationManageController::class, 'destroy'])->name('destroy');
    });

    // Cotações
    Route::prefix('cotacoes')->name('quotes.')->group(function () {
        Route::get('/', [QuoteManageController::class, 'index'])->name('index');
        Route::get('/criar', [QuoteManageController::class, 'create'])->name('create');
        Route::post('/', [QuoteManageController::class, 'store'])->name('store');
        Route::get('/{id}', [QuoteManageController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [QuoteManageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [QuoteManageController::class, 'update'])->name('update');
        Route::post('/{id}/aprovar', [QuoteManageController::class, 'approve'])->name('approve');
        Route::post('/{id}/recusar', [QuoteManageController::class, 'reject'])->name('reject');
        Route::post('/{id}/enviar', [QuoteManageController::class, 'send'])->name('send');
        Route::delete('/{id}', [QuoteManageController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/pdf', [QuoteManageController::class, 'print'])->name('pdf');
    });

    // Financeiro
    Route::middleware('role:Administrador|Gerente|Financeiro')->prefix('financeiro')->name('financeiro.')->group(function () {
        Route::get('/', [FinanceController::class, 'dashboard'])->name('index');
        Route::get('/relatorios', [FinanceController::class, 'reports'])->name('reports');
        Route::prefix('pagamentos')->name('payments.')->group(function () {
            Route::get('/', [FinanceController::class, 'payments'])->name('index');
            Route::get('/criar', [FinanceController::class, 'createPayment'])->name('create');
            Route::post('/', [FinanceController::class, 'storePayment'])->name('store');
            Route::get('/{id}', [FinanceController::class, 'showPayment'])->name('show');
            Route::post('/{id}/confirmar', [FinanceController::class, 'confirmPayment'])->name('confirm');
            Route::post('/{id}/cancelar', [FinanceController::class, 'cancelPayment'])->name('cancel');
        });
    });

    // Notificações
    Route::prefix('notificacoes')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
        Route::post('/{id}/ler', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/marcar-todas', [NotificationController::class, 'markAllAsRead'])->name('markAllRead');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/limpar-todas', [NotificationController::class, 'clearAll'])->name('clearAll');
        Route::get('/api/nao-lidas', [NotificationController::class, 'unreadCount'])->name('unreadCount');
    });

    // Atividades
    Route::prefix('atividades')->name('activities.')->group(function () {
        Route::get('/', [ActivityController::class, 'index'])->name('index');
        Route::get('/{id}', [ActivityController::class, 'show'])->name('show');
        Route::get('/limpar', [ActivityController::class, 'clearOld'])->name('clearOld');
    });

    // Utilizadores
    Route::middleware('role:Administrador|Gerente')->prefix('utilizadores')->name('users.')->group(function () {
        Route::get('/', [UserManageController::class, 'index'])->name('index');
        Route::get('/criar', [UserManageController::class, 'create'])->name('create');
        Route::post('/', [UserManageController::class, 'store'])->name('store');
        Route::get('/{id}', [UserManageController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [UserManageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserManageController::class, 'update'])->name('update');
        Route::post('/{id}/toggle-status', [UserManageController::class, 'toggleStatus'])->name('toggleStatus');
        Route::delete('/{id}', [UserManageController::class, 'destroy'])->name('destroy');
    });

    // Seguradoras
    Route::middleware('role:Administrador|Gerente')->prefix('seguradoras')->name('insurances.')->group(function () {
        Route::get('/', [InsuranceManageController::class, 'index'])->name('index');
        Route::get('/criar', [InsuranceManageController::class, 'create'])->name('create');
        Route::post('/', [InsuranceManageController::class, 'store'])->name('store');
        Route::get('/{id}', [InsuranceManageController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [InsuranceManageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [InsuranceManageController::class, 'update'])->name('update');
        Route::post('/{id}/toggle-status', [InsuranceManageController::class, 'toggleStatus'])->name('toggleStatus');
        Route::delete('/{id}', [InsuranceManageController::class, 'destroy'])->name('destroy');
    });

    // Relatórios
    Route::middleware('role:Administrador|Gerente')->prefix('relatorios')->name('reports.')->group(function () {
        Route::get('/', [ManagerialReportsController::class, 'index'])->name('index');
        Route::get('/{id}/download', function($id) {
            $report = Report::findOrFail($id);
            if (!$report->file_path || !Storage::disk('public')->exists($report->file_path)) {
                return redirect()->back()->with('error', 'Arquivo não encontrado.');
            }
            return response()->download(storage_path('app/public/' . $report->file_path));
        })->name('download');
    });

    // Painel do Médico
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