<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
class SocialLoginController extends Controller
{
    public function signup(Request $request)
    {
        try {
            //code...
            $request_params = $request->all();
            $data = $this->prepareParamsForUserSignup($request_params);
            $user = User::updateorcreate(['email'=>$request_params['email']],$data);
            $token = $user->createToken($user->google_id);
            return response()->json(['message'=>'User created successfully','success'=>true,'token'=>$token->plainTextToken]);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false]);
        }


    }
    function prepareParamsForUserSignup($request_params)
    {
        $data = [];
        if(!empty($request_params))
        {
            $data = [
                'name'=>$request_params['name'],
                'email'=>$request_params['email'],
                'image'=>$request_params['image'],
                'google_id'=>$request_params['google_id'],
                'password'=>bcrypt(12345678),
            ];
            return $data;
        }
        return [];
    }

}
