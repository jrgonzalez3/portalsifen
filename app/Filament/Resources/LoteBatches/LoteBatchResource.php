<?php

namespace App\Filament\Resources\LoteBatches;

use App\Filament\Resources\LoteBatches\Pages\CreateLoteBatch;
use App\Filament\Resources\LoteBatches\Pages\EditLoteBatch;
use App\Filament\Resources\LoteBatches\Pages\ListLoteBatches;
use App\Filament\Resources\LoteBatches\Pages\ViewLoteBatch;
use App\Filament\Resources\LoteBatches\Schemas\LoteBatchForm;
use App\Filament\Resources\LoteBatches\Schemas\LoteBatchInfolist;
use App\Filament\Resources\LoteBatches\Tables\LoteBatchesTable;
use App\Models\LoteBatch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LoteBatchResource extends Resource
{
    protected static ?string $model = LoteBatch::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $recordTitleAttribute = 'batch_number';
    
    protected static ?string $navigationLabel = 'Consultas Lote';
    
    protected static ?string $modelLabel = 'Consulta Lote';
    
    protected static ?string $pluralModelLabel = 'Consultas Lote';
    
    protected static ?string $navigationGroup = 'Consultas SIFEN';
    
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return LoteBatchForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LoteBatchInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoteBatchesTable::configure($table);
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
            'index' => ListLoteBatches::route('/'),
            'create' => CreateLoteBatch::route('/create'),
            'view' => ViewLoteBatch::route('/{record}'),
            'edit' => EditLoteBatch::route('/{record}/edit'),
        ];
    }
}
