<?php

use Illuminate\Support\Facades\Route;
use Modules\Simulator\Http\Controllers\SimulatorController;

Route::post('/simulate', [SimulatorController::class, 'simulate'])->name('simulate');
Route::post('/scenarios', [SimulatorController::class, 'persist'])->name('persist');
Route::post('/export/pdf', [SimulatorController::class, 'exportPdfFromInput'])->name('export.pdf');
Route::post('/export/xlsx', [SimulatorController::class, 'exportXlsxFromInput'])->name('export.xlsx');
