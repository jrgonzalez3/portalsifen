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
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CdcDocumentResource extends Resource
{
    protected static ?string $model = CdcDocument::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'cdc_number';
    
    protected static ?string $navigationLabel = 'Consultas CDC';
    
    protected static ?string $modelLabel = 'Consulta CDC';
    
    protected static ?string $pluralModelLabel = 'Consultas CDC';
    
    protected static ?string $navigationGroup = 'Consultas SIFEN';
    
    protected static ?int $navigationSort = 3;

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
            'create' => CreateCdcDocument::route('/create'),
            'view' => ViewCdcDocument::route('/{record}'),
            'edit' => EditCdcDocument::route('/{record}/edit'),
        ];
    }
}
