<?php

namespace App\Filament\Resources\RucQueries\Pages;

use App\Filament\Resources\RucQueries\RucQueryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRucQuery extends ViewRecord
{
    protected static string $resource = RucQueryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
