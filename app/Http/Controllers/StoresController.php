<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StoresController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'owner_name' => 'required|min:3|max:255',
            'address' => 'required|min:3|max:1025',
            'commercial_id' => 'required|numeric'
        ]);

        $store = new Store();
        $store->name = $request->name;
        $store->owner_name = $request->owner_name;
        $store->address = $request->address;
        $store->commercial_id = $request->commercial_id;

        $store->save();
        return response([
            'message' => 'store has been created successfully',
            'store' => $store
        ]);
    }

    public function getAll()
    {
        $store = Store::all();
        return $store;
    }

    public function show($id)
    {
        $store = Store::find($id);
        if ($store === null) {
            return response([
                'message' => 'service not found'
            ], 404);
        }
        return $store;
    }

    public function destroy($id)
    {
        $store = Store::find($id);
        if ($store === null) {
            return response([
                'message' => 'service not found'
            ], 404);
        }
        $store->delete();

        return response([
            'message' => 'user has been deleted successfully'
        ]);
    }

    public function update(Request $request, $id)
    {
        $store = Store::find($id);
        if ($store === null) {
            return response([
                'message' => 'store not found'
            ], 404);
        }
        $request->validate([
            'name' => 'required|min:3|max:255',
            'owner_name' => 'required|min:3|max:255',
            'address' => 'required|min:3|max:1025',
            'commercial_id' => 'required|numeric'
        ]);
        $store->name = $request->input('name');
        $store->owner_name = $request->input('owner_name');
        $store->address = $request->input('address');
        $store->commercial_id = $request->input('commercial_id');

        if ($request->password != null) {
            $store->password = Hash::make($request->input('password'));
        }

        $store->save();

        return response([
            'message' => 'store has been updated successfully',
            'store' => $store
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'commercial_id' => 'required|numeric',
            'password' => 'required'
        ]);

        $commercialId = intval($request->commercial_id);
        $password = $request->password;

        $store = Store::where('commercial_id', $commercialId)->first();
        if ($store == null) {
            return response(['message' => 'wrong commercial id'], 404);
        }

        if ($store->password == null) {
            return response(['message' => 'account is not active'], 501);
        }

        $isHashedMatched = Hash::check($password, $store->password);
        if (!$isHashedMatched) {
            return response(['message' => 'wrong password'], 401);
        }

        $accessToken = $store->createToken($store->name);
        return response([
            'message' => 'login successful',
            'token' => $accessToken->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        $store = $request->user();
        $store->currentAccessToken()->delete();
        return response([
            'message' => 'logout successfully'
        ]);
    }
}
