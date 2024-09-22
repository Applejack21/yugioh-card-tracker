<?php

namespace App\Actions\Card;

use App\Actions\BanList\CreateBanList;
use App\Actions\CardCardSet\CreateCardCardSet;
use App\Actions\CardPrice\CreateCardPrice;
use App\Actions\CardRarity\CreateCardRarity;
use App\Models\Card;
use App\Models\CardRarity;
use App\Models\CardSet;

class CreateCard
{
	/**
	 * Import a new card to the database.
	 *
	 * @param  array  $request  Request param data.
	 */
	public function execute(array $request): Card
	{
		// Get the images array. Returns 3 images but only interested in the main full card image.
		$images = $request['card_images'] ?? [];

		// Get relationship data.
		$sets = $request['card_sets'] ?? [];
		$prices = $request['card_prices'] ?? [];
		$banList = $request['banlist_info'] ?? [];

		// Map data for the database.
		$request = $this->formatDataForDatabase($request);

		// Save the card.
		$card = Card::create([
			...$request,
		]);

		// Save this image (if we have an image).
		if ($images && isset($images[0]) && isset($images[0]['image_url'])) {
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
	 * @param  array  $request  Data from the API.
	 */
	private function formatDataForDatabase(array $request): array
	{
		$tcgDate = null;
		$ocgDate = null;

		// Get release dates.
		if (isset($request['misc_info']) && isset($request['misc_info'][0])) {
			$tcgDate = $request['misc_info'][0]['tcg_date'] ?? null;
			$ocgDate = $request['misc_info'][0]['ocg_date'] ?? null;
		}

		// Set the data to store in the database.
		return [
			'card_id' => $request['id'],
			'name' => $request['name'],
			'type' => $request['type'],
			'human_readable_card_type' => $request['humanReadableCardType'],
			'frame_type' => $request['frameType'],
			'type_line' => $request['typeline'] ?? [],
			'description' => $request['desc'] ?? null,
			'ygo_pro_deck_url' => $request['ygoprodeck_url'],
			'atk' => $request['atk'] ?? null,
			'def' => $request['def'] ?? null,
			'level' => $request['level'] ?? null,
			'race' => $request['race'] ?? null,
			'attribute' => $request['attribute'] ?? null,
			'archetype' => $request['archetype'] ?? null,
			'scale' => $request['scale'] ?? null,
			'link_val' => $request['linkval'] ?? null,
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
			if (is_null($cardRarity = CardRarity::where('name', $set['set_rarity'])->where('code', $set['set_rarity_code'])->first())) {
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

			// Assign this card to this set with rarity and price.
			$cardSet = (new CreateCardCardSet)->execute([
				'card_id' => $card->id,
				'card_set_id' => $cardSet->id,
				'card_rarity_id' => $cardRarity->id,
				'code' => $set['set_code'],
				'price' => $set['set_price'],
			]);
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
		$cardPrice = (new CreateCardPrice)->execute([
			'card_id' => $card->id,
			...$prices,
		]);
	}

	/**
	 * Insert the ban list for this card.
	 *
	 * @param  Card  $card  The card the ban list is for.
	 * @param  array  $banList  The ban list for this card.
	 */
	private function insertBanList(Card $card, array $banList): void
	{
		$banList = (new CreateBanList)->execute([
			'card_id' => $card->id,
			'ban_tcg' => $banList['ban_tcg'] ?? null,
			'ban_ocg' => $banList['ban_ocg'] ?? null,
			'ban_goat' => $banList['ban_goat'] ?? null,
		]);
	}
}
