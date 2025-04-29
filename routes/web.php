<?php

use App\Http\Controllers\AdsController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\Manager\User as ManagerUser;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicCarController;
use App\Http\Controllers\Manager\User;
use App\Http\Controllers\ReviewController;
use App\Models\Car;
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
Route::get('/', [CarController::class,'viewAll'])->name('welcom');

Route::get('/ads/redirect/{id}', [App\Http\Controllers\AdsController::class, 'redirectAd'])->name('ads.redirect');



// شكاوي واقتراحات
Route::get('/review/create/{car?}', [ReviewController::class, 'create'])->name('review.create');
Route::post('/review', [ReviewController::class, 'store'])->name('review.store');

// مسارات الإدارة (تحتاج صلاحيات مدير)
Route::middleware(['auth', 'can:viewAny,App\Models\Review'])->group(function () {
    Route::get('/admin/reviews', [ReviewController::class, 'index'])->name('review.index');
    Route::patch('/admin/reviews/{review}/toggle-publish', [ReviewController::class, 'togglePublish'])->name('review.toggle-publish');
    Route::delete('/admin/reviews/{review}', [ReviewController::class, 'destroy'])->name('review.destroy');
});



Route::get('/allCar', function () {
    return View('allCar');
});
Route::get('cars/{car}', [PublicCarController::class, 'show'])
    ->name('cars.details');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::middleware(['auth'])->group(function () {
//     Route::get('/admin/dashboard', function () {
//         return view('admin.dashbord');
//     })->name('admin.dashboard')->middleware('admin');


// });


Route::middleware(['auth'])->group(function () {
    Route::resource('/car',CarController::class);
    Route::post('/cars/{car}/toggle-sold', [CarController::class, 'toggleSoldStatus'])
    ->name('car.toggle-sold');

});

Route::middleware(['auth'])->controller(\App\Http\Controllers\Manager\User::class)->group(function () {
    Route::get('manager/user', 'index')->name('manager.user.index');
    Route::get('manager/user/create', 'create')->name('manager.user.create');
    Route::post('manager/user/store', 'store')->name('manager.user.store');
    Route::get('manager/user/edit/{id}', 'edit')->name('manager.user.edit');
    Route::put('manager/user/update/{id}', 'update')->name('manager.user.update');
    Route::delete('manager/user/delete/{id}', 'destroy')->name('manager.user.destroy');
    Route::patch('manager/user/{id}/toggle-role', 'toggleRole')->name('manager.user.toggle-role');
    Route::patch('manager/user/{id}/toggle-status', 'toggleStatus')->name('manager.user.toggle-status');

});
Route::middleware(['auth'])->controller(AdsController::class)->group(function () {
    Route::get('manager/ads/index','index')->name('manager.ads.index');
    Route::get('manager/ads/create','create')->name('manager.ads.create');
    Route::post('manager/ads/store','store')->name('manager.ads.store');
    Route::get('manager/ads/edit/{id}', 'edit')->name('manager.ads.edit');
    Route::put('manager/ads/update/{id}', 'update')->name('manager.ads.update');
    Route::delete('manager/ads/delete/{id}', 'destroy')->name('manager.ads.destroy');
    Route::get('manager/ads/{id}/show','show')->name('manager.ads.show');
    Route::post('/admin/ads/{id}/toggle-visibility',  'toggleAdVisibility')->name('admin.ads.toggle');


});

// // طرق الأدمن
// Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
//     Route::get('/cars/pending', [CarController::class, 'pendingCars'])->name('admin.cars.pending');
//     Route::get('/cars/review/{id}', [CarController::class, 'reviewCar'])->name('admin.cars.review');
//     Route::post('/cars/approve/{id}', [CarController::class, 'approveCar'])->name('admin.cars.approve');
//     Route::post('/cars/reject/{id}', [CarController::class, 'rejectCar'])->name('admin.cars.reject');
// });

// طلب إعلان مميز
Route::post('/cars/{id}/request-featured', [CarController::class, 'requestFeatured'])
    ->name('cars.request-featured');

// لوحة تحكم الأدمن للإعلانات المميزة
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/featured-requests', [CarController::class, 'featuredRequests'])
        ->name('admin.featured-requests');
    Route::post('/approve-featured/{id}', [CarController::class, 'approveFeatured'])
        ->name('admin.approve-featured');
    Route::post('/reject-featured/{id}', [CarController::class, 'rejectFeatured'])
        ->name('admin.reject-featured');
});

require __DIR__.'/auth.php';
