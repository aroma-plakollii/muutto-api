<?php

namespace App\Http\Controllers;

use App\Models\MbFreeCity;
use Illuminate\Http\Request;

class MbFreeCityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MbFreeCity::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $freeCity = new MbFreeCity();

        $freeCity->company_id = $request->company_id;
        $freeCity->name = $request->name;
        $freeCity->price = $request->price;

        return $freeCity->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MBFreeCity::find($id);
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
        $freeCity = MBFreeCity::find($id);
        $freeCity->update($request->all());

        return $freeCity;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return MBFreeCity::destroy($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getAllByCompany($id)
    {
        return MBFreeCity::where('company_id', $id)->get();
    }
}
