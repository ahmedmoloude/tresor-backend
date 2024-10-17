<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContribuableController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);




// contribulables 
Route::get('/tax-payers', [ContribuableController::class, 'getContribuables']);
Route::post('/tax-payers', [ContribuableController::class, 'store']);
Route::put('/tax-payers/{id}', [ContribuableController::class, 'update']);


Route::get('/tax-payers/{id}', [ContribuableController::class, 'getTaxPayerDetails']);




Route::get('/roles', [ContribuableController::class, 'getAllRoles']);


Route::post('/upload-excel', [ContribuableController::class, 'uploadExcel']);


Route::post('/close-account/{id}', [ContribuableController::class,'closeContribuable']);

Route::get('tax-payers/export-fiche/{id}', [ContribuableController::class,'exportcontribuablePDF']);

Route::get('tax-payers/export-situation/{id}', [ContribuableController::class,'sutiationcontribuablePDF']);






Route::get('contribuables/{id}/protocols', [ContribuableController::class, 'getTaxPayerProtocols']);
Route::post('contribuables/{id}/protocols', [ContribuableController::class, 'createProtocol']);
Route::put('protocols/{id}', [ContribuableController::class, 'updateProtocol']);
Route::delete('protocols/{id}', [ContribuableController::class, 'deleteProtocol']);

Route::get('contribuables/{id}/payments', [ContribuableController::class, 'getTaxPayerPayments']);
Route::post('contribuables/{id}/payments', [ContribuableController::class, 'createPayment']);

Route::get('contribuables/{id}/documents', [ContribuableController::class, 'getTaxPayerDocuments']);
Route::post('contribuables/{id}/documents', [ContribuableController::class, 'uploadDocument']);
Route::delete('documents/{id}', [ContribuableController::class, 'deleteDocument']);

Route::get('document-types', [ContribuableController::class, 'getDocumentTypes']);

Route::get('contribuables-all', [ContribuableController::class, 'getAllContribuables']);
