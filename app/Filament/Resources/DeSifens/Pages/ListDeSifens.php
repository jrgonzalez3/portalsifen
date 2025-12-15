<?php

namespace App\Filament\Resources\DeSifens\Pages;

use App\Filament\Resources\DeSifens\DeSifenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeSifens extends ListRecords
{
    protected static string $resource = DeSifenResource::class;

    protected function getHeaderActions(): array
    {
        return [
       //     CreateAction::make(),
        ];
    }
}
