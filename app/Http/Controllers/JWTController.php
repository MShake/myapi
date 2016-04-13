<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTController extends Controller
{

    /**
     * @SWG\Post(
     *     path="/authenticate",
     *     summary="Create a token",
     *     description="Use this method to create a token",
     *     operationId="createAuthenticate",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"authenticate"},
     *      @SWG\Parameter(
     *         description="E-mail",
     *         in="formData",
     *         name="email",
     *         type="string",
     *         required=true,
     *         maximum="255"
     *     ),
     *      @SWG\Parameter(
     *         description="Password",
     *         in="formData",
     *         name="password",
     *         type="string",
     *         required=true,
     *         maximum="255"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Token created"
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Invalid Credentials"
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Could not create token"
     *     )
     * )
     */
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }

        } catch (JWTException $e) {

            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    public function hashPassword(Request $request)
    {
        $password = $request->password;
        $hashedPassword = Hash::make($password);
        return $hashedPassword;
    }
}
