<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255),
                TextInput::make('password')
                    ->required()
                    ->password()
                    ->minLength(8)
                    ->maxLength(255),
                Select::make('occupation')
                    ->options([
                        'Developer' => 'Developer',
                        'Designer' => 'Designer',
                        'Project Manager' => 'Project Manager',
                    ])
                    ->required(),
                Select::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->required(),
                FileUpload::make('photo')
                    ->required()
                    ->image(),
            ]);
    }
}
