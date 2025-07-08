<?php

namespace App\Filament\Resources\FoodRiskHistoryResource\Pages;

use App\Filament\Resources\FoodRiskHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFoodRiskHistory extends EditRecord
{
    protected static string $resource = FoodRiskHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
