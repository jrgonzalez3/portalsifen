<?php

namespace App\Filament\Resources\ApiLogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ApiLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('api_name'),
                TextEntry::make('environment'),
                TextEntry::make('endpoint'),
                TextEntry::make('status_code')
                    ->numeric(),
                TextEntry::make('response_time')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
