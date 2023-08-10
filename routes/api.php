<?php

use App\Http\Controllers\api\admin;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\ToursController;
use App\Http\Controllers\Api\TravelsController;
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

Route::get('travels', [TravelsController::class, 'index']);
Route::get('travel/{travel:slug}/tours', [ToursController::class, 'index']);

Route::post('/login', [LoginController::class, 'login']);

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::post('travels', [admin\TravelController::class, 'store']);
        Route::post('travels/{travel}/tours', [admin\TourController::class, 'store']);
    });
    Route::put('travels/{travel}', [admin\TravelController::class, 'update']);
});
