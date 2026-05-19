<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Game Ranking API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://127.0.0.1:8000";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.9.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.9.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-rankings" class="tocify-header">
                <li class="tocify-item level-1" data-unique="rankings">
                    <a href="#rankings">Rankings</a>
                </li>
                                    <ul id="tocify-subheader-rankings" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="rankings-GETapi-v1-rankings-weekly">
                                <a href="#rankings-GETapi-v1-rankings-weekly">Top semanal</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rankings-GETapi-v1-rankings-monthly">
                                <a href="#rankings-GETapi-v1-rankings-monthly">Top mensal</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rankings-GETapi-v1-rankings-yearly">
                                <a href="#rankings-GETapi-v1-rankings-yearly">Top anual</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rankings-GETapi-v1-rankings-history">
                                <a href="#rankings-GETapi-v1-rankings-history">Histórico de ranking por query string</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rankings-GETapi-v1-rankings-history--id-">
                                <a href="#rankings-GETapi-v1-rankings-history--id-">Histórico de ranking</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rankings-GETapi-v1-rankings-platforms--platform-">
                                <a href="#rankings-GETapi-v1-rankings-platforms--platform-">Ranking por Plataforma</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rankings-GETapi-v1-games">
                                <a href="#rankings-GETapi-v1-games">Listar jogos</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rankings-GETapi-v1-games-most-played">
                                <a href="#rankings-GETapi-v1-games-most-played">Jogos mais jogados</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: May 19, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>Microsserviço de rankings e métricas de jogos para integração com o ecossistema GameVerse.</p>
<aside>
    <strong>Base URL</strong>: <code>http://127.0.0.1:8000</code>
</aside>
<pre><code>Esta API expõe rankings semanais, mensais e anuais, jogos mais jogados, histórico de pontuação e filtros por plataforma.

&lt;aside&gt;Use os exemplos da documentação para demonstrar como o frontend ou outros microsserviços podem consumir os dados de ranking.&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>To authenticate requests, include an <strong><code>Authorization</code></strong> header with the value <strong><code>"Bearer {YOUR_JWT_TOKEN}"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<p>Use um token JWT RS256 emitido pelo serviço de autenticação integrado ao GameVerse.</p>

        <h1 id="rankings">Rankings</h1>

    

                                <h2 id="rankings-GETapi-v1-rankings-weekly">Top semanal</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Retorna o ranking dos jogos com melhor desempenho na última semana.</p>

