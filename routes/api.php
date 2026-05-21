<?php

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
    Route::get('/rankings/history/{id}', [GameController::class, 'history']);

    // Jogos
    Route::get('/games/most-played', [GameController::class, 'mostPlayed']);
});

Route::get('/health-check-key', function () {
    $rawPublicKey = (string) config('jwt.public_key');
    $formattedPublicKey = str_replace('\\n', "\n", $rawPublicKey);
    $publicKeyResource = openssl_pkey_get_public($formattedPublicKey);

    return response()->json([
        'raw_key_empty' => $rawPublicKey === '',
        'key_length' => strlen($formattedPublicKey),
        'openssl_accepted' => $publicKeyResource !== false,
        'openssl_error' => openssl_error_string(),
    ]);
});
