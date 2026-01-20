<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolController;

Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/create', [ToolController::class, 'create'])->name('tools.create');
Route::post('/tools', [ToolController::class, 'store'])->name('tools.store');


Route::get('/', function () {
    return view('welcome');
});
