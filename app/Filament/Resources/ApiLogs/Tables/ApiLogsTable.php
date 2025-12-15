<?php

namespace App\Filament\Resources\ApiLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ApiLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('api_name')
                    ->label('API')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('environment')
                    ->label('Entorno')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'dev' => 'gray',
                        'pruebas' => 'warning',
                        'prod' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('endpoint')
                    ->label('Endpoint')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('status_code')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 200 && $state < 300 => 'success',
                        $state >= 400 && $state < 500 => 'warning',
                        $state >= 500 => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('response_time')
                    ->label('Tiempo (ms)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('environment')
                    ->label('Entorno')
                    ->options([
                        'dev' => 'Desarrollo',
                        'pruebas' => 'Pruebas',
                        'prod' => 'Producción',
                    ])
                    ->placeholder('Todos los entornos'),
                SelectFilter::make('api_name')
                    ->label('API')
                    ->options([
                        'kude' => 'Kude',
                        'ruc' => 'RUC',
                        'lote' => 'Lote',
                        'cdc' => 'CDC',
                    ])
                    ->placeholder('Todas las APIs'),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
