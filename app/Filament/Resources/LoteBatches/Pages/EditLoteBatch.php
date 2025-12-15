<?php

namespace App\Filament\Resources\LoteBatches\Pages;

use App\Filament\Resources\LoteBatches\LoteBatchResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLoteBatch extends EditRecord
{
    protected static string $resource = LoteBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
