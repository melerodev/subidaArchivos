<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;

Route::get('/', [UploadController::class, 'index'])->name('inicio');

Route::get('/subida-archivos', function () {
    return view('subida-archivos');
})->name('subida-archivos');

Route::post('/upload', [UploadController::class, 'upload'])->name('upload');
Route::delete('/delete/{id}', [UploadController::class, 'destroy'])->name('delete');