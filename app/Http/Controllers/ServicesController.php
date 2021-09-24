<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'fees' => 'required|min:0',
            'period' => 'required|numeric|min:1',
        ]);

        $service = new Service();

        $service->name = $request->name;
        $service->fees = $request->fees;
        $service->period = $request->period;
        $service->description = $request->description;

        $service->save();
        return response([
            'message' => 'service has been created',
            'service' => $service
        ]);
    }

    public function getAll()
    {
        $service = Service::all();
        return $service;
    }

    public function show($id)
    {
        $service = Service::find($id);
        if ($service === null) {
            return response([
                'message' => 'service not found'
            ], 404);
        }
        return $service;
    }

    public function destroy($id)
    {
        $service = Service::find($id);
        if ($service === null) {
            return response([
                'message' => 'service not found'
            ], 404);
        }
        $service->delete();
        return response(['message' => 'service has been deleted successfully']);
    }

    public function update(Request $request, $id)
    {
        $service = Service::find($id);
        if ($service === null) {
            return response([
                'message' => 'service not found'
            ], 404);
        }
        $request->validate([
            'name' => 'required|min:3|max:255',
            'fees' => 'required|min:0',
            'period' => 'required|numeric|min:1',
        ]);
        $service->name = $request->input('name');
        $service->fees = $request->input('fees');
        $service->period = $request->input('period');

        $service->save();
        return response([
            'message' => 'service has been updated successfully',
            'service'=>$service
        ]);
    }
}
