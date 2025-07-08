<?php

namespace App\Filament\Resources\CustomFoodResource\Pages;

use App\Filament\Resources\CustomFoodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomFood extends EditRecord
{
    protected static string $resource = CustomFoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
