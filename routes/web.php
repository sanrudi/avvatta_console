<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionReportController;
use App\Http\Controllers\VideoContentReportController;
use App\Http\Controllers\ActivityReportController;


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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('', function () {
    return view('dashboard');
});

Route::get('login', function () {
    return view('auth/login');
});
Route::get('register', function () {
    return view('register');
});

Route::get('register', function () {
    return view('register');
});

// Subscription Report
Route::get('subscription-report', [App\Http\Controllers\SubscriptionReportController::class, 'index'])->name('subscription-report');
// Video Content Report
Route::get('video-content-report', [App\Http\Controllers\VideoContentReportController::class, 'index'])->name('video-content-report');
Route::get('video-articles-report', [App\Http\Controllers\VideoContentReportController::class, 'videoArticles'])->name('video-articles-report');
Route::get('video-most-watched-report', [App\Http\Controllers\VideoContentReportController::class, 'mostWatched'])->name('video-most-watched-report');

// Activity Report
Route::get('activity-report', [App\Http\Controllers\ActivityReportController::class, 'index'])->name('activity-report');
// Game Report
Route::get('game-report', [App\Http\Controllers\GameReportController::class, 'index'])->name('game-report');
Route::get('export-game-content', [App\Http\Controllers\GameReportController::class, 'exportGameContent'])->name('export-game-content');
Route::get('repeated-game-by-user',[App\Http\Controllers\GameReportController::class, 'repeatedGameBySingleUser'])->name('repeated-game-by-user');
Route::get('export-repeated-game-content',[App\Http\Controllers\GameReportController::class, 'exportRepeatedGameBySingleUser'])->name('export-repeated-game-content');
Route::get('most-played-games',[App\Http\Controllers\GameReportController::class, 'mostPlayedGames'])->name('most-played-games');
Route::get('export-most-played-games',[App\Http\Controllers\GameReportController::class, 'exportMostPlayedGames'])->name('export-most-played-games');
//Kids Report
Route::get('kids-report', [App\Http\Controllers\KidsReportController::class, 'kidsReport'])->name('kids-report');
// Route::get('export-game-content', [App\Http\Controllers\GameReportController::class, 'exportGameContent'])->name('export-game-content');
Route::get('most-watched-kids-content',[App\Http\Controllers\KidsReportController::class, 'mostWatchedKidsContent'])->name('most-watched-kids-content');
// Route::get('export-repeated-game-content',[App\Http\Controllers\GameReportController::class, 'exportRepeatedGameBySingleUser'])->name('export-repeated-game-content');
Route::get('top-kids-content',[App\Http\Controllers\KidsReportController::class, 'topKidsContent'])->name('top-kids-content');
// Route::get('export-most-played-games',[App\Http\Controllers\GameReportController::class, 'exportMostPlayedGames'])->name('export-most-played-games');
Route::get('repeated-kidscontent-by-user',[App\Http\Controllers\KidsReportController::class, 'repeatedKidsContentByUser'])->name('repeated-kidscontent-by-user');