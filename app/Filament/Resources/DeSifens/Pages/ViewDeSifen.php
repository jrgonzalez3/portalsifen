<?php

namespace App\Filament\Resources\DeSifens\Pages;

use App\Filament\Resources\DeSifens\DeSifenResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDeSifen extends ViewRecord
{
    protected static string $resource = DeSifenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
