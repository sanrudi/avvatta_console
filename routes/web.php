<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionReportController;
use App\Http\Controllers\VideoContentReportController;
use App\Http\Controllers\ElearnContentReportController;
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
Route::get('subscription-customer', [App\Http\Controllers\SubscriptionReportController::class, 'subscriptionCustomer'])->name('subscription-customer');
Route::get('subscription-total', [App\Http\Controllers\SubscriptionReportController::class, 'subscriptionTotal'])->name('subscription-total');
Route::get('daily-transactions', [App\Http\Controllers\SubscriptionReportController::class, 'dailyTransactions'])->name('daily-transactions');

// Video Content Report
Route::get('video-content-report', [App\Http\Controllers\VideoContentReportController::class, 'index'])->name('video-content-report');
Route::get('video-articles-report', [App\Http\Controllers\VideoContentReportController::class, 'videoArticles'])->name('video-articles-report');
Route::get('video-most-watched-report', [App\Http\Controllers\VideoContentReportController::class, 'mostWatched'])->name('video-most-watched-report');
Route::get('video-top-repeat-user-report', [App\Http\Controllers\VideoContentReportController::class, 'topRepeatedBySingleUser'])->name('video-top-repeat-user-report');
Route::get('video-top-genre-watched-report', [App\Http\Controllers\VideoContentReportController::class, 'topGenreWatched'])->name('video-top-genre-watched-report');
Route::get('video-all-category-user-report', [App\Http\Controllers\VideoContentReportController::class, 'allCategoryUsers'])->name('video-all-category-user-report');
// Revenue Report
Route::get('revenue-report', [App\Http\Controllers\RevenueReportController::class, 'index'])->name('revenue-report');

// Activity Report
Route::get('activity-report', [App\Http\Controllers\ActivityReportController::class, 'index'])->name('activity-report');
// Game Report
Route::get('game-report', [App\Http\Controllers\GameReportController::class, 'index'])->name('game-report');
Route::get('export-game-content', [App\Http\Controllers\GameReportController::class, 'exportGameContent'])->name('export-game-content');
Route::get('repeated-game-by-user',[App\Http\Controllers\GameReportController::class, 'repeatedGameBySingleUser'])->name('repeated-game-by-user');
Route::get('export-repeated-game-content',[App\Http\Controllers\GameReportController::class, 'exportRepeatedGameBySingleUser'])->name('export-repeated-game-content');
Route::get('most-played-games',[App\Http\Controllers\GameReportController::class, 'mostPlayedGames'])->name('most-played-games');
Route::get('export-most-played-games',[App\Http\Controllers\GameReportController::class, 'exportMostPlayedGames'])->name('export-most-played-games');
Route::get('game-articles-report', [App\Http\Controllers\GameReportController::class, 'gameArticles'])->name('game-articles-report');
//Kids Report
Route::get('kids-report', [App\Http\Controllers\KidsReportController::class, 'kidsReport'])->name('kids-report');
// Route::get('export-game-content', [App\Http\Controllers\GameReportController::class, 'exportGameContent'])->name('export-game-content');
Route::get('most-watched-kids-content',[App\Http\Controllers\KidsReportController::class, 'mostWatchedKidsContent'])->name('most-watched-kids-content');
// Route::get('export-repeated-game-content',[App\Http\Controllers\GameReportController::class, 'exportRepeatedGameBySingleUser'])->name('export-repeated-game-content');
Route::get('top-kids-content',[App\Http\Controllers\KidsReportController::class, 'topKidsContent'])->name('top-kids-content');
// Route::get('export-most-played-games',[App\Http\Controllers\GameReportController::class, 'exportMostPlayedGames'])->name('export-most-played-games');
Route::get('repeated-kidscontent-by-user',[App\Http\Controllers\KidsReportController::class, 'repeatedKidsContentByUser'])->name('repeated-kidscontent-by-user');
//User Report
Route::get('user-report',[App\Http\Controllers\UserReportController::class, 'userReport'])->name('user-report');
Route::get('user-registration-report',[App\Http\Controllers\UserReportController::class, 'userRegistrationReport'])->name('user-registration-report');
Route::get('user-sub-profile-report',[App\Http\Controllers\UserReportController::class, 'userSubProfileReport'])->name('user-sub-profile-report');
Route::get('user-login-report',[App\Http\Controllers\UserReportController::class, 'userLoginReport'])->name('user-login-report');   

// Elearning
Route::get('most-watched-elearn-content',[App\Http\Controllers\ElearnContentReportController::class, 'mostWatchedElearnContent'])->name('most-watched-elearn-content');
Route::get('top-ten-elearn-content',[App\Http\Controllers\ElearnContentReportController::class, 'topTenElearnContent'])->name('top-ten-elearn-content');
Route::get('elearn-report',[App\Http\Controllers\ElearnContentReportController::class, 'elearnReport'])->name('elearn-report');
Route::get('elearn-top-genre-watched-report',[App\Http\Controllers\ElearnContentReportController::class, 'topGenreWatched'])->name('elearn-top-genre-watched-report');
Route::get('elearn-top-repeat-user-report',[App\Http\Controllers\ElearnContentReportController::class, 'topRepeatedBySingleUser'])->name('elearn-top-repeat-user-report');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
