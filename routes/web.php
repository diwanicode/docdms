<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\HandleBusinessLanguage; 
use App\Http\Middleware\HandleBusinessOwner; 
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\BusinessEmployeeController; 
use App\Http\Controllers\BusinessClientController; 
use App\Http\Controllers\BusinessDepartmentController; 
use App\Http\Controllers\CounturyFileController; 
use Inertia\Inertia;



 
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

    Route::prefix('business/{business:slug}')
        ->middleware(['auth', HandleBusinessLanguage::class, HandleBusinessOwner::class])
        ->scopeBindings()
        ->group(function () {
            Route::singleton('', BusinessController::class)->names([
                'show' => 'business.show',
                'edit' => 'business.edit',
                'update' => 'business.update',
                'destroy' => 'business.destroy',
            ]);
            Route::get('/dashboard', [BusinessController::class, 'dashboard'])->name('business.dashboard');
            Route::post('/dashboard/reports', [BusinessController::class, 'dashboardReports'])->name('business.dashboard.reports');
            Route::get('/calendar', [BusinessController::class, 'calendar'])->name('business.calendar'); 
            Route::get('/employees', [BusinessController::class, 'employees'])->name('business.employees');
            Route::get('/clients', [BusinessController::class, 'clients'])->name('business.clients');
           
            Route::get('/files', [BusinessController::class, 'files'])->name('business.files');
            Route::post('/files/{businessClient:client_key}/store', [CounturyFileController::class, 'storeFile'])->name('business.files.store');
            Route::post('/files/{businessFile:file_key}/update', [CounturyFileController::class, 'updateFile'])->name('business.files.update');
            Route::get('/files/{businessFile:file_key}/download', [CounturyFileController::class, 'downloadFile'])->name('business.files.download');
            Route::delete('/files/{businessFile:file_key}/destroy', [CounturyFileController::class, 'deleteFileManual'])->name('business.files.destroy');
          
            Route::post('/departments/store', [BusinessDepartmentController::class, 'storeDepartment'])->name('business.departments.store');
            Route::post('/departments/{businessDepartment:key}/update', [BusinessDepartmentController::class, 'updateDepartment'])->name('business.departments.update');
            Route::delete('/departments/{businessDepartment:key}/destroy', [BusinessDepartmentController::class, 'deleteDepartment'])->name('business.departments.destroy');
           
            Route::post('/employees/store', [BusinessEmployeeController::class, 'storeEmployee'])->name('business.employees.store');
            Route::post('/employees/{businessEmployee:employee_key}/update', [BusinessEmployeeController::class, 'updateEmployee'])->name('business.employees.update');
         
            Route::post('/clients/store', [BusinessClientController::class, 'storeClient'])->name('business.clients.store');
            Route::post('/clients/{businessClient:client_key}/update', [BusinessClientController::class, 'updateClient'])->name('business.clients.update');
        });
     Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::fallback(function () {
    return Inertia::render('Error/NotFound');
    
});
