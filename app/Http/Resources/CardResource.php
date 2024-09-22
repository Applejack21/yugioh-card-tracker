<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class CardResource extends JsonResource
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
			'card_sets' => CardCardSetsResource::collection($this->whenLoaded('cardSets')),
			'card_prices' => new CardPriceResource($this->whenLoaded('cardPrice')),
			'card_id' => $this->card_id,
			'name' => $this->name,
			'type' => $this->type,
			'type_image' => $this->getTypeImage(),
			'human_readable_card_type' => $this->human_readable_card_type,
			'frame_type' => $this->frame_type,
			'short_description' => Str::limit($this->description, 100, preserveWords: true),
			'description' => $this->description,
			'ygo_pro_deck_url' => $this->ygo_pro_deck_url,
			'atk' => $this->atk,
			'def' => $this->def,
			'level' => $this->level,
			'race' => $this->race,
			'attribute' => $this->attribute,
			'archetype' => $this->archetype,
			'scale' => $this->scale,
			'link_val' => $this->link_val,
			'type_line' => $this->type_line,
			'type_line_string' => $this->type_line ? implode(' / ', $this->type_line) : null,
			'tcg_date' => $this->tcg_date,
			'ocg_date' => $this->ocg_date,
			'tcg_date_formatted' => $this?->tcg_date?->format('Y-m-d'),
			'ocg_date_formatted' => $this?->ocg_date?->format('Y-m-d'),
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}

	/**
	 * Get the image for the type of card this is.
	 */
	private function getTypeImage(): string
	{
		if (Str::contains(strtolower($this->type), 'spell card')) {
			return url('images/types/spell.svg');
		}

		if (Str::contains(strtolower($this->type), 'trap card')) {
			return url('images/types/trap.svg');
		}

		$attribute = strtolower($this->attribute);

		return url("images/types/$attribute.svg");
	}
}
