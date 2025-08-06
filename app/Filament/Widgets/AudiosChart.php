<?php

namespace App\Filament\Widgets;

use App\Models\Audio;
use Filament\Widgets\ChartWidget;

class AudiosChart extends ChartWidget
{
    protected static ?string $heading = 'Audio Added by Month';

    protected function getData(): array
    {
        // Get the completed Order count for each of the past 12 months
        $audioCountByMonth = Audio::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->where('status', 'Active')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        $data = array_fill(1, 12, 0);
        foreach ($audioCountByMonth as $month => $count) {
            $data[$month] = $count;
        }
        return [
            'datasets' => [
                [
                    'label' => 'Audio Added (' . now()->year . ')',
                    'data' => array_values($data),
                    'backgroundColor' => '#4CAF50',
                ],
            ],
            'labels' => [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ],
        ];
    }


    protected function getType(): string
    {
        return 'line';
    }
}
