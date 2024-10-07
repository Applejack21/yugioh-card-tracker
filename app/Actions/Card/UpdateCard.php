<?php

namespace App\Actions\Card;

use App\Actions\BanList\CreateBanList;
use App\Actions\BanList\UpdateBanList;
use App\Actions\CardCardSet\CreateCardCardSet;
use App\Actions\CardCardSet\UpdateCardCardSet;
use App\Actions\CardPrice\CreateCardPrice;
use App\Actions\CardPrice\UpdateCardPrice;
use App\Actions\CardRarity\CreateCardRarity;
use App\Actions\CardRarity\UpdateCardRarity;
use App\Models\BanList;
use App\Models\Card;
use App\Models\CardCardSet;
use App\Models\CardPrice;
use App\Models\CardRarity;
use App\Models\CardSet;

class UpdateCard
{
    /**
     * Update a card in the database.
     *
     * @param  Card  $card  The card we're updating.
     * @param  array  $request  Request param data.
     * @param  bool  $updateImage  Decide if the image should be updated or not.
     */
    public function execute(Card $card, array $request, $updateImage): Card
    {
        // Get the images array. Returns 3 images but only interested in the main full card image.
        $images = $request['card_images'] ?? [];

        // Get relationship data.
        $sets = $request['card_sets'] ?? [];
        $prices = $request['card_prices'] ?? [];
        $banList = $request['banlist_info'] ?? [];

        // Map data for the database.
        $request = $this->formatDataForDatabase($card, $request);

        $card->update([
            ...$request,
        ]);

        // Update the card's image (if we have an image) and bool is true.
        if ($images && isset($images[0]) && isset($images[0]['image_url']) && $updateImage) {
            $this->saveImage($card, $images[0]['image_url']);
        }

        // Assign this card to its sets.
        if ($sets && count($sets) > 0) {
            $this->assignCardToSets($card, $sets);
        }

        // Save the card prices.
        if ($prices && $prices[0] && count($prices[0]) > 0) {
            $this->insertCardPrices($card, $prices[0]);
        }

        // Save the banlist for this card.
        if ($banList && count($banList) > 0) {
            $this->insertBanList($card, $banList);
        }

        return tap($card)->refresh();
    }

    /**
     * Format the data from the API to insert it into the database.
     *
     * @param  Card  $card  The card we're updating. Used if the value isn't returned from API use whatever card value is.
     * @param  array  $request  Data from the API.
     */
    private function formatDataForDatabase(Card $card, array $request): array
    {
        $tcgDate = null;
        $ocgDate = null;

        // Get release dates.
        if (isset($request['misc_info']) && isset($request['misc_info'][0])) {
            $tcgDate = $request['misc_info'][0]['tcg_date'] ?? $card->tcg_date;
            $ocgDate = $request['misc_info'][0]['ocg_date'] ?? $card->ocg_date;
        }

        // Set the data to store in the database.
        return [
            'card_id' => $request['id'] ?? $card->card_id,
            'name' => $request['name'] ?? $card->name,
            'type' => $request['type'] ?? $card->type,
            'human_readable_card_type' => $request['humanReadableCardType'] ?? $card->human_readable_card_type,
            'frame_type' => $request['frameType'] ?? $card->frame_type,
            'type_line' => $request['typeline'] ?? $card->type_line,
            'description' => $request['desc'] ?? $card->description,
            'ygo_pro_deck_url' => $request['ygoprodeck_url'] ?? $card->ygo_pro_deck_url,
            'atk' => $request['atk'] ?? $card->atk,
            'def' => $request['def'] ?? $card->def,
            'level' => $request['level'] ?? $card->level,
            'race' => $request['race'] ?? $card->race,
            'attribute' => $request['attribute'] ?? $card->attribute,
            'archetype' => $request['archetype'] ?? $card->archetype,
            'scale' => $request['scale'] ?? $card->scale,
            'link_val' => $request['linkval'] ?? $card->link_val,
            'tcg_date' => $tcgDate,
            'ocg_date' => $ocgDate,
        ];
    }