<span id="example-requests-GETapi-v1-rankings-weekly">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/rankings/weekly" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/rankings/weekly"
);

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-rankings-weekly">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 59
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 11,
        &quot;name&quot;: &quot;Apex Legends&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 218457,
        &quot;weekly_points&quot;: 945,
        &quot;monthly_points&quot;: 8776,
        &quot;yearly_points&quot;: 56526,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 12,
        &quot;name&quot;: &quot;Call of Duty: Warzone&quot;,
        &quot;platform&quot;: &quot;Battle.net&quot;,
        &quot;active_players&quot;: 243114,
        &quot;weekly_points&quot;: 877,
        &quot;monthly_points&quot;: 2426,
        &quot;yearly_points&quot;: 36655,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 14,
        &quot;name&quot;: &quot;Cyberpunk 2077&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 1161973,
        &quot;weekly_points&quot;: 874,
        &quot;monthly_points&quot;: 4853,
        &quot;yearly_points&quot;: 27988,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 8,
        &quot;name&quot;: &quot;EA SPORTS FC 24&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 398998,
        &quot;weekly_points&quot;: 872,
        &quot;monthly_points&quot;: 5333,
        &quot;yearly_points&quot;: 81468,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 10,
        &quot;name&quot;: &quot;League of Legends&quot;,
        &quot;platform&quot;: &quot;Riot Launcher&quot;,
        &quot;active_players&quot;: 1166370,
        &quot;weekly_points&quot;: 786,
        &quot;monthly_points&quot;: 4506,
        &quot;yearly_points&quot;: 21445,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 9,
        &quot;name&quot;: &quot;Roblox&quot;,
        &quot;platform&quot;: &quot;Multiplataforma&quot;,
        &quot;active_players&quot;: 991415,
        &quot;weekly_points&quot;: 770,
        &quot;monthly_points&quot;: 2080,
        &quot;yearly_points&quot;: 22209,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Counter-Strike 2&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 1086549,
        &quot;weekly_points&quot;: 729,
        &quot;monthly_points&quot;: 1215,
        &quot;yearly_points&quot;: 71182,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 15,
        &quot;name&quot;: &quot;Stardew Valley&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 1117483,
        &quot;weekly_points&quot;: 702,
        &quot;monthly_points&quot;: 7545,
        &quot;yearly_points&quot;: 42912,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 2,
        &quot;name&quot;: &quot;Elden Ring&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 715531,
        &quot;weekly_points&quot;: 697,
        &quot;monthly_points&quot;: 7369,
        &quot;yearly_points&quot;: 44291,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 4,
        &quot;name&quot;: &quot;Helldivers 2&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 217823,
        &quot;weekly_points&quot;: 617,
        &quot;monthly_points&quot;: 5232,
        &quot;yearly_points&quot;: 24531,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-rankings-weekly" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-rankings-weekly"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-rankings-weekly"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-rankings-weekly" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-rankings-weekly">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-rankings-weekly" data-method="GET"
      data-path="api/v1/rankings/weekly"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-rankings-weekly', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-rankings-weekly"
                    onclick="tryItOut('GETapi-v1-rankings-weekly');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-rankings-weekly"
                    onclick="cancelTryOut('GETapi-v1-rankings-weekly');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-rankings-weekly"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/rankings/weekly</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-rankings-weekly"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-rankings-weekly"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-rankings-weekly"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="rankings-GETapi-v1-rankings-monthly">Top mensal</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Retorna o ranking dos jogos com melhor desempenho no último mês.</p>

<span id="example-requests-GETapi-v1-rankings-monthly">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/rankings/monthly" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/rankings/monthly"
);

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-rankings-monthly">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 58
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 13,
        &quot;name&quot;: &quot;Minecraft&quot;,
        &quot;platform&quot;: &quot;Multiplataforma&quot;,
        &quot;active_players&quot;: 242066,
        &quot;weekly_points&quot;: 184,
        &quot;monthly_points&quot;: 9278,
        &quot;yearly_points&quot;: 33053,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 11,
        &quot;name&quot;: &quot;Apex Legends&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 218457,
        &quot;weekly_points&quot;: 945,
        &quot;monthly_points&quot;: 8776,
        &quot;yearly_points&quot;: 56526,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 15,
        &quot;name&quot;: &quot;Stardew Valley&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 1117483,
        &quot;weekly_points&quot;: 702,
        &quot;monthly_points&quot;: 7545,
        &quot;yearly_points&quot;: 42912,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 2,
        &quot;name&quot;: &quot;Elden Ring&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 715531,
        &quot;weekly_points&quot;: 697,
        &quot;monthly_points&quot;: 7369,
        &quot;yearly_points&quot;: 44291,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 6,
        &quot;name&quot;: &quot;Fortnite&quot;,
        &quot;platform&quot;: &quot;Epic Games&quot;,
        &quot;active_players&quot;: 1091171,
        &quot;weekly_points&quot;: 611,
        &quot;monthly_points&quot;: 5678,
        &quot;yearly_points&quot;: 96832,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 8,
        &quot;name&quot;: &quot;EA SPORTS FC 24&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 398998,
        &quot;weekly_points&quot;: 872,
        &quot;monthly_points&quot;: 5333,
        &quot;yearly_points&quot;: 81468,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 4,
        &quot;name&quot;: &quot;Helldivers 2&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 217823,
        &quot;weekly_points&quot;: 617,
        &quot;monthly_points&quot;: 5232,
        &quot;yearly_points&quot;: 24531,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 14,
        &quot;name&quot;: &quot;Cyberpunk 2077&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 1161973,
        &quot;weekly_points&quot;: 874,
        &quot;monthly_points&quot;: 4853,
        &quot;yearly_points&quot;: 27988,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 10,
        &quot;name&quot;: &quot;League of Legends&quot;,
        &quot;platform&quot;: &quot;Riot Launcher&quot;,
        &quot;active_players&quot;: 1166370,
        &quot;weekly_points&quot;: 786,
        &quot;monthly_points&quot;: 4506,
        &quot;yearly_points&quot;: 21445,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 5,
        &quot;name&quot;: &quot;Baldur&#039;s Gate 3&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 296988,
        &quot;weekly_points&quot;: 352,
        &quot;monthly_points&quot;: 3595,
        &quot;yearly_points&quot;: 62260,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-rankings-monthly" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-rankings-monthly"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-rankings-monthly"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-rankings-monthly" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-rankings-monthly">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-rankings-monthly" data-method="GET"
      data-path="api/v1/rankings/monthly"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-rankings-monthly', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-rankings-monthly"
                    onclick="tryItOut('GETapi-v1-rankings-monthly');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-rankings-monthly"
                    onclick="cancelTryOut('GETapi-v1-rankings-monthly');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-rankings-monthly"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/rankings/monthly</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-rankings-monthly"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-rankings-monthly"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-rankings-monthly"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="rankings-GETapi-v1-rankings-yearly">Top anual</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Retorna o ranking dos jogos com melhor desempenho no último ano.</p>

