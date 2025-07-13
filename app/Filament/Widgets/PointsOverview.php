<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PointsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();
        $totalPoints = User::sum('points');
        $avgPoints = $totalUsers > 0 ? round($totalPoints / $totalUsers, 1) : 0;
        $topUser = User::orderBy('points', 'desc')->first();

        return [
            Stat::make('Łączna liczba punktów', $totalPoints)
                ->description('Wszystkie punkty wszystkich użytkowników')
                ->descriptionIcon('heroicon-m-star')
                ->color('success'),
            Stat::make('Średnia punktów', $avgPoints)
                ->description('Średnia punktów na użytkownika')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
            Stat::make('Najlepszy użytkownik', $topUser ? $topUser->name : 'Brak')
                ->description($topUser ? $topUser->points . ' punktów' : '')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('warning'),
        ];
    }
}
