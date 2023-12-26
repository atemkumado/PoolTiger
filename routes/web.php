<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TalentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('index');
// });


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/process-data/{data}', [TalentController::class, 'getData'])->name('process.data');
});
//DELETE WHEN COMPLETE APP
Route::get('/testing', [ProfileController::class, 'testing'])->name('testing');
Route::get('/phpinfo', function () {
    phpinfo();
});
require __DIR__.'/auth.php';
require __DIR__.'/talents/filter.php';
