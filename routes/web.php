<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\CardSetController;
use App\Http\Controllers\HomepageController;
use App\Http\Middleware\VerifiedIfLoggedIn;
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

// Routes for non-logged in/logged in (and verified users).
Route::middleware(VerifiedIfLoggedIn::class)->group(function () {
    Route::controller(CardController::class)->name('card.')->group(function () {
        Route::get('/cards', 'index')->name('index');
        Route::get('/card/{card:card_id}')->name('show');
    });

    Route::controller(CardSetController::class)->name('card-set.')->group(function () {
        Route::get('/card-sets', 'index')->name('index');
        Route::get('/card-set/{cardSet}', 'show')->name('show');
    });
});

// Routes for logged in users only.
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::controller(CardController::class)->name('card.')->group(function () {
        Route::post('/card/{card}', 'addToCollection')->name('add-card-to-collection');
    });
});
