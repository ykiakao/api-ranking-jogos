<?php

return [
    'issuer' => env('JWT_ISSUER'),
    'audience' => env('JWT_AUDIENCE'),
    'public_key' => env('JWT_PUBLIC_KEY_PEM'),
];
