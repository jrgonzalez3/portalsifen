<?php

namespace App\Filament\Resources\CdcDocuments\Pages;

use App\Filament\Resources\CdcDocuments\CdcDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCdcDocuments extends ListRecords
{
    protected static string $resource = CdcDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\CdcConsultationWidget::class,
        ];
    }

    protected $listeners = ['cdc-consulted' => '$refresh'];
}
