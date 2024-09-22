<?php

namespace App\Actions\CardSet;

use App\Models\CardSet;

class CreateCardSet
{
    /**
     * Import a new card set to the database.
     *
     * @param  array  $request  Request param data.
     */
    public function execute(array $request): CardSet
    {
        $date = $request['tcg_date'] == '0000-00-00' ? today() : $request['tcg_date'];

        $cardSet = CardSet::create([
            'name' => $request['set_name'],
            'code' => $request['set_code'],
            'total_cards' => $request['num_of_cards'],
            'release_date' => $date,
            'ygo_pro_deck_image_url' => $request['set_image'] ?? null,
        ]);

        // Save the image of the card set.
        if (isset($request['set_image'])) {
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
