<?php

namespace App\Filament\Resources\RucQueries;

use App\Filament\Resources\RucQueries\Pages\CreateRucQuery;
use App\Filament\Resources\RucQueries\Pages\EditRucQuery;
use App\Filament\Resources\RucQueries\Pages\ListRucQueries;
use App\Filament\Resources\RucQueries\Pages\ViewRucQuery;
use App\Filament\Resources\RucQueries\Schemas\RucQueryForm;
use App\Filament\Resources\RucQueries\Schemas\RucQueryInfolist;
use App\Filament\Resources\RucQueries\Tables\RucQueriesTable;
use App\Models\RucQuery;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RucQueryResource extends Resource
{
    protected static ?string $model = RucQuery::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?string $recordTitleAttribute = 'ruc_number';
    
    protected static ?string $navigationLabel = 'Consultas RUC';
    
    protected static ?string $modelLabel = 'Consulta RUC';
    
    protected static ?string $pluralModelLabel = 'Consultas RUC';
    
    protected static ?string $navigationGroup = 'Consultas SIFEN';
    
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return RucQueryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RucQueryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RucQueriesTable::configure($table);
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
            'create' => CreateRucQuery::route('/create'),
            'view' => ViewRucQuery::route('/{record}'),
            'edit' => EditRucQuery::route('/{record}/edit'),
        ];
    }
}
