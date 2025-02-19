<?php

namespace App\Filament\Widgets;

use App\Models\Equipment;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
        stat::make('Нийт', Equipment::where('status', 1)->sum('rent_amount'))
            ->description('Бүртгэлтэй нийт хөрөнгө')
            ->color('success')
            ->descriptionIcon('heroicon-m-squares-plus', IconPosition::Before),
        stat::make('Нийт', Equipment::count())
           ->description('Энэ сард бүртгэгдсэн')
           ->color('success')
           ->descriptionIcon('heroicon-m-squares-plus',IconPosition::Before),
        stat::make('Нийт',Equipment::count())
           ->description('Энэ жид бүртгэгдсэн')
           ->color('success')
           ->descriptionIcon('heroicon-m-squares-plus',IconPosition::Before),
        ];
    }
}