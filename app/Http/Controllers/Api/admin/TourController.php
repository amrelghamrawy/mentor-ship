<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TourRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;

/**
 * @group admin
 * @authenticated
 * @header Content-Type application/json
 * 
 * Api for managging a Tours 
 * 
 */

class TourController extends Controller
{

    /**
     * Tour store Api
     * 
     * Tour store for a specific travel 
     * 
     * @response{
     * "data": 
     *   {
     *       "id": "99a520f1-18fe-494c-a96d-f758b4021893",
     *       "name": "Voluptatem.",
     *       "starting_date": "2023-07-14",
     *       "ending_date": "2023-07-24",
     *       "price": "282.90"
     *   }
     * }
     */

    public function store(Travel $travel, TourRequest $request)
    {
        $tour = $travel->tours()->create($request->validated());

        return new TourResource($tour);
    }
}
