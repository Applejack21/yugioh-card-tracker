<?php

namespace App\Actions\Card;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Laravel\Scout\Builder;

class GetCards
{
    /**
     * Get a list of data from the model.
     *
     * @param  Request  $request  Request param data.
     * @param  bool  $paginate  Decide if the data should be paginated or not. Default is true.
     * @param  array  $load  Decide what relationship data to load.
     * @param  array  $count  Decide what relationship data to count for card sets.
     * @param  array  $select  Decide what data to select. Default is all columns.
     * @return LengthAwarePaginator|Collection - Paginator if paginate is true, Collection if false.
     */
    public function execute(Request $request, $paginate = true, $load = [], $count = [], $select = ['*']): LengthAwarePaginator|Collection
    {
        $cards = Card::search($request->search)
            ->query(function ($query) use ($select) {
                $query->select($select);
            })
            ->when($request->orderBy, function (Builder $query, string $val) use ($request) {
                $query->orderBy($val, $request->orderDirection ?? 'asc');
            }, function (Builder $query) {
                $query->orderBy('tcg_date', 'desc');
            });

        // If we're paginating data.
        if ($paginate) {
            $cards = $cards->paginate($request->perPage ?? 30)
                ->appends(['query' => null]);
        } else {
            // If we aren't paginating data.
            $cards = $cards->get();
        }

        if (count($load) > 0) {
            $cards->loadMissing($load);
        }

        if (count($count) > 0) {
            $cards->loadCount($count);
        }

        return $cards;
    }
}
