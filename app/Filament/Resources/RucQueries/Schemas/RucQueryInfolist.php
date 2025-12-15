<?php

namespace App\Filament\Resources\RucQueries\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RucQueryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('ruc_number'),
                TextEntry::make('taxpayer_name'),
                TextEntry::make('environment'),
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
