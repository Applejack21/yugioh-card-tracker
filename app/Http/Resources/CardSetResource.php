<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

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
			'name' => $this->name,
			'code' => $this->code,
			'total_cards' => $this->total_cards,
			'release_date' => $this->release_date,
			'release_date_formatted' => Carbon::parse($this->release_date)->format('Y-m-d'),
			'ygo_pro_deck_image_url' => $this->ygo_pro_deck_image_url,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
    }
}
