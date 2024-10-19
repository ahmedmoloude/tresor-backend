<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\RoleAnneeController;
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
Route::get('payments-types-data', [ContribuableController::class, 'getPaymentTypesData']);


Route::get('/contribuables/filtered', [ContribuableController::class, 'getFilteredContribuables']);

Route::post('/contribuables/{id}/payment-by-article', [ContribuableController::class, 'savePayment']);


Route::post('/contribuables/{id}/payment-by-protocol', [ContribuableController::class, 'savePaymentEchance']);





Route::post('/programmes/add-with-contribuables', [ProgrammeController::class, 'addProgrammeWithContribuables']);


Route::prefix('programmes')->group(function () {
    Route::get('/all', [ProgrammeController::class, 'getProgrammes']);
    Route::post('/add', [ProgrammeController::class, 'addProgramme']);
    Route::delete('/delete/{id}', [ProgrammeController::class, 'deleteProgramme']);
    Route::get('/exportprogrammePDF/{id}', [ProgrammeController::class, 'exportProgrammePDF']);
    Route::post('/addContribuables/{id}', [ProgrammeController::class, 'addContribuablesToProgramme']);
});


Route::post('budgets/{id}/upload-expense-document', [BudgetController::class, 'uploadExpenseDocument']);
Route::post('budgets/{id}/upload-income-document', [BudgetController::class, 'uploadIncomeDocument']);

Route::apiResource('budgets', controller: BudgetController::class);

Route::get('years', [BudgetController::class, 'getYears']);
Route::get('dashboard-stats', [ContribuableController::class, 'getDashboardStats']);



// roles 
Route::apiResource(name: 'role-annees', controller: RoleAnneeController::class);


Route::get('/activites', [RoleAnneeController::class, 'indexActivites']);
Route::post('/activites', [RoleAnneeController::class, 'storeActivite']);
Route::get('/activites/{id}', [RoleAnneeController::class, 'showActivite']);
Route::put('/activites/{id}', [RoleAnneeController::class, 'updateActivite']);
Route::delete('/activites/{id}', [RoleAnneeController::class, 'destroyActivite']);

// Tax Parameters (Forchets)
Route::get('/tax-params', [RoleAnneeController::class, 'indexForchets']);
Route::post('/tax-params', [RoleAnneeController::class, 'storeForchet']);
Route::get('/tax-params/{id}', [RoleAnneeController::class, 'showForchet']);
Route::put('/tax-params/{id}', [RoleAnneeController::class, 'updateForchet']);
Route::delete('/tax-params/{id}', [RoleAnneeController::class, 'destroyForchet']);

// Categories
Route::get('/categories', [RoleAnneeController::class, 'indexCategories']);
Route::post('/categories', [RoleAnneeController::class, 'storeCategorie']);
Route::get('/categories/{id}', [RoleAnneeController::class, 'showCategorie']);
Route::put('/categories/{id}', [RoleAnneeController::class, 'updateCategorie']);
Route::delete('/categories/{id}', [RoleAnneeController::class, 'destroyCategorie']);

// Additional routes
Route::get('/emplacements', [RoleAnneeController::class, 'getEmplacements']);
Route::get('/tailles', [RoleAnneeController::class, 'getTailles']);

Route::get('/tax-payers/export-pvf/{id}', [ContribuableController::class, 'fichdefermercontribuable']);







Route::get('/export-taxpayer-situation-pdf', [ContribuableController::class, 'pdfSuiviPayementCtb']);




Route::apiResource('users', UserController::class);