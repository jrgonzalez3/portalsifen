<?php

namespace App\Filament\Resources\RucQueries\Pages;

use App\Filament\Resources\RucQueries\RucQueryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRucQuery extends EditRecord
{
    protected static string $resource = RucQueryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
