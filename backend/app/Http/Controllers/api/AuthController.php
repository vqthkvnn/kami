<?php


namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup', 'post', 'comment',
            'subject']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login()
    {
        $credentials = request(['user_email', 'password']);
        $credentials['user_status'] = 1;
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email hoặc password không tồn tại'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function signup(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = User::all()->count();
            var_dump($id);
            $user = User::create([
                'user_name' => $request['user_name'],
                'user_email' => $request['user_email'],
                'password' => $request['password'],
                'id' => (int)($id+1),
            ]);
            DB::commit();
            return response()->json(['success'=>true,
                'data'=>$user,
                ], 200);
        } catch (\Exception $exception) {
            report($exception);
            DB::rollback();
            return response()->json([
                'error' => true,
                'success' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json([auth()->user()->user_name]);
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
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