    /**
     * Save the image for this card.
     *
     * @param  Card  $card  The card model to save the image to.
     * @param  string  $imageUrl  The image url.
     */
    private function saveImage(Card $card, string $imageUrl): void
    {
        try {
            $card->addMediaFromUrl($imageUrl)
                ->toMediaCollection('image');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Link this card to the sets it appears in.
     *
     * @param  Card  $card  The card to link the sets to.
     * @param  array  $sets  The card sets to link to.
     */
    private function assignCardToSets(Card $card, array $sets): void
    {
        foreach ($sets as $set) {
            // Check to see if this rarity exists in the database.
            $cardRarity = CardRarity::where('name', $set['set_rarity'])->where('code', $set['set_rarity_code'])->first();

            if ($cardRarity) {
                $cardRarity = (new UpdateCardRarity)->execute($cardRarity, [
                    'name' => $set['set_rarity'] ?? $cardRarity->name,
                    'code' => $set['set_rarity_code'] ?? $cardRarity->code,
                ]);
            } else {
                $cardRarity = (new CreateCardRarity)->execute([
                    'name' => $set['set_rarity'],
                    'code' => $set['set_rarity_code'],
                ]);
            }

            // Check to see if this card set exists in the database. It should do as cards are imported after the sets.
            // If the card set doesn't exist then just end this operation.
            if (is_null($cardSet = CardSet::where('name', $set['set_name'])->first())) {
                return;
            }

            // Get the card card set record for this card and set.
            $cardCardSet = CardCardSet::where('card_id', $card->id)
                ->where('card_set_id', $cardSet->id)
                ->where('card_rarity_id', $cardRarity->id)
                ->first();

            // Update this record in the database.
            if ($cardCardSet) {
                $cardSet = (new UpdateCardCardSet)->execute($cardCardSet, [
                    'card_id' => $card->id,
                    'card_set_id' => $cardSet->id,
                    'card_rarity_id' => $cardRarity->id,
                    'code' => $set['set_code'] ?? $cardCardSet->code,
                    'price' => $set['set_price'] ?? $cardCardSet->price,
                ]);
            } else {
                $cardSet = (new CreateCardCardSet)->execute([
                    'card_id' => $card->id,
                    'card_set_id' => $cardSet->id,
                    'card_rarity_id' => $cardRarity->id,
                    'code' => $set['set_code'],
                    'price' => $set['set_price'],
                ]);
            }
        }
    }

    /**
     * Insert the card prices.
     *
     * @param  Card  $card  The card the prices are being assigned to.
     * @param  array  $prices  The prices array for this card.
     */
    private function insertCardPrices(Card $card, array $prices): void
    {
        $cardPrice = CardPrice::where('card_id', $card->id)->first();

        if ($cardPrice) {
            $cardPrice = (new UpdateCardPrice)->execute($cardPrice, [
                'card_id' => $card->id,
                ...$prices,
            ]);
        } else {
            $cardPrice = (new CreateCardPrice)->execute([
                'card_id' => $card->id,
                ...$prices,
            ]);
        }
    }

    /**
     * Insert the ban list for this card.
     *
     * @param  Card  $card  The card the ban list is for.
     * @param  array  $banList  The ban list for this card.
     */
    private function insertBanList(Card $card, array $banList): void
    {
        $banList = BanList::where('card_id', $card->id)->first();

        if ($banList) {
            $banList = (new UpdateBanList)->execute($banList, [
                'card_id' => $card->id,
                'ban_tcg' => $banList['ban_tcg'] ?? null,
                'ban_ocg' => $banList['ban_ocg'] ?? null,
                'ban_goat' => $banList['ban_goat'] ?? null,
            ]);
        } else {
            $banList = (new CreateBanList)->execute([
                'card_id' => $card->id,
                'ban_tcg' => $banList['ban_tcg'] ?? null,
                'ban_ocg' => $banList['ban_ocg'] ?? null,
                'ban_goat' => $banList['ban_goat'] ?? null,
            ]);
        }
    }
}
