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
    } elseif (preg_match('/-----BEGIN ([A-Z ]*PUBLIC KEY)-----(.*)/s', $formattedPublicKey, $matches)) {
        $pemType = $matches[1];
        $bodySource = preg_split('/-----END|END\s+(?:RSA\s+)?PUBLIC\s+KEY/i', $matches[2], 2)[0];
        $body = preg_replace('/[^A-Za-z0-9+\/=]/', '', $bodySource);
        $bodyLength = strlen($body);

        if ($bodyLength > 100) {
            $formattedPublicKey = "-----BEGIN {$pemType}-----\n"
                . chunk_split($body, 64, "\n")
                . "-----END {$pemType}-----\n";
        }
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
    $publicKeyDetails = $publicKeyResource === false ? null : openssl_pkey_get_details($publicKeyResource);
    $publicKeyPem = is_array($publicKeyDetails) ? ($publicKeyDetails['key'] ?? null) : null;

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
        'public_key_fingerprint_sha256' => is_string($publicKeyPem) ? hash('sha256', $publicKeyPem) : null,
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

Route::get('/health-check-token', function (\Illuminate\Http\Request $request) {
    $authHeader = $request->header('Authorization');

    if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        return response()->json([
            'authorization_header_present' => (bool) $authHeader,
            'error' => 'Missing or invalid Bearer token',
        ], 401);
    }

    $token = $matches[1];
    $parts = explode('.', $token);

    if (count($parts) !== 3) {
        return response()->json(['error' => 'Invalid token structure'], 401);
    }

    $decodeBase64Url = function (string $value): string|false {
        $value .= str_repeat('=', (4 - strlen($value) % 4) % 4);

        return base64_decode(strtr($value, '-_', '+/'), true);
    };

    $headerJson = $decodeBase64Url($parts[0]);
    $payloadJson = $decodeBase64Url($parts[1]);
    $signature = $decodeBase64Url($parts[2]);

    if ($headerJson === false || $payloadJson === false || $signature === false) {
        return response()->json(['error' => 'Invalid base64url token segment'], 401);
    }

    $header = json_decode($headerJson, true);
    $payload = json_decode($payloadJson, true);

    if (!is_array($header) || !is_array($payload)) {
        return response()->json(['error' => 'Invalid token JSON'], 401);
    }

    $rawPublicKey = (string) config('jwt.public_key');
    $formattedPublicKey = trim($rawPublicKey);

    if (
        (str_starts_with($formattedPublicKey, '"') && str_ends_with($formattedPublicKey, '"')) ||
        (str_starts_with($formattedPublicKey, "'") && str_ends_with($formattedPublicKey, "'"))
    ) {
        $formattedPublicKey = substr($formattedPublicKey, 1, -1);
    }

    $formattedPublicKey = trim(str_replace(['\\r\\n', '\\n', '\\r', "\r\n", "\r"], "\n", $formattedPublicKey));

    if (preg_match('/-----BEGIN ([A-Z ]*PUBLIC KEY)-----(.*?)-----END \1-----/s', $formattedPublicKey, $keyMatches)) {
        $pemType = $keyMatches[1];
        $body = preg_replace('/[^A-Za-z0-9+\/=]/', '', $keyMatches[2]);
        $formattedPublicKey = "-----BEGIN {$pemType}-----\n"
            . chunk_split($body, 64, "\n")
            . "-----END {$pemType}-----\n";
    } elseif (preg_match('/-----BEGIN ([A-Z ]*PUBLIC KEY)-----(.*)/s', $formattedPublicKey, $keyMatches)) {
        $pemType = $keyMatches[1];
        $bodySource = preg_split('/-----END|END\s+(?:RSA\s+)?PUBLIC\s+KEY/i', $keyMatches[2], 2)[0];
        $body = preg_replace('/[^A-Za-z0-9+\/=]/', '', $bodySource);

        if (strlen($body) > 100) {
            $formattedPublicKey = "-----BEGIN {$pemType}-----\n"
                . chunk_split($body, 64, "\n")
                . "-----END {$pemType}-----\n";
        }
    }

    while (openssl_error_string() !== false) {
        // Clear stale OpenSSL errors before verifying the current token.
    }

    $publicKeyResource = openssl_pkey_get_public($formattedPublicKey);
    $publicKeyDetails = $publicKeyResource === false ? null : openssl_pkey_get_details($publicKeyResource);
    $publicKeyPem = is_array($publicKeyDetails) ? ($publicKeyDetails['key'] ?? null) : null;
    $signatureResult = $publicKeyResource === false
        ? false
        : openssl_verify($parts[0] . '.' . $parts[1], $signature, $publicKeyResource, OPENSSL_ALGO_SHA256);
    $openSslErrors = [];

    while (($error = openssl_error_string()) !== false) {
        $openSslErrors[] = $error;
    }

    return response()->json([
        'token_header' => [
            'alg' => $header['alg'] ?? null,
            'typ' => $header['typ'] ?? null,
        ],
        'token_claims' => [
            'iss' => $payload['iss'] ?? null,
            'aud' => $payload['aud'] ?? null,
            'sub_present' => !empty($payload['sub']),
            'exp' => $payload['exp'] ?? null,
            'expired' => isset($payload['exp']) && is_numeric($payload['exp'])
                ? time() >= (int) $payload['exp']
                : null,
        ],
        'expected' => [
            'alg' => 'RS256',
            'iss' => config('jwt.issuer'),
            'aud' => config('jwt.audience'),
        ],
        'checks' => [
            'public_key_loaded' => $publicKeyResource !== false,
            'public_key_fingerprint_sha256' => is_string($publicKeyPem) ? hash('sha256', $publicKeyPem) : null,
            'signature_bytes' => strlen($signature),
            'signature_valid' => $signatureResult === 1,
            'signature_result' => $signatureResult,
            'issuer_valid' => ($payload['iss'] ?? null) === config('jwt.issuer'),
            'audience_valid' => is_array($payload['aud'] ?? null)
                ? in_array(config('jwt.audience'), $payload['aud'], true)
                : ($payload['aud'] ?? null) === config('jwt.audience'),
        ],
        'openssl_errors' => $openSslErrors,
    ]);
});
