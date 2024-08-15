<?php

namespace App\Http\Controllers;

use App\Models\BookingRequestProduct;
use Illuminate\Http\Request;

class BookingRequestProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BookingRequestProduct::all();
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
            'name'=> 'required',
            'description'=> 'required',
            'duration'=> 'required',
        ]);

        $bookingRequestProduct = new BookingRequestProduct();
        $bookingRequestProduct->name = $request->name;
        $bookingRequestProduct->description = $request->description;
        $bookingRequestProduct->duration = $request->duration;
        $bookingRequestProduct->image = $request->image;

        return $bookingRequestProduct->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return BookingRequestProduct::find($id);
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
        $bookingRequestProduct = BookingRequestProduct::find($id);
        $bookingRequestProduct->update($request->all());

        return $bookingRequestProduct;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bookingRequestProduct = BookingRequestProduct::findOrFail($id);

        return $bookingRequestProduct->delete();
        // return MsProduct::destroy($id);
    }
}
