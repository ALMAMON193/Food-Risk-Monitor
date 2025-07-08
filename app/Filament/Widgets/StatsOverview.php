<?php

namespace App\Filament\Widgets;

use App\Models\CustomFood;
use App\Models\FoodRiskHistory;
use App\Models\LogSymptom;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', number_format(User::count()))
                ->description('+12 from last week')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($this->getUserChartData())
                ->color('success'),

            Stat::make('Total Foods', number_format(FoodRiskHistory::count()))
                ->description('+5 new entries')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($this->getFoodChartData())
                ->color('success'),

            Stat::make('Custom Foods', number_format(CustomFood::count()))
                ->description('+2 custom added')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($this->getCustomFoodChartData())
                ->color('info'),

            Stat::make('Log Symptoms', number_format(LogSymptom::count()))
                ->description('+3 logs this week')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($this->getLogSymptomChartData())
                ->color('warning'),
        ];
    }

    // 🧠 Helper methods to create mini chart data from last 7 days

    protected function getUserChartData(): array
    {
        return $this->getLast7DaysData(User::class);
    }

    protected function getFoodChartData(): array
    {
        return $this->getLast7DaysData(FoodRiskHistory::class);
    }

    protected function getCustomFoodChartData(): array
    {
        return $this->getLast7DaysData(CustomFood::class);
    }

    protected function getLogSymptomChartData(): array
    {
        return $this->getLast7DaysData(LogSymptom::class);
    }

    // 🔄 Common method for generating last 7 days count data
    protected function getLast7DaysData(string $model): array
    {
        $start = now()->subDays(6)->startOfDay();
        $end = now()->endOfDay();

        $data = $model::whereBetween('created_at', [$start, $end])
            ->get()
            ->groupBy(fn($item) => $item->created_at->format('D'))
            ->map(fn($items) => $items->count());

        // Ensure all 7 days are present
        $days = collect(range(0, 6))
            ->map(fn($i) => now()->subDays(6 - $i)->format('D'));

        return $days->map(fn($day) => $data[$day] ?? 0)->values()->toArray();
    }
}
