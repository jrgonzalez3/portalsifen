<?php

namespace App\Filament\Resources\CdcDocuments\Pages;

use App\Filament\Resources\CdcDocuments\CdcDocumentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCdcDocument extends ViewRecord
{
    protected static string $resource = CdcDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
