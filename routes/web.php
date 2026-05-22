<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    $baseUrl = request()->root();
    $routes = [
        ['name' => 'base', 'method' => 'GET', 'path' => '/', 'auth' => 'Sem JWT', 'type' => 'open'],
        ['name' => 'health', 'method' => 'GET', 'path' => '/health', 'auth' => 'Sem JWT', 'type' => 'open'],
        ['name' => 'api_health', 'method' => 'GET', 'path' => '/api/health', 'auth' => 'Sem JWT', 'type' => 'open'],
        ['name' => 'health_check_key', 'method' => 'GET', 'path' => '/api/health-check-key', 'auth' => 'Sem JWT', 'type' => 'open'],
        ['name' => 'health_check_db', 'method' => 'GET', 'path' => '/api/health-check-db', 'auth' => 'Sem JWT', 'type' => 'open'],
        ['name' => 'health_check_token', 'method' => 'GET', 'path' => '/api/health-check-token', 'auth' => 'Bearer para diagnostico', 'type' => 'diagnostic'],
        ['name' => 'ranking_semanal', 'method' => 'GET', 'path' => '/api/v1/rankings/weekly', 'auth' => 'JWT obrigatorio', 'type' => 'jwt'],
        ['name' => 'ranking_mensal', 'method' => 'GET', 'path' => '/api/v1/rankings/monthly', 'auth' => 'JWT obrigatorio', 'type' => 'jwt'],
        ['name' => 'ranking_anual', 'method' => 'GET', 'path' => '/api/v1/rankings/yearly', 'auth' => 'JWT obrigatorio', 'type' => 'jwt'],
        ['name' => 'ranking_plataforma', 'method' => 'GET', 'path' => '/api/v1/rankings/platforms/Steam', 'auth' => 'JWT obrigatorio', 'type' => 'jwt'],
        ['name' => 'historico_jogador', 'method' => 'GET', 'path' => '/api/v1/rankings/history/1', 'auth' => 'JWT obrigatorio', 'type' => 'jwt'],
        ['name' => 'mais_jogados', 'method' => 'GET', 'path' => '/api/v1/games/most-played', 'auth' => 'JWT obrigatorio', 'type' => 'jwt'],
    ];

    $cards = collect($routes)->map(function (array $route) use ($baseUrl) {
        $url = $baseUrl . ($route['path'] === '/' ? '' : $route['path']);
        $authClass = match ($route['type']) {
            'jwt' => 'auth-jwt',
            'diagnostic' => 'auth-diagnostic',
            default => 'auth-open',
        };

        return sprintf(
            '<article class="route-card">
                <div class="route-heading">
                    <span class="method">%s</span>
                    <h2>%s</h2>
                </div>
                <div class="auth %s">%s</div>
                <a href="%s" target="_blank" rel="noreferrer">%s</a>
                <div class="actions">
                    <button type="button" data-action="open" data-url="%s">Abrir</button>
                    <button type="button" data-action="copy" data-url="%s">Copiar</button>
                    <button type="button" data-action="request" data-auth="%s" data-url="%s">Testar</button>
                </div>
            </article>',
            e($route['method']),
            e($route['name']),
            $authClass,
            e($route['auth']),
            e($url),
            e($url),
            e($url),
            e($url),
            e($route['type']),
            e($url),
        );
    })->implode('');

    return response(<<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>API Ranking Jogos</title>
    <style>
        :root {
            color-scheme: dark;
            --bg: #111225;
            --panel: #17213d;
            --panel-border: #27396a;
            --text: #f5f7ff;
            --muted: #aab4d4;
            --gold: #ffd84a;
            --green: #2bd071;
            --red: #ff6b6b;
            --blue: #5aa7ff;
            --code: #080b18;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top, #1d2040 0, var(--bg) 48rem);
            color: var(--text);
        }

        main {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            padding: 40px 0 48px;
        }

        header {
            text-align: center;
            margin-bottom: 32px;
        }

        h1 {
            margin: 0;
            color: var(--gold);
            font-size: clamp(2rem, 6vw, 3.4rem);
            letter-spacing: 0;
            font-weight: 900;
        }

        header p {
            margin: 10px 0 0;
            color: var(--muted);
            font-size: 1rem;
        }

        .status {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 14px;
            width: min(560px, 100%);
            margin: 28px auto 34px;
            padding: 14px 18px;
            border: 1px solid var(--panel-border);
            border-radius: 8px;
            background: rgba(23, 33, 61, 0.86);
        }

        .token-panel {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            width: min(860px, 100%);
            margin: 0 auto 28px;
            padding: 16px;
            border: 1px solid var(--panel-border);
            border-radius: 8px;
            background: rgba(23, 33, 61, 0.82);
        }

        .token-panel label {
            grid-column: 1 / -1;
            color: var(--muted);
            font-size: 0.92rem;
            font-weight: 700;
        }

        .token-panel input {
            min-width: 0;
            width: 100%;
            height: 42px;
            padding: 0 12px;
            border: 1px solid var(--panel-border);
            border-radius: 6px;
            background: var(--code);
            color: var(--text);
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        }

        button {
            min-height: 38px;
            padding: 0 14px;
            border: 1px solid rgba(255, 216, 74, 0.42);
            border-radius: 6px;
            background: rgba(255, 216, 74, 0.12);
            color: var(--gold);
            font: inherit;
            font-weight: 800;
            cursor: pointer;
        }

        button:hover {
            background: rgba(255, 216, 74, 0.2);
        }

        .status strong {
            color: var(--gold);
        }

        .dot {
            width: 12px;
            height: 12px;
            margin-top: 5px;
            border-radius: 999px;
            background: var(--green);
            box-shadow: 0 0 14px var(--green);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .route-card {
            min-width: 0;
            padding: 20px;
            border: 1px solid var(--panel-border);
            border-radius: 8px;
            background: rgba(23, 33, 61, 0.92);
        }

        .route-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .method {
            flex: 0 0 auto;
            padding: 5px 12px;
            border-radius: 4px;
            background: #127b3a;
            color: #d9ffe7;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: 0.78rem;
            font-weight: 800;
        }

        h2 {
            min-width: 0;
            margin: 0;
            color: var(--gold);
            font-size: 1.08rem;
            overflow-wrap: anywhere;
        }

        .auth {
            display: inline-flex;
            margin-bottom: 12px;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .auth-open {
            color: #d9ffe7;
            background: rgba(43, 208, 113, 0.16);
        }

        .auth-jwt {
            color: #ffe1e1;
            background: rgba(255, 107, 107, 0.16);
        }

        .auth-diagnostic {
            color: #dcecff;
            background: rgba(90, 167, 255, 0.16);
        }

        a {
            display: block;
            width: 100%;
            padding: 12px;
            border-left: 3px solid var(--gold);
            border-radius: 5px;
            background: var(--code);
            color: #dfe7ff;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: 0.84rem;
            line-height: 1.35;
            text-decoration: none;
            overflow-wrap: anywhere;
        }

        a:hover {
            color: #ffffff;
            outline: 1px solid var(--gold);
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 12px;
        }

        .actions button {
            flex: 1 1 108px;
        }

        .result {
            display: none;
            margin-top: 28px;
            padding: 18px;
            border: 1px solid var(--panel-border);
            border-radius: 8px;
            background: rgba(8, 11, 24, 0.9);
        }

        .result.visible {
            display: block;
        }

        .result-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
            color: var(--muted);
        }

        pre {
            max-height: 420px;
            margin: 0;
            overflow: auto;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            color: #e8edff;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: 0.88rem;
            line-height: 1.55;
        }

        footer {
            margin-top: 34px;
            color: #697399;
            text-align: center;
            font-size: 0.82rem;
        }

        @media (max-width: 760px) {
            main {
                width: min(100% - 20px, 1120px);
                padding-top: 28px;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .token-panel {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main>
        <header>
            <h1>API RANKING JOGOS</h1>
            <p>Menu de navegacao - rotas GET disponiveis</p>
        </header>

        <section class="status" aria-label="Status da API">
            <span class="dot" aria-hidden="true"></span>
            <span>Status: <strong>ok</strong></span>
            <span>Service: <strong>api-ranking-jogos</strong></span>
        </section>

        <section class="token-panel" aria-label="Token JWT">
            <label for="jwt-token">JWT para testar rotas privadas</label>
            <input id="jwt-token" type="password" autocomplete="off" placeholder="Cole somente o token, sem Bearer">
            <button type="button" id="save-token">Salvar token</button>
        </section>

        <section class="grid">
            {$cards}
        </section>

        <section id="result" class="result" aria-live="polite">
            <div class="result-header">
                <strong id="result-title">Resposta</strong>
                <span id="result-status"></span>
            </div>
            <pre id="result-body"></pre>
        </section>

        <footer>api-ranking-jogos - Railway</footer>
    </main>
    <script>
        const tokenInput = document.getElementById('jwt-token');
        const saveToken = document.getElementById('save-token');
        const result = document.getElementById('result');
        const resultTitle = document.getElementById('result-title');
        const resultStatus = document.getElementById('result-status');
        const resultBody = document.getElementById('result-body');

        tokenInput.value = localStorage.getItem('rankingJwtToken') || '';

        saveToken.addEventListener('click', () => {
            localStorage.setItem('rankingJwtToken', tokenInput.value.trim());
            saveToken.textContent = 'Salvo';
            setTimeout(() => saveToken.textContent = 'Salvar token', 1400);
        });

        function showResult(title, status, body) {
            result.classList.add('visible');
            resultTitle.textContent = title;
            resultStatus.textContent = status;
            resultBody.textContent = body;
            result.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        async function copyText(text) {
            await navigator.clipboard.writeText(text);
        }

        document.addEventListener('click', async (event) => {
            const button = event.target.closest('button[data-action]');

            if (!button) {
                return;
            }

            const url = button.dataset.url;
            const action = button.dataset.action;

            if (action === 'open') {
                window.open(url, '_blank', 'noreferrer');
                return;
            }

            if (action === 'copy') {
                await copyText(url);
                const previous = button.textContent;
                button.textContent = 'Copiado';
                setTimeout(() => button.textContent = previous, 1200);
                return;
            }

            const headers = { Accept: 'application/json' };
            const authType = button.dataset.auth;
            const token = tokenInput.value.trim();

            if ((authType === 'jwt' || authType === 'diagnostic') && token) {
                headers.Authorization = 'Bearer ' + token;
            }

            if ((authType === 'jwt' || authType === 'diagnostic') && !token) {
                showResult('Token ausente', '401 local', 'Cole um JWT no campo acima para testar esta rota.');
                return;
            }

            try {
                showResult('Carregando', '...', '');
                const response = await fetch(url, { headers });
                const contentType = response.headers.get('content-type') || '';
                const body = contentType.includes('application/json')
                    ? JSON.stringify(await response.json(), null, 2)
                    : await response.text();

                showResult(url, response.status + ' ' + response.statusText, body);
            } catch (error) {
                showResult(url, 'Erro', error.message);
            }
        });
    </script>
</body>
</html>
HTML);
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
