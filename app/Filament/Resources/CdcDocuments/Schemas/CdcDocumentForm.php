<?php

namespace App\Filament\Resources\CdcDocuments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CdcDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cdc_number')
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
