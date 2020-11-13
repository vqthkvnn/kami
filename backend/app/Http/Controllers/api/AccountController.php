<?php


namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function login(LoginRequest  $request){
        $model = new Account();
        return response()->json([
            'data'=> $request->only($model->getFillable()),
            'success' => true
        ]);
    }
    public function logout(Request $request){
        return response()->json([
            'data'=> $request->all(),
            'success' => true
        ]);
    }
    public function create(){
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
