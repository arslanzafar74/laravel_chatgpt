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
        $request_params = $request->all();
        $data = $this->prepareParamsForUserSignup($request_params);
        User::updateorcreate(['email'=>$request_params['email']],$data);
        return response()->json(['message'=>'User created successfully','success'=>true]);

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
                'password'=>bcrypt(12345678),
            ];
            return $data;
        }
        return [];
    }

}
