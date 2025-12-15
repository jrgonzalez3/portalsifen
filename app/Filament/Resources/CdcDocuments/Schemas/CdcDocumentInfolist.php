<?php

namespace App\Filament\Resources\CdcDocuments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Schema;

class CdcDocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Documento')
                    ->schema([
                        TextEntry::make('cdc_number')
                            ->label('CDC')
                            ->columnSpan(2),
                        TextEntry::make('environment')
                            ->label('Entorno')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'prod' => 'danger',
                                'test' => 'info',
                                default => 'gray',
                            }),
                        TextEntry::make('status')
                            ->label('Estado')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Aprobado', 'Encontrado' => 'success',
                                'Rechazado' => 'danger',
                                default => 'warning',
                            }),
                        TextEntry::make('created_at')
                            ->label('Fecha Consulta')
                            ->dateTime(),
                    ])->columns(2),

                Section::make('Respuesta Técnica')
                    ->schema([
                         TextEntry::make('response_data')
                            ->label('JSON Respuesta')
                            ->formatStateUsing(fn ($state) => new HtmlString('<pre style="overflow-x: auto; background: #f3f4f6; padding: 10px; border-radius: 5px; font-size: 0.85em;">'.json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).'</pre>'))
                            ->columnSpanFull()
                    ])
            ]);
    }
}
