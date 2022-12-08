<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
     public function register(Request $request){

        $registrationData = $request->all();

        $validate = Validator::make($registrationData, [
            'name' => 'required|max:60',
            'username' => 'required|unique:users',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required',
        ]);

        // return error validation input
        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $registrationData['password'] = bcrypt($request->password); // enkripsi password
        $user = User::create($registrationData); // membuat user baru
        // $user->sendApiEmailVerificationNotification();
        return response([
            'message' => 'Register Success',
            'user' => $user
        ], 200); // return data user dalam bentuk json
    }

    public function login(Request $request)
    {
        $loginData = $request->all();

        $validate = Validator::make($loginData,[
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]); //membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()],400); 

        if(!Auth::attempt($loginData))
            return response(['message' => 'Invalid Credentials'],401); 
        
        $user = Auth::user();
        // if ($user->email_verified_at == NULL) {
        //     return response([
        //         'message' => 'Please Verify Your Email'
        //     ], 401); //Return error jika belum verifikasi email
        // }
        $token = $user->createToken('Authentication Token')->accessToken; //generate token
        
        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]); //retun data user dan token dalam bentuk json
    }

    public function logout(){
        $user = Auth::user()->token();
        $user->revoke();

        return response([
            'message' => 'Berhasil Logout',
        ]); //retun data user dan token dalam bentuk json
    }
}
