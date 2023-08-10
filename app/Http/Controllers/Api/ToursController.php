<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ToursRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;
use Illuminate\Database\Eloquent\Builder;


/**
 * @group public 
 * 
 * Api for managging a Tours

 */
class ToursController extends Controller
{
    /** 
     * Tours list
     * 
     * list all tours for a travel 
     * 
     * @queryParam page the page you require. Example: 1
     * @response{
     * "data": [
     *       {
     *           "id": "99a520f1-18fe-494c-a96d-f758b4021893",
     *           "name": "Voluptatem.",
     *           "starting_date": "2023-07-14",
     *          "ending_date": "2023-07-24",
     *         "price": "282.90"
     *    }
     *  ],
     * }
     *
     */

    public function index(Travel $travel, ToursRequest $request)
    {
        $tours = $travel->tours()
            ->when(
                $request->dateFrom,
                fn (Builder $builder) => $builder->where('starting_date', '>=', $request->dateFrom)
            )
            ->when(
                $request->dateTo,
                fn (Builder $builder) => $builder->where('ending_date', '<=', $request->dateTo)
            )
            ->when(
                $request->priceFrom,
                fn (Builder $builder) => $builder->where('price', '>=', $request->priceFrom * 100)
            )
            ->when(
                $request->priceTo,
                fn (Builder $builder) => $builder->where('price', '<=', $request->priceTo * 100)
            )
            ->when(
                $request->sortBy && $request->sortOrder,
                fn (Builder $builder) => $builder->orderBy($request->sortBy, $request->sortOrder)
            )
            ->orderBy('starting_date')
            ->paginate(15);

        return TourResource::collection($tours);
    }
}
