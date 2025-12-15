<?php

namespace App\Filament\Resources\LoteBatches\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LoteBatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('batch_number')
                    ->label('Nº Lote')
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
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),

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
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
