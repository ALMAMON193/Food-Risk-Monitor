<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogSymptomResource\Pages;
use App\Models\LogSymptom;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class LogSymptomResource extends Resource
{
    protected static ?string $model = LogSymptom::class;

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
                TextColumn::make('bloating')->label('Bloating'),
                TextColumn::make('gas')->label('Gas'),
                TextColumn::make('pain')->label('Pain'),
                TextColumn::make('stool_issues')->label('Stool Issues'),
                TextColumn::make('created_at')->dateTime()->label('Logged At'),
            ])
            ->filters([])
            ->actions([

                Tables\Actions\DeleteAction::make(), // ✅ Delete
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
            'index' => Pages\ListLogSymptoms::route('/'),

        ];
    }
}
