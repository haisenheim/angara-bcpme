<?php

use Illuminate\Support\Facades\Route;
use Modules\Simulator\Http\Controllers\ScenarioController;
use Modules\Simulator\Http\Controllers\SimulatorController;

Route::get('/', [SimulatorController::class, 'index'])->name('index');

Route::get('/scenarios', [ScenarioController::class, 'index'])->name('scenarios.index');
Route::get('/scenarios/{scenario:token}', [ScenarioController::class, 'show'])->name('scenarios.show');
Route::post('/scenarios/{scenario:token}/submit', [ScenarioController::class, 'submit'])->name('scenarios.submit');
Route::post('/scenarios/{scenario:token}/validate', [ScenarioController::class, 'validateScenario'])->name('scenarios.validate');
Route::post('/scenarios/{scenario:token}/reject', [ScenarioController::class, 'reject'])->name('scenarios.reject');
Route::post('/scenarios/{scenario:token}/detach', [ScenarioController::class, 'detach'])->name('scenarios.detach');
Route::delete('/scenarios/{scenario:token}', [ScenarioController::class, 'destroy'])->name('scenarios.destroy');

Route::get('/scenarios/{scenario:token}/pdf', [ScenarioController::class, 'pdf'])->name('scenarios.pdf');
Route::get('/scenarios/{scenario:token}/xlsx', [ScenarioController::class, 'xlsx'])->name('scenarios.xlsx');
