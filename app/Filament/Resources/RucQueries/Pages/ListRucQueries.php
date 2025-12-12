<?php

namespace App\Filament\Resources\RucQueries\Pages;

use App\Filament\Resources\RucQueries\RucQueryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRucQueries extends ListRecords
{
    protected static string $resource = RucQueryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
