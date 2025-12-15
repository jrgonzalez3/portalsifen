<?php

namespace App\Filament\Resources\CdcDocuments;

use App\Filament\Resources\CdcDocuments\Pages\CreateCdcDocument;
use App\Filament\Resources\CdcDocuments\Pages\EditCdcDocument;
use App\Filament\Resources\CdcDocuments\Pages\ListCdcDocuments;
use App\Filament\Resources\CdcDocuments\Pages\ViewCdcDocument;
use App\Filament\Resources\CdcDocuments\Schemas\CdcDocumentForm;
use App\Filament\Resources\CdcDocuments\Schemas\CdcDocumentInfolist;
use App\Filament\Resources\CdcDocuments\Tables\CdcDocumentsTable;
use App\Models\CdcDocument;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CdcDocumentResource extends Resource
{
    protected static ?string $model = CdcDocument::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'cdc_number';
    
    protected static ?string $navigationLabel = 'Consultar CDC';
    
    protected static ?string $modelLabel = 'Consultar CDC';
    
    protected static ?string $pluralModelLabel = 'Consultar CDC';
    
    protected static string|UnitEnum|null $navigationGroup = 'Consultas SIFEN';
    
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return CdcDocumentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CdcDocumentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CdcDocumentsTable::configure($table);
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
            'index' => ListCdcDocuments::route('/'),
            'view' => ViewCdcDocument::route('/{record}'),
        ];
    }
}
