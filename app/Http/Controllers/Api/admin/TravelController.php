<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TravelRequest;
use App\Http\Resources\TravelsResource;
use App\Models\Travel;

/**
 * @group admin
 * @authenticated
 * @header Content-Type application/json
 * 
 * Api for managging a travel 
 * 
 */
class TravelController extends Controller
{

    /**
     * Add A travel to Travels list 
     *
     * This endpoint allows you to add a Travel to the Travels list.
     * It's a really useful endpoint, and you should be an admin 
     * <aside class="notice">We mean it; you really should.😕</aside>
     * @response{
     * "data": 
     *      {
     *          "id": "99a51e92-ad40-4e85-a7d5-d778e45b6d6b",
     *          "name": "Prof. Rylan Williamson DDS",
     *          "slug": "prof-rylan-williamson-dds",
     *          "description": "Dignissimos optio ut numquam quo nam id.",
     *          "number_of_days": 14,
     *          "number_of_nights": 13
     *      },
     * }
     */
    public function store(TravelRequest $request)
    {

        $travel = Travel::create($request->validated());

        return new TravelsResource($travel);
    }

    /**
     * update A travel 
     *
     * This endpoint allows you to update a Travel .
     * It's a really useful endpoint, and you should be an admin\
     * @response{
     * "data": 
     *      {
     *          "id": "99a51e92-ad40-4e85-a7d5-d778e45b6d6b",
     *          "name": "Prof. Rylan Williamson DDS",
     *          "slug": "prof-rylan-williamson-dds",
     *          "description": "Dignissimos optio ut numquam quo nam id.",
     *          "number_of_days": 12,
     *          "number_of_nights": 13
     *      },
     * }
     *
     */

    public function update(Travel $travel, TravelRequest $request)
    {
        $travel->update($request->validated());
        return new TravelsResource($travel);
    }
}
