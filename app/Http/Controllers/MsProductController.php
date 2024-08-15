<?php

namespace App\Http\Controllers;

use App\Models\MsUnitProductPrice;
use App\Models\MsProduct;
use Illuminate\Http\Request;

class MsProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MsProduct::all();
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
            'type_id' => 'required',
            'name' => 'required',
            'capacity_info' => 'required',
            'description'=> 'required',
            'duration'=> 'required',
        ]);

        $product = new MsProduct();
        $product->type_id = $request->type_id;
        $product->name = $request->name;
        $product->capacity_info = $request->capacity_info;
        $product->description = $request->description;
        $product->duration = $request->duration;
        $product->image = $request->image;

        return $product->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MsProduct::find($id);
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
        $product = MsProduct::find($id);
        $product->update($request->all());

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return MsProduct::destroy($id);
    }

    public function unit_product_details(Request $request){
        $unit_product_details = MsUnitProductPrice::where('unit_id', $request->unit_id)
            ->where('product_id', $request->product_id)
            ->first();
        return $unit_product_details;
    }
}
