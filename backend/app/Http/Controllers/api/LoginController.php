<?php


namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $login = $request->validate([
            'user_email' => 'required|string',
            'password' => 'required|string'
        ]);
//        $login['password'] = bcrypt($login['password']);
//        var_dump($login);
        if (!$check = Auth::guard('api')->attempt($login)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        return response()->json([
            'success' => true,
            'data' => Auth::user()
        ], 200);
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false
            ], 500);
        }
    }

    public function me()
    {
        var_dump(Auth::user());
        if (Auth::check()) {
            return response()->json([
                'success' => true,
                'data' => Auth::user()
            ], 200);
        } else {
            return response()->json([
                'success' => false
            ], 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        $model = new Account();
        $data = [];
        if (Account::where('user_name', $request->get('user_name')) != null) {
            return response()->json([
                'message' => 'User name not exists'
            ], 400);
        }
        if (Account::where('user_email', $request->get('user_email')) != null) {
            return response()->json([
                'message' => 'Email not exists'
            ], 400);
        } else {
            DB::beginTransaction();
            try {
                $data['user_name'] = $request->get('user_name');
                $data['user_email'] = $request->get('user_email');
                $data['password'] = bcrypt($request->get('password'));
                $model->fill($data);
                $model->save();
                DB::commit();
                return response()->json([
                    'message' => 'Create User Complete!'
                ], 200);
            } catch (\Exception $exception) {
                return response()->json([
                    'message' => 'Error Server'
                ], 500);

            }
        }

    }
}
