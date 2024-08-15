<?php

namespace App\Http\Controllers;

use App\Models\MsUnitAvailability;
use Illuminate\Http\Request;

class MsUnitAvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MsUnitAvailability::all();
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
            'unit_id' => 'required',
        ]);

        $unit_availability = new MsUnitAvailability();
        $unit_availability->company_id = intval($request->company_id);
        $unit_availability->unit_id = intval($request->unit_id);
        $unit_availability->start_date = $request->start_date;
        $unit_availability->end_date = $request->end_date;

        return $unit_availability->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MsUnitAvailability::find($id);
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
        $unit_availability = MsUnitAvailability::find($id);

        $company_id = intval($request->company_id);
        $unit_id = intval($request->unit_id);

        $unit_availability->update([
            'company_id'=> $company_id,
            'unit_id' => $unit_id,
            'start_date'=> $request->start_date,
            'end_date'=> $request->end_date,
        ]);

        return $unit_availability;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return MsUnitAvailability::destroy($id);
    }

    public function getUnitAvailabilityByCompany($id)
    {
        return MsUnitAvailability::where('company_id', $id)->get();
    }
}