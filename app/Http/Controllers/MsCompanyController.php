<?php

namespace App\Http\Controllers;
use App\Models\MsCompany;
use App\Models\MsFreeCity;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MsCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MsCompany::all();
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

        $company = new MsCompany();
        $company->name = $request->name;
        $company->business_number = $request->business_number;
        $company->description = $request->description;
        $company->first_name = $request->first_name;
        $company->last_name = $request->last_name;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->address = $request->address;
        $company->register_date = $request->register_date;
        $company->termination_date = $request->termination_date;
        $company->api_key = $request->api_key;
        $company->private_key = $request->private_key;
        $company->secret_key = Hash::make(time());
        $company->is_featured = intval($request->is_featured);
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
        return MsCompany::find($id);
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
        $company = MsCompany::find($id);

        $is_featured = intval($request->is_featured);
        $status = intval($request->status);

        $company->update([
            'name' => $request->name,
            'business_number' => $request->business_number,
            'description' => $request->description,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'register_date' => $request->register_date,
            'termination_date' => $request->termination_date,
            'api_key' => $request->api_key,
            'private_key' => $request->private_key,
            'is_featured' => $is_featured,
            'status' => $status,
        ]);

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
        $company = MsCompany::findOrFail($id);

        return $company->delete();
    }

    public function pricing(Request $request){
        $companies = DB::table('ms_companies')
            ->join('ms_company_product_prices', 'ms_companies.id', '=', 'ms_company_product_prices.company_id')
            ->join('ms_products', 'ms_products.id', '=', 'ms_company_product_prices.product_id')
            ->where([
                ['ms_products.id', $request->productId],
                ['ms_companies.status', 1]
            ])
            ->select('ms_companies.*', 'ms_company_product_prices.*')
            ->get();

        foreach ($companies as $key => $company) {
            $freeStartCityDistance = MsFreeCity::where([['name', $request->timeAddressForm['startAddress']['city']], ['company_id', $company->id]])->exists();
            $freeEndCityDistance = MsFreeCity::where([['name', $request->timeAddressForm['endAddress']['city']], ['company_id', $company->id]])->exists();

            $baseStartDistance = $this->getDistance($company->address, $request->timeAddressForm['startAddress']['name']);
            $startEndDistance = $this->getDistance($request->timeAddressForm['startAddress']['name'], $request->timeAddressForm['endAddress']['name']);
            $endBaseDistance = $this->getDistance($request->timeAddressForm['endAddress']['name'], $company->address);

            $startDistance = $freeStartCityDistance ? 0 : $baseStartDistance;
            $endDistance = $freeEndCityDistance ? 0 : $endBaseDistance;
            $distance = ($startEndDistance - ($company->included_km * 1000)) + $startDistance + $endDistance;
            $company->road_price = $distance > 0 ? ($distance / 1000) * intval($company->price_per_km) : 0;

            // Flat area calculate
            $startFlatMPrice = isset($request->timeAddressForm['startAddress']['flat_squarem']) ? (intval($request->timeAddressForm['startAddress']['flat_squarem']) - intval($company->included_m2)) * intval($company->price_per_m2) : 0;
            $startFlatMPrice = $startFlatMPrice >= 0 ? $startFlatMPrice : 0;

            // Elevator calculation
            $floorPricePercentage = 0;
            $startFloorValue = 0;

            if (intval($request->timeAddressForm['startAddress']['floor']) == 'a'
                OR intval($request->timeAddressForm['startAddress']['floor']) == 'b'
                OR intval($request->timeAddressForm['startAddress']['floor']) == 'c'){
                $startFloorValue = 0;
            }else if (intval($request->timeAddressForm['startAddress']['floor']) == 'd'
                OR intval($request->timeAddressForm['startAddress']['floor']) == 'e'){
                $startFloorValue = 1;
            }else{
                $startFloorValue = intval($request->timeAddressForm['startAddress']['floor']);
            }

            $endFloorValue = 0;
            if (intval($request->timeAddressForm['endAddress']['floor']) == 'a'
                OR intval($request->timeAddressForm['endAddress']['floor']) == 'b'
                OR intval($request->timeAddressForm['endAddress']['floor']) == 'c'){
                $endFloorValue = 0;
            }else if (intval($request->timeAddressForm['endAddress']['floor']) == 'd'
                OR intval($request->timeAddressForm['endAddress']['floor']) == 'e'){
                $endFloorValue = 1;
            }else{
                $endFloorValue = intval($request->timeAddressForm['endAddress']['floor']);
            }

            $startElevator = isset($request->timeAddressForm['startAddress']['elevator']) ? intval($request->timeAddressForm['startAddress']['elevator']) : 0;
            $endElevator = isset($request->timeAddressForm['endAddress']['elevator']) ? intval($request->timeAddressForm['endAddress']['elevator']) : 0;

            $startFloor = $startFloorValue;
            $endFloor = $endFloorValue;

            $startElevatorPercentage = 0;
            $endElevatorPercentage = 0;

            switch ($startElevator) {
                case 1:
                    $startElevatorPercentage = intval($company->no_elevator);
                    break;
                case 2:
                    $startElevatorPercentage = intval($company->small_elevator);
                    break;
                case 3:
                    $startElevatorPercentage = intval($company->big_elevator);
                    break;
                case 4: 
                    $startElevatorPercentage = intval($company->new_building);
            }

            switch ($endElevator) {
                case 1:
                    $endElevatorPercentage = intval($company->no_elevator);
                    break;
                case 2:
                    $endElevatorPercentage = intval($company->small_elevator);
                    break;
                case 3:
                    $endElevatorPercentage = intval($company->big_elevator);
                    break;
                case 4:
                    $endElevatorPercentage = intval($company->new_building);
                    break;
            }

            $floorPricePercentage = $startFloor * $startElevatorPercentage + $endFloor * $endElevatorPercentage;

            //Outdoor distance calculation
            $outdoorPricePercentage = 0;
            $startOutdoorPrice =
                isset($request->timeAddressForm['startAddress']['outdoor_distance']) ?
                    (intval($request->timeAddressForm['startAddress']['outdoor_distance']) - intval($company->included_meters_outdoor)) * intval($company->outdoor_price_per_meter) : 0;
            $startOutdoorPricePercentage = $startOutdoorPrice >= 0 ? $startOutdoorPrice : 0;

            $endOutdoorPrice =
                isset($request->timeAddressForm['endAddress']['outdoor_distance']) ?
                    (intval($request->timeAddressForm['endAddress']['outdoor_distance']) - intval($company->included_meters_outdoor)) * intval($company->outdoor_price_per_meter) : 0;
            $endOutdoorPricePercentage = $endOutdoorPrice >= 0 ? $endOutdoorPrice : 0;
            $outdoorPricePercentage = $startOutdoorPricePercentage + $endOutdoorPricePercentage;

            // Storage distance calculation
            $storagePricePercentage = 0;
            $startStoragePricePercentage = 0;
            $endStoragePricePercentage = 0;
            $startStorage = isset($request->timeAddressForm['startAddress']['storage']) ? intval($request->timeAddressForm['startAddress']['storage']) : 0;
            $endStorage = isset($request->timeAddressForm['endAddress']['storage']) ? intval($request->timeAddressForm['endAddress']['storage']) : 0;
            $startStorageArea = isset($request->timeAddressForm['startAddress']['storage_area']) ? intval($request->timeAddressForm['startAddress']['storage_area']) : 0;
            $endStorageArea = isset($request->timeAddressForm['endAddress']['storage_area']) ? intval($request->timeAddressForm['endAddress']['storage_area']) : 0;
            $startStorageFloor = isset($request->timeAddressForm['startAddress']['storage_floor']) ? intval($request->timeAddressForm['startAddress']['storage_floor']) : 1;
            $endStorageFloor = isset($request->timeAddressForm['endAddress']['storage_floor']) ? intval($request->timeAddressForm['endAddress']['storage_floor']) : 1;

            switch ($startStorage) {
                case 2:
                    $startStoragePricePercentage = ($startStorageArea - intval($company->included_m2_basement_storage)) * intval($company->basement_storage_price_per_m2);
                    break;
                case 3:
                    $startStoragePricePercentage = ($startStorageArea - intval($company->included_m2_roof_storage)) * intval($company->roof_storage_price_per_m2);
                    $startStoragePricePercentage = $startStoragePricePercentage >= 0 ? $startStoragePricePercentage : 0;
                    $startStoragePricePercentage += $startElevatorPercentage * $startStorageFloor;
                    break;
            }

            switch ($endStorage) {
                case 2:
                    $endStoragePricePercentage = ($endStorageArea - intval($company->included_m2_basement_storage)) * intval($company->basement_storage_price_per_m2);
                    break;
                case 3:
                    $endStoragePricePercentage = ($endStorageArea - intval($company->included_m2_roof_storage)) * intval($company->roof_storage_price_per_m2);
                    $endStoragePricePercentage = $endStoragePricePercentage >= 0 ? $endStoragePricePercentage : 0;
                    $endStoragePricePercentage += $endElevatorPercentage * $endStorageFloor;
                    break;
            }

            $storagePricePercentage = $startStoragePricePercentage + $endStoragePricePercentage;

            $price = 0;
            $dateString = $request->timeAddressForm['date'];

            // Create a DateTime object in the UTC timezone
            $dateUtc = new DateTime($dateString, new DateTimeZone('UTC'));

            // Convert the timezone to Helsinki
            $dateLocal = $dateUtc->setTimezone(new DateTimeZone('Europe/Helsinki'));

            // Parse the date string in the Helsinki timezone
            $date = Carbon::parse($dateLocal->format('Y-m-d H:i:s'));

            if ($date->isWeekend()) {
                if ($date->isSaturday()) {
                    $price = $company->saturday_price;
                } elseif ($date->isSunday()) {
                    $price = $company->sunday_price;
                }
            } else {
                $price = $company->price;
            }

            $company->pre_price = ceil($company->road_price + $price);

            $company->pre_price += $price * ($floorPricePercentage + $outdoorPricePercentage) / 100 + $startFlatMPrice + $storagePricePercentage;
            $company->is_featured = intval($company->is_featured);
        }

        return $companies;

    }

    /**
     * @param $start
     * @param $end
     * @return number
     *
     * This function calculates the distance in km using google api
     */
    public function getDistance($start, $end){
        $res = [];
        $googleKey = env('GOOGLE_KEY');

        $end_data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=".urlencode($start)."&destinations=".urlencode($end)."&key=". $googleKey ."");

        $res = json_decode($end_data)->rows[0]->elements[0]->distance->value;

        return $res;
    }

    public function getCompanyByUser($id) {
        return MsCompany::where('user_id',$id)->select('id')->first();
    }

    public function getCompanyByUser2($id) {
        return MsCompany::where('user_id',$id)->first();
    }
}
