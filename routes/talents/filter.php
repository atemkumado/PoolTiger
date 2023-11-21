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
    $dataFilter = Talent::getFilter();

    Route::get('/', function () use ($dataFilter){
        return app(TalentController::class)->index($dataFilter);
    })->name('talents.filter');

    Route::get('filter', function (TalentRequest $request) use ($dataFilter) {
        return app(TalentController::class)->list($request, $dataFilter);
    })->name('talents.list');

    Route::get('/detail', [TalentController::class, 'detail'])
        ->name('talents.detail');
});
