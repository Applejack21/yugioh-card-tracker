<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardCardSetsResource extends JsonResource
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
            'card' => new CardResource($this->whenLoaded('card')),
            'card_set' => new CardSetResource($this->whenLoaded('cardSet')),
            'card_rarity' => new CardRarityResource($this->whenLoaded('cardRarity')),
            'code' => $this->code,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
