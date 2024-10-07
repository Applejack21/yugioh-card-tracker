<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardSetResource extends JsonResource
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
            'image' => $this->getFirstMedia('image') ? $this->getFirstMediaUrl('image') : $this->ygo_pro_deck_url,
            'name' => $this->name,
            'code' => $this->code,
            'total_cards' => $this->total_cards,
            'release_date' => $this->release_date,
            'release_date_formatted' => $this?->release_date?->format('Y-m-d'),
            'ygo_pro_deck_image_url' => $this->ygo_pro_deck_image_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
