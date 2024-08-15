<?php

namespace App\Http\Controllers;

use App\Models\MsUnitProductPrice;
use Illuminate\Http\Request;

class MsUnitProductPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return MsUnitProductPrice::all();
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
            'unit_id' => 'required',
            'product_id' => 'required',
            'price' => 'required',
            'saturday_price' => 'required',
            'sunday_price' => 'required',
            'discount_price' => 'required',
            'price_per_m2' => 'required',
            'included_m2' => 'required',
            'no_elevator' => 'required',
            'small_elevator' => 'required',
            'big_elevator' => 'required',
            'new_building' => 'required',
            'price_per_km' => 'required',
            'included_km' => 'required',
            'basement_storage_price_per_m2' => 'required',
            'included_m2_basement_storage' => 'required',
            'roof_storage_price_per_m2' => 'required',
            'included_m2_roof_storage' => 'required',
            'included_meters_outdoor' => 'required',
            'outdoor_price_per_meter' => 'required',
            'description' => 'required',
        ]);

        $company_unit_price = new MsUnitProductPrice();
        $company_unit_price->unit_id = intval($request->unit_id);
        $company_unit_price->product_id = intval($request->product_id);
        $company_unit_price->price = $request->price;
        $company_unit_price->saturday_price = $request->saturday_price;
        $company_unit_price->sunday_price = $request->sunday_price;
        $company_unit_price->discount_price = $request->discount_price;
        $company_unit_price->price_per_m2 = $request->price_per_m2;
        $company_unit_price->included_m2 = $request->included_m2;
        $company_unit_price->no_elevator = $request->no_elevator;
        $company_unit_price->small_elevator = $request->small_elevator;
        $company_unit_price->big_elevator = $request->big_elevator;
        $company_unit_price->new_building = $request->new_building;
        $company_unit_price->price_per_km = $request->price_per_km;
        $company_unit_price->included_km = $request->included_km;
        $company_unit_price->basement_storage_price_per_m2 = $request->basement_storage_price_per_m2;
        $company_unit_price->included_m2_basement_storage = $request->included_m2_basement_storage;
        $company_unit_price->roof_storage_price_per_m2 = $request->roof_storage_price_per_m2;
        $company_unit_price->included_m2_roof_storage = $request->included_m2_roof_storage;
        $company_unit_price->included_meters_outdoor = $request->included_meters_outdoor;
        $company_unit_price->outdoor_price_per_meter = $request->outdoor_price_per_meter;
        $company_unit_price->description = $request->description;


        return $company_unit_price->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MsUnitProductPrice::where('product_id', $id)->first();
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
        $product_id = intval($id);
        $unit_id = intval($request->unit_id);

        $unit_product_price = MsUnitProductPrice::where('product_id', $product_id)
        ->where('unit_id', $unit_id)
        ->firstOrFail();

        $unit_product_price->update([
        'price' => $request->price,
        'saturday_price' => $request->saturday_price,
        'sunday_price' => $request->sunday_price,
        'discount_price' => $request->discount_price,
        'price_per_m2' => $request->price_per_m2,
        'included_m2' => $request->included_m2,
        'no_elevator' => $request->no_elevator,
        'small_elevator' => $request->small_elevator,
        'big_elevator' => $request->big_elevator,
        'new_building' => $request->new_building,
        'price_per_km' => $request->price_per_km,
        'included_km' => $request->included_km,
        'basement_storage_price_per_m2' => $request->basement_storage_price_per_m2,
        'included_m2_basement_storage' => $request->included_m2_basement_storage,
        'roof_storage_price_per_m2' => $request->roof_storage_price_per_m2,
        'included_m2_roof_storage' => $request->included_m2_roof_storage,
        'included_meters_outdoor' => $request->included_meters_outdoor,
        'outdoor_price_per_meter' => $request->outdoor_price_per_meter,
        'description' => $request->description,
        ]);

        return $unit_product_price;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Return prices by company
     */
    public function getPricesByUnit(Request $request) {
        return MsUnitProductPrice::where('product_id', $request->product_id)->where('unit_id', $request->unit_id)->first();
    }
}
