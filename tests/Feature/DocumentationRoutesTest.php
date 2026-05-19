<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Symfony\Component\Yaml\Yaml;
use Tests\TestCase;

class DocumentationRoutesTest extends TestCase
{
    public function test_openapi_documentation_matches_public_api_routes(): void
    {
        $openApi = Yaml::parse(file_get_contents(storage_path('app/scribe/openapi.yaml')));
        $paths = array_keys($openApi['paths']);

        $this->assertSame([
            '/api/v1/rankings/weekly',
            '/api/v1/rankings/monthly',
            '/api/v1/rankings/yearly',
            '/api/v1/rankings/history/{id}',
            '/api/v1/games/most-played',
        ], $paths);

        $this->assertNotContains('/api/test-auth', $paths);
        $this->assertNotContains('/api/health', $paths);
        $this->assertNotContains('/api/v1/games', $paths);
        $this->assertNotContains('/api/v1/rankings/platforms/{platform}', $paths);
        $this->assertNotContains('/api/v1/rankings/history', $paths);
    }

    public function test_registered_api_v1_routes_are_only_the_requested_endpoints(): void
    {
        $routes = collect(Route::getRoutes())
            ->filter(fn ($route) => str_starts_with($route->uri(), 'api/v1/'))
            ->map(fn ($route) => $route->uri())
            ->values()
            ->all();

        $this->assertSame([
            'api/v1/rankings/weekly',
            'api/v1/rankings/monthly',
            'api/v1/rankings/yearly',
            'api/v1/rankings/history/{id}',
            'api/v1/games/most-played',
        ], $routes);
    }
}
