<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Coupon::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required',
            'price' => 'required',
        ]);

        $coupon = new Coupon();
        $coupon->company_id = $request->company_id;
        $coupon->code = $request->code;
        $coupon->price = $request->price;
        $coupon->available_usages = $request->available_usages;
        $coupon->status = true;
        $coupon->is_percentage = $request->is_percentage ?? 0;
        $coupon->is_unlimited = $request->is_unlimited ?? 0;

        $coupon->save();

        return response()->json($coupon);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::find($id);

        $status = intval($request->status);

        $coupon->update([
            'price' => $request->price,
            'is_percentage' => $request->is_percentage,
            'status' => $status,
        ]);

        return $coupon;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Coupon::destroy($id);
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Coupon::find($id);
    }

    public function getCouponsByCompany($id){
        return Coupon::where('company_id', $id)->get();
    }


    public function checkCoupon(Request $request) {
        $coupon = Coupon::where('status', true)
            ->where('company_id', $request->company_id)
            ->where('code', $request->coupon_code)
            ->first();
    
        if (!$coupon) {
            return response()->json(['error' => "Coupon is not valid"]);
        }

        return $coupon;
    }
}
