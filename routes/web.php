<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeConfigController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\HrConfigController;
use App\Http\Controllers\MedicalCertificateController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WorkLogController;
use App\Http\Controllers\WorkScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['can:admin'])->group(function () {
        // Funcionários
        Route::resource('employees', EmployeeController::class);
        Route::get('/employees/{employee}/schedule', [WorkScheduleController::class, 'edit'])->name('schedule.edit');
        Route::put('/employees/{employee}/schedule', [WorkScheduleController::class, 'update'])->name('schedule.update');
        Route::get('/employees/{employee}/config', [EmployeeConfigController::class, 'edit'])->name('employee-config.edit');
        Route::put('/employees/{employee}/config', [EmployeeConfigController::class, 'update'])->name('employee-config.update');
        Route::delete('/employees/{employee}/config/{config}', [EmployeeConfigController::class, 'destroy'])->name('employee-config.destroy');

        // Configurações RH
        Route::resource('hr-config', HrConfigController::class);

        // Folha de Pagamento
        Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
        Route::post('/payroll/calculate', [PayrollController::class, 'calculate'])->name('payroll.calculate');
        Route::get('/payroll/{payroll}', [PayrollController::class, 'show'])->name('payroll.show');
        Route::put('/payroll/{payroll}', [PayrollController::class, 'update'])->name('payroll.update');
        Route::patch('/payroll/{payroll}/close', [PayrollController::class, 'close'])->name('payroll.close');

        // Atestados Médicos
        Route::get('/certificates', [MedicalCertificateController::class, 'index'])->name('certificates.index');
        Route::get('/certificates/create', [MedicalCertificateController::class, 'create'])->name('certificates.create');
        Route::post('/certificates', [MedicalCertificateController::class, 'store'])->name('certificates.store');
        Route::patch('/certificates/{certificate}/approve', [MedicalCertificateController::class, 'approve'])->name('certificates.approve');
        Route::patch('/certificates/{certificate}/reject', [MedicalCertificateController::class, 'reject'])->name('certificates.reject');
        Route::delete('/certificates/{certificate}', [MedicalCertificateController::class, 'destroy'])->name('certificates.destroy');

        // Feriados
        Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays.index');
        Route::post('/holidays', [HolidayController::class, 'store'])->name('holidays.store');
        Route::delete('/holidays/{holiday}', [HolidayController::class, 'destroy'])->name('holidays.destroy');

        // Relatórios
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    Route::middleware(['can:employee'])->group(function () {
        Route::get('/work-log', [WorkLogController::class, 'index'])->name('work-log.index');
        Route::post('/work-log/punch', [WorkLogController::class, 'punch'])->name('work-log.punch');
        Route::get('/work-log/history', [WorkLogController::class, 'history'])->name('work-log.history');

        // Módulo do Funcionário (Financeiro/RH)
        Route::get('/my-dashboard', [\App\Http\Controllers\Employee\DashboardController::class, 'index'])->name('employee.dashboard');
        Route::get('/my-payroll', [\App\Http\Controllers\Employee\PayrollController::class, 'index'])->name('employee.payroll.index');
        Route::get('/my-payroll/{payroll}', [\App\Http\Controllers\Employee\PayrollController::class, 'show'])->name('employee.payroll.show');
        Route::get('/my-vacations', [\App\Http\Controllers\Employee\VacationController::class, 'index'])->name('employee.vacations.index');
        Route::post('/my-vacations', [\App\Http\Controllers\Employee\VacationController::class, 'store'])->name('employee.vacations.store');
        Route::get('/my-certificates', [\App\Http\Controllers\Employee\CertificateController::class, 'index'])->name('employee.certificates.index');
        Route::post('/my-certificates', [\App\Http\Controllers\Employee\CertificateController::class, 'store'])->name('employee.certificates.store');

    });

});

require __DIR__.'/auth.php';