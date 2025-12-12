<?php

namespace App\Filament\Resources\RucQueries\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RucQueryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ruc_number')
                    ->required(),
                TextInput::make('taxpayer_name'),
                TextInput::make('taxpayer_data'),
                TextInput::make('environment')
                    ->required(),
                TextInput::make('user_id')
                    ->numeric(),
            ]);
    }
}
