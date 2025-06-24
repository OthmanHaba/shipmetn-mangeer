<?php

use App\Http\Controllers\InvoicePrintController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('filament.admin.pages.dashboard');
});

// Invoice print route
Route::get('/invoices/{invoice}/print', [InvoicePrintController::class, 'print'])
    ->name('invoices.print')
    ->middleware(['web', 'auth']);
