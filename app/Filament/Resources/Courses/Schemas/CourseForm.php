<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        FileUpload::make('thumbnail')
                            ->required()
                            ->image()
                            ->disk('public')
                            ->directory('courses')
                            ->visibility('public')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Fieldset::make('Additional')
                    ->schema([
                        Repeater::make('benefits')
                            ->relationship('benefits')
                            ->schema([
                                TextInput::make('name')
                                    ->required(),
                            ])
                            ->columnSpanFull(),
                        Textarea::make('about')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('is_popular')
                            ->required()
                            ->options([
                                true => 'Popular',
                                false => 'Not Popular',
                            ]),
                        Select::make('category_id')
                            ->required()
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
