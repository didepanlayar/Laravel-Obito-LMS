<?php

namespace App\Filament\Resources\CourseMentors\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CourseMentorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->required()
                    ->relationship('course', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('user_id')
                    ->label('Mentor')
                    ->required()
                    ->options(function () {
                        return User::role('mentor')->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload(),
                Textarea::make('about')
                    ->required(),
                Select::make('is_active')
                    ->required()
                    ->options([
                        true => 'Active',
                        false => 'Banned',
                    ]),
            ]);
    }
}
