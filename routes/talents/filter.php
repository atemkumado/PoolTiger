<?php

use App\Http\Controllers\TalentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [TalentController::class, 'index'])
->name('talents.filter');
Route::get('filter', [TalentController::class, 'getList'])
->name('talents.list');
Route::get('profile', [TalentController::class, 'getProfile'])
->name('talents.profile');

