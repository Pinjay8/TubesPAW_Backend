<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    public function getuser($id){
        $user = User::find($id);

        if(!is_null($user)){
            return response([
                'message' => 'Retrieve User Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'User Not Found',
            'data' => null
        ], 404);
    }


    public function updateprofile(Request $request, $id){
        $user = User::find($id);

        if(is_null($user)){
            return response([
                'message' => 'User Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'name' => 'required|max:60',
            'email' => 'required|email:rfc,dns',
            'username' => ['required', Rule::unique('users')->ignore($user)],
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);
        
        $user->name = $updateData['name'];
        $user->email = $updateData['email'];
        $user->username = $updateData['username'];
        
        if($user->save()){
            return response([
            'message' => 'Update User Success',
            'data' => $user,
            ],200);
        }

        return response([
            'message' => 'Update User Failed',
            'data' => null
        ],400);
    }
}
