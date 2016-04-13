<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
<<<<<<< HEAD
=======
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
>>>>>>> b6d50b01ac5b60907a9cddaf28bfdd2ba3947f72

class JWTController extends Controller
{
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
<<<<<<< HEAD
        } catch (JWTException $e) {
=======
        } catch (JWTExceptioneption $e) {
>>>>>>> b6d50b01ac5b60907a9cddaf28bfdd2ba3947f72
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }
}
