<?php

namespace App\Filament\Imports;

use App\Models\Food;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class FoodImporter extends Importer
{
    protected static ?string $model = Food::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('food_name')->requiredMapping(),

            ImportColumn::make('serving_quantity')
                ->requiredMapping(),
            ImportColumn::make('unit')->requiredMapping(),
            ImportColumn::make('us_measurement')->label('US Measurement'),
            ImportColumn::make('metric_measurement')->label('Metric Measurement'),

            ImportColumn::make('fodmap_rating')->requiredMapping(),
            ImportColumn::make('fodmap_type'),

            // FODMAP triggers
            ImportColumn::make('fructose')->boolean(),
            ImportColumn::make('lactose')->boolean(),
            ImportColumn::make('sorbitol')->boolean(),
            ImportColumn::make('mannitol')->boolean(),
            ImportColumn::make('fructans')->boolean(),
            ImportColumn::make('gos')->boolean(),

            ImportColumn::make('food_category')->requiredMapping(),
            ImportColumn::make('ibs_notes'),
            ImportColumn::make('dietary_tags'),

            ImportColumn::make('vegan')->boolean(),
            ImportColumn::make('gluten_free')->boolean(),
            ImportColumn::make('vegetarian')->boolean(),

            ImportColumn::make('usda_match'),

            ImportColumn::make('bloating_risk_standard')
                ->numeric()->rules(['numeric', 'between:0,10'])->requiredMapping(),

            ImportColumn::make('bloating_risk_low')
                ->numeric()->rules(['numeric', 'between:0,10'])->requiredMapping(),

            ImportColumn::make('bloating_risk_medium')
                ->numeric()->rules(['numeric', 'between:0,10'])->requiredMapping(),

            ImportColumn::make('bloating_risk_high')
                ->numeric()->rules(['numeric', 'between:0,10'])->requiredMapping(),

            ImportColumn::make('reference'),
        ];
    }

    public function resolveRecord(): ?Food
    {
        // Update if same food_name exists, otherwise create new
        return Food::firstOrNew(['food_name' => $this->data['food_name']]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = number_format($import->successful_rows) . ' rows imported';
        if ($failed = $import->getFailedRowsCount()) {
            $body .= " • " . number_format($failed) . ' failed';
        }
        return $body . '.';
    }
}
