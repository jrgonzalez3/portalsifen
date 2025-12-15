<?php

namespace App\Filament\Resources\LoteBatches\Pages;

use App\Filament\Resources\LoteBatches\LoteBatchResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLoteBatches extends ListRecords
{
    protected static string $resource = LoteBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
