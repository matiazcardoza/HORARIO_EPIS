<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\ShowCourses;
use App\Http\Livewire\Docentes;
use App\Http\Livewire\AddDocente;

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
    if (Auth::user()->usertype == 'admin') {
        return redirect()->route('admin.dashboard');
    } else {
        return view('dashboard'); // Vista para el usuario normal
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    //implementacion de ruta livewire
    //Route::get('/users/tasks', ShowdashboardTasks::class)->name('users/tasks');
    Route::get('admin/dashboard', ShowCourses::class)->name('admin.dashboard');
    Route::get('admin/docente', Docentes::class)->name('admin.docente');
    Route::post('courses/import', [CourseController::class, 'import']);
    Route::get('courses/export', [CourseController::class, 'export']);
    Route::get('/admin/add-docente', AddDocente::class)->name('admin.add-docente');
});

//Route::get('courses', 'CourseController@index');
//Route::post('courses/import', 'CourseController@import');

require __DIR__ . '/auth.php';

//Route::get('admin/dashboard', [CourseController::class, 'index']);
//Route::post('courses/import', 'CourseController@import');

//Route::get('admin/dashboard', [HomeController::class, 'index']);
//Route::post('courses/import', [CourseController::class, 'import']);
//Route::get('courses/export', [CourseController::class, 'export']);
