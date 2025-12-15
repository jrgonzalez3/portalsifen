<?php

namespace App\Filament\Resources\RucQueries;

use App\Filament\Resources\RucQueries\Pages\CreateRucQuery;
use App\Filament\Resources\RucQueries\Pages\EditRucQuery;
use App\Filament\Resources\RucQueries\Pages\ListRucQueries;
use App\Filament\Resources\RucQueries\Pages\ViewRucQuery;
use App\Models\RucQuery;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
class RucQueryResource extends Resource
{
    protected static ?string $model = RucQuery::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?string $recordTitleAttribute = 'ruc_number';
    
    protected static ?string $navigationLabel = 'Consultar RUC';
    
    protected static ?string $modelLabel = 'Consultar RUC';
    
    protected static ?string $pluralModelLabel = 'Consultar RUC';
    
    protected static string|UnitEnum|null $navigationGroup = 'Consultas SIFEN';
    
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Consulta de Contribuyente')
                    ->description('Ingrese el RUC sin dígito verificador y seleccione el entorno.')
                    ->schema([
                        TextInput::make('ruc_number')
                            ->label('Número de RUC')
                            ->helperText('Ingrese solo el número, sin el dígito verificador.')
                            ->required()
                            ->numeric()
                            ->maxLength(20),
                        \Filament\Forms\Components\Select::make('environment')
                            ->label('Entorno')
                            ->options([
                                'test' => 'Testing (Pruebas)',
                                'prod' => 'Producción',
                            ])
                            ->default('test')
                            ->required()
                            ->native(false),
                    ])->columns(2)
                    ->visibleOn('create'), // Only for creation
                    
                // Detail View Section
                Section::make('Detalle del Contribuyente')
                     ->schema([
                         Placeholder::make('ruc_view')
                            ->label('RUC')
                            ->content(fn($record) => $record->ruc_number),
                         Placeholder::make('name_view')
                            ->label('Razón Social')
                            ->content(fn($record) => $record->taxpayer_name),
                         Placeholder::make('status_view')
                            ->label('Estado')
                            ->content(fn($record) => $record->status),
                         Placeholder::make('env_view')
                            ->label('Entorno')
                            ->content(fn($record) => $record->environment === 'prod' ? 'Producción' : 'Testing'),
                         Placeholder::make('date_view')
                            ->label('Fecha')
                            ->content(fn($record) => $record->created_at),
                          Placeholder::make('json_view')
                            ->label('Respuesta JSON Completa')
                            ->content(fn($record) => new HtmlString('<pre style="overflow-x: auto; background: #f3f4f6; padding: 10px; border-radius: 5px;">'.json_encode($record->taxpayer_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).'</pre>'))
                            ->columnSpanFull()
                     ])
                     ->columns(2)
                     ->visibleOn('view')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ruc_number')
                    ->label('RUC')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('taxpayer_name')
                    ->label('Razón Social')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('environment')
                    ->label('Entorno')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'test' => 'warning',
                        'prod' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match (strtoupper($state)) {
                        'ACTIVO' => 'success',
                        'BLOQUEADO' => 'danger',
                        'SUSPENDIDO' => 'warning',
                        'CANCELADO' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Fecha Consulta')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('environment')
                    ->options([
                        'test' => 'Testing',
                        'prod' => 'Producción',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRucQueries::route('/'),
            'view' => ViewRucQuery::route('/{record}'),
        ];
    }
}
