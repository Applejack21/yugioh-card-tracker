<?php

namespace App\Http\Controllers;

use App\Actions\CardSet\GetCardSets;
use App\Http\Requests\StoreCardSetRequest;
use App\Http\Requests\UpdateCardSetRequest;
use App\Http\Resources\CardSetResource;
use App\Models\CardSet;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CardSetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request  Any request data.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('CardSets', [
            'title' => 'Card Sets',
            'cardSets' => fn () => CardSetResource::collection((new GetCardSets)->execute($request)),
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
    public function store(StoreCardSetRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request  Any request data.
     * @param  CardSet  $cardSet  The card set we're viewing.
     */
    public function show(Request $request, CardSet $cardSet)
    {
        dd($cardSet);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CardSet $cardSet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCardSetRequest $request, CardSet $cardSet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CardSet $cardSet)
    {
        //
    }
}
