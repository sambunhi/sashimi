<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiController;

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

Route::prefix('v1')->controller(ApiController::class)->group(function () {
    Route::get('/crawler/link', 'getLinksNeedHandle');
    Route::get('/crawler', 'getSystemInfo');
    Route::get('/trends', 'getTrends');
    Route::get('/articles', 'getArticles');
    Route::post('/article', 'saveCrawlerLinks')->middleware('crawler');
    Route::put('/article', 'saveArticleKeywords')->middleware('crawler');
});
