<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JwtAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $authHeader = $request->header('Authorization');

            if (!$authHeader) {
                return response()->json(['message' => 'Missing Authorization header'], 401);
            }

            if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                return response()->json(['message' => 'Invalid token format'], 401);
            }

            $token = $matches[1];

            if (config('jwt.allow_any_token')) {
                $request->attributes->set('auth', [
                    'id' => $this->subjectFromUnverifiedToken($token),
                    'token' => $token
                ]);

                return $next($request);
            }

            [$header, $payload, $signature] = $this->decodeToken($token);

            if (($header['alg'] ?? null) !== 'RS256') {
                return response()->json(['message' => 'Invalid token algorithm'], 401);
            }

            if (
                !$this->signatureIsValid($token, $signature) ||
                ($payload['iss'] ?? null) !== config('jwt.issuer') ||
                ($payload['aud'] ?? null) !== config('jwt.audience') ||
                empty($payload['sub']) ||
                $this->tokenIsExpired($payload)
            ) {
                return response()->json(['message' => 'Invalid token'], 401);
            }

            $request->attributes->set('auth', [
                'id' => $payload['sub'],
                'token' => $token
            ]);

            return $next($request);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }
    }

    private function decodeToken(string $token): array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            throw new \InvalidArgumentException('Invalid token structure');
        }

        return [
            $this->base64UrlDecodeJson($parts[0]),
            $this->base64UrlDecodeJson($parts[1]),
            $this->base64UrlDecode($parts[2]),
        ];
    }

    private function base64UrlDecodeJson(string $value): array
    {
        $decoded = json_decode($this->base64UrlDecode($value), true);

        if (!is_array($decoded)) {
            throw new \InvalidArgumentException('Invalid token payload');
        }

        return $decoded;
    }

    private function base64UrlDecode(string $value): string
    {
        $value .= str_repeat('=', (4 - strlen($value) % 4) % 4);
        $decoded = base64_decode(strtr($value, '-_', '+/'), true);

        if ($decoded === false) {
            throw new \InvalidArgumentException('Invalid base64url value');
        }

        return $decoded;
    }

    private function signatureIsValid(string $token, string $signature): bool
    {
        [$header, $payload] = explode('.', $token, 3);
        $publicKey = str_replace('\\n', "\n", (string) config('jwt.public_key'));

        if ($publicKey === '') {
            return false;
        }

        return openssl_verify(
            $header . '.' . $payload,
            $signature,
            $publicKey,
            OPENSSL_ALGO_SHA256
        ) === 1;
    }

    private function tokenIsExpired(array $payload): bool
    {
        if (!isset($payload['exp']) || !is_numeric($payload['exp'])) {
            return true;
        }

        return time() >= (int) $payload['exp'];
    }

    private function subjectFromUnverifiedToken(string $token): string
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return 'external-consumer';
        }

        try {
            $payload = $this->base64UrlDecodeJson($parts[1]);
            return (string) ($payload['sub'] ?? 'external-consumer');
        } catch (\Exception $e) {
            return 'external-consumer';
        }
    }
}
