<?php

namespace App\Http\Controllers;

use App\Models\MbBlockedDates;
use Illuminate\Http\Request;

class MbBlockedDatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MbBlockedDates::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $blockedDates = new MbBlockedDates();

        $blockedDates->company_id = $request->company_id;
        $blockedDates->date = $request->date;
        $blockedDates->status = $request->status;

        return $blockedDates->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MbBlockedDates::find($id);
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
        $blockedDates = MbBlockedDates::find($id);
        $blockedDates->update($request->all());

        return $blockedDates;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return MbBlockedDates::destroy($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getAllByCompany($id)
    {
        return MbBlockedDates::where('company_id', $id)->get();
    }
}