<span id="example-requests-GETapi-v1-rankings-yearly">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/rankings/yearly" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/rankings/yearly"
);

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-rankings-yearly">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 57
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 6,
        &quot;name&quot;: &quot;Fortnite&quot;,
        &quot;platform&quot;: &quot;Epic Games&quot;,
        &quot;active_players&quot;: 1091171,
        &quot;weekly_points&quot;: 611,
        &quot;monthly_points&quot;: 5678,
        &quot;yearly_points&quot;: 96832,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 8,
        &quot;name&quot;: &quot;EA SPORTS FC 24&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 398998,
        &quot;weekly_points&quot;: 872,
        &quot;monthly_points&quot;: 5333,
        &quot;yearly_points&quot;: 81468,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Counter-Strike 2&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 1086549,
        &quot;weekly_points&quot;: 729,
        &quot;monthly_points&quot;: 1215,
        &quot;yearly_points&quot;: 71182,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 7,
        &quot;name&quot;: &quot;Grand Theft Auto V&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 262363,
        &quot;weekly_points&quot;: 199,
        &quot;monthly_points&quot;: 2257,
        &quot;yearly_points&quot;: 62350,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 5,
        &quot;name&quot;: &quot;Baldur&#039;s Gate 3&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 296988,
        &quot;weekly_points&quot;: 352,
        &quot;monthly_points&quot;: 3595,
        &quot;yearly_points&quot;: 62260,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 3,
        &quot;name&quot;: &quot;Valorant&quot;,
        &quot;platform&quot;: &quot;Riot Launcher&quot;,
        &quot;active_players&quot;: 821498,
        &quot;weekly_points&quot;: 241,
        &quot;monthly_points&quot;: 1030,
        &quot;yearly_points&quot;: 57266,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 11,
        &quot;name&quot;: &quot;Apex Legends&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 218457,
        &quot;weekly_points&quot;: 945,
        &quot;monthly_points&quot;: 8776,
        &quot;yearly_points&quot;: 56526,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 2,
        &quot;name&quot;: &quot;Elden Ring&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 715531,
        &quot;weekly_points&quot;: 697,
        &quot;monthly_points&quot;: 7369,
        &quot;yearly_points&quot;: 44291,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 15,
        &quot;name&quot;: &quot;Stardew Valley&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 1117483,
        &quot;weekly_points&quot;: 702,
        &quot;monthly_points&quot;: 7545,
        &quot;yearly_points&quot;: 42912,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 12,
        &quot;name&quot;: &quot;Call of Duty: Warzone&quot;,
        &quot;platform&quot;: &quot;Battle.net&quot;,
        &quot;active_players&quot;: 243114,
        &quot;weekly_points&quot;: 877,
        &quot;monthly_points&quot;: 2426,
        &quot;yearly_points&quot;: 36655,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-rankings-yearly" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-rankings-yearly"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-rankings-yearly"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-rankings-yearly" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-rankings-yearly">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-rankings-yearly" data-method="GET"
      data-path="api/v1/rankings/yearly"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-rankings-yearly', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-rankings-yearly"
                    onclick="tryItOut('GETapi-v1-rankings-yearly');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-rankings-yearly"
                    onclick="cancelTryOut('GETapi-v1-rankings-yearly');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-rankings-yearly"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/rankings/yearly</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-rankings-yearly"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-rankings-yearly"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-rankings-yearly"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="rankings-GETapi-v1-rankings-history">Histórico de ranking por query string</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Retorna a evolução de um jogo específico usando o parâmetro <code>id</code> na query string.</p>

