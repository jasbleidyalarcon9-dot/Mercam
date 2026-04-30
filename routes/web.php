<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductoController;
use App\Models\Post;

Route::get("/", function () {
    return view("welcome");
})->name('welcome');
// Route::get('/', function () {
//     $posts=Post::latest()->take(5)->get();
//     return view('welcome',compact('posts'));
// });

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/profile', function () {
    return view('profile.edit');
})->name('profile.edit');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('productos', ProductoController::class);
    Route::resource('posts', PostController::class);

});

