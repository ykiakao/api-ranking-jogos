<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game; // Certifique-se de que o model Game está sendo importado

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $jogosAtuais = [
            ['id' => 1, 'name' => 'Counter-Strike 2', 'platform' => 'Steam', 'active_players' => 1450000, 'weekly_points' => 980, 'monthly_points' => 9400, 'yearly_points' => 91000],
            ['id' => 2, 'name' => 'Elden Ring', 'platform' => 'Steam', 'active_players' => 320000, 'weekly_points' => 870, 'monthly_points' => 8500, 'yearly_points' => 88000],
            ['id' => 3, 'name' => 'Valorant', 'platform' => 'Riot Launcher', 'active_players' => 1180000, 'weekly_points' => 920, 'monthly_points' => 9100, 'yearly_points' => 96000],
            ['id' => 4, 'name' => 'Helldivers 2', 'platform' => 'Steam', 'active_players' => 410000, 'weekly_points' => 820, 'monthly_points' => 7900, 'yearly_points' => 74000],
            ['id' => 5, 'name' => 'Baldur\'s Gate 3', 'platform' => 'Steam', 'active_players' => 260000, 'weekly_points' => 760, 'monthly_points' => 8300, 'yearly_points' => 90000],
            ['id' => 6, 'name' => 'Fortnite', 'platform' => 'Epic Games', 'active_players' => 1800000, 'weekly_points' => 950, 'monthly_points' => 9300, 'yearly_points' => 99000],
            ['id' => 7, 'name' => 'Grand Theft Auto V', 'platform' => 'Steam', 'active_players' => 720000, 'weekly_points' => 700, 'monthly_points' => 7200, 'yearly_points' => 81000],
            ['id' => 8, 'name' => 'EA SPORTS FC 24', 'platform' => 'Steam', 'active_players' => 540000, 'weekly_points' => 650, 'monthly_points' => 6900, 'yearly_points' => 76000],
            ['id' => 9, 'name' => 'Roblox', 'platform' => 'Multiplataforma', 'active_players' => 1600000, 'weekly_points' => 890, 'monthly_points' => 9000, 'yearly_points' => 97000],
            ['id' => 10, 'name' => 'League of Legends', 'platform' => 'Riot Launcher', 'active_players' => 1500000, 'weekly_points' => 930, 'monthly_points' => 9200, 'yearly_points' => 98000],
            ['id' => 11, 'name' => 'Apex Legends', 'platform' => 'Steam', 'active_players' => 680000, 'weekly_points' => 610, 'monthly_points' => 6600, 'yearly_points' => 70000],
            ['id' => 12, 'name' => 'Call of Duty: Warzone', 'platform' => 'Battle.net', 'active_players' => 830000, 'weekly_points' => 810, 'monthly_points' => 7800, 'yearly_points' => 86000],
            ['id' => 13, 'name' => 'Minecraft', 'platform' => 'Multiplataforma', 'active_players' => 1750000, 'weekly_points' => 880, 'monthly_points' => 8800, 'yearly_points' => 95000],
            ['id' => 14, 'name' => 'Cyberpunk 2077', 'platform' => 'Steam', 'active_players' => 210000, 'weekly_points' => 560, 'monthly_points' => 6100, 'yearly_points' => 72000],
            ['id' => 15, 'name' => 'Stardew Valley', 'platform' => 'Steam', 'active_players' => 180000, 'weekly_points' => 520, 'monthly_points' => 5400, 'yearly_points' => 68000],
        ];

        foreach ($jogosAtuais as $jogo) {
            Game::updateOrCreate(['id' => $jogo['id']], $jogo);
        }
    }
}
