<?php

use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FileUploadController::class, 'index'])->name('index');
Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload');
