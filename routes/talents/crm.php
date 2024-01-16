<?php

use App\Models\Talent;
use App\Services\VtigerService;
use Illuminate\Http\Request;
use App\Helper\SingletonApiHelper;
use App\Http\Requests\TalentRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TalentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/type-list', [TalentController::class, 'getTypeList'])->name('crm.typelists');
Route::get('/crm', [TalentController::class, 'crm'])->name('crm.fetch');
Route::get('/field-info', [TalentController::class, 'getFieldList'])->name('crm.fields');
Route::get('/module-query/{module}', [TalentController::class, 'getModuleQuery'])->name('crm.fields');
