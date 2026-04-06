<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditLogController;

require __DIR__ . '/auth.php';

// ----  Lang switch -----------------------------------
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'vi'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::middleware('jwt.cookie')->group(function () {
    // ---- Dashboard -----------------------------------
    Route::get('/', function () {
        return view('dashboard.index');
    })->middleware('jwt.cookie')->name('dashboard');

    // ---- Users ---------------------------------------
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/data', [UserController::class, 'data'])->name('data');
        Route::get('/filter-data', [UserController::class, 'getFilterData'])->name('filter_data');
        Route::get('/getTeamsDropdown', [UserController::class, 'getTeamsDropdown'])->name('teams_data');

        Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore');
    });
    Route::resource('users', UserController::class);

    // ---- Profile ---------------------------------------
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // ---- Role ---------------------------------------
    Route::resource('/roles', RoleController::class)->except(['show']);

    // ---- Audit logs ---------------------------------------
    Route::prefix('audit-logs')->name('audit-logs.')->group(function(){
        Route::get('/data', [AuditLogController::class, 'data'])->name('data');
        Route::get('/filter-data', [AuditLogController::class, 'getFilterData'])->name('filter_data');
        Route::post('/{id}/restore', [AuditLogController::class, 'restore'])->name('restore');
    });
    Route::resource('/audit-logs', AuditLogController::class)->except(['show']);
});
