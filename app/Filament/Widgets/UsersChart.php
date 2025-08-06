<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class UsersChart extends ChartWidget
{
    protected static ?string $heading = 'User Registrations by Month';

    protected function getData(): array
    {
        $userCountByMonth = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('count', 'month')
            ->toArray();

        $data = array_fill(1, 12, 0);
        foreach ($userCountByMonth as $month => $count) {
            $data[$month] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'User Registrations (' . now()->year . ')',
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
        return 'bar';
    }
}
