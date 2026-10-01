<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'entity_type' => class_basename($this->entity_type),
            'entity_id' => $this->entity_id,
            'summary' => $this->summary,
            'actor' => $this->actor ? [
                'id' => $this->actor->id,
                'name' => $this->actor->name,
            ] : [
                'id' => null,
                'name' => 'Sistema',
            ],
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}