<?php

namespace App\Filament\Resources\CustomFoodResource\Pages;

use App\Filament\Resources\CustomFoodResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomFood extends CreateRecord
{
    protected static string $resource = CustomFoodResource::class;
}
