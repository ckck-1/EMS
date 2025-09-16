<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date->format('Y-m-d'),
            'time' => $this->time->format('H:i'),
            'location' => $this->location,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'max_attendees' => $this->max_attendees,
            'confirmed_attendees_count' => $this->confirmed_attendees_count,
            'is_full' => $this->isFull(),
            'is_upcoming' => $this->isUpcoming(),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'color' => $this->category->color,
            ],
            'organizer' => [
                'id' => $this->organizer->id,
                'name' => $this->organizer->name,
            ],
        ];
    }
}