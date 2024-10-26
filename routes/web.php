<?php

use App\Models\Document;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;


Route::get('/', [DocumentController::class, 'index']);
Route::get('/', function () {
    $documents = Document::all(); // Récupère tous les documents de la base de données
    return view('documents.index', compact('documents'));});
Route::post('/documents', [DocumentController::class, 'store']);
Route::get('/documents/{id}/generate-qr', [DocumentController::class, 'generateQrCode']);
Route::get('/documents', [DocumentController::class, 'index']);
Route::get('/documents/{id}', [DocumentController::class, 'show']);
Route::delete('/documents/{id}', [DocumentController::class, 'destroy']);
Route::get('/documents/{id}/download-qr', [DocumentController::class, 'downloadQrCode']);

