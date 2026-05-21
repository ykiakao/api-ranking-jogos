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

            [$header, $payload, $signature] = $this->decodeToken($token);

            if (($header['alg'] ?? null) !== 'RS256') {
                return response()->json(['message' => 'Invalid token algorithm'], 401);
            }

            if (!$this->signatureIsValid($token, $signature)) {
                return response()->json(['message' => 'Invalid token signature'], 401);
            }

            if (($payload['iss'] ?? null) !== config('jwt.issuer')) {
                return response()->json(['message' => 'Invalid token issuer'], 401);
            }

            if (!$this->audienceIsValid($payload['aud'] ?? null)) {
                return response()->json(['message' => 'Invalid token audience'], 401);
            }

            if (empty($payload['sub'])) {
                return response()->json(['message' => 'Invalid token subject'], 401);
            }

            if ($this->tokenIsExpired($payload)) {
                return response()->json(['message' => 'Invalid or expired token'], 401);
            }

            $request->attributes->set('auth', [
                'id' => $payload['sub'],
                'token' => $token
            ]);

            return $next($request);

        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
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
        $publicKey = $this->normalizePublicKey((string) config('jwt.public_key'));

        if ($publicKey === '') {
            throw new \RuntimeException('JWT public key is empty');
        }

        $this->flushOpenSslErrors();
        $keyResource = openssl_pkey_get_public($publicKey);

        if ($keyResource === false) {
            throw new \RuntimeException($this->openSslErrorMessage('OpenSSL could not read JWT public key'));
        }

        $result = openssl_verify(
            $header . '.' . $payload,
            $signature,
            $keyResource,
            OPENSSL_ALGO_SHA256
        );

        if ($result === false) {
            throw new \RuntimeException($this->openSslErrorMessage('OpenSSL could not verify JWT signature'));
        }

        return $result === 1;
    }

    private function normalizePublicKey(string $publicKey): string
    {
        $publicKey = trim($publicKey);

        if (
            (str_starts_with($publicKey, '"') && str_ends_with($publicKey, '"')) ||
            (str_starts_with($publicKey, "'") && str_ends_with($publicKey, "'"))
        ) {
            $publicKey = substr($publicKey, 1, -1);
        }

        $publicKey = trim(str_replace(['\\r\\n', '\\n', '\\r', "\r\n", "\r"], "\n", $publicKey));

        if ($publicKey === '') {
            return '';
        }

        if (
            preg_match(
                '/-----BEGIN ([A-Z ]*PUBLIC KEY)-----(.*?)-----END \1-----/s',
                $publicKey,
                $matches
            )
        ) {
            $type = $matches[1];
            $body = preg_replace('/[^A-Za-z0-9+\/=]/', '', $matches[2]);

            if ($body === '') {
                return '';
            }

            return "-----BEGIN {$type}-----\n"
                . chunk_split($body, 64, "\n")
                . "-----END {$type}-----\n";
        }

        if (!str_contains($publicKey, '-----BEGIN')) {
            $body = preg_replace('/[^A-Za-z0-9+\/=]/', '', $publicKey);

            if (strlen($body) > 100) {
                return "-----BEGIN PUBLIC KEY-----\n"
                    . chunk_split($body, 64, "\n")
                    . "-----END PUBLIC KEY-----\n";
            }
        }

        return trim($publicKey) . "\n";
    }

    private function flushOpenSslErrors(): void
    {
        while (openssl_error_string() !== false) {
            // Clear stale OpenSSL errors before reading the next operation result.
        }
    }

    private function openSslErrorMessage(string $fallback): string
    {
        $errors = [];

        while (($error = openssl_error_string()) !== false) {
            $errors[] = $error;
        }

        return $errors === [] ? $fallback : implode(' | ', $errors);
    }

    private function tokenIsExpired(array $payload): bool
    {
        if (!isset($payload['exp']) || !is_numeric($payload['exp'])) {
            return true;
        }

        return time() >= (int) $payload['exp'];
    }

    private function audienceIsValid(mixed $audience): bool
    {
        $expectedAudience = config('jwt.audience');

        if (is_string($audience)) {
            return $audience === $expectedAudience;
        }

        if (is_array($audience)) {
            return in_array($expectedAudience, $audience, true);
        }

        return false;
    }

}
