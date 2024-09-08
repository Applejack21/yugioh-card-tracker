<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CardSet>
 */
class CardSetFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'name' => $this->faker->words(5, true),
			'code' => Str::upper($this->faker->lexify()) . '-EN',
			'total_cards' => $this->faker->numberBetween(1, 100),
			'release_date' => $this->faker->dateTimeBetween('-10 years'),
		];
	}

	// Not actually needed for card sets, but will be for cards rarity they appear per set.
	// So leave commented out here for future use on card_card_sets table.
	//
	// /**
	//  * Return a random rarity for a card set.
	//  */
	// private function randomRarity(): string
	// {
	//     return Arr::random([
	//         'Normal',
	//         'Common',
	//         'Normal Rare',
	//         'Rare',
	//         'Super Rare',
	//         'Holofoil Rare',
	//         'Ultra Rare',
	//         'Ultimate Rare',
	//         'Secret Rare',
	//         'Ultra Secret Rare',
	//         'Secret Ultra Rare',
	//         'Prismatic Secret Rare',
	//         'Holographic Rare',
	//         'Parallel Rare',
	//         'Normal Parallel Rare',
	//         'Super Parallel Rare',
	//         'Ultra Parallel Rare',
	//         'Duel Terminal Normal Parallel Rare',
	//         'Duel Terminal Rare Parallel Rare',
	//         'Duel Terminal Super Parallel Rare',
	//         'Duel Terminal Ultra Parallel Rare',
	//         'Duel Terminal Secret Parallel Rare',
	//         'Gold Rare',
	//     ]);
	// }

	// /**
	//  * Return the rarity code associated with the rarity.
	//  *
	//  * @param  string  $rarity  The rarity to find the code from.
	//  */
	// private function rarityCode(string $rarity): string
	// {
	//     return match ($rarity) {
	//         'Normal' => '(N)',
	//         'Normal Rare' => '(NR)',
	//         'Rare' => '(R)',
	//         'Super Rare' => '(SR)',
	//         'Holofoil Rare' => '(HFR)',
	//         'Ultra Rare' => '(UR)',
	//         'Ultimate Rare' => '(UtR)',
	//         'Secret Rare' => '(ScR)',
	//         'Ultra Secret Rare' => '(UScR)',
	//         'Secret Ultra Rare' => '(ScUR)',
	//         'Prismatic Secret Rare' => '(PScR)',
	//         'Holographic Rare' => '(HGR)',
	//         'Parallel Rare' => '(PR)',
	//         'Normal Parallel Rare' => '(NPR)',
	//         'Super Parallel Rare' => '(SPR)',
	//         'Ultra Parallel Rare' => '(UPR)',
	//         'Duel Terminal Normal Parallel Rare' => '(DNPR)',
	//         'Duel Terminal Rare Parallel Rare' => '(DRPR)',
	//         'Duel Terminal Super Parallel Rare' => '(DSPR)',
	//         'Duel Terminal Ultra Parallel Rare' => '(DUPR)',
	//         'Duel Terminal Secret Parallel Rare' => '(DScPR)',
	//         'Gold Rare' => '(GUR)',
	//     };
	// }
}
