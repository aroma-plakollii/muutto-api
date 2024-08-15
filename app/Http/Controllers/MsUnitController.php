<?php

namespace App\Http\Controllers;

use App\Models\MsUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Models\MsFreeCity;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Hash;

class MsUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MsUnit::all();
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
            'price' => 'required',
            'persons' => 'required',
            'capacity' => 'required',
            'max_shift'=> 'required',
            // 'start_time'=> 'required',
            // 'end_time'=> 'required',
        ]);

        $unit = new MsUnit();
        $unit->company_id = intval($request->company_id);
        $unit->region_id = intval($request->region_id);
        $unit->address = $request->address;
        $unit->name = $request->name;
        $unit->price = $request->price;
        $unit->persons = $request->persons;
        $unit->capacity = $request->capacity;
        $unit->availability = intval($request->availability);
        $unit->max_shift = $request->max_shift;
        $unit->start_time = $request->start_time;
        $unit->end_time = $request->end_time;
        $unit->image = $request->image;

        return $unit->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MsUnit::find($id);
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
        $unit = MsUnit::find($id);

        $company_id = intval($request->company_id);
        $region_id = intval($request->region_id);
        $availability = intval($request->availability);

        $unit->update([
            'company_id'=> $company_id,
            'region_id' => $region_id,
            'name' => $request->name,
            'address' => $request->address,
            'price' => $request->price,
            'persons'=> $request->persons,
            'capacity'=> $request->capacity,
            'availability'=> $availability,
            'max_shift'=> $request->max_shift,
            'start_time'=> $request->start_time,
            'end_time' => $request->end_time,
            'image' => $request->image,
        ]);

        return $unit;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unit = MsUnit::findOrFail($id);

        $unit->bookings()->where('unit_id', $id)->delete();
        $unit->ms_unit_availabilities()->where('unit_id', $id)->delete();

        return $unit->delete();
    }

    public function getUnitsByCompany($id)
    {
        return MsUnit::where('company_id', $id)->get();
    }

    // public function get_units_available(Request $request){
    //     $unitIdsWithPrices = DB::table('ms_unit_product_prices')
    //     ->where('product_id', $request->product_id)
    //     ->pluck('unit_id')
    //     ->toArray();

    //     $units = DB::table('ms_units')
    //         ->whereIn('id', $unitIdsWithPrices)
    //         ->where('company_id', $request->company_id)
    //         ->get()
    //         ->toArray();

    //     $unit_bookings = DB::table('ms_units as u')
    //         ->join('ms_bookings as b', 'u.id', '=', 'b.unit_id')
    //         ->where('u.company_id', $request->company_id)
    //         ->whereDate('b.start_date', $request->start_date)
    //         ->whereIn('u.id', $unitIdsWithPrices) // Ensure bookings only for units with prices
    //         ->get()
    //         ->toArray();

    //     foreach ($units as $key => $unit) {
    //         $units[$key]->bookings = array_filter($unit_bookings, function($obj) use ($unit) {
    //             return $obj->unit_id == $unit->id;
    //         });

    //         $temp_time = intval($unit->start_time);
    //         $hoursArray = [];

    //         while ($temp_time !== intval($unit->end_time)) {
    //             $hoursArray[$temp_time] = true;
    //             $temp_time = $temp_time + 1;
    //         }

    //         foreach ($units[$key]->bookings as $bkKey => $booking) {
    //             $start_hour = intval(date('H:i', strtotime($booking->start_date)));
    //             $end_hour = intval(date('H:i', strtotime($booking->end_date)));

    //             while ($start_hour <= $end_hour) {
    //                 $hoursArray[$start_hour] = false;
    //                 $start_hour = $start_hour + 1;
    //             }
    //         }

    //         $units[$key]->hours = $hoursArray;
    //     }

    //     return response()->json($units, Response::HTTP_OK);

    // }

    // public function get_units_available(Request $request) {
    //     $unitsWithPricesAndDescriptions = DB::table('ms_units')
    //     ->join('ms_unit_product_prices', 'ms_units.id', '=', 'ms_unit_product_prices.unit_id')
    //     ->join('ms_products', 'ms_unit_product_prices.product_id', '=', 'ms_products.id')
    //     ->where('ms_unit_product_prices.product_id', $request->product_id)
    //     ->where('ms_units.region_id', $request->region_id)
    //     ->select('ms_units.*', 'ms_unit_product_prices.description as unit_description', 'ms_products.capacity_info as capacity_info')
    //     ->get()
    //     ->toArray();
    
    //     $unit_bookings = DB::table('ms_units as u')
    //     ->join('ms_bookings as b', 'u.company_id', '=', 'b.company_id')
    //     ->whereDate('b.start_date', $request->start_date)
    //     ->select('u.*', 'b.*')
    //     ->get()
    //     ->toArray();
    
    //     foreach ($unitsWithPricesAndDescriptions as $key => $unit) {
    //         $unit_bookings_filtered = array_filter($unit_bookings, function($obj) use ($unit) {
    //             return $obj->unit_id == $unit->id;
    //         });
    
    //         $temp_time = intval($unit->start_time);
    //         $hoursArray = [];
    
    //         while ($temp_time !== intval($unit->end_time)) {
    //             $hoursArray[$temp_time] = true;
    //             $temp_time = $temp_time + 1;
    //         }
    
    //         foreach ($unit_bookings_filtered as $booking) {
    //             $start_hour = intval(date('H:i', strtotime($booking->start_date)));
    //             $end_hour = intval(date('H:i', strtotime($booking->end_date)));
    
    //             while ($start_hour <= $end_hour) {
    //                 $hoursArray[$start_hour] = false;
    //                 $start_hour = $start_hour + 1;
    //             }
    //         }
    
    //         $unitsWithPricesAndDescriptions[$key]->hours = $hoursArray;
    //         $unitsWithPricesAndDescriptions[$key]->bookings = $unit_bookings_filtered;
    //     }
    
    //     return response()->json($unitsWithPricesAndDescriptions, Response::HTTP_OK);
    // }

    // public function get_units_available(Request $request) {
    //     $unitsWithPricesAndDescriptions = DB::table('ms_units')
    //         ->join('ms_unit_product_prices', 'ms_units.id', '=', 'ms_unit_product_prices.unit_id')
    //         ->join('ms_products', 'ms_unit_product_prices.product_id', '=', 'ms_products.id')
    //         ->where('ms_unit_product_prices.product_id', $request->product_id)
    //         ->where('ms_units.region_id', $request->region_id)
    //         ->select('ms_units.*', 'ms_unit_product_prices.description as unit_description', 'ms_products.capacity_info as capacity_info')
    //         ->get()
    //         ->toArray();
    
    //     $unit_bookings = DB::table('ms_units as u')
    //         ->join('ms_bookings as b', 'u.company_id', '=', 'b.company_id')
    //         ->whereDate('b.start_date', $request->start_date)
    //         ->select('u.*', 'b.*')
    //         ->get()
    //         ->toArray();
    
    //     foreach ($unitsWithPricesAndDescriptions as $key => $unit) {
    //         // Initialize hours array
    //         $temp_time = intval($unit->start_time);
    //         $hoursArray = [];
    //         while ($temp_time !== intval($unit->end_time)) {
    //             $hoursArray[$temp_time] = true;
    //             $temp_time++;
    //         }
    
    //         // Update hours based on unit availability
    //         $unit_availabilities = DB::table('ms_unit_availabilities')
    //             ->where('unit_id', $unit->id)
    //             ->whereDate('start_date', '<=', $request->start_date)
    //             ->whereDate('end_date', '>=', $request->start_date)
    //             ->get();
    
    //         foreach ($unit_availabilities as $availability) {
    //             $start_hour = intval(date('H', strtotime($availability->start_date)));
    //             $end_hour = intval(date('H', strtotime($availability->end_date)));
    
    //             for ($hour = $start_hour; $hour <= $end_hour; $hour++) {
    //                 $hoursArray[$hour] = false;
    //             }
    //         }
    
    //         // Update hours based on bookings
    //         $unit_bookings_filtered = array_filter($unit_bookings, function($obj) use ($unit) {
    //             return $obj->unit_id == $unit->id;
    //         });
    
    //         foreach ($unit_bookings_filtered as $booking) {
    //             $start_hour = intval(date('H', strtotime($booking->start_date)));
    //             $end_hour = intval(date('H', strtotime($booking->end_date)));
    
    //             for ($hour = $start_hour; $hour <= $end_hour; $hour++) {
    //                 $hoursArray[$hour] = false;
    //             }
    //         }
    
    //         $unitsWithPricesAndDescriptions[$key]->hours = $hoursArray;
    //         $unitsWithPricesAndDescriptions[$key]->bookings = $unit_bookings_filtered;
    //     }
    
    //     return response()->json($unitsWithPricesAndDescriptions, Response::HTTP_OK);
    // }

    public function get_units_available(Request $request) {
        $unitsWithPricesAndDescriptions = DB::table('ms_units')
            ->join('ms_unit_product_prices', 'ms_units.id', '=', 'ms_unit_product_prices.unit_id')
            ->join('ms_products', 'ms_unit_product_prices.product_id', '=', 'ms_products.id')
            ->where('ms_unit_product_prices.product_id', $request->product_id)
            ->where('ms_units.region_id', $request->region_id)
            ->select('ms_units.*', 'ms_unit_product_prices.description as unit_description', 'ms_products.capacity_info as capacity_info')
            ->get()
            ->toArray();
    
        $unit_bookings = DB::table('ms_units as u')
            ->join('ms_bookings as b', 'u.company_id', '=', 'b.company_id')
            ->whereDate('b.start_date', $request->start_date)
            ->select('u.*', 'b.*')
            ->get()
            ->toArray();
    
        $filteredUnits = []; // Array to hold units that do not match the pattern
    
        foreach ($unitsWithPricesAndDescriptions as $key => $unit) {
            // Initialize hours array
            $temp_time = intval($unit->start_time);
            $hoursArray = [];
            while ($temp_time < intval($unit->end_time)) {
                $hoursArray[$temp_time] = true;
                $temp_time++;
            }
    
            // Update hours based on unit availability
            $unit_availabilities = DB::table('ms_unit_availabilities')
                ->where('unit_id', $unit->id)
                ->whereDate('start_date', '<=', $request->start_date)
                ->whereDate('end_date', '>=', $request->start_date)
                ->get();
    
            foreach ($unit_availabilities as $availability) {
                $start_hour = intval(date('H', strtotime($availability->start_date)));
                $end_hour = intval(date('H', strtotime($availability->end_date)));
    
                for ($hour = $start_hour; $hour <= $end_hour; $hour++) {
                    $hoursArray[$hour] = false;
                }
            }
    
            // Update hours based on bookings
            $unit_bookings_filtered = array_filter($unit_bookings, function($obj) use ($unit) {
                return $obj->unit_id == $unit->id;
            });
    
            foreach ($unit_bookings_filtered as $booking) {
                $start_hour = intval(date('H', strtotime($booking->start_date)));
                $end_hour = intval(date('H', strtotime($booking->end_date)));
    
                for ($hour = $start_hour; $hour <= $end_hour; $hour++) {
                    $hoursArray[$hour] = false;
                }
            }
    
            // Check if the pattern of false hours is met
            $excludeUnit = true; // Assume we need to exclude the unit
            for ($hour = intval($unit->start_time); $hour < intval($unit->end_time); $hour += 2) {
                if (!isset($hoursArray[$hour]) || $hoursArray[$hour] === true) {
                    $excludeUnit = false; // If any hour in the pattern is true, keep the unit
                    break;
                }
            }
    
            if (!$excludeUnit) {
                $unitsWithPricesAndDescriptions[$key]->hours = $hoursArray;
                $unitsWithPricesAndDescriptions[$key]->bookings = $unit_bookings_filtered;
                $filteredUnits[] = $unitsWithPricesAndDescriptions[$key]; // Include this unit
            }
        }
    
        return response()->json($filteredUnits, Response::HTTP_OK);
    }

    public function get_units_available_times(Request $request) {
        $units = DB::table('ms_units')
            ->where('company_id', $request->company_id)
            ->get()
            ->toArray();
    
        $unit_bookings = DB::table('ms_units as u')
            ->join('ms_bookings as b', 'u.id', '=', 'b.unit_id')
            ->where('u.company_id', $request->company_id)
            ->whereDate('b.start_date', $request->start_date)
            ->get()
            ->toArray();
    
        foreach ($units as $key => $unit) {
            // Initialize hours array
            $temp_time = intval($unit->start_time);
            $hoursArray = [];
            while ($temp_time !== intval($unit->end_time)) {
                $hoursArray[$temp_time] = true;
                $temp_time++;
            }
    
            // Update hours based on unit availability
            $unit_availabilities = DB::table('ms_unit_availabilities')
                ->where('unit_id', $unit->id)
                ->whereDate('start_date', '<=', $request->start_date)
                ->whereDate('end_date', '>=', $request->start_date)
                ->get();
    
            foreach ($unit_availabilities as $availability) {
                $start_hour = intval(date('H', strtotime($availability->start_date)));
                $end_hour = intval(date('H', strtotime($availability->end_date)));
    
                for ($hour = $start_hour; $hour <= $end_hour; $hour++) {
                    $hoursArray[$hour] = false;
                }
            }
    
            // Update hours based on bookings
            $units[$key]->bookings = array_filter($unit_bookings, function($obj) use ($unit) {
                return $obj->unit_id == $unit->id;
            });
    
            foreach ($units[$key]->bookings as $booking) {
                $start_hour = intval(date('H', strtotime($booking->start_date)));
                $end_hour = intval(date('H', strtotime($booking->end_date)));
    
                for ($hour = $start_hour; $hour <= $end_hour; $hour++) {
                    $hoursArray[$hour] = false;
                }
            }
    
            $units[$key]->hours = $hoursArray;
        }
    
        return response()->json($units, Response::HTTP_OK);
    }

    public function get_units_available2(Request $request) {
        $company_id = $request->company_id;
        $start_date = $request->start_date;

        $units = MsUnit::where('company_id', $company_id)->get();

        $unit_bookings = DB::table('ms_units')
            ->join('ms_bookings', 'ms_units.id', '=', 'ms_bookings.unit_id')
            ->where('ms_units.company_id', $company_id)
            ->whereDate('ms_bookings.start_date', $start_date)
//            ->where('ms_bookings.payment_status', 'paid')
            ->select('ms_bookings.*')
            ->get();

        foreach ($units as $key => $unit) {
            $unit->bookings = [];

            foreach ($unit_bookings as $key => $value) {
//                if ($obj['unit_id'] == $unit['id']) {
//                    $unit->bookings[] = $obj;
//                }
//                array_push($obj);
//                $unit->bookings[$key] = $value;


                print_r($value->unit_id);
            }
//            $temp_time = intval($unit['start_time']);
//            $hoursArray = [];
//            while($temp_time !== intval($unit['end_time'])) {
//                $hoursArray[$temp_time] = true;
//                $temp_time = $temp_time + 1;
//            }
//
//            foreach ($unit->bookings as $bkKey => $booking) {
//                $start_hour = intval(date( 'H:i', strtotime( $booking['start_date'] )));
//                $end_hour = intval(date( 'H:i', strtotime( $booking['start_date'] )));
//                while($start_hour <= $end_hour) {
//                    $hoursArray[$start_hour] = false;
//                    $start_hour = $start_hour + 1;
//                }
//            }
//
//            $unit->hours = $hoursArray;
        }

        return $units;
    }

    // public function get_units_available_for_all_companies(Request $request)
    //     {
    //     $companies = DB::table('ms_companies')->get();
    //     $availableCompanies = [];

    //     foreach ($companies as $company) {
    //         $units = DB::table('ms_units')
    //             ->where('company_id', $company->id)
    //             ->get()
    //             ->toArray();

    //         $unit_bookings = DB::table('ms_units as u')
    //             ->join('ms_bookings as b', 'u.id', '=', 'b.unit_id')
    //             ->where('u.company_id', $company->id)
    //             ->whereDate('b.start_date', $request->start_date)
    //             ->get()
    //             ->toArray();

    //         foreach ($units as $key => $unit) {
    //             $units[$key]->bookings = array_filter($unit_bookings, function ($obj) use ($unit) {
    //                 return $obj->unit_id == $unit->id;
    //             });

    //             $temp_time = intval($unit->start_time);
    //             $hoursArray = [];

    //             while ($temp_time !== intval($unit->end_time)) {
    //                 $hoursArray[$temp_time] = true;
    //                 $temp_time = $temp_time + 1;
    //             }

    //             foreach ($units[$key]->bookings as $bkKey => $booking) {
    //                 $start_hour = intval(date('H:i', strtotime($booking->start_date)));
    //                 $end_hour = intval(date('H:i', strtotime($booking->end_date)));

    //                 while ($start_hour <= $end_hour) {
    //                     $hoursArray[$start_hour] = false;
    //                     $start_hour = $start_hour + 1;
    //                 }
    //             }

    //             $units[$key]->hours = $hoursArray;
    //         }

    //         $unit_available = false;
    //         foreach ($units as $unit) {
    //             $unit_available = true;
    //             for ($i = (int)$unit->start_time; $i < (int)$unit->end_time; $i += 4) {
    //                 $block = array_slice($unit->hours, (int)$i, 4);
    //                 if (in_array(false, $block)) {
    //                     $unit_available = false;
    //                     break;
    //                 }
    //             }
    //             if ($unit_available) {
    //                 break;
    //             }
    //         }

    //         if ($unit_available) {
    //             $availableCompanies[] = $company->id;
    //         }
    //     }

    //     $companyProductPrices = DB::table('ms_companies')
    //         ->join('ms_company_product_prices', 'ms_companies.id', '=', 'ms_company_product_prices.company_id')
    //         ->where('product_id', $request->product_id)
    //         ->whereIn('ms_companies.id', $availableCompanies)
    //         ->select('ms_company_product_prices.id')
    //         ->get()
    //         ->toArray();

    //     return response()->json($companyProductPrices, Response::HTTP_OK);
    // }

    public function get_units_available_for_all_companies(Request $request)
        {
        $companies = DB::table('ms_companies')->get();
        $availableCompanies = [];

        foreach ($companies as $company) {
            $units = DB::table('ms_units')
                ->where('company_id', $company->id)
                ->get()
                ->toArray();

            $unit_bookings = DB::table('ms_units as u')
                ->join('ms_bookings as b', 'u.id', '=', 'b.unit_id')
                ->where('u.company_id', $company->id)
                ->whereDate('b.start_date', $request->start_date)
                ->get()
                ->toArray();

            foreach ($units as $key => $unit) {
                $units[$key]->bookings = array_filter($unit_bookings, function ($obj) use ($unit) {
                    return $obj->unit_id == $unit->id;
                });

                $temp_time = intval($unit->start_time);
                $hoursArray = [];

                while ($temp_time !== intval($unit->end_time)) {
                    $hoursArray[$temp_time] = true;
                    $temp_time = $temp_time + 1;
                }

                foreach ($units[$key]->bookings as $bkKey => $booking) {
                    $start_hour = intval(date('H:i', strtotime($booking->start_date)));
                    $end_hour = intval(date('H:i', strtotime($booking->end_date)));

                    while ($start_hour <= $end_hour) {
                        $hoursArray[$start_hour] = false;
                        $start_hour = $start_hour + 1;
                    }
                }

                $units[$key]->hours = $hoursArray;
            }

            $unit_available = false;
            foreach ($units as $unit) {
                $unit_available = true;
                for ($i = (int)$unit->start_time; $i < (int)$unit->end_time; $i += 4) {
                    $block = array_slice($unit->hours, (int)$i, 4);
                    if (in_array(false, $block)) {
                        $unit_available = false;
                        break;
                    }
                }
                if ($unit_available) {
                    break;
                }
            }

            if ($unit_available) {
                $availableCompanies[] = $company->id;
            }
        }

        $availableUnits = DB::table('ms_units')
        ->whereIn('company_id', $availableCompanies)
        ->pluck('id');

        // Adjusting the final query to include company_id
        $unitProductPrices = DB::table('ms_unit_product_prices')
            ->join('ms_units', 'ms_unit_product_prices.unit_id', '=', 'ms_units.id')
            ->whereIn('ms_unit_product_prices.unit_id', $availableUnits)
            ->where('ms_unit_product_prices.product_id', $request->product_id)
            ->select('ms_unit_product_prices.id', 'ms_units.company_id')
            ->get()
            ->toArray();

        return response()->json($unitProductPrices, Response::HTTP_OK);
    }

    public function pricing2(Request $request) {
    // Joining units with their prices and products, then with companies
    $units = DB::table('ms_units')
        ->join('ms_unit_product_prices', 'ms_units.id', '=', 'ms_unit_product_prices.unit_id')
        ->join('ms_products', 'ms_products.id', '=', 'ms_unit_product_prices.product_id')
        ->join('ms_companies', 'ms_units.company_id', '=', 'ms_companies.id')
        ->where([
            ['ms_products.id', $request->productId],
            ['ms_companies.status', 1]
        ])
        ->select('ms_companies.*', 'ms_units.*', 'ms_unit_product_prices.*')
        ->get();

    foreach ($units as $key => $unit) {
        $freeStartCityDistance = MsFreeCity::where([
            ['name', $request->timeAddressForm['startAddress']['city']], 
            ['company_id', $unit->company_id]
        ])->exists();
        
        $freeEndCityDistance = MsFreeCity::where([
            ['name', $request->timeAddressForm['endAddress']['city']], 
            ['company_id', $unit->company_id]
        ])->exists();

        $baseStartDistance = $this->getDistance($unit->address, $request->timeAddressForm['startAddress']['name']);
        $startEndDistance = $this->getDistance($request->timeAddressForm['startAddress']['name'], $request->timeAddressForm['endAddress']['name']);
        $endBaseDistance = $this->getDistance($request->timeAddressForm['endAddress']['name'], $unit->address);

        $startDistance = $freeStartCityDistance ? 0 : $baseStartDistance;
        $endDistance = $freeEndCityDistance ? 0 : $endBaseDistance;
        $distance = ($startEndDistance - ($unit->included_km * 1000)) + $startDistance + $endDistance;
        $unit->road_price = $distance > 0 ? ($distance / 1000) * intval($unit->price_per_km) : 0;

        // Flat area calculation
        $startFlatMPrice = isset($request->timeAddressForm['startAddress']['flat_squarem']) ? 
            (intval($request->timeAddressForm['startAddress']['flat_squarem']) - intval($unit->included_m2)) * intval($unit->price_per_m2) : 0;
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
                $startElevatorPercentage = intval($unit->no_elevator);
                break;
            case 2:
                $startElevatorPercentage = intval($unit->small_elevator);
                break;
            case 3:
                $startElevatorPercentage = intval($unit->big_elevator);
                break;
            case 4: 
                $startElevatorPercentage = intval($unit->new_building);
        }

        switch ($endElevator) {
            case 1:
                $endElevatorPercentage = intval($unit->no_elevator);
                break;
            case 2:
                $endElevatorPercentage = intval($unit->small_elevator);
                break;
            case 3:
                $endElevatorPercentage = intval($unit->big_elevator);
                break;
            case 4:
                $endElevatorPercentage = intval($unit->new_building);
                break;
        }

        $floorPricePercentage = $startFloor * $startElevatorPercentage + $endFloor * $endElevatorPercentage;

        //Outdoor distance calculation
        $outdoorPricePercentage = 0;
        $startOutdoorPrice =
            isset($request->timeAddressForm['startAddress']['outdoor_distance']) ?
                (intval($request->timeAddressForm['startAddress']['outdoor_distance']) - intval($unit->included_meters_outdoor)) * intval($unit->outdoor_price_per_meter) : 0;
        $startOutdoorPricePercentage = $startOutdoorPrice >= 0 ? $startOutdoorPrice : 0;

        $endOutdoorPrice =
            isset($request->timeAddressForm['endAddress']['outdoor_distance']) ?
                (intval($request->timeAddressForm['endAddress']['outdoor_distance']) - intval($unit->included_meters_outdoor)) * intval($unit->outdoor_price_per_meter) : 0;
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
                $startStoragePricePercentage = ($startStorageArea - intval($unit->included_m2_basement_storage)) * intval($unit->basement_storage_price_per_m2);
                break;
            case 3:
                $startStoragePricePercentage = ($startStorageArea - intval($unit->included_m2_roof_storage)) * intval($unit->roof_storage_price_per_m2);
                $startStoragePricePercentage = $startStoragePricePercentage >= 0 ? $startStoragePricePercentage : 0;
                $startStoragePricePercentage += $startElevatorPercentage * $startStorageFloor;
                break;
        }

        switch ($endStorage) {
            case 2:
                $endStoragePricePercentage = ($endStorageArea - intval($unit->included_m2_basement_storage)) * intval($unit->basement_storage_price_per_m2);
                break;
            case 3:
                $endStoragePricePercentage = ($endStorageArea - intval($unit->included_m2_roof_storage)) * intval($unit->roof_storage_price_per_m2);
                $endStoragePricePercentage = $endStoragePricePercentage >= 0 ? $endStoragePricePercentage : 0;
                $endStoragePricePercentage += $endElevatorPercentage * $endStorageFloor;
                break;
        }

        $storagePricePercentage = $startStoragePricePercentage + $endStoragePricePercentage;

        // Date and time-based pricing adjustments
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
                $price = $unit->saturday_price;
            } elseif ($date->isSunday()) {
                $price = $unit->sunday_price;
            }
        } else {
            $price = $unit->price;
        }

        $unit->pre_price = ceil($unit->road_price + $price);

        $unit->pre_price += $price * ($floorPricePercentage + $outdoorPricePercentage) / 100 + $startFlatMPrice + $storagePricePercentage;
        $unit->is_featured = intval($unit->is_featured);
    }

    return $units;
    }

    public function pricing(Request $request) {
        $units = DB::table('ms_units')
            ->join('ms_unit_product_prices', 'ms_units.id', '=', 'ms_unit_product_prices.unit_id')
            ->join('ms_products', 'ms_products.id', '=', 'ms_unit_product_prices.product_id')
            ->where('ms_products.id', $request->productId)
            ->where('ms_units.region_id', $request->regionId)
            ->select('ms_units.*', 'ms_unit_product_prices.*')
            ->get();
    
        foreach ($units as $key => $unit) {
            
            $company = DB::table('ms_companies')
            ->where('id', $unit->company_id)
            ->first();

            $freeStartCityDistance = MsFreeCity::where([
                ['name', $request->timeAddressForm['startAddress']['city']], 
                ['company_id', $unit->company_id]
            ])->exists();
    
            $freeEndCityDistance = MsFreeCity::where([
                ['name', $request->timeAddressForm['endAddress']['city']], 
                ['company_id', $unit->company_id]
            ])->exists();
    
            $baseStartDistance = $this->getDistance($unit->address, $request->timeAddressForm['startAddress']['name']);
            $startEndDistance = $this->getDistance($request->timeAddressForm['startAddress']['name'], $request->timeAddressForm['endAddress']['name']);
            $endBaseDistance = $this->getDistance($request->timeAddressForm['endAddress']['name'], $unit->address);
    
            $startDistance = $freeStartCityDistance ? 0 : $baseStartDistance;
            $endDistance = $freeEndCityDistance ? 0 : $endBaseDistance;
            $distance = ($startEndDistance - ($unit->included_km * 1000)) + $startDistance + $endDistance;
            $unit->road_price = $distance > 0 ? ($distance / 1000) * doubleval($unit->price_per_km) : 0;

            $price = 0;
            $dateString = $request->timeAddressForm['date'];
    
            $dateUtc = new DateTime($dateString, new DateTimeZone('UTC'));
    
            $dateLocal = $dateUtc->setTimezone(new DateTimeZone('Europe/Helsinki'));
    
            $date = Carbon::parse($dateLocal->format('Y-m-d H:i:s'));

            if ($date->isWeekend()) {
                $basePrice = $unit->price;
            
                if ($date->isSaturday()) {
                    $price = $basePrice + ($basePrice * ($unit->saturday_price / 100));
                } elseif ($date->isSunday()) {
                    $price = $basePrice + ($basePrice * ($unit->sunday_price / 100));
                }
            } else {
                $price = $unit->price;
            }
    
            $unit->pre_price = $unit->road_price + $price;
            $unit->discounted_price = 0;

            if ($unit->discount_price > 0) {
                $unit->discounted_price = $unit->pre_price - $unit->discount_price;
            }

            $unit->pre_price = number_format($unit->pre_price, 2, '.', '');
            $unit->discounted_price = number_format($unit->discounted_price, 2, '.', '');

            $company->is_featured = intval($company->is_featured);
        }
    
        return $units;
    }

    public function extraPricing(Request $request) {
        $unitId = $request->unitId;
        $productId = $request->productId;
        $totalPrice = $request->priceDetails['price'];
        $discountedPrice = $request->priceDetails['discountedPrice'];

        $unit = DB::table('ms_units')
            ->join('ms_unit_product_prices', 'ms_units.id', '=', 'ms_unit_product_prices.unit_id')
            ->where('ms_unit_product_prices.product_id', $productId)
            ->where('ms_units.id', $unitId)
            ->select('ms_units.*', 'ms_unit_product_prices.*')
            ->first();

        if (!$unit) {
            return response()->json(['error' => 'Unit not found'], 404);
        }
    
        // Initialize extra costs
        $extraCosts = 0;
        
        // Check if flat_squarem is provided and calculate extra cost
        if (isset($request->timeAddressForm['startAddress']['flat_squarem'])) {
            $flatSquareMeters = intval($request->timeAddressForm['startAddress']['flat_squarem']);
            $includedSquareMeters = intval($unit->included_m2);
            $pricePerSquareMeter = intval($unit->price_per_m2);
            $extraFlatMPrice = ($flatSquareMeters - $includedSquareMeters) * $pricePerSquareMeter;
            $extraCosts += max($extraFlatMPrice, 0);
        }

        // Calculate start elevator percentage
        $startElevator = isset($request->timeAddressForm['startAddress']['elevator']) ? intval($request->timeAddressForm['startAddress']['elevator']) : 0;
        $startElevatorPercentage = 0;

        switch ($startElevator) {
            case 1:
                $startElevatorPercentage = intval($unit->no_elevator);
                break;
            case 2:
                $startElevatorPercentage = intval($unit->small_elevator);
                break;
            case 3:
                $startElevatorPercentage = intval($unit->big_elevator);
                break;
            case 4:
                $startElevatorPercentage = intval($unit->new_building);
        }

        // Check if floor is provided and calculate extra cost
        if (isset($request->timeAddressForm['startAddress']['floor'])) {
            $startFloorValue = 0;
            $floorPricePercentageStart = 0;

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

            $startFloor = $startFloorValue;

            $floorPricePercentageStart = $startFloor * $startElevatorPercentage;

            $extraCosts += $floorPricePercentageStart;
        }

         // Calculate start elevator percentage

        $endElevator = isset($request->timeAddressForm['endAddress']['elevator']) ? intval($request->timeAddressForm['endAddress']['elevator']) : 0;
        
        $endElevatorPercentage = 0;

        switch ($endElevator) {
            case 1:
                $endElevatorPercentage = intval($unit->no_elevator);
                break;
            case 2:
                $endElevatorPercentage = intval($unit->small_elevator);
                break;
            case 3:
                $endElevatorPercentage = intval($unit->big_elevator);
                break;
            case 4:
                $endElevatorPercentage = intval($unit->new_building);
                break;
        }

        // Check if floor is provided and calculate extra cost
        if (isset($request->timeAddressForm['endAddress']['floor'])) {

            $endFloorValue = 0;
            $floorPricePercentageEnd = 0;
            
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

            $endFloor = $endFloorValue;

            $floorPricePercentageEnd = $endFloor * $endElevatorPercentage;

            $extraCosts += $floorPricePercentageEnd;
        }

         // Check if outdoor_distance is provided and calculate extra cost
        if (isset($request->timeAddressForm['startAddress']['outdoor_distance'])) {
            $startOutdoorDistance = intval($request->timeAddressForm['startAddress']['outdoor_distance']);
            $includedOutdoorDistance = intval($unit->included_meters_outdoor);
            $outdoorPricePerMeter = intval($unit->outdoor_price_per_meter);
    
            $startOutdoorPrice = ($startOutdoorDistance - $includedOutdoorDistance) * $outdoorPricePerMeter;
            $startOutdoorPricePercentage = $startOutdoorPrice >= 0 ? $startOutdoorPrice : 0;
        
            $extraCosts += $startOutdoorPricePercentage;
        }

        // Check if outdoor distance is provided and calculate extra cost
        if (isset($request->timeAddressForm['endAddress']['outdoor_distance'])) {
            $endOutdoorDistance = intval($request->timeAddressForm['endAddress']['outdoor_distance']);
            $includedOutdoorDistance = intval($unit->included_meters_outdoor);
            $outdoorPricePerMeter = intval($unit->outdoor_price_per_meter);

            $endOutdoorPrice = ($endOutdoorDistance - $includedOutdoorDistance) * $outdoorPricePerMeter;
            $endOutdoorPricePercentage = $endOutdoorPrice >= 0 ? $endOutdoorPrice : 0;

            $extraCosts += $endOutdoorPricePercentage;
        }

        // Check if storage is provided and calculate extra cost
        if (isset($request->timeAddressForm['startAddress']['storage'])) {
            $startStorageType = intval($request->timeAddressForm['startAddress']['storage']);
            $startStorageArea = isset($request->timeAddressForm['startAddress']['storage_area']) ? intval($request->timeAddressForm['startAddress']['storage_area']) : 0;
            $startStorageFloor = isset($request->timeAddressForm['startAddress']['storage_floor']) ? intval($request->timeAddressForm['startAddress']['storage_floor']) : 1;
        
            $startStoragePricePercentage = 0;
        
            switch ($startStorageType) {
                case 2:
                    $startStoragePricePercentage = ($startStorageArea - intval($unit->included_m2_basement_storage)) * intval($unit->basement_storage_price_per_m2);
                    break;
                case 3:
                    $startStoragePricePercentage = ($startStorageArea - intval($unit->included_m2_roof_storage)) * intval($unit->roof_storage_price_per_m2);
                    $startStoragePricePercentage = $startStoragePricePercentage >= 0 ? $startStoragePricePercentage : 0;
                    $startStoragePricePercentage += $startElevatorPercentage * $startStorageFloor; // Assuming elevator price needs to be added
                    break;
            }
        
            $extraCosts += $startStoragePricePercentage;
        }

        // Check if end storage data is provided and calculate extra cost
        if (isset($request->timeAddressForm['endAddress']['storage'])) {
            $endStorageType = intval($request->timeAddressForm['endAddress']['storage']);
            $endStorageArea = isset($request->timeAddressForm['endAddress']['storage_area']) ? intval($request->timeAddressForm['endAddress']['storage_area']) : 0;
            $endStorageFloor = isset($request->timeAddressForm['endAddress']['storage_floor']) ? intval($request->timeAddressForm['endAddress']['storage_floor']) : 1;

            $endStoragePricePercentage = 0;

            switch ($endStorageType) {
                case 2:
                    $endStoragePricePercentage = ($endStorageArea - intval($unit->included_m2_basement_storage)) * intval($unit->basement_storage_price_per_m2);
                    break;
                case 3:
                    $endStoragePricePercentage = ($endStorageArea - intval($unit->included_m2_roof_storage)) * intval($unit->roof_storage_price_per_m2);
                    $endStoragePricePercentage = $endStoragePricePercentage >= 0 ? $endStoragePricePercentage : 0;
                    $endStoragePricePercentage += $endElevatorPercentage * $endStorageFloor; // Assuming elevator price needs to be added
                    break;
            }

            $extraCosts += $endStoragePricePercentage;
        }

        $totalPrice += $totalPrice * ($extraCosts / 100);
        $discountedPrice += $discountedPrice * ($extraCosts / 100);

        return (object)[
            'totalPrice' => $totalPrice,
            'discountedPrice' => $discountedPrice,
        ];
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
}
