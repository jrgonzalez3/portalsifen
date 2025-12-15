<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make('Consultas RUC', \App\Models\RucQuery::count())
                ->description('Contribuyentes consultados')
                ->descriptionIcon('heroicon-m-magnifying-glass')
                ->color('info'),
            Stat::make('Consultas CDC', \App\Models\CdcDocument::count())
                ->description('Documentos CDC')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),
            Stat::make('Lotes Procesados', \App\Models\LoteBatch::count())
                ->description('Lotes consultados')
                ->descriptionIcon('heroicon-m-queue-list')
                ->color('primary'),
        ];
    }
}
