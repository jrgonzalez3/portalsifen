<?php

namespace App\Filament\Resources\DeSifens\Pages;

use App\Filament\Resources\DeSifens\DeSifenResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDeSifen extends EditRecord
{
    protected static string $resource = DeSifenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
