<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodRiskHistoryResource\Pages;
use App\Models\FoodRiskHistory;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class FoodRiskHistoryResource extends Resource
{
    protected static ?string $model = FoodRiskHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                ->label('User Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('food_name')->label('Food Name')->searchable(),
                TextColumn::make('serving_quantity')->label('Serving Quantity'),
                TextColumn::make('us_measurement')->label('US Measurement'),
                TextColumn::make('metric_measurement')->label('Metric Measurement'),
                TextColumn::make('meal_type')->label('Meal Type'),
                TextColumn::make('risk_score')->label('Risk Score'),
                TextColumn::make('risk_label')->label('Risk Label'),
            ])
            ->filters([])
            ->actions([

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFoodRiskHistories::route('/'),

        ];
    }
}
