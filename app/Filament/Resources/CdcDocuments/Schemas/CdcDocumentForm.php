<?php

namespace App\Filament\Resources\CdcDocuments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Schema;

class CdcDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Documento')
                    ->schema([
                        TextInput::make('cdc_number')
                            ->label('Número de CDC')
                            ->columnSpan(2)
                            ->readOnly(),
                        TextInput::make('environment')
                            ->label('Entorno')
                            ->readOnly(),
                        TextInput::make('status')
                            ->label('Estado SIFEN')
                            ->readOnly(),
                    ])->columns(2),

                Section::make('Respuesta Técnica')
                    ->schema([
                         Placeholder::make('json_view')
                            ->label('Respuesta JSON Completa')
                            ->content(fn($record) => $record ? new HtmlString('<pre style="overflow-x: auto; background: #f3f4f6; padding: 10px; border-radius: 5px; font-size: 0.85em;">'.json_encode($record->response_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).'</pre>') : '-')
                            ->columnSpanFull()
                    ])
            ]);
    }
}
