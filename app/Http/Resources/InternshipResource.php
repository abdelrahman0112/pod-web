<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InternshipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'company_name' => $this->company_name,
            'location' => $this->location,
            'type' => $this->type,
            'duration' => $this->duration,
            'application_deadline' => $this->application_deadline?->toISOString(),
            'start_date' => $this->start_date?->toISOString(),
            'status' => is_object($this->status) && method_exists($this->status, 'value') ? $this->status->value : $this->status,
            'has_applied' => $user && $this->applications()->where('user_id', $user->id)->exists(),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
