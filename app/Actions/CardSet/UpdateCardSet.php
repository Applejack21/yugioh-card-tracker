<?php

namespace App\Actions\CardSet;

use App\Models\CardSet;

class UpdateCardSet
{
    /**
     * Update a card set in the database.
     *
     * @param  CardSet  $cardSet  The card set we're updating.
     * @param  array  $request  Request param data.
     */
    public function execute(CardSet $cardSet, array $request): CardSet
    {
        $date = $request['tcg_date'] == '0000-00-00' ? today() : $request['tcg_date'];

        $cardSet->update([
            'name' => $request['set_name'] ?? $cardSet->name,
            'code' => $request['set_code'] ?? $cardSet->code,
            'total_cards' => $request['num_of_cards'] ?? $cardSet->total_cards,
            'release_date' => $date ?? $cardSet->release_date,
            'ygo_pro_deck_image_url' => $request['set_image'] ?? $cardSet->ygo_pro_deck_image_url,
        ]);

        // Add the card set image. Should be added when the card set record is made but just in case it didn't get added.
        if ($cardSet->getFirstMediaUrl('image') == '' && isset($request['set_image'])) {
            $this->saveImage($cardSet, $request['set_image']);
        }

        return tap($cardSet)->refresh();
    }

    /**
     * Save the image for this card set.
     *
     * @param  CardSet  $cardSet  The card set model to save the image to.
     * @param  string  $imageUrl  The image url.
     */
    private function saveImage(CardSet $cardSet, string $imageUrl): void
    {
        try {
            $cardSet->addMediaFromUrl($imageUrl)
                ->toMediaCollection('image');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
