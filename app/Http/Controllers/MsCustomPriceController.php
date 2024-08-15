<?php

namespace App\Http\Controllers;

use App\Models\MsCustomPrice;
use Illuminate\Http\Request;

class MsCustomPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MsCustomPrice::all();
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
            'product_id' => 'required',
            'date' => 'required',
            'price'=> 'required',
        ]);

        $custom_price = new MsCustomPrice();
        $custom_price->company_id = intval($request->company_id);
        $custom_price->product_id = intval($request->product_id);
        $custom_price->date = $request->date;
        $custom_price->price = $request->price;

        return $custom_price->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MsCustomPrice::find($id);
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
        $custom_price = MsCustomPrice::find($id);

        $company_id = intval($request->company_id);
        $product_id = intval($request->product_id);

        $custom_price->update([
            'company_id'=> $company_id,
            'product_id' => $product_id,
            'date'=> $request->date,
            'price'=> $request->price,
        ]);

        return $custom_price;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return MsCustomPrice::destroy($id);
    }

    public function getCustomPricesByCompany($id)
    {
        return MsCustomPrice::where('company_id', $id)->get();
    }
}