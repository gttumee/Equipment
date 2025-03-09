<?php

namespace App\Filament\Widgets;

use App\Models\Equipment;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
        stat::make('Нийт', Equipment::where('status', 'active')->count())
            ->description('Бүртгэлтэй нийт хөрөнгө')
            ->color('success')
            ->descriptionIcon('heroicon-m-squares-plus', IconPosition::Before),
        stat::make('Нийт', function () {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            return Equipment::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', 'active')
            ->count();
            })
            ->description('Энэ сард бүртгэгдсэн')
            ->color('success')
            ->descriptionIcon('heroicon-m-squares-plus', IconPosition::Before),
            
        stat::make('Нийт', Equipment::where('status', '<>','avtive')->count())
            ->description('Нийт хасалт хийгдсэн')
            ->color('danger')
            ->descriptionIcon('heroicon-m-squares-plus', IconPosition::Before),
        ];
    }
}