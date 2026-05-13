<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolController;

Route::get('/', function () {
    return view('welcome');
});

// Tools CRUD Routes
Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/create', [ToolController::class, 'create'])->name('tools.create');
Route::post('/tools', [ToolController::class, 'store'])->name('tools.store');
Route::get('/tools/{id}/edit', [ToolController::class, 'edit'])->name('tools.edit');
Route::put('/tools/{id}', [ToolController::class, 'update'])->name('tools.update');
Route::delete('/tools/{id}', [ToolController::class, 'destroy'])->name('tools.destroy');

// Features: Changelog, Health & Compare
Route::get('/tools/{id}/changelog', [ToolController::class, 'changelog'])->name('tools.changelog');
Route::get('/tools/health/{id}', [ToolController::class, 'checkHealth'])->name('tools.health');
Route::get('/tools/compare', [ToolController::class, 'compare'])->name('tools.compare'); 