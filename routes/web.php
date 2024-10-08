<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Route::get('courses', 'CourseController@index');
//Route::post('courses/import', 'CourseController@import');

require __DIR__.'/auth.php';

//Route::get('admin/dashboard', [CourseController::class, 'index']);
//Route::post('courses/import', 'CourseController@import');

Route::get('admin/dashboard', [HomeController::class, 'index']);
//Route::get('courses/import', [CourseContoller::class, 'import']);
Route::post('courses/import', [CourseController::class, 'import']);
Route::get('courses/export', [CourseController::class, 'export']);


