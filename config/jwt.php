<?php

return [
    'issuer' => env('JWT_ISSUER'),
    'audience' => env('JWT_AUDIENCE'),
    'public_key' => env('JWT_PUBLIC_KEY_PEM'),
    'allow_any_token' => env('JWT_ALLOW_ANY_TOKEN', false),
];
