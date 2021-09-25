<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'user_name' => 'required|min:3|max:255|unique:users|alpha_dash',
            'password' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->user_name =  trim(strtolower($request->input('user_name')));
        $user->password = Hash::make($request->input('password'));

        $user->save();

        return response([
            'message' => 'user has been created successfully',
            'user' => $user
        ]);
    }

    public function getAll()
    {
        $user = User::all();
        return $user;
    }

    public function show($id)
    {
        $user = User::find($id);

        if ($user === null) {
            return response([
                'message' => 'user not found'
            ], 404);
        }
        return $user;
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user === null) {
            return response([
                'message' => 'user not found'
            ], 404);
        }
        $user->delete();
        return response(['message' => 'user has been deleted successfully']);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($user === null) {
            return response([
                'message' => 'user not found'
            ], 404);
        }
        $request->validate([
            'name' => 'required|min:3|max:255',
            'user_name' => 'required|min:3|max:255|unique:users|alpha_dash',
            'password' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
        ]);
        $user->name = $request->input('name');
        $user->user_name =  trim(strtolower($request->input('user_name')));
        $user->password = Hash::make($request->input('password'));

        $user->save();
        return response(['message' => 'user has been updated successfully']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'user_name' => 'required',
            'password' => 'required'
        ]);
        $userName = trim(strtolower($request->user_name));
        $password = $request->password;

        $user = User::where('user_name', $userName)->first();
        if ($user == null) {
            return response([
                'message' => 'wrong user name or password'
            ], 401);
        }

        $isPasswordMatched = Hash::check($password, $user->password);

        if (!$isPasswordMatched) {
            return response([
                'message' => 'wrong user name or password'
            ], 401);
        }

        $accessToken = $user->createToken($user->name);
        return response([
            'message' => 'login successful',
            'token' => $accessToken->plainTextToken
        ]);
    }
    

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response([
            'message'=>'logout successful'
        ]);
        
    }
}
