<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardPriceResource extends JsonResource
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
            'cardmarket_price' => $this->cardmarket_price,
            'tcgplayer_price' => $this->tcgplayer_price,
            'ebay_price' => $this->ebay_price,
            'amazon_price' => $this->amazon_price,
            'coolstuffinc_price' => $this->coolstuffinc_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
