<?php

use App\Models\Todo;
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

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('/todos')->name('todos.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('ToDos/List');
        })->name('list');

        Route::get('/{slug}', function (string $slug) {
            $todo = Todo::where('slug', $slug)->first();

            return Inertia::render('ToDos/Show', [
                'todo' => $todo,
            ]);
        })->name('show');
    });
});
