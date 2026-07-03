<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\BookCatalog;
use App\Livewire\BookDetail;
use App\Livewire\EbookReader;
use App\Livewire\AdminCirculation;
use App\Livewire\AdminBooks;

Route::get('/', function () {
    return view('welcome', [
        'featuredBooks' => \App\Models\Book::with('category')->latest()->take(4)->get(),
        'totalBooks' => \App\Models\Book::count(),
        'totalEbooks' => \App\Models\Book::whereNotNull('pdf_file')->count(),
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/catalog', BookCatalog::class)->name('catalog.index');
    Route::get('/catalog/{book}', BookDetail::class)->name('catalog.show');
    Route::get('/ebook/{book}', EbookReader::class)->name('ebook.read');
    Route::post('/ebook/borrow/{borrow}/finish', [App\Http\Controllers\DashboardController::class, 'finishEbook'])->name('ebook.finish');
    
    // Admin only routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/circulation', AdminCirculation::class)->name('circulation');
        Route::get('/books', AdminBooks::class)->name('books');
    });
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
