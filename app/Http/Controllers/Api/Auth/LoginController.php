<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


/**
 * @group Login
 * 
 * login Api to get your access token 
 */
class LoginController extends Controller
{
    /**
     * 
     * @response{
     *  {
     *      "access_token": "1|6cTyUTp7OZNRNGiZVX4wK4DSIWIPZwqUXjNBHall"
     *  }
     * }
     */
    public function login(LoginRequest $request)
    {

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'the provided credentials are incorrect',
            ], 422);
        }

        $device = substr($request->userAgent() ?? '', 0, 255);

        return response()->json(
            [
                'access_token' => $user->createToken($device)->plainTextToken,
            ]
        );
    }
}
