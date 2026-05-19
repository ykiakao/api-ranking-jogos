<?php

namespace Tests\Feature;

use Symfony\Component\Yaml\Yaml;
use Tests\TestCase;

class DocumentationRoutesTest extends TestCase
{
    public function test_openapi_documentation_matches_public_api_routes(): void
    {
        $response = $this->get('/docs.openapi');

        $response->assertOk();

        $openApi = Yaml::parse(file_get_contents(storage_path('app/scribe/openapi.yaml')));
        $paths = array_keys($openApi['paths']);

        $this->assertSame([
            '/api/v1/rankings/weekly',
            '/api/v1/rankings/monthly',
            '/api/v1/rankings/yearly',
            '/api/v1/rankings/history',
            '/api/v1/rankings/history/{id}',
            '/api/v1/rankings/platforms/{platform}',
            '/api/v1/games',
            '/api/v1/games/most-played',
        ], $paths);

        $this->assertNotContains('/api/test-auth', $paths);
        $this->assertNotContains('/api/health', $paths);
    }
}
