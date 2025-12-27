<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\KebijakanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('book', BookController::class);
    Route::resource('transaction', TransaksiController::class);
    Route::resource('kebijakan', KebijakanController::class);
    Route::resource('report', ReportController::class);
    Route::get('/export', [BookController::class, 'export'])
        ->name('book.export');
    Route::patch('/status/{id}', [BookController::class, 'status'])
        ->name('book.status');
    Route::get(
        'transaction/search/anggota',
        [TransaksiController::class, 'searchAnggota']
    )->name('transaction.search.anggota');
    Route::get(
        'transaction/search/buku',
        [TransaksiController::class, 'searchBuku']
    )->name('transaction.search.buku');
});

require __DIR__ . '/auth.php';
