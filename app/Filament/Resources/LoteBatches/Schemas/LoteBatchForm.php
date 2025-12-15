<?php

namespace App\Filament\Resources\LoteBatches\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LoteBatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('batch_number')
                    ->required(),
                TextInput::make('environment')
                    ->required(),
                TextInput::make('response_data'),
                TextInput::make('status'),
                TextInput::make('user_id')
                    ->numeric(),
            ]);
    }
}
