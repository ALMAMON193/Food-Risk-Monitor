<?php

namespace App\Services;

use App\Models\FoodRiskHistory;
use App\Models\LogSymptom;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AnalyticsService
{
    public function generate($period, $userId): array
    {
        $now = Carbon::now();
        $startDate = $period === 'monthly'
            ? $now->copy()->startOfMonth()
            : $now->copy()->subDays(6)->startOfDay();

        $endDate = $now->copy()->endOfDay();

        $foodRisk = FoodRiskHistory::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $symptoms = LogSymptom::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return [
            'food_risk' => $this->prepareChartData($foodRisk, 'risk_score', $period),
            'symptom' => $this->prepareChartData($symptoms, 'bloating', $period),
        ];
    }

    private function prepareChartData(Collection $data, $field, $period): array
    {
        $labels = [];
        $values = [];

        if ($period === 'last_7_days') {
            $days = collect(range(0, 6))->map(function ($i) {
                return Carbon::now()->subDays(6 - $i)->format('D');
            });

            foreach ($days as $day) {
                $labels[] = $day;
                $values[] = $data->filter(fn($item) => Carbon::parse($item->created_at)->format('D') === $day)
                    ->sum($field);
            }
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $weeks = [];

            while ($start < $end) {
                $weekLabel = 'Week ' . $start->weekOfMonth;
                if (!in_array($weekLabel, $labels)) {
                    $labels[] = $weekLabel;
                    $weekStart = $start->copy();
                    $weekEnd = $start->copy()->endOfWeek();

                    $values[] = $data->filter(function ($item) use ($weekStart, $weekEnd) {
                        $date = Carbon::parse($item->created_at);
                        return $date->between($weekStart, $weekEnd);
                    })->avg($field) ?? 0;
                }

                $start->addWeek();
            }
        }

        return [
            'labels' => $labels,
            'data' => array_map(fn($v) => round($v, 2), $values),
            'headline' => count($values) ? round(array_sum($values) / count($values), 2) . '%' : '0%',
            'change' => '+0%' // Change comparison logic can be added
        ];
    }
}
