<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Auth;


class HomeController extends Controller
{
    public function register(Request $request)
    {
  
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|required',
            'password' => 'required'
        ]);
        if($validator->fails()) {
             $data = [
                'message'=>$validator->errors(),
                ];
            return response([
                'success'=>false,
                'message'=>"Field Missing"
            ]);
        }
        if (! Auth::attempt(['email' => $request->email, 'password'=>$request->password], $request->filled('remember'))) {
            $data = ['success'=>false,
                    'message'=>"Invalid Credentials",
                    ];
            return response($data,200);
        }else{
           
            $user = User::where('email',$request->email)->first();
 
            $user->tokens()->delete();
            return  response([
                'success'=>true,
                'token'=>$user->createToken('authtoken')->plainTextToken,
                'user'=>$user,
            ]
            );
        }

    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response([
            'success'=>'true',
            'message'=>'Logout Success'
        ]);
    }

  
}
