<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Pricing;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Product and Price')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Select::make('pricing_id')
                                        ->required()
                                        ->relationship('pricing', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->live()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $pricing = Pricing::find($state);

                                            $price = $pricing->price;
                                            $duration = $pricing->duration;

                                            $subTotal = $price * $state;
                                            $totalPpn = $subTotal * 0.11;
                                            $totalAmount = $subTotal + $totalPpn;

                                            $set('total_tax_amount', $totalPpn);
                                            $set('grand_total_amount', $totalAmount);
                                            $set('sub_total_amount', $price);
                                            $set('duration', $duration);
                                        })
                                        ->afterStateHydrated(function (callable $set, $state) {
                                            $pricingId = $state;
                                            if ($pricingId) {
                                                $pricing = Pricing::find($pricingId);
                                                $duration = $pricing->duration;
                                                $set('duration', $duration);
                                            }
                                        }),
                                    TextInput::make('duration')
                                        ->required()
                                        ->numeric()
                                        ->readOnly()
                                        ->prefix('Month'),
                                ]),
                            Grid::make(3)
                                ->schema([
                                    TextInput::make('sub_total_amount')
                                        ->required()
                                        ->numeric()
                                        ->readOnly()
                                        ->prefix('IDR'),
                                    TextInput::make('total_tax_amount')
                                        ->required()
                                        ->numeric()
                                        ->readOnly()
                                        ->prefix('IDR'),
                                    TextInput::make('grand_total_amount')
                                        ->required()
                                        ->numeric()
                                        ->readOnly()
                                        ->prefix('IDR')
                                        ->helperText('Harga sudah termasuk PPN 11%'),
                                ]),
                            Grid::make(2)
                                ->schema([
                                    DatePicker::make('started_at')
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            $duration = $get('duration');
                                            if ($state && $duration) {
                                                $endedAt = Carbon::parse($state)->addMonth($duration);
                                                $set('ended_at', $endedAt->format('Y-m-d'));
                                            }
                                        }),
                                    DatePicker::make('ended_at')
                                        ->required()
                                        ->readOnly(),
                                ])
                        ]),
                    Step::make('Customer Information')
                        ->schema([
                            Select::make('user_id')
                                ->required()
                                ->relationship('student', 'email')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $user = User::find($state);

                                    $name = $user->name;
                                    $email = $user->email;

                                    $set('name', $name);
                                    $set('email', $email);
                                })
                                ->afterStateHydrated(function ($state, callable $set) {
                                    $userId = $state;
                                    if ($userId) {
                                        $user = User::find($userId);
                                        $name = $user->name;
                                        $email = $user->email;
                                        $set('name', $name);
                                        $set('email', $email);
                                    }
                                }),
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->readOnly(),
                            TextInput::make('email')
                                ->required()
                                ->maxLength(255)
                                ->readOnly(),
                        ]),
                    Step::make('Payment Information')
                        ->schema([
                            ToggleButtons::make('is_paid')
                                ->label('Apakah sudah membayar?')
                                ->required()
                                ->boolean()
                                ->grouped()
                                ->icons([
                                    true => Heroicon::Pencil,
                                    false => Heroicon::Clock,
                                ]),
                            Select::make('payment_type')
                                ->required()
                                ->options([
                                    'Midtrans' => 'Midtrans',
                                    'Manual' => 'Manual',
                                ]),
                            FileUpload::make('proof')
                                ->image(),
                        ]),
                ])
                ->columnSpan('full')
                ->columns(1)
                ->skippable()
            ]);
    }
}
