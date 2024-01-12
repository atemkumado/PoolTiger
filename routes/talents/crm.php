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

Route::get('/typelist', function () {
    $service = app(VtigerService::class);
    $service->loadSession();
    $data = $service->getListTypes();
    echo json_encode($data, JSON_PRETTY_PRINT);
    return true;
})->name('crm.typelists');

Route::get('/crm', function () {
    $service = app(VtigerService::class);
    $service->loadSession();
    $data = $service->fetchData();
    echo json_encode($data, JSON_PRETTY_PRINT);
    return true;
})->name('crm.fetch');

Route::get('/field-info', function () {
    $service = app(VtigerService::class);
    $service->loadSession();
    $data = $service->getFieldInfo();
    echo json_encode($data, JSON_PRETTY_PRINT);
    return true;
})->name('crm.fields');

