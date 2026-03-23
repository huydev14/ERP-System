<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

// Lang switch
Route::get('lang/{locale}', function ($locale){
    if(in_array($locale, ['en', 'vi'])){
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');


Route::get('/', function () {
    return view('dashboard.index');
})->middleware('jwt.cookie')->name('dashboard');

Route::middleware('jwt.cookie')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware('jwt.cookie')->prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/data', [UserController::class, 'data'])->name('data');
    Route::get('/filter-data', [UserController::class, 'getFilterData'])->name('filter_data');

    Route::post('/create', [UserController::class, 'store'])->name('store');
});

Route::middleware('jwt.cookie')->group(function(){
    Route::resource('/roles', RoleController::class)->except(['show']);
});
