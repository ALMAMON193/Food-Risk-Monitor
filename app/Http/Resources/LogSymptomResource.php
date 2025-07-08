<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogSymptomResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'food_name'           =>$this->food_name ?? '',
            'bloating'            => $this->bloating ?? '',
            'gas'                 =>$this->gas ?? '',
            'pain'                =>$this->pain ?? '',
            'stool_issues'        =>$this->stool_issues ?? '',
            'notes'                =>$this->notes ?? '',
            'created_at'          => $this->created_at?->toDateTimeString(),
            'updated_at'          => $this->updated_at?->toDateTimeString(),
        ];
    }
}
