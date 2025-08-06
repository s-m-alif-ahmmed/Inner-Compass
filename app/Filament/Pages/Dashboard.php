<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AudiosChart;
use App\Filament\Widgets\DashboardOverview;
use App\Filament\Widgets\UsersChart;
use Filament\Pages\Page;

class Dashboard extends \Filament\Pages\Dashboard
{
    public function getWidgets(): array
    {
        return [
            DashboardOverview::class,
            AudiosChart::class,
            UsersChart::class,
        ];
    }
}
