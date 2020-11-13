<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $credentials =  Validator::make($request->all(), [
            'user_email' => 'required|email',
            'user_pass' => 'required|string',
        ]);
        if ($credentials->fails()) {
            return response()->json($credentials->errors(), 422);
        }

        if (! $token = auth()->attempt($credentials->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
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
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    /**
     * Register a User.
     *
     * @return JsonResponse
     */
    public function register() {

        $model = new Account();
        $data = [];
        $data['user_name'] = 'abc12345678';
        $data['user_email'] = 'abc12345678@kami.com';
        $data['user_pass'] = bcrypt('123456782');
        $model->fill($data);
        $model->save();
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $model
        ], 201);
    }
}
