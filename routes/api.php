<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware(['jwt.auth'])->group(function () {

    // Rankings
    Route::get('/rankings/weekly', [GameController::class, 'weeklyRanking']);
    Route::get('/rankings/monthly', [GameController::class, 'monthlyRanking']);
    Route::get('/rankings/yearly', [GameController::class, 'yearlyRanking']);
    Route::get('/rankings/history', [GameController::class, 'historyByQuery']);
    Route::get('/rankings/history/{id}', [GameController::class, 'history']);
    Route::get('/rankings/platforms/{platform}', [GameController::class, 'platformRanking']);

    // Jogos
    Route::get('/games', [GameController::class, 'index']);
    Route::get('/games/most-played', [GameController::class, 'mostPlayed']);
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::middleware(['jwt.auth'])->get('/test-auth', function (Request $request) {
    return response()->json([
        'userId' => $request->attributes->get('auth')['id']
    ]);
});
