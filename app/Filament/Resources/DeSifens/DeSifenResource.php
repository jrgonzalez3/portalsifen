<?php

namespace App\Filament\Resources\DeSifens;

use App\Filament\Resources\DeSifens\Pages\CreateDeSifen;
use App\Filament\Resources\DeSifens\Pages\EditDeSifen;
use App\Filament\Resources\DeSifens\Pages\ListDeSifens;
use App\Filament\Resources\DeSifens\Pages\ViewDeSifen;
use App\Filament\Resources\DeSifens\Schemas\DeSifenForm;
use App\Filament\Resources\DeSifens\Schemas\DeSifenInfolist;
use App\Filament\Resources\DeSifens\Tables\DeSifensTable;
use App\Models\DeSifen;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeSifenResource extends Resource
{
    protected static ?string $model = DeSifen::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;
    
    protected static ?string $navigationLabel = 'Documentos Generados';
    
    protected static ?string $modelLabel = 'Documento Generado';
    
    protected static ?string $pluralModelLabel = 'Documentos Generados';
    
    protected static string|UnitEnum|null $navigationGroup = 'Sistema';
    
    protected static ?int $navigationSort = 5;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return DeSifenForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DeSifenInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeSifensTable::configure($table);
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
            'index' => ListDeSifens::route('/'),
           'create' => CreateDeSifen::route('/create'),
            'view' => ViewDeSifen::route('/{record}'),
            'edit' => EditDeSifen::route('/{record}/edit'),
        ];
    }
}
