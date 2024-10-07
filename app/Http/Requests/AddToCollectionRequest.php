<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddToCollectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Get a list of rarity ids this card can have.
        $cardRarities = $this->card->getCardRarities()
            ->map(function ($rarity) {
                return $rarity->id;
            })
            ->toArray();

        return [
            'rarity_id' => ['required', 'integer', Rule::in($cardRarities), Rule::exists('card_rarities', 'id')],
            'quantity' => ['required', 'integer'],
        ];
    }
}
