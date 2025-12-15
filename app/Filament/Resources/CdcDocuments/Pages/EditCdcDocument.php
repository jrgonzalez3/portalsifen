<?php

namespace App\Filament\Resources\CdcDocuments\Pages;

use App\Filament\Resources\CdcDocuments\CdcDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCdcDocument extends EditRecord
{
    protected static string $resource = CdcDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
