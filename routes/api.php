<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\CheckAuthor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function  () {
    Route::get('me', [AuthController::class, 'me'])->name('me');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
     Route::get('', [CategoryController::class, 'index'])->name('index');
     Route::group(['middleware' => 'auth:api'], function () {
         Route::get('my', [CategoryController::class, 'byUser'])->name('my');
         Route::post('', [CategoryController::class, 'store'])->name('store');
         Route::group(['prefix' => '{category}', 'middleware' => [CheckAuthor::class]], function () {
             Route::get('', [CategoryController::class, 'show'])->name('show');
             Route::put('', [CategoryController::class, 'update'])->name('update');
             Route::delete('', [CategoryController::class, 'destroy'])->name('destroy');
         });
     });
});
