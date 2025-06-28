<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Food;
use Filament\Tables;
use Filament\Forms\Components;
use Filament\Resources\Resource;
use App\Filament\Imports\FoodImporter;
use Filament\Tables\Actions\ImportAction;
use App\Filament\Resources\FoodResource\Pages;

class FoodResource extends Resource
{
    protected static ?string $model = Food::class;
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    /* ---------- Form ---------- */
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2) // Two-column layout
                ->schema([

                    // LEFT COLUMN – Food Info
                    Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\TextInput::make('food_name')->required()->maxLength(255),
                            Forms\Components\TextInput::make('serving_quantity')->required(),
                            Forms\Components\TextInput::make('unit')->required(),
                            Forms\Components\TextInput::make('us_measurement')->label('US Measurement'),
                            Forms\Components\TextInput::make('metric_measurement')->label('Metric Measurement'),

                            Forms\Components\Select::make('fodmap_rating')
                                ->options([
                                    'Low' => 'Low',
                                    'Moderate' => 'Moderate',
                                    'High' => 'High',
                                ])->required(),

                            Forms\Components\TextInput::make('fodmap_type'),
                            Forms\Components\TextInput::make('food_category')->required(),
                            Forms\Components\TextInput::make('dietary_tags'),
                            Forms\Components\TextInput::make('usda_match'),
                            Forms\Components\TextInput::make('reference'),
                        ]),

                    // RIGHT COLUMN – Health Info
                    Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\Fieldset::make('FODMAP Triggers')
                                ->schema([
                                    Forms\Components\Checkbox::make('fructose'),
                                    Forms\Components\Checkbox::make('lactose'),
                                    Forms\Components\Checkbox::make('sorbitol'),
                                    Forms\Components\Checkbox::make('mannitol'),
                                    Forms\Components\Checkbox::make('fructans'),
                                    Forms\Components\Checkbox::make('gos'),
                                ]),

                            Forms\Components\Fieldset::make('Bloating Risk (0–10)')
                                ->schema([
                                    Forms\Components\TextInput::make('bloating_risk_standard')->numeric()->required(),
                                    Forms\Components\TextInput::make('bloating_risk_low')->numeric()->required(),
                                    Forms\Components\TextInput::make('bloating_risk_medium')->numeric()->required(),
                                    Forms\Components\TextInput::make('bloating_risk_high')->numeric()->required(),
                                ]),

                            Forms\Components\Textarea::make('ibs_notes')->columnSpanFull(),

                            Forms\Components\Toggle::make('vegan'),
                            Forms\Components\Toggle::make('gluten_free'),
                            Forms\Components\Toggle::make('vegetarian'),
                        ]),
                ]),
        ]);
    }



    /* ---------- Table ---------- */
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('food_name')->sortable()->searchable(),
                Tables\Columns\BadgeColumn::make('fodmap_rating')->colors([
                    'success' => 'Low',
                    'warning' => 'Moderate',
                    'danger' => 'High'
                ]),
                Tables\Columns\TextColumn::make('serving_quantity'),
                Tables\Columns\TextColumn::make('food_category'),
                Tables\Columns\TextColumn::make('bloating_risk_standard')
                    ->label('Bloating Risk (Std)'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('fodmap_rating')
                    ->options(['Low' => 'Low', 'Moderate' => 'Moderate', 'High' => 'High']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFood::route('/'),
            'create' => Pages\CreateFood::route('/create'),
            'edit'   => Pages\EditFood::route('/{record}/edit'),
        ];
    }
}
