<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TravelsResource;
use App\Models\Travel;

/**
 * @group public 
 * 
 * managging public travels
 */
class TravelsController extends Controller
{
    /** 
     * travels list
     * 
     * list all public travels 
     * @queryParam page the page you require Example: 1
     * @response {
     * "data": [
     *       {
     *          "id": "99a51e92-abe2-447c-ad77-98c9c4d720d6",
     *         "name": "William Cremin Jr.",
     *        "slug": "william-cremin-jr",
     *           "description": "Reiciendis corrupti eos voluptatem dignissimos non nostrum neque.",
     *          "number_of_days": 3,
     *          "number_of_nights": 2
     *      },
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
    public function index()
    {
        /**
         * get public travels
         */
        $travels = Travel::where('is_public', true)->paginate(15);

        return TravelsResource::collection($travels);
    }
}
