<?php

use App\Models\Talent;
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

Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return app(TalentController::class)->index();
    })->name('talents.filter');

    Route::get('filter', function (TalentRequest $request) {
        return app(TalentController::class)->list($request);
    })->name('talents.list');

    Route::get('/detail/{id}', function ($id)  {
        return app(TalentController::class)->detail($id);
    })->name('talents.detail');
});
