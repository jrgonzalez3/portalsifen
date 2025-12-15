<?php

namespace App\Filament\Resources\LoteBatches\Pages;

use App\Filament\Resources\LoteBatches\LoteBatchResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLoteBatch extends ViewRecord
{
    protected static string $resource = LoteBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }
}
