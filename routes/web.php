<?php

use Illuminate\Support\Facades\Route;

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
