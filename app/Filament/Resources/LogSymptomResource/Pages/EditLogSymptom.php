<?php

namespace App\Filament\Resources\LogSymptomResource\Pages;

use App\Filament\Resources\LogSymptomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLogSymptom extends EditRecord
{
    protected static string $resource = LogSymptomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
