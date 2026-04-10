<?php

namespace App\Filament\Resources\Pricings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class PricingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('IDR'),
                        TextInput::make('duration')
                            ->required()
                            ->numeric()
                            ->prefix('Month'),
                    ]),
            ]);
    }
}
