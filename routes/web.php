<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\Portal\PatientPortalController;
use App\Http\Controllers\Portal\PatientRegistrationController;
use App\Http\Controllers\Portal\PasswordRecoveryController;
use App\Http\Controllers\Portal\PasswordChangeController;
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
use App\Http\Controllers\Admin\ContentManagementController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\FirstLoginController;

/*
|--------------------------------------------------------------------------
| Web Routes - Makombe Consultório Médico
|--------------------------------------------------------------------------
*/

// ============================================
// 1. PÁGINA INICIAL
// ============================================
Route::get('/', function () {
    $services = \App\Models\Service::where('is_active', true)->orderBy('order')->get();
    $team = \App\Models\TeamMember::where('is_active', true)->orderBy('order')->limit(4)->get();
    $settings = \App\Models\SiteSetting::all()->pluck('value', 'key')->toArray();
    
    return view('welcome', compact('services', 'team', 'settings'));
})->name('welcome');


// ============================================
// 2. AUTENTICAÇÃO STAFF (ADMIN)
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [StaffLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StaffLoginController::class, 'login'])->name('staff.login');
});

Route::post('/logout', [StaffLoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout'); 


// ============================================
// 3. PORTAL DO PACIENTE (PÚBLICO)
// ============================================
Route::get('/portal/login', [PatientPortalController::class, 'showLogin'])->name('patient.login');
Route::post('/portal/login', [PatientPortalController::class, 'login'])->name('patient.login.post');

Route::get('/pacientes/registo', [PatientRegistrationController::class, 'showRegistrationForm'])->name('patient.register');
Route::post('/pacientes/registo', [PatientRegistrationController::class, 'register'])->name('patient.register.post');
Route::get('/pacientes/registo/sucesso', [PatientRegistrationController::class, 'registerSuccess'])->name('patient.register.success');

Route::get('/termos', [PatientRegistrationController::class, 'showTerms'])->name('patient.terms');
Route::get('/privacidade', [PatientRegistrationController::class, 'showPrivacy'])->name('patient.privacy');

Route::get('/recuperar-senha', [PasswordRecoveryController::class, 'showRecoveryForm'])->name('patient.password.recovery');
Route::post('/recuperar-senha', [PasswordRecoveryController::class, 'recover'])->name('patient.password.recover');
Route::get('/resetar-senha', [PasswordRecoveryController::class, 'showResetForm'])->name('patient.password.reset');
Route::post('/resetar-senha', [PasswordRecoveryController::class, 'reset'])->name('patient.password.reset.post');


// ============================================
// 4. PORTAL DO PACIENTE (PROTEGIDO)
// ============================================
Route::middleware(['web', 'patient.auth'])
    ->prefix('portal')
    ->name('patient.')
    ->group(function () {
        
        Route::get('/paciente', [PatientPortalController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [PatientPortalController::class, 'logout'])->name('logout');
        
        Route::get('/agendar', [PatientPortalController::class, 'showSchedule'])->name('schedule');
        Route::post('/agendar', [PatientPortalController::class, 'schedule'])->name('schedule.store');
        
        Route::get('/consultas', [PatientPortalController::class, 'consultations'])->name('consultations');
        Route::get('/consultas/{id}', [PatientPortalController::class, 'showConsultation'])->name('consultations.show');
        Route::post('/consultas/{id}/cancelar', [PatientPortalController::class, 'cancelConsultation'])->name('consultations.cancel');
        Route::post('/consultas/{id}/reenviar-whatsapp', [PatientPortalController::class, 'resendWhatsApp'])->name('consultations.resend-whatsapp');
        
        Route::get('/cotacoes', [PatientPortalController::class, 'quotes'])->name('quotes');
        Route::get('/cotacoes/{id}', [PatientPortalController::class, 'showQuote'])->name('quotes.show');
        
        Route::get('/pagamentos', [PatientPortalController::class, 'payments'])->name('payments');
        Route::get('/seguradoras', [PatientPortalController::class, 'insurances'])->name('insurances');
        
        Route::get('/perfil', [PatientPortalController::class, 'profile'])->name('profile');
        Route::post('/perfil', [PatientPortalController::class, 'updateProfile'])->name('profile.update');
        
        Route::get('/criar-senha', [PasswordChangeController::class, 'showChangeForm'])->name('password.change');
        Route::post('/criar-senha', [PasswordChangeController::class, 'change'])->name('password.change.post');
        Route::post('/adiar-senha', [PasswordChangeController::class, 'postpone'])->name('password.postpone');
    });


// ============================================
// 5. PAINEL ADMINISTRATIVO (PROTEGIDO)
// ============================================
Route::middleware(['auth', 'verified', 'role:Administrador|Gerente|Medico|Enfermeiro|Atendente|Financeiro'])
    ->group(function () {
        
        // Dashboard Principal
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // ============================================
        // PRIMEIRO ACESSO (Mudar Palavra-passe)
        // ============================================
        Route::middleware(['auth'])->group(function () {
            Route::get('/primeiro-acesso', [FirstLoginController::class, 'show'])->name('first-login.show');
            Route::post('/primeiro-acesso', [FirstLoginController::class, 'update'])->name('first-login.update');
        });
        
        // ----------------------------------------
        // Módulo: Perfil do Utilizador (Staff/Admin)
        // ----------------------------------------
        Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');

        // ----------------------------------------
        // Módulo: Pacientes
        // ----------------------------------------
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

                // ----------------------------------------
        // Módulo: Consultas
        // ----------------------------------------
        Route::prefix('consultas')->name('consultations.')->group(function () {
            Route::get('/', [ConsultationManageController::class, 'index'])->name('index');
            Route::get('/criar', [ConsultationManageController::class, 'create'])->name('create');
            Route::post('/', [ConsultationManageController::class, 'store'])->name('store');
            Route::get('/{id}', [ConsultationManageController::class, 'show'])->name('show');
            Route::get('/{id}/editar', [ConsultationManageController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ConsultationManageController::class, 'update'])->name('update');
            
            // Rotas de ação rápida (POST para segurança)
            Route::post('/{id}/concluir', [ConsultationManageController::class, 'complete'])->name('complete');
            Route::post('/{id}/cancelar', [ConsultationManageController::class, 'cancel'])->name('cancel');
            Route::post('/{id}/faltou', [ConsultationManageController::class, 'markAsNoShow'])->name('markAsNoShow');
            
            Route::delete('/{id}', [ConsultationManageController::class, 'destroy'])->name('destroy');
        });

        // ----------------------------------------
        // Módulo: Cotações
        // ----------------------------------------
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
            Route::post('/{id}/marcar-paga', [QuoteManageController::class, 'markAsPaid'])->name('markAsPaid');
            Route::post('/{id}/converter', [QuoteManageController::class, 'convertToConsultation'])->name('convert');
            Route::delete('/{id}', [QuoteManageController::class, 'destroy'])->name('destroy');
        });

        // ----------------------------------------
        // Módulo: Financeiro
        // ----------------------------------------
        Route::middleware('role:Administrador|Gerente|Financeiro')
            ->prefix('financeiro')
            ->name('financeiro.')
            ->group(function () {
                
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

        // ----------------------------------------
        // Módulo: Notificações
        // ----------------------------------------
        Route::prefix('notificacoes')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
            Route::post('/{id}/ler', [NotificationController::class, 'markAsRead'])->name('markAsRead');
            Route::post('/marcar-todas', [NotificationController::class, 'markAllAsRead'])->name('markAllRead');
            Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
            Route::delete('/limpar-todas', [NotificationController::class, 'clearAll'])->name('clearAll');
            Route::get('/api/nao-lidas', [NotificationController::class, 'unreadCount'])->name('unreadCount');
        });

        // ----------------------------------------
        // Módulo: Atividades
        // ----------------------------------------
        Route::prefix('atividades')->name('activities.')->group(function () {
            Route::get('/', [ActivityController::class, 'index'])->name('index');
            Route::get('/{id}', [ActivityController::class, 'show'])->name('show');
            Route::get('/limpar', [ActivityController::class, 'clearOld'])->name('clearOld');
        });

        // ----------------------------------------
        // Módulo: Utilizadores
        // ----------------------------------------
        Route::middleware('role:Administrador|Gerente')
            ->prefix('utilizadores')
            ->name('users.')
            ->group(function () {
                Route::get('/', [UserManageController::class, 'index'])->name('index');
                Route::get('/criar', [UserManageController::class, 'create'])->name('create');
                Route::post('/', [UserManageController::class, 'store'])->name('store');
                Route::get('/{id}', [UserManageController::class, 'show'])->name('show');
                Route::get('/{id}/editar', [UserManageController::class, 'edit'])->name('edit');
                Route::put('/{id}', [UserManageController::class, 'update'])->name('update');
                Route::post('/{id}/toggle-status', [UserManageController::class, 'toggleStatus'])->name('toggleStatus');
                Route::delete('/{id}', [UserManageController::class, 'destroy'])->name('destroy');
            });

                // ----------------------------------------
        // Módulo: Seguradoras
        // ----------------------------------------
        Route::middleware('role:Administrador|Gerente')
            ->prefix('seguradoras')
            ->name('insurances.')
            ->group(function () {
                Route::get('/', [InsuranceManageController::class, 'index'])->name('index');
                Route::get('/criar', [InsuranceManageController::class, 'create'])->name('create');
                Route::post('/', [InsuranceManageController::class, 'store'])->name('store');
                Route::get('/{id}', [InsuranceManageController::class, 'show'])->name('show'); // VERIFICAR ESTA
                Route::get('/{id}/editar', [InsuranceManageController::class, 'edit'])->name('edit');
                Route::put('/{id}', [InsuranceManageController::class, 'update'])->name('update');
                Route::delete('/{id}', [InsuranceManageController::class, 'destroy'])->name('destroy');
                Route::post('/{id}/toggle', [InsuranceManageController::class, 'toggleStatus'])->name('toggle'); // VERIFICAR ESTA
            });

        // ----------------------------------------
        // Módulo: Especialidades
        // ----------------------------------------
        Route::middleware('role:Administrador|Gerente')
            ->prefix('especialidades')
            ->name('admin.specialties.')
            ->group(function () {
                Route::get('/', [SpecialtyController::class, 'index'])->name('index');
                Route::get('/criar', [SpecialtyController::class, 'create'])->name('create');
                Route::post('/', [SpecialtyController::class, 'store'])->name('store');
                Route::get('/{id}', [SpecialtyController::class, 'show'])->name('show');
                Route::get('/{id}/editar', [SpecialtyController::class, 'edit'])->name('edit');
                Route::put('/{id}', [SpecialtyController::class, 'update'])->name('update');
                Route::delete('/{id}', [SpecialtyController::class, 'destroy'])->name('destroy');
                Route::post('/{id}/toggle', [SpecialtyController::class, 'toggleStatus'])->name('toggle');
            });

        // ----------------------------------------
        // Módulo: Relatórios Gerenciais
        // ----------------------------------------
        Route::middleware('role:Administrador|Gerente')
            ->prefix('relatorios')
            ->name('reports.')
            ->group(function () {
                Route::get('/', [ManagerialReportsController::class, 'index'])->name('index');
            });

        // ----------------------------------------
        // Módulo: Painel do Médico
        // ----------------------------------------
        Route::middleware('role:Administrador|Gerente|Medico')
            ->prefix('medico')
            ->name('doctor.')
            ->group(function () {
                Route::get('/', [DoctorDashboardController::class, 'index'])->name('index');
                Route::get('/{id}', [DoctorDashboardController::class, 'show'])->name('show');
                Route::get('/{id}/atender', [DoctorDashboardController::class, 'attend'])->name('attend');
                Route::post('/{id}/atender', [DoctorDashboardController::class, 'storeAttendance'])->name('storeAttendance');
                Route::post('/{id}/concluir', [DoctorDashboardController::class, 'complete'])->name('complete');
                Route::post('/{id}/cancelar', [DoctorDashboardController::class, 'cancel'])->name('cancel');
                Route::post('/{id}/video/iniciar', [DoctorDashboardController::class, 'startVideoCall'])->name('video.start');
            });

        // ----------------------------------------
        // Módulo: Gestão de Conteúdo (CMS)
        // ----------------------------------------
        Route::middleware('role:Administrador|Gerente')
            ->prefix('admin/conteudo')
            ->name('admin.content.')
            ->group(function () {
                
                Route::get('/', [ContentManagementController::class, 'index'])->name('index');
                
                Route::get('/carrossel', [ContentManagementController::class, 'carousel'])->name('carousel');
                Route::post('/carrossel', [ContentManagementController::class, 'storeCarousel'])->name('carousel.store');
                Route::put('/carrossel/{id}', [ContentManagementController::class, 'updateCarousel'])->name('carousel.update');
                Route::delete('/carrossel/{id}', [ContentManagementController::class, 'destroyCarousel'])->name('carousel.destroy');
                
                Route::get('/equipa', [ContentManagementController::class, 'team'])->name('team');
                Route::post('/equipa', [ContentManagementController::class, 'storeTeam'])->name('team.store');
                Route::put('/equipa/{id}', [ContentManagementController::class, 'updateTeam'])->name('team.update');
                Route::delete('/equipa/{id}', [ContentManagementController::class, 'destroyTeam'])->name('team.destroy');
                
                Route::get('/servicos', [ContentManagementController::class, 'services'])->name('services');
                Route::post('/servicos', [ContentManagementController::class, 'storeService'])->name('service.store');
                Route::put('/servicos/{id}', [ContentManagementController::class, 'updateService'])->name('service.update');
                Route::delete('/servicos/{id}', [ContentManagementController::class, 'destroyService'])->name('service.destroy');
                
                Route::get('/contactos', [ContentManagementController::class, 'contacts'])->name('contacts');
                Route::post('/contactos', [ContentManagementController::class, 'storeContact'])->name('contact.store');
                Route::put('/contactos/{id}', [ContentManagementController::class, 'updateContact'])->name('contact.update');
                Route::delete('/contactos/{id}', [ContentManagementController::class, 'destroyContact'])->name('contact.destroy');
                
                Route::get('/configuracoes', [ContentManagementController::class, 'settings'])->name('settings');
                Route::post('/configuracoes', [ContentManagementController::class, 'updateSettings'])->name('settings.update');
                Route::post('/configuracoes/upload-about-image', [ContentManagementController::class, 'uploadAboutImage'])->name('settings.uploadAboutImage');
                
                Route::get('/sobre-nos', [ContentManagementController::class, 'about'])->name('about');
                Route::post('/sobre-nos', [ContentManagementController::class, 'updateAbout'])->name('about.update');
            });
    });