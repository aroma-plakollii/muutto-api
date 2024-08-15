<?php

namespace App\Http\Controllers;

use App\Models\MsFreeCity;
use Illuminate\Http\Request;

class MsFreeCityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MsFreeCity::all();
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
            'name' => 'required',
            'company_id' => 'required',
        ]);

        $free_city = new MsFreeCity();
        $free_city->name = $request->name;
        $free_city->company_id = intval($request->company_id);

        return $free_city->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MsFreeCity::find($id);
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
        $free_city = MsFreeCity::find($id);

        $company_id = intval($request->company_id);

        $free_city->update([
            'company_id'=> $company_id,
            'name'=> $request->name,
        ]);

        return $free_city;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return MsFreeCity::destroy($id);
    }

    public function getFreeCitiesByCompany($id)
    {
        return MsFreeCity::where('company_id', $id)->get();
    }
}