<span id="example-requests-GETapi-v1-rankings-history">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/rankings/history?id=1" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"id\": 16
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/rankings/history"
);

const params = {
    "id": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 16
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-rankings-history">
            <blockquote>
            <p>Example response (422):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 56
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The selected id is invalid.&quot;,
    &quot;errors&quot;: {
        &quot;id&quot;: [
            &quot;The selected id is invalid.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-rankings-history" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-rankings-history"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-rankings-history"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-rankings-history" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-rankings-history">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-rankings-history" data-method="GET"
      data-path="api/v1/rankings/history"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-rankings-history', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-rankings-history"
                    onclick="tryItOut('GETapi-v1-rankings-history');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-rankings-history"
                    onclick="cancelTryOut('GETapi-v1-rankings-history');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-rankings-history"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/rankings/history</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-rankings-history"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-rankings-history"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-rankings-history"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-rankings-history"
               value="1"
               data-component="query">
    <br>
<p>O ID do jogo. Example: <code>1</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-rankings-history"
               value="16"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the games table. Example: <code>16</code></p>
        </div>
        </form>

                    <h2 id="rankings-GETapi-v1-rankings-history--id-">Histórico de ranking</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Retorna a evolução de um jogo específico ao longo do tempo.</p>

<span id="example-requests-GETapi-v1-rankings-history--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/rankings/history/1" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/rankings/history/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-rankings-history--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 55
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;game&quot;: &quot;Counter-Strike 2&quot;,
    &quot;history&quot;: [
        {
            &quot;period&quot;: &quot;Semana 1&quot;,
            &quot;points&quot;: 729
        },
        {
            &quot;period&quot;: &quot;M&ecirc;s Atual&quot;,
            &quot;points&quot;: 1215
        },
        {
            &quot;period&quot;: &quot;Ano Atual&quot;,
            &quot;points&quot;: 71182
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-rankings-history--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-rankings-history--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-rankings-history--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-rankings-history--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-rankings-history--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-rankings-history--id-" data-method="GET"
      data-path="api/v1/rankings/history/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-rankings-history--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-rankings-history--id-"
                    onclick="tryItOut('GETapi-v1-rankings-history--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-rankings-history--id-"
                    onclick="cancelTryOut('GETapi-v1-rankings-history--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-rankings-history--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/rankings/history/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-rankings-history--id-"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-rankings-history--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-rankings-history--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-rankings-history--id-"
               value="1"
               data-component="url">
    <br>
<p>O ID do jogo. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="rankings-GETapi-v1-rankings-platforms--platform-">Ranking por Plataforma</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Retorna os jogos mais bem ranqueados de uma plataforma específica.</p>

<span id="example-requests-GETapi-v1-rankings-platforms--platform-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/rankings/platforms/Steam" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/rankings/platforms/Steam"
);

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-rankings-platforms--platform-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 54
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 14,
        &quot;name&quot;: &quot;Cyberpunk 2077&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 1161973,
        &quot;weekly_points&quot;: 874,
        &quot;monthly_points&quot;: 4853,
        &quot;yearly_points&quot;: 27988,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 15,
        &quot;name&quot;: &quot;Stardew Valley&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 1117483,
        &quot;weekly_points&quot;: 702,
        &quot;monthly_points&quot;: 7545,
        &quot;yearly_points&quot;: 42912,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Counter-Strike 2&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 1086549,
        &quot;weekly_points&quot;: 729,
        &quot;monthly_points&quot;: 1215,
        &quot;yearly_points&quot;: 71182,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 2,
        &quot;name&quot;: &quot;Elden Ring&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 715531,
        &quot;weekly_points&quot;: 697,
        &quot;monthly_points&quot;: 7369,
        &quot;yearly_points&quot;: 44291,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 8,
        &quot;name&quot;: &quot;EA SPORTS FC 24&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 398998,
        &quot;weekly_points&quot;: 872,
        &quot;monthly_points&quot;: 5333,
        &quot;yearly_points&quot;: 81468,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 5,
        &quot;name&quot;: &quot;Baldur&#039;s Gate 3&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 296988,
        &quot;weekly_points&quot;: 352,
        &quot;monthly_points&quot;: 3595,
        &quot;yearly_points&quot;: 62260,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 7,
        &quot;name&quot;: &quot;Grand Theft Auto V&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 262363,
        &quot;weekly_points&quot;: 199,
        &quot;monthly_points&quot;: 2257,
        &quot;yearly_points&quot;: 62350,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 11,
        &quot;name&quot;: &quot;Apex Legends&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 218457,
        &quot;weekly_points&quot;: 945,
        &quot;monthly_points&quot;: 8776,
        &quot;yearly_points&quot;: 56526,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 4,
        &quot;name&quot;: &quot;Helldivers 2&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 217823,
        &quot;weekly_points&quot;: 617,
        &quot;monthly_points&quot;: 5232,
        &quot;yearly_points&quot;: 24531,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-rankings-platforms--platform-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-rankings-platforms--platform-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-rankings-platforms--platform-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-rankings-platforms--platform-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-rankings-platforms--platform-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-rankings-platforms--platform-" data-method="GET"
      data-path="api/v1/rankings/platforms/{platform}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-rankings-platforms--platform-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-rankings-platforms--platform-"
                    onclick="tryItOut('GETapi-v1-rankings-platforms--platform-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-rankings-platforms--platform-"
                    onclick="cancelTryOut('GETapi-v1-rankings-platforms--platform-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-rankings-platforms--platform-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/rankings/platforms/{platform}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-rankings-platforms--platform-"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-rankings-platforms--platform-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-rankings-platforms--platform-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>platform</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="platform"                data-endpoint="GETapi-v1-rankings-platforms--platform-"
               value="Steam"
               data-component="url">
    <br>
<p>O nome da plataforma. Example: <code>Steam</code></p>
            </div>
                    </form>

                    <h2 id="rankings-GETapi-v1-games">Listar jogos</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Retorna os jogos cadastrados com seus IDs para o frontend escolher qual histórico consultar.</p>

<span id="example-requests-GETapi-v1-games">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/games" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/games"
);

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-games">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 53
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 11,
        &quot;name&quot;: &quot;Apex Legends&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 218457,
        &quot;weekly_points&quot;: 945,
        &quot;monthly_points&quot;: 8776,
        &quot;yearly_points&quot;: 56526,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 5,
        &quot;name&quot;: &quot;Baldur&#039;s Gate 3&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 296988,
        &quot;weekly_points&quot;: 352,
        &quot;monthly_points&quot;: 3595,
        &quot;yearly_points&quot;: 62260,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 12,
        &quot;name&quot;: &quot;Call of Duty: Warzone&quot;,
        &quot;platform&quot;: &quot;Battle.net&quot;,
        &quot;active_players&quot;: 243114,
        &quot;weekly_points&quot;: 877,
        &quot;monthly_points&quot;: 2426,
        &quot;yearly_points&quot;: 36655,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Counter-Strike 2&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 1086549,
        &quot;weekly_points&quot;: 729,
        &quot;monthly_points&quot;: 1215,
        &quot;yearly_points&quot;: 71182,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 14,
        &quot;name&quot;: &quot;Cyberpunk 2077&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 1161973,
        &quot;weekly_points&quot;: 874,
        &quot;monthly_points&quot;: 4853,
        &quot;yearly_points&quot;: 27988,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 8,
        &quot;name&quot;: &quot;EA SPORTS FC 24&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 398998,
        &quot;weekly_points&quot;: 872,
        &quot;monthly_points&quot;: 5333,
        &quot;yearly_points&quot;: 81468,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 2,
        &quot;name&quot;: &quot;Elden Ring&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 715531,
        &quot;weekly_points&quot;: 697,
        &quot;monthly_points&quot;: 7369,
        &quot;yearly_points&quot;: 44291,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 6,
        &quot;name&quot;: &quot;Fortnite&quot;,
        &quot;platform&quot;: &quot;Epic Games&quot;,
        &quot;active_players&quot;: 1091171,
        &quot;weekly_points&quot;: 611,
        &quot;monthly_points&quot;: 5678,
        &quot;yearly_points&quot;: 96832,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 7,
        &quot;name&quot;: &quot;Grand Theft Auto V&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 262363,
        &quot;weekly_points&quot;: 199,
        &quot;monthly_points&quot;: 2257,
        &quot;yearly_points&quot;: 62350,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 4,
        &quot;name&quot;: &quot;Helldivers 2&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 217823,
        &quot;weekly_points&quot;: 617,
        &quot;monthly_points&quot;: 5232,
        &quot;yearly_points&quot;: 24531,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 10,
        &quot;name&quot;: &quot;League of Legends&quot;,
        &quot;platform&quot;: &quot;Riot Launcher&quot;,
        &quot;active_players&quot;: 1166370,
        &quot;weekly_points&quot;: 786,
        &quot;monthly_points&quot;: 4506,
        &quot;yearly_points&quot;: 21445,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 13,
        &quot;name&quot;: &quot;Minecraft&quot;,
        &quot;platform&quot;: &quot;Multiplataforma&quot;,
        &quot;active_players&quot;: 242066,
        &quot;weekly_points&quot;: 184,
        &quot;monthly_points&quot;: 9278,
        &quot;yearly_points&quot;: 33053,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 9,
        &quot;name&quot;: &quot;Roblox&quot;,
        &quot;platform&quot;: &quot;Multiplataforma&quot;,
        &quot;active_players&quot;: 991415,
        &quot;weekly_points&quot;: 770,
        &quot;monthly_points&quot;: 2080,
        &quot;yearly_points&quot;: 22209,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 15,
        &quot;name&quot;: &quot;Stardew Valley&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 1117483,
        &quot;weekly_points&quot;: 702,
        &quot;monthly_points&quot;: 7545,
        &quot;yearly_points&quot;: 42912,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 3,
        &quot;name&quot;: &quot;Valorant&quot;,
        &quot;platform&quot;: &quot;Riot Launcher&quot;,
        &quot;active_players&quot;: 821498,
        &quot;weekly_points&quot;: 241,
        &quot;monthly_points&quot;: 1030,
        &quot;yearly_points&quot;: 57266,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-games" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-games"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-games"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-games" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-games">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-games" data-method="GET"
      data-path="api/v1/games"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-games', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-games"
                    onclick="tryItOut('GETapi-v1-games');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-games"
                    onclick="cancelTryOut('GETapi-v1-games');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-games"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/games</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-games"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-games"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-games"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="rankings-GETapi-v1-games-most-played">Jogos mais jogados</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Retorna o top 10 jogos com base no número de jogadores ativos.</p>

<span id="example-requests-GETapi-v1-games-most-played">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/games/most-played" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/games/most-played"
);

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-games-most-played">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 52
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 10,
        &quot;name&quot;: &quot;League of Legends&quot;,
        &quot;platform&quot;: &quot;Riot Launcher&quot;,
        &quot;active_players&quot;: 1166370,
        &quot;weekly_points&quot;: 786,
        &quot;monthly_points&quot;: 4506,
        &quot;yearly_points&quot;: 21445,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 14,
        &quot;name&quot;: &quot;Cyberpunk 2077&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 1161973,
        &quot;weekly_points&quot;: 874,
        &quot;monthly_points&quot;: 4853,
        &quot;yearly_points&quot;: 27988,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 15,
        &quot;name&quot;: &quot;Stardew Valley&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 1117483,
        &quot;weekly_points&quot;: 702,
        &quot;monthly_points&quot;: 7545,
        &quot;yearly_points&quot;: 42912,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 6,
        &quot;name&quot;: &quot;Fortnite&quot;,
        &quot;platform&quot;: &quot;Epic Games&quot;,
        &quot;active_players&quot;: 1091171,
        &quot;weekly_points&quot;: 611,
        &quot;monthly_points&quot;: 5678,
        &quot;yearly_points&quot;: 96832,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Counter-Strike 2&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 1086549,
        &quot;weekly_points&quot;: 729,
        &quot;monthly_points&quot;: 1215,
        &quot;yearly_points&quot;: 71182,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 9,
        &quot;name&quot;: &quot;Roblox&quot;,
        &quot;platform&quot;: &quot;Multiplataforma&quot;,
        &quot;active_players&quot;: 991415,
        &quot;weekly_points&quot;: 770,
        &quot;monthly_points&quot;: 2080,
        &quot;yearly_points&quot;: 22209,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 3,
        &quot;name&quot;: &quot;Valorant&quot;,
        &quot;platform&quot;: &quot;Riot Launcher&quot;,
        &quot;active_players&quot;: 821498,
        &quot;weekly_points&quot;: 241,
        &quot;monthly_points&quot;: 1030,
        &quot;yearly_points&quot;: 57266,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 2,
        &quot;name&quot;: &quot;Elden Ring&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 715531,
        &quot;weekly_points&quot;: 697,
        &quot;monthly_points&quot;: 7369,
        &quot;yearly_points&quot;: 44291,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 8,
        &quot;name&quot;: &quot;EA SPORTS FC 24&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 398998,
        &quot;weekly_points&quot;: 872,
        &quot;monthly_points&quot;: 5333,
        &quot;yearly_points&quot;: 81468,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    },
    {
        &quot;id&quot;: 5,
        &quot;name&quot;: &quot;Baldur&#039;s Gate 3&quot;,
        &quot;platform&quot;: &quot;Steam&quot;,
        &quot;active_players&quot;: 296988,
        &quot;weekly_points&quot;: 352,
        &quot;monthly_points&quot;: 3595,
        &quot;yearly_points&quot;: 62260,
        &quot;created_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-18T21:57:31.000000Z&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-games-most-played" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-games-most-played"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-games-most-played"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-games-most-played" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-games-most-played">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-games-most-played" data-method="GET"
      data-path="api/v1/games/most-played"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-games-most-played', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-games-most-played"
                    onclick="tryItOut('GETapi-v1-games-most-played');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-games-most-played"
                    onclick="cancelTryOut('GETapi-v1-games-most-played');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-games-most-played"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/games/most-played</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-games-most-played"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-games-most-played"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-games-most-played"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
