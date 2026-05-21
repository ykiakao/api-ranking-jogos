<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'api-ranking-jogos',
    ]);
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
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
