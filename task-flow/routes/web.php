<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Livewire\Tasks\CreateTask;
use App\Livewire\Tasks\EditTask;
use App\Livewire\Tasks\TaskList;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Inertia/Vue Task Routes
    Route::resource('tasks', TaskController::class);

    // Livewire Task Routes
    Route::get('/livewire/tasks', TaskList::class)->name('livewire.tasks');
    Route::get('/livewire/tasks/create', CreateTask::class)->name('livewire.tasks.create');
    Route::get('/livewire/tasks/{task}/edit', EditTask::class)->name('livewire.tasks.edit');
});

require __DIR__.'/auth.php';
