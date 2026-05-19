<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

/**
 * @group Rankings
 */
class GameController extends Controller
{
    /**
     * Listar jogos
     *
     * Retorna os jogos cadastrados com seus IDs para o frontend escolher qual histórico consultar.
     */
    public function index()
    {
        $games = Game::orderBy('name')->get();
        return response()->json($games);
    }

    /**
     * Top semanal
     *
     * Retorna o ranking dos jogos com melhor desempenho na última semana.
     */
    public function weeklyRanking()
    {
        $games = Game::orderBy('weekly_points', 'desc')->take(10)->get();
        return response()->json($games);
    }

    /**
     * Top mensal
     *
     * Retorna o ranking dos jogos com melhor desempenho no último mês.
     */
    public function monthlyRanking()
    {
        $games = Game::orderBy('monthly_points', 'desc')->take(10)->get();
        return response()->json($games);
    }

    /**
     * Top anual
     *
     * Retorna o ranking dos jogos com melhor desempenho no último ano.
     */
    public function yearlyRanking()
    {
        $games = Game::orderBy('yearly_points', 'desc')->take(10)->get();
        return response()->json($games);
    }

    /**
     * Jogos mais jogados
     *
     * Retorna o top 10 jogos com base no número de jogadores ativos.
     */
    public function mostPlayed()
    {
        $games = Game::orderBy('active_players', 'desc')->take(10)->get();
        return response()->json($games);
    }

    /**
     * Histórico de ranking
     *
     * Retorna a evolução de um jogo específico ao longo do tempo.
     *
     * @urlParam id int required O ID do jogo. Example: 1
     */
    public function history($id)
    {
        $game = Game::findOrFail($id);
        return response()->json([
            'game' => $game->name,
            'history' => [
                ['period' => 'Semana 1', 'points' => $game->weekly_points],
                ['period' => 'Mês Atual', 'points' => $game->monthly_points],
                ['period' => 'Ano Atual', 'points' => $game->yearly_points],
            ]
        ]);
    }

    /**
     * Histórico de ranking por query string
     *
     * Retorna a evolução de um jogo específico usando o parâmetro `id` na query string.
     *
     * @queryParam id int required O ID do jogo. Example: 1
     */
    public function historyByQuery(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:games,id'],
        ]);

        return $this->history($request->integer('id'));
    }

    /**
     * Ranking por Plataforma
     *
     * Retorna os jogos mais bem ranqueados de uma plataforma específica.
     *
     * @urlParam platform string required O nome da plataforma. Example: Steam
     */
    public function platformRanking($platform)
    {
        $games = Game::where('platform', $platform)
                     ->orderBy('active_players', 'desc')
                     ->get();
                     
        return response()->json($games);
    }
}
