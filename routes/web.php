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
    $formattedPublicKey = trim($rawPublicKey);

    if (
        (str_starts_with($formattedPublicKey, '"') && str_ends_with($formattedPublicKey, '"')) ||
        (str_starts_with($formattedPublicKey, "'") && str_ends_with($formattedPublicKey, "'"))
    ) {
        $formattedPublicKey = substr($formattedPublicKey, 1, -1);
    }

    $formattedPublicKey = trim(str_replace(['\\r\\n', '\\n', '\\r', "\r\n", "\r"], "\n", $formattedPublicKey));
    $pemType = null;
    $bodyLength = null;

    if (preg_match('/-----BEGIN ([A-Z ]*PUBLIC KEY)-----(.*?)-----END \1-----/s', $formattedPublicKey, $matches)) {
        $pemType = $matches[1];
        $body = preg_replace('/[^A-Za-z0-9+\/=]/', '', $matches[2]);
        $bodyLength = strlen($body);
        $formattedPublicKey = "-----BEGIN {$pemType}-----\n"
            . chunk_split($body, 64, "\n")
            . "-----END {$pemType}-----\n";
    } elseif (!str_contains($formattedPublicKey, '-----BEGIN')) {
        $body = preg_replace('/[^A-Za-z0-9+\/=]/', '', $formattedPublicKey);
        $bodyLength = strlen($body);

        if ($bodyLength > 100) {
            $pemType = 'PUBLIC KEY';
            $formattedPublicKey = "-----BEGIN PUBLIC KEY-----\n"
                . chunk_split($body, 64, "\n")
                . "-----END PUBLIC KEY-----\n";
        }
    }

    while (openssl_error_string() !== false) {
        // Clear stale OpenSSL errors before testing the current key.
    }

    $publicKeyResource = openssl_pkey_get_public($formattedPublicKey);
    $openSslErrors = [];

    while (($error = openssl_error_string()) !== false) {
        $openSslErrors[] = $error;
    }

    return response()->json([
        'raw_key_empty' => $rawPublicKey === '',
        'raw_key_length' => strlen($rawPublicKey),
        'formatted_key_length' => strlen($formattedPublicKey),
        'pem_type' => $pemType,
        'pem_body_length' => $bodyLength,
        'has_begin_marker' => str_contains($rawPublicKey, '-----BEGIN PUBLIC KEY-----'),
        'has_rsa_begin_marker' => str_contains($rawPublicKey, '-----BEGIN RSA PUBLIC KEY-----'),
        'has_end_marker' => str_contains($rawPublicKey, '-----END PUBLIC KEY-----'),
        'has_rsa_end_marker' => str_contains($rawPublicKey, '-----END RSA PUBLIC KEY-----'),
        'openssl_accepted' => $publicKeyResource !== false,
        'openssl_errors' => $openSslErrors,
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
