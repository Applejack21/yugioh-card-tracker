<?php

namespace App\Http\Controllers;

use App\Actions\Card\GetCards;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Http\Resources\CardResource;
use App\Models\Card;
use Illuminate\Http\Request;
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
            'cards' => fn () => CardResource::collection((new GetCards)->execute($request, load: ['cardPrice', 'cardSets.cardSet', 'cardSets.cardRarity'])),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCardRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCardRequest $request, Card $card)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        //
    }
}
