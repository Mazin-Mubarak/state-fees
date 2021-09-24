<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'amount' => 'required|min:1',
            'payment_type' => 'required',
            'service_id' => 'required|numeric',
            'store_id' => 'required|numeric',
            'user_id'=>'required|numeric',
            'image' => 'required|image'
        ]);

        $payment = new Payment();

        $payment->amount = $request->amount;
        $payment->payment_type = $request->payment_type;
        $payment->service_id=$request->service_id;
        $payment->store_id=$request->store_id;
        $payment->user_id=$request->user_id;

        $image = $request->file('image');
        $imageName = $image->store('public/images');

        $payment->receipt_image_path = $imageName;

        $payment->save();
        return response([
            'message' => 'payment has been created ',
            'payment' => $payment
        ]);
    }

    public function getAll()
    {
        $payment = Payment::all();
        return $payment;
    }

    public function show($id)
    {
        $payment = Payment::find($id);
        if ($payment === null) {
            return response([
                'message' => 'payment not found'
            ], 404);
        }
        return $payment;
    }

    public function destroy($id)
    {
        $payment = Payment::find($id);
        if ($payment === null) {
            return response([
                'message' => 'payment not found'
            ], 404);
        }
        $payment->delete();
        return response(['message' => 'payment has been deleted successfully']);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);
        if ($payment === null) {
            return response([
                'message' => 'payment not found'
            ], 404);
        }
        $request->validate([
            'amount' => 'required|min:1',
            'payment_type' => 'required',
            'receipt_image_path' => 'required',
            'service_id' => 'required|numeric',
            'store_id' => 'required|numeric'
        ]);

        $payment->amount = $request->input('amount');
        $payment->payment_type = $request->input('payment_type');
        $payment->receipt_image_path = $request->input('receipt_image_path');
        $payment->service_id = $request->input('service_id');
        $payment->store_id = $request->input('store_id');

        $payment->save();
        return response([
            'message'=>'payment has been updated successfully',
            'payment'=>$payment
        ]);
    }
}
