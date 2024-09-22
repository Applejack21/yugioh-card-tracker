<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\CardSetController;
use App\Http\Controllers\HomepageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

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

// Routes anyone can see.
Route::controller(HomepageController::class)->name('homepage.')->group(function () {
    Route::get('/', 'index')->name('index');
});

Route::controller(CardController::class)->name('card.')->group(function () {
    Route::get('/cards', 'index')->name('index');
    Route::get('/card/{card:card_id}')->name('show');
});

Route::controller(CardSetController::class)->name('card-set.')->group(function () {
    Route::get('/card-sets', 'index')->name('index');
    Route::get('/card-set/{cardSet}', 'show')->name('show');
});

// Routes only logged in and verified users can see.
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    //
});
