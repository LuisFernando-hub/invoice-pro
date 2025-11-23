<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('LandingPage', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::post('/language/{locale}', function ($locale) {
    session()->put('locale', $locale);
    return redirect()->back();
})->name('language');

Route::get('/dashboard', function () {
    $user = auth()->user();
    $invoices = $user->invoices();
    
    return Inertia::render('Dashboard', [
        'invoiceCount' => $invoices->count(),
        'invoiceTotal' => $invoices->sum('total_amount'),
        'monthlyStats' => $invoices->selectRaw('strftime("%Y-%m", created_at) as month, sum(total_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month'),
        'onTrial' => $user->onTrial(),
        'canCreateInvoice' => $user->canCreateInvoice(),
        'trialEndsAt' => $user->created_at->addMonths(3)->format('Y-m-d')
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('invoices', \App\Http\Controllers\InvoiceController::class);
    Route::post('invoices/{invoice}/duplicate', [\App\Http\Controllers\InvoiceController::class, 'duplicate'])->name('invoices.duplicate');
    Route::get('invoices/{invoice}/download/{format}', [\App\Http\Controllers\InvoiceController::class, 'download'])->name('invoices.download');

    Route::get('/subscription', [\App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription', [\App\Http\Controllers\SubscriptionController::class, 'store'])->name('subscription.store');
});

require __DIR__.'/auth.php';
