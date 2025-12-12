<?php

namespace App\Filament\Resources\ApiLogs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ApiLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('api_name')
                    ->required(),
                TextInput::make('environment')
                    ->required(),
                TextInput::make('endpoint')
                    ->required(),
                TextInput::make('request_data'),
                TextInput::make('response_data'),
                TextInput::make('status_code')
                    ->numeric(),
                TextInput::make('response_time')
                    ->numeric(),
                Textarea::make('error_message')
                    ->columnSpanFull(),
            ]);
    }
}
