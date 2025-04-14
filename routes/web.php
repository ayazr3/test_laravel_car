<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Spatie\FlareClient\View;

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
//     return view('welcome');
// });
Route::get('/', [CarController::class,'viewAll']);

Route::get('/allCar', function () {
    return View('allCar');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashbord');
    })->name('admin.dashboard')->middleware('admin');


});

Route::middleware(['auth'])->group(function () {
    Route::resource('/car',CarController::class);
    Route::post('/cars/{car}/toggle-sold', [CarController::class, 'toggleSoldStatus'])
    ->name('car.toggle-sold');

});



require __DIR__.'/auth.php';
