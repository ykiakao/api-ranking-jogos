<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GameRankingApiTest extends TestCase
{
    use RefreshDatabase;

    private string $jwt;
    private string $privateKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configureJwt();
        $this->seedGames();
    }

    public function test_rankings_require_jwt_authentication(): void
    {
        $this->getJson('/api/v1/rankings/weekly')
            ->assertUnauthorized()
            ->assertJson(['message' => 'Missing Authorization header']);
    }

    public function test_weekly_monthly_yearly_and_most_played_rankings_return_top_ten_in_expected_order(): void
    {
        $this->getJsonWithJwt('/api/v1/rankings/weekly')
            ->assertOk()
            ->assertJsonCount(10)
            ->assertJsonPath('0.name', 'Game 12')
            ->assertJsonPath('9.name', 'Game 3');

        $this->getJsonWithJwt('/api/v1/rankings/monthly')
            ->assertOk()
            ->assertJsonCount(10)
            ->assertJsonPath('0.name', 'Game 12')
            ->assertJsonPath('9.name', 'Game 3');

        $this->getJsonWithJwt('/api/v1/rankings/yearly')
            ->assertOk()
            ->assertJsonCount(10)
            ->assertJsonPath('0.name', 'Game 12')
            ->assertJsonPath('9.name', 'Game 3');

        $this->getJsonWithJwt('/api/v1/games/most-played')
            ->assertOk()
            ->assertJsonCount(10)
            ->assertJsonPath('0.name', 'Game 12')
            ->assertJsonPath('9.name', 'Game 3');
    }

    public function test_history_returns_score_evolution_for_a_game(): void
    {
        $this->getJsonWithJwt('/api/v1/rankings/history/5')
            ->assertOk()
            ->assertJsonPath('game', 'Game 5')
            ->assertJsonPath('history.0.period', 'Semana 1')
            ->assertJsonPath('history.0.points', 500)
            ->assertJsonPath('history.1.period', 'Mês Atual')
            ->assertJsonPath('history.1.points', 5000)
            ->assertJsonPath('history.2.period', 'Ano Atual')
            ->assertJsonPath('history.2.points', 50000);
    }

    public function test_accepts_token_with_audience_array_containing_expected_audience(): void
    {
        $this->jwt = $this->makeJwt($this->privateKey, ['other-api', 'ranking-api']);

        $this->getJsonWithJwt('/api/v1/games/most-played')
            ->assertOk()
            ->assertJsonCount(10);
    }

    public function test_rejects_generic_bearer_token(): void
    {
        $this->withHeader('Authorization', 'Bearer token-do-front')
            ->getJson('/api/v1/rankings/weekly')
            ->assertUnauthorized()
            ->assertJson(['message' => 'Invalid or expired token']);
    }

    private function getJsonWithJwt(string $uri)
    {
        return $this->withHeader('Authorization', 'Bearer '.$this->jwt)
            ->getJson($uri);
    }

    private function configureJwt(): void
    {
        $key = openssl_pkey_new([
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'private_key_bits' => 2048,
        ]);

        openssl_pkey_export($key, $privateKey);
        $this->privateKey = $privateKey;
        $publicKey = openssl_pkey_get_details($key)['key'];

        config([
            'jwt.issuer' => 'gameverse-auth',
            'jwt.audience' => 'ranking-api',
            'jwt.public_key' => $publicKey,
        ]);

        $this->jwt = $this->makeJwt($privateKey);
    }

    private function makeJwt(string $privateKey, string|array $audience = 'ranking-api'): string
    {
        $encode = fn (string $value): string => rtrim(strtr(base64_encode($value), '+/', '-_'), '=');

        $header = $encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $payload = $encode(json_encode([
            'iss' => 'gameverse-auth',
            'aud' => $audience,
            'sub' => 'consumer-project',
            'iat' => time(),
            'exp' => time() + 3600,
        ]));

        $data = $header.'.'.$payload;
        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        return $data.'.'.$encode($signature);
    }

    private function seedGames(): void
    {
        $now = now();

        for ($id = 1; $id <= 12; $id++) {
            DB::table('games')->insert([
                'id' => $id,
                'name' => 'Game '.$id,
                'platform' => $id % 2 === 0 ? 'Steam' : 'Riot Launcher',
                'active_players' => $id * 1000,
                'weekly_points' => $id * 100,
                'monthly_points' => $id * 1000,
                'yearly_points' => $id * 10000,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
