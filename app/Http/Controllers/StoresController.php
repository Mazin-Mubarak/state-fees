<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'owner_name' => 'required|min:3|max:255',
            'address' => 'required|min:3|max:1025'
        ]);

        $store = new Store();
        $store->name = $request->name;
        $store->owner_name = $request->owner_name;
        $store->address = $request->address;

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
                'message' => 'service not found'
            ], 404);
        }
        $request->validate([
            'name' => 'required|min:3|max:255',
            'owner_name' => 'required|min:3|max:255',
            'address' => 'required|min:3|max:1025'
        ]);
        $store->name = $request->input('name');
        $store->owner_name = $request->input('owner_name');
        $store->address = $request->input('address');

        $store->save();

        return response([
            'message' => 'store has been updated successfully',
            'store' => $store
        ]);
    }
}
