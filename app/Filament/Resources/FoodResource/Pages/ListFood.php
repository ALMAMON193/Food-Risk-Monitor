<?php

namespace App\Filament\Resources\FoodResource\Pages;

use Filament\Actions;
use App\Filament\Imports\FoodImporter;
use App\Filament\Resources\FoodResource;
use Filament\Resources\Pages\ListRecords;

class ListFood extends ListRecords
{
    protected static string $resource = FoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ImportAction::make()->importer(FoodImporter::class),
        ];
    }
}
