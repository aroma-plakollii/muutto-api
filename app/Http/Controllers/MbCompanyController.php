<?php

namespace App\Http\Controllers;

use App\Models\MbCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MbCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MbCompany::all();
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
            'first_name'=> 'required',
            'last_name'=> 'required',
            'name'=> 'required',
            'email'=> 'required',
            'phone'=> 'required',
            'address'=> 'required',
        ]);

        $company = new MbCompany();
        $company->name = $request->name;
        $company->first_name = $request->first_name;
        $company->last_name = $request->last_name;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->address = $request->address;
        $company->business_number = $request->business_number;
        $company->private_key = $request->private_key;
        $company->api_key = $request->api_key;
        $company->secret_key = Hash::make(time());
        $company->status = intval($request->status);

        return $company->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MbCompany::find($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCompanyBySecret($secret)
    {
        return MbCompany::where('secret_key', $secret)->get();
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
        $company = MbCompany::find($id);
        $company->update($request->all());

        return $company;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return MbCompany::destroy($id);
    }

    public function getCompanyByUser($id) {
        return MbCompany::where('user_id',$id)->select('id')->first();
    }
}
