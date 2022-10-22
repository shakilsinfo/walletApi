<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{   

    public function login(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid! User email or password is invalid.','status'=>401], 401);
        }

        return $this->respondWithToken($token);
    }

     /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
     
    
    
    
    public function userInfo()
    {   
        
        return response()->json([
            'status' => 200,
            'message' => 'Data Found',
            'user' => [
                'user_name' => auth()->user()->name,
                'user_email' => is_null(auth()->user()->email)?'':auth()->user()->email,
                'user_phone' => auth()->user()->phone_number,
                'currency' => auth()->user()->currency,
                'user_type' => is_null(auth()->user()->user_type)?'':auth()->user()->user_type,
            ]
            
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => 200,
            'message' => 'Successfully Loggedin',

            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 * 24,
            'user' => [
                'user_name' => auth()->user()->name,
                'user_email' => is_null(auth()->user()->email)?'':auth()->user()->email,
                'user_phone' => auth()->user()->phone_number,
                'currency' => auth()->user()->currency
            ]
            
        ]);
    }
}
