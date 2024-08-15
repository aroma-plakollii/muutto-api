<?php

namespace App\Http\Controllers;

use App\Models\MbBooking;
use App\Models\MbCompany;
use App\Models\MbFreeCity;
use App\Models\MbPrice;
use Illuminate\Http\Request;
use VismaPay\VismaPay;

class MbPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MbPrice::with('company')->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getAllByCompany($id)
    {
        return MbPrice::where('company_id', $id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $price = new MbPrice();

        $price->company_id = $request->company_id;
        $price->price_per_day = $request->price_per_day;
        $price->price_per_package = $request->price_per_package;
        $price->price_per_km = $request->price_per_km;
        $price->booking_price = $request->booking_price;
        $price->additional_price = $request->additional_price;
        $price->additional_package_price = $request->additional_package_price;
        $price->package_days = $request->package_days;
        $price->included_km = $request->included_km;
        $price->min_boxes = $request->min_boxes;
        $price->type = $request->type;

        return $price->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MbPrice::find($id);
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
        $price = MbPrice::find($id);
        $price->update($request->all());

        return $price;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return MbPrice::destroy($id);
    }

    /**
     * Get Day Price.
     *
     * @request  $request
     */
    public function getDayPrice(Request $request) {
        $res = [];
        $priceDetails = MbPrice::with('company')->where('company_id', $request->company_id)->where('type', $request->type)->get();

        $baseAddress = $priceDetails[0]->company->address;
        $start_date = new \DateTime($request->start_date);
        $end_date = new \DateTime($request->end_date);
        $from = \Carbon\Carbon::parse($start_date);
        $to = \Carbon\Carbon::parse($end_date);
        $days = $from->diffInDays($to) + 1;
        $quantity = intval($request->quantity);
        $includedKm = intval($priceDetails[0]->included_km);
        $start_address = $request->start_address;
        $end_address = $request->end_address;
        $start_city = $request->start_city;
        $end_city = $request->end_city;

        $start_distance = 0;
        $end_distance = 0;
        $start_distance_price = 0;
        $end_distance_price = 0;

        if ($start_address){
            $startDistance = $this->getStartDistance($baseAddress, $start_address);
            $start_distance = $startDistance['start_distance'] / 1000;
            $startCity = $this->cityExists($start_city);
            $start_distance_price = sizeof($startCity) ? intval($startCity[0]->price) : ceil(($start_distance - $includedKm) * $priceDetails[0]->price_per_km);
        }

        if ($end_address) {
            $endDistance = $this->getEndDistance($baseAddress, $end_address);
            $end_distance = $endDistance['end_distance'] / 1000;
            $endCity = $this->cityExists($end_city);
            $end_distance_price = sizeof($endCity) ? intval($endCity[0]->price) : ceil(($end_distance - $includedKm) * $priceDetails[0]->price_per_km);
        }

        //Pricing
        $rent_price = ceil($quantity * $days * $priceDetails[0]->price_per_day);
        $booking_price = ceil($priceDetails[0]->booking_price);
        $price = $start_distance_price + $end_distance_price + $rent_price;

        //Response
        $res['start_date'] = $start_date;
        $res['end_date'] = $end_date;
        $res['start_address'] = $start_address;
        $res['end_address'] = $end_address;
        $res['start_city'] = $start_city;
        $res['end_city'] = $end_city;
        $res['start_distance'] = $start_distance;
        $res['end_distance'] = $end_distance;
        $res['included_distance'] = $includedKm;
        $res['quantity'] = $quantity;
        $res['days'] = $days;

        //Response Price
        $res['price']['start_distance_price'] = $start_distance_price;
        $res['price']['end_distance_price'] = $end_distance_price;
        $res['price']['rent_price'] = $rent_price;
        $res['price']['base_price'] = $booking_price;
        $res['price']['total'] = $price;

        return $res;
    }

    /**
     * Get Package Price
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function getPackagePrice(Request $request) {
        $res = [];
        $priceDetails = MbPrice::with('company')->where('company_id', $request->company_id)->where('type', $request->type)->get();

        $baseAddress = $priceDetails[0]->company->address;
        $start_date = new \DateTime($request->start_date);
        $end_date = new \DateTime($request->end_date);
        $start_address = $request->start_address;
        $start_city = $request->start_city;
        $end_city = $request->end_city;
        $end_address = $request->end_address;
        $quantity = intval($request->quantity);

        $includedKm = intval($priceDetails[0]->included_km);
        $includedDays = intval($priceDetails[0]->package_days);

        $from = \Carbon\Carbon::parse($start_date);
        $to = \Carbon\Carbon::parse($end_date);
        $days = $from->diffInDays($to) + 1;
        $calculatedDays = $days < $includedDays ? $includedDays : $days;

        $start_distance = 0;
        $end_distance = 0;
        $start_distance_price = 0;
        $end_distance_price = 0;

        if ($start_address){
            $startDistance = $this->getStartDistance($baseAddress, $start_address);
            $start_distance = $startDistance['start_distance'] / 1000;
            $startCity = $this->cityExists($start_city);
            $start_distance_price = sizeof($startCity) ? intval($startCity[0]->price) : ceil(($start_distance - $includedKm) * $priceDetails[0]->price_per_km);
        }

        if ($end_address) {
            $endDistance = $this->getEndDistance($baseAddress, $end_address);
            $end_distance = $endDistance['end_distance'] / 1000;
            $endCity = $this->cityExists($end_city);
            $end_distance_price = sizeof($endCity) ? intval($endCity[0]->price) : ceil(($end_distance - $includedKm) * $priceDetails[0]->price_per_km);
        }


        $rent_price = ceil(($quantity * $priceDetails[0]->price_per_package) * $calculatedDays);
        $booking_price = ceil($priceDetails[0]->booking_price);
        $price = $start_distance_price + $end_distance_price + $rent_price + $booking_price;

        //Response
        $res['start_date'] = $start_date;
        $res['end_date'] = $end_date;
        $res['start_address'] = $start_address;
        $res['end_address'] = $end_address;
        $res['start_city'] = $start_city;
        $res['end_city'] = $end_city;
        $res['start_distance'] = $start_distance;
        $res['end_distance'] = $end_distance;
        $res['included_distance'] = $includedKm;
        $res['quantity'] = $quantity;
        $res['days'] = $days;

        //Response Price
        $res['price']['start_distance_price'] = $start_distance_price;
        $res['price']['end_distance_price'] = $end_distance_price;
        $res['price']['rent_price'] = $rent_price;
        $res['price']['base_price'] = $booking_price;
        $res['price']['total'] = $price;

        return $res;
    }

    /**
     * Get Continue Price
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function getContinuePrice(Request $request) {
        $res = [];
        $bookingDetails = MbBooking::find($request->id);

        $end_date = \Carbon\Carbon::parse($bookingDetails->end_date);
        $new_end_date = \Carbon\Carbon::parse($request->date);
        $days = $end_date->diffInDays($new_end_date);
        $quantity = $bookingDetails->quantity;

        $priceDetails = MbPrice::where('company_id', $bookingDetails->company_id)->where('type', $bookingDetails->type)->get();

        $price = ceil(intval($quantity) * intval($days) * (float)$priceDetails[0]->additional_price);

        $res['price'] = $price;

        return $res;
    }

    /**
     * Get distance
     * @param $base
     * @param $start
     * @param $end
     * @return array
     */
    public function getDistance($base, $start, $end){
        $res = [];
        $googleKey = env('GOOGLE_KEY');

        $start_data 	= file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=".urlencode($base)."&destinations=".urlencode($start)."&key=". $googleKey ."");
        $end_data 		= file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=".urlencode($base)."&destinations=".urlencode($end)."&key=". $googleKey ."");

        $res['start_distance']  = json_decode($start_data)->rows[0]->elements[0]->distance->value;
        $res['end_distance'] 	= json_decode($end_data)->rows[0]->elements[0]->distance->value;

        return $res;
    }

    /**
     * Get Start distance
     * @param $base
     * @param $start
     * @return array
     */
    public function getStartDistance($base, $start){
        $res = [];
        $googleKey = env('GOOGLE_KEY');

        $start_data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=".urlencode($base)."&destinations=".urlencode($start)."&key=". $googleKey ."");

        $res['start_distance']  = json_decode($start_data)->rows[0]->elements[0]->distance->value;

        return $res;
    }

    /**
     * Get Start distance
     * @param $base
     * @param $end
     * @return array
     */
    public function getEndDistance($base, $end){
        $res = [];
        $googleKey = env('GOOGLE_KEY');

        $end_data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=".urlencode($base)."&destinations=".urlencode($end)."&key=". $googleKey ."");

        $res['end_distance'] = json_decode($end_data)->rows[0]->elements[0]->distance->value;

        return $res;
    }

    /**
     * Get City
     * @param $city
     * @return void
     */
    public function cityExists($city) {
        $city = MbFreeCity::where('name', $city)->get();

        return $city;
    }
}
