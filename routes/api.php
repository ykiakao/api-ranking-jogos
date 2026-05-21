<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
    Route::get('/rankings/platforms/{platform}', [GameController::class, 'platformRanking']);

    // Jogos
    Route::get('/games/most-played', [GameController::class, 'mostPlayed']);
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::get('/health-check-key', function () {
    $rawPublicKey = (string) config('jwt.public_key');
    $formattedPublicKey = trim(str_replace(['\\r\\n', '\\n', '\\r', "\r\n", "\r"], "\n", $rawPublicKey));

    if (
        preg_match(
            '/-----BEGIN PUBLIC KEY-----(.*?)-----END PUBLIC KEY-----/s',
            $formattedPublicKey,
            $matches
        )
    ) {
        $body = preg_replace('/\s+/', '', $matches[1]);
        $formattedPublicKey = "-----BEGIN PUBLIC KEY-----\n"
            . chunk_split($body, 64, "\n")
            . "-----END PUBLIC KEY-----\n";
    }

    $publicKeyResource = openssl_pkey_get_public($formattedPublicKey);

    return response()->json([
        'raw_key_empty' => $rawPublicKey === '',
        'raw_key_length' => strlen($rawPublicKey),
        'formatted_key_length' => strlen($formattedPublicKey),
        'has_begin_marker' => str_contains($rawPublicKey, '-----BEGIN PUBLIC KEY-----'),
        'has_end_marker' => str_contains($rawPublicKey, '-----END PUBLIC KEY-----'),
        'openssl_accepted' => $publicKeyResource !== false,
        'openssl_error' => openssl_error_string(),
    ]);
});

Route::get('/health-check-db', function () {
    try {
        $hasGamesTable = Schema::hasTable('games');

        return response()->json([
            'connection' => config('database.default'),
            'driver' => DB::connection()->getDriverName(),
            'database' => DB::connection()->getDatabaseName(),
            'games_table_exists' => $hasGamesTable,
            'games_count' => $hasGamesTable ? DB::table('games')->count() : null,
        ]);
    } catch (Throwable $e) {
        return response()->json([
            'connection' => config('database.default'),
            'error' => $e->getMessage(),
        ], 500);
    }
});
