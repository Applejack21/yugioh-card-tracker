<?php

namespace App\Http\Controllers;

use App\Actions\Card\GetCards;
use App\Http\Requests\AddToCollectionRequest;
use App\Http\Resources\CardResource;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CardController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @param  Request  $request  Any request data.
	 */
	public function index(Request $request): Response
	{
		return Inertia::render('Cards', [
			'title' => 'Cards',
			'cards' => fn() => CardResource::collection((new GetCards)->execute($request, load: ['cardPrice', 'cardSets.cardSet', 'cardSets.cardRarity'])),
		]);
	}

	/**
	 * Add the passed card to the user's collection.
	 *
	 * @param  AddToCollectionRequest  $request  The request data.
	 * @param  Card  $card  The card the user is adding to their account.
	 */
	public function addToCollection(AddToCollectionRequest $request, Card $card)
	{
		// Get the user.
		$user = Auth::user();

		// Does this user have this card currently in their collection? If so update it.
		if ($collectionRow = $user->hasCardInCollection($card->id, $request->rarity_id)) {
			// Update the row in the database.
			// We can't use updateExistingPivot() as if the user has multiple cards (of different rarites) it'll make them all the same rarity and quantity.
			// as it'll go off the "card_id" but not both "card_id" and "card_rarity_id".
			DB::table('card_user')
				->where('user_id', $user->id)
				->where('card_id', $card->id)
				->where('card_rarity_id', $request->rarity_id)
				->update([
					'quantity' => $collectionRow->pivot->quantity + $request->quantity,
				]);
		} else {
			// Otherwise add it. We can add it via attach normally as it doesn't exist yet.
			$user->cards()->attach([
				$card->id => [
					'card_rarity_id' => $request->rarity_id,
					'quantity' => $request->quantity,
				],
			]);
		}

		return redirect()->back()->with('message', [
			'id' => Str::uuid(),
			'type' => 'success',
			'message' => 'Added "' . $card->name . '" to your collection successfully.',
		]);
	}

	/**
	 * Decrease the quantity of the collected card by 1.
	 *
	 * @param  Request  $request  The request data the contains the rarity_id to decrease.
	 * @param  Card  $card  The card the user has collected.
	 */
	public function decreaseCardQuantity(Request $request, Card $card)
	{
		$user = Auth::user();

		if ($collectionRow = $user->hasCardInCollection($card->id, $request->rarity_id)) {
			$newQuantity = $collectionRow->pivot->quantity - 1;
			DB::table('card_user')
				->where('user_id', $user->id)
				->where('card_id', $card->id)
				->where('card_rarity_id', $request->rarity_id)
				->update([
					'quantity' => $newQuantity < 0 ? 0 : $newQuantity,
				]);

			return redirect()->back()->with('message', [
				'id' => Str::uuid(),
				'type' => 'success',
				'message' => 'Decreased "' . $card->name . '" quantity by 1.',
			]);
		}
	}
}
