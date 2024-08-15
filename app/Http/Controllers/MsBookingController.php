<?php

namespace App\Http\Controllers;

use App\Mail\MovingServiceBookingConfirmation;
use App\Mail\BookingReceivedConfirmation;
use App\Mail\ExtraServiceBookingPaid;
use App\Mail\PayRightAwayConfirmation;
use App\Mail\MsBookingPaid;
use App\Mail\PaymentCanceled;
use App\Models\MsExtraService;
use App\Models\MsBooking;
use App\Models\MsCompany;
use App\Models\MsProduct;
use App\Models\MsUnit;
use App\Models\MsUnitProductPrice;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Mailer;
use VismaPay\VismaPay;

class MsBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MsBooking::all();
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
            'product_id' => 'required',
            'booking_number' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'price' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'start_address' => 'required',
            'end_address' => 'required',
            'start_door_number' => 'required',
            'end_door_number'=> 'required',
            'start_door_code'=> 'required',
            'end_door_code'=> 'required',
            'start_comment'=> 'required',
            'end_comment'=> 'required',
            'start_floor'=> 'required',
            'end_floor'=> 'required',
            'start_elevator'=> 'required',
            'end_elevator'=> 'required',
            'start_outdoor_distance'=> 'required',
            'end_outdoor_distance'=> 'required',
            'start_storage'=> 'required',
            'end_storage'=> 'required',
            'start_storage_m2'=> 'required',
            'end_storage_m2'=> 'required',
            'start_storage_floor'=> 'required',
            'end_storage_floor'=> 'required',
            'start_square_meters'=> 'required',
            'end_square_meters'=> 'required',
            'payment_status'=> 'required',
            'progress_status'=> 'required',
        ]);

        $booking = new MsBooking();
        $booking->company_id = intval($request->company_id);
        $booking->unit_id = intval($request->unit_id);
        $booking->product_id = intval($request->product_id);
        $booking->booking_number = $request->booking_number;
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->price = $request->price;
        $booking->first_name = $request->first_name;
        $booking->last_name = $request->last_name;
        $booking->email = $request->email;
        $booking->phone = $request->phone;
        $booking->start_address = $request->start_address;
        $booking->end_address = $request->end_address;
        $booking->start_door_number = $request->start_door_number;
        $booking->end_door_number = $request->end_door_number;
        $booking->start_door_code = $request->start_door_code;
        $booking->end_door_code = $request->end_door_code;
        $booking->start_comment = $request->start_comment;
        $booking->end_comment = $request->end_comment;
        $booking->start_floor = $request->start_floor;
        $booking->end_floor = $request->end_floor;
        $booking->start_elevator = $request->start_elevator;
        $booking->end_elevator = $request->end_elevator;
        $booking->start_outdoor_distance = $request->start_outdoor_distance;
        $booking->end_outdoor_distance = $request->end_outdoor_distance;
        $booking->start_storage = $request->start_storage;
        $booking->end_storage = $request->end_storage;
        $booking->start_storage_m2 = $request->start_storage_m2;
        $booking->end_storage_m2 = $request->end_storage_m2;
        $booking->start_storage_floor = $request->start_storage_floor;
        $booking->end_storage_floor = $request->end_storage_floor;
        $booking->start_square_meters = $request->start_square_meters;
        $booking->end_square_meters = $request->end_square_meters;
        $booking->payment_status = $request->payment_status;
        $booking->progress_status = $request->progress_status;

        return $booking->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MsBooking::find($id);
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
        $booking = MsBooking::find($id);

        $company_id = intval($request->company_id);
        $unit_id = intval($request->unit_id);
        $product_id = intval($request->product_id);

        $startDateTime = Carbon::parse(str_replace('T', ' ', $request->start_date) . ' ' . $request->start_time . ':00');
        $endDateTime = Carbon::parse(str_replace('T', ' ', $request->end_date) . ' ' . $request->end_time . ':00');

        $booking->update([
            'company_id'=> $company_id,
            'unit_id' => $unit_id,
            'product_id' => $product_id,
            'booking_number' => $request->booking_number,
            'start_date' => $startDateTime,
            'end_date' => $endDateTime,
            'price' => $request->price,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'start_address' => $request->start_address,
            'end_address' => $request->end_address,
            'start_door_number' => $request->start_door_number,
            'end_door_number' => $request->end_door_number,
            'start_door_code' => $request->start_door_code,
            'end_door_code' => $request->end_door_code,
            'start_comment' => $request->start_comment,
            'end_comment' => $request->end_comment,
            'start_floor' => $request->start_floor,
            'end_floor' => $request->end_floor,
            'start_elevator' => $request->start_elevator,
            'end_elevator' => $request->end_elevator,
            'start_outdoor_distance' => $request->start_outdoor_distance,
            'end_outdoor_distance' => $request->end_outdoor_distance,
            'start_storage' => $request->start_storage,
            'end_storage' => $request->end_storage,
            'start_storage_m2' => $request->start_storage_m2,
            'end_storage_m2' => $request->end_storage_m2,
            'start_storage_floor' => $request->start_storage_floor,
            'end_storage_floor' => $request->end_storage_floor,
            'start_square_meters' => $request->start_square_meters,
            'end_square_meters' => $request->end_square_meters,
            'payment_status' => $request->payment_status,
            'progress_status' => $request->progress_status,
        ]);

        return $booking;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return MsBooking::destroy($id);
    }

    public function getBookingsByCompany($id)
    {
        return MsBooking::where('company_id', $id)->get();
    }

    /**
     * @param $date
     * @return void
     */
    public function getBookingsByDay(Request $request) {
        $date = Carbon::createFromFormat('Y-m-d', $request->date);

        return MsBooking::with(['days' => function ($query) {
            $query->where('payment_status', 'paid');
        }])
            ->where('payment_status', 'paid')
            ->where('company_id', $request->company_id)
            ->where(function($booking) use ($date) {
                $booking->whereDate('start_date', $date);
                $booking->orWhereDate('end_date', $date);
            })
            ->get();
    }

    /**
     * @param $date
     * @return void
     */

    public function getBookingsByMonth(Request $request){
        $date = Carbon::createFromFormat('Y-m-d', $request->date);
        $month = $date->format('m');
        $year = $date->format('Y');

        return $bookings = MsBooking::where('company_id', $request->company_id)
            ->where(function ($booking) use ($month, $year) {
                $booking->whereYear('start_date', $year)
                    ->whereMonth('start_date', $month);
            })
            ->orderByDesc('start_date')
            ->get();
    }

    /**
     * @param Request $request
     * @return void
     */
    public function updateStatus(Request $request) {
        $booking = MsBooking::find($request->id);
    
        return $booking->update(['progress_status' => 'done']);
    }

    /**
     * @param Request $request
     * @return Request
     *
     * Store booking from booking form (movingservice)
     */
    public function createBooking(Request $request) {
        $booking_number = "MS".time();

        $booking = new MsBooking();
        $booking->company_id = intval($request->company_id);
        $booking->unit_id = intval($request->unit_id);
        $booking->product_id = intval($request->product_id);
        $booking->booking_number = $booking_number;
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->price = $request->price;
        $booking->first_name = $request->first_name;
        $booking->last_name = $request->last_name;
        $booking->email = $request->email;
        $booking->phone = $request->phone;
        $booking->start_address = $request->start_address;
        $booking->end_address = $request->end_address;
        $booking->start_door_number = $request->start_door_number;
        $booking->end_door_number = $request->end_door_number;
        $booking->start_door_code = $request->start_door_code;
        $booking->end_door_code = $request->end_door_code;
        $booking->start_comment = $request->start_comment;
        $booking->end_comment = $request->end_comment;
        $booking->start_floor = $request->start_floor;
        $booking->end_floor = $request->end_floor;
        $booking->start_elevator = $request->start_elevator;
        $booking->end_elevator = $request->end_elevator;
        $booking->start_outdoor_distance = $request->start_outdoor_distance;
        $booking->end_outdoor_distance = $request->end_outdoor_distance;
        $booking->start_storage = $request->start_storage;
        $booking->end_storage = $request->end_storage;
        $booking->start_storage_m2 = $request->start_storage_m2;
        $booking->end_storage_m2 = $request->end_storage_m2;
        $booking->start_storage_floor = $request->start_storage_floor;
        $booking->end_storage_floor = $request->end_storage_floor;
        $booking->start_square_meters = $request->start_square_meters;
        $booking->end_square_meters = $request->end_square_meters;
        $booking->payment_status = 'unpaid';
        $booking->progress_status = 'to-start'; //to-start, started, done

        $couponValid = null;

        if (!empty($request->coupon_code)) {
            $code = $request->coupon_code;
        
            $coupon = Coupon::where('code', $code)
                ->where('company_id', $booking->company_id)
                ->active()
                ->first();
        
            if ($coupon) {
                if(!$coupon->status){
                    $couponValid = false;
                }else{
                    $booking->price -= ($coupon->is_percentage ? ($coupon->price / 100 * $booking->price) : $coupon->price);
        
                    if (!$coupon->is_unlimited) {
                        $coupon->used += 1;
                    }
                    $coupon->checkAndUpdateStatus();
    
                    $couponValid = true;
                }
            } else {
                $couponValid = false;
            }
        }
        
        $isStored = $booking->save();

        function getFloorName($floor)
        {
            switch ($floor) {
                case 'a':
                    return 'Maa-taso';
                case 'b':
                    return 'Rivitalo 1 kerros';
                case 'c':
                    return 'Omakotitalo 1 kerros';
                case 'd':
                    return 'Rivitalo 2 kerros';
                case 'e':
                    return 'Omakotitalo 2 kerros';
                default:
                    return "Kerros $floor";
            }
        }

        function getElevatorName($elevator)
        {
            switch ($elevator) {
                case '1':
                    return 'Ei hissiä';
                case '2':
                    return 'Pieni hissi (alle 1m2)';
                case '3':
                    return 'Iso hisi (1m2 tai isompi)';
                case '4':
                    return 'Uudiskohde';
                default:
                    return '';
            }
        }

        function getStorageName($storage)
        {
            switch ($storage) {
                case '1':
                    return 'Varastoa ei ole';
                case '2':
                    return 'Kellarikaappi / ulkovarasto';
                case '3':
                    return 'Vintti / Ullakko';
                default:
                    return '';
            }
        }

        if ($isStored){
            $email_data = [];
            $email_data['booking_number'] = $booking_number;
            $email_data['first_name'] = $booking->first_name;
            $email_data['last_name'] = $booking->last_name;
            $email_data['email'] = $booking->email;
            $email_data['phone'] = $booking->phone;
            $email_data['start_address'] = $booking->start_address;
            $email_data['end_address'] = $booking->end_address;
            $email_data['start_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->format('d.m.Y');
            $email_data['start_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->format('H:i').'h';
            $end_time = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->addHours(2);
            $email_data['end_time'] = $end_time->format('H:i').'h';
            $email_data['price'] = $booking->price;
            $email_data['start_door_number'] = $booking->start_door_number;
            $email_data['end_door_number'] = $booking->end_door_number;
            $email_data['start_door_code'] = $booking->start_door_code;
            $email_data['end_door_code'] = $booking->end_door_code;
            $email_data['start_comment'] = $booking->start_comment;
            $email_data['end_comment'] = $booking->end_comment;
            if ($booking->start_floor) {
                $email_data['start_floor'] = getFloorName($booking->start_floor);
            }
            if ($booking->end_floor) {
                $email_data['end_floor'] = getFloorName($booking->end_floor);
            }
            if ($booking->start_elevator) {
                $email_data['start_elevator'] = getElevatorName($booking->start_elevator);
            }
            if ($booking->end_elevator) {
                $email_data['end_elevator'] = getElevatorName($booking->end_elevator);
            }
            $email_data['start_outdoor_distance'] = $booking->start_outdoor_distance;
            $email_data['end_outdoor_distance'] = $booking->end_outdoor_distance;
            if ($booking->start_storage) {
                $email_data['start_storage'] = getStorageName($booking->start_storage);
            }
            if ($booking->end_storage) {
                $email_data['end_storage'] = getStorageName($booking->end_storage);
            }
            $email_data['start_storage_m2'] = $booking->start_storage_m2;
            $email_data['end_storage_m2'] = $booking->end_storage_m2;
            if ($booking->start_storage_floor) {
                $email_data['start_storage_floor'] = getFloorName($booking->start_storage_floor);
            }
            if ($booking->end_storage_floor) {
                $email_data['end_storage_floor'] = getFloorName($booking->end_storage_floor);
            }
            $email_data['start_square_meters'] = $booking->start_square_meters;
            $email_data['end_square_meters'] = $booking->end_square_meters;
            $current_time = \Carbon\Carbon::now();
            $email_data['timezone'] = $current_time;

            if($booking->product_id){
                $product = MsProduct::find($booking->product_id);
                $email_data['product_name'] = $product->name;
                
                $unitProductPrice = MsUnitProductPrice::where([
                    'unit_id' => $booking->unit_id,
                    'product_id' => $booking->product_id
                ])->first();

                $email_data['unit_description'] = $unitProductPrice->description;
            }

            $isSent = Mail::to($request->email)
                    ->send(new BookingReceivedConfirmation($email_data), [], function ($message) {
                        $message->setTimeZone('Europe/Helsinki');
                    });

            if ($isSent){

                $companyId = $booking->company_id;
                $company = MsCompany::find($companyId);
                $companyEmail = $company->email;

                Mail::to($companyEmail)
                    ->send(new BookingReceivedConfirmation($email_data), [], function ($message) {
                        $message->setTimeZone('Europe/Helsinki');
                    });
           
                return true;
            }
        }
    }

    public function payRightAway(Request $request) {
        $booking_number = "MS".time();

        $booking = new MsBooking();
        $booking->company_id = intval($request->company_id);
        $booking->unit_id = intval($request->unit_id);
        $booking->product_id = intval($request->product_id);
        $booking->booking_number = $booking_number;
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->price = $request->price;
        $booking->first_name = $request->first_name;
        $booking->last_name = $request->last_name;
        $booking->email = $request->email;
        $booking->phone = $request->phone;
        $booking->start_address = $request->start_address;
        $booking->end_address = $request->end_address;
        $booking->start_door_number = $request->start_door_number;
        $booking->end_door_number = $request->end_door_number;
        $booking->start_door_code = $request->start_door_code;
        $booking->end_door_code = $request->end_door_code;
        $booking->start_comment = $request->start_comment;
        $booking->end_comment = $request->end_comment;
        $booking->start_floor = $request->start_floor;
        $booking->end_floor = $request->end_floor;
        $booking->start_elevator = $request->start_elevator;
        $booking->end_elevator = $request->end_elevator;
        $booking->start_outdoor_distance = $request->start_outdoor_distance;
        $booking->end_outdoor_distance = $request->end_outdoor_distance;
        $booking->start_storage = $request->start_storage;
        $booking->end_storage = $request->end_storage;
        $booking->start_storage_m2 = $request->start_storage_m2;
        $booking->end_storage_m2 = $request->end_storage_m2;
        $booking->start_storage_floor = $request->start_storage_floor;
        $booking->end_storage_floor = $request->end_storage_floor;
        $booking->start_square_meters = $request->start_square_meters;
        $booking->end_square_meters = $request->end_square_meters;
        $booking->payment_status = 'unpaid';
        $booking->progress_status = 'to-start'; //to-start, started, done

        $couponValid = null;

        if (!empty($request->coupon_code)) {
            $code = $request->coupon_code;
        
            $coupon = Coupon::where('code', $code)
                ->where('company_id', $booking->company_id)
                ->active()
                ->first();
        
            if ($coupon) {
                if(!$coupon->status){
                    $couponValid = false;
                }else{
                    $booking->price -= ($coupon->is_percentage ? ($coupon->price / 100 * $booking->price) : $coupon->price);
        
                    if (!$coupon->is_unlimited) {
                        $coupon->used += 1;
                    }
                    $coupon->checkAndUpdateStatus();
    
                    $couponValid = true;
                }
            } else {
                $couponValid = false;
            }
        }
        

        $isStored = $booking->save();

        function getFloorName($floor)
        {
            switch ($floor) {
                case 'a':
                    return 'Maa-taso';
                case 'b':
                    return 'Rivitalo 1 kerros';
                case 'c':
                    return 'Omakotitalo 1 kerros';
                case 'd':
                    return 'Rivitalo 2 kerros';
                case 'e':
                    return 'Omakotitalo 2 kerros';
                default:
                    return "Kerros $floor";
            }
        }

        function getElevatorName($elevator)
        {
            switch ($elevator) {
                case '1':
                    return 'Ei hissiä';
                case '2':
                    return 'Pieni hissi (alle 1m2)';
                case '3':
                    return 'Iso hisi (1m2 tai isompi)';
                case '4':
                    return 'Uudiskohde';
                default:
                    return '';
            }
        }

        function getStorageName($storage)
        {
            switch ($storage) {
                case '1':
                    return 'Varastoa ei ole';
                case '2':
                    return 'Kellarikaappi / ulkovarasto';
                case '3':
                    return 'Vintti / Ullakko';
                default:
                    return '';
            }
        }

        if ($isStored){
            $payment = $this->addPayment($booking, $booking_number);

            // return $payment['success_url'];

            if ($payment['success_url']){
                $email_data['booking_number'] = $booking_number;
                $email_data['first_name'] = $booking->first_name;
                $email_data['last_name'] = $booking->last_name;
                $email_data['email'] = $booking->email;
                $email_data['phone'] = $booking->phone;
                $email_data['start_address'] = $booking->start_address;
                $email_data['end_address'] = $booking->end_address;
                $email_data['start_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->format('d.m.Y');
                $email_data['start_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->format('H:i').'h';
                $end_time = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->addHours(2);
                $email_data['end_time'] = $end_time->format('H:i').'h';
                $email_data['price'] = $booking->price;
                $email_data['start_door_number'] = $booking->start_door_number;
                $email_data['end_door_number'] = $booking->end_door_number;
                $email_data['start_door_code'] = $booking->start_door_code;
                $email_data['end_door_code'] = $booking->end_door_code;
                $email_data['start_comment'] = $booking->start_comment;
                $email_data['end_comment'] = $booking->end_comment;
                $email_data['start_floor'] = getFloorName($booking->start_floor);
                $email_data['end_floor'] = getFloorName($booking->end_floor);
                $email_data['start_elevator'] = getElevatorName($booking->start_elevator);
                $email_data['end_elevator'] = getElevatorName($booking->end_elevator);
                $email_data['start_outdoor_distance'] = $booking->start_outdoor_distance;
                $email_data['end_outdoor_distance'] = $booking->end_outdoor_distance;
                $email_data['start_storage'] = getStorageName($booking->start_storage);
                $email_data['end_storage'] = getStorageName($booking->end_storage);
                $email_data['start_storage_m2'] = $booking->start_storage_m2;
                $email_data['end_storage_m2'] = $booking->end_storage_m2;
                $email_data['start_storage_floor'] = getFloorName($booking->start_storage_floor);
                $email_data['end_storage_floor'] = getFloorName($booking->end_storage_floor);
                $email_data['start_square_meters'] = $booking->start_square_meters;
                $email_data['end_square_meters'] = $booking->end_square_meters;

                if($booking->product_id){
                    $product = MsProduct::find($booking->product_id);
                    $email_data['product_name'] = $product->name;
                    
                    $unitProductPrice = MsUnitProductPrice::where([
                        'unit_id' => $booking->unit_id,
                        'product_id' => $booking->product_id
                    ])->first();
    
                    $email_data['unit_description'] = $unitProductPrice->description;
                }

                $companyId = $booking->company_id;
                $company = MsCompany::find($companyId);
                $companyEmail = $company->email;

                // $isSent = Mail::to($companyEmail)->send(new PayRightAwayConfirmation($email_data));

                $isSent = Mail::to($companyEmail)
                    ->send(new PayRightAwayConfirmation($email_data), [], function ($message) {
                        $message->setTimeZone('Europe/Helsinki');
                    });

                if($isSent){
                    return $payment['success_url'];
                }
            }
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function addPayment($request, $order_number) {
        $res = [];

        $company = MsCompany::find($request->company_id);

        $apiKey = $company->api_key;
        $privateKey = $company->private_key;
        $returnUrl = env('PAYMENT_RETURN_URL');

        $payment = new VismaPay($apiKey, $privateKey);

        // $price = intval($request->price . "00");
        $price = intval($request->price * 100);

        $vat = ceil(100 * $price / (100 + 24));
        $prePrice = $vat;

        $payment->addCharge(
            array(
                'order_number' => $order_number,
                'amount' => $price,
                'currency' => 'EUR',
                'email' => $request->email
            )
        );
        $payment->addCustomer(
            array(
                'firstname' => $request->first_name,
                'lastname' => $request->last_name,
                'email' => $request->email,
                'address_street' => $request->start_address,
                'address_city' => '',
                'address_zip' => ''
            )
        );
        $payment->addProduct(
            array(
                'id' => $order_number,
                'title' => 'Moving Service',
                'count' => 1,
                'pretax_price' => $prePrice,
                'tax' => 24,
                'price' => $price,
                'type' => 1
            )
        );
        $payment->addPaymentMethod(
            array(
                'type' => 'e-payment',
                'return_url' => $returnUrl,
                'notify_url' => $returnUrl,
                'lang' => 'fi',
                'token_valid_until' => strtotime('+2 hours'),
                'selected' => array(
                    'fellowfinance',
                    'joustoraha',
                    'oplasku',
                    'banks',
                    'creditcards',
                    'creditinvoices',
                    'nordea',
                    'nordeab2b',
                    'handelsbanken',
                    'osuuspankki',
                    'danskebank',
                    'spankki',
                    'saastopankki',
                    'paikallisosuuspankki',
                    'aktia',
                    'alandsbanken',
                    'omasaastopankki',
                    'wallets',
                    'laskuyritykselle',
                    'mobilepay',
                    'masterpass',
                    'pivo',
                    'siirto'
                )
            )
        );

        try{
            $response = $payment->createCharge();

            if($response->result == 0) {
                $url = $payment::API_URL . '/token/' . $response->token;
                $res['success_url'] = $url;
            } else {
                if(isset($response->errors) && !empty($response->errors)){
                    $res["error"] ='Validation errors: ' . print_r($response->errors, true);
                }else{
                    $res["error"] = 'Jokin meni pieleen. Yritä myöhemmin uudelleen tai ota meihin yhteyttä numeroon';
                }
            }

        }catch(VismaPay\VismaPayException $e){
            error_log($e->getMessage());
        }

        return $res;
    }

    public function sendPaymentEmail($id) {
        $email_data = [];
        $booking = MsBooking::find($id);
        $booking_number = "MS".time();

        function getFloorName($floor)
        {
            switch ($floor) {
                case 'a':
                    return 'Maa-taso';
                case 'b':
                    return 'Rivitalo 1 kerros';
                case 'c':
                    return 'Omakotitalo 1 kerros';
                case 'd':
                    return 'Rivitalo 2 kerros';
                case 'e':
                    return 'Omakotitalo 2 kerros';
                default:
                    return "Kerros $floor";
            }
        }

        function getElevatorName($elevator)
        {
            switch ($elevator) {
                case '1':
                    return 'Ei hissiä';
                case '2':
                    return 'Pieni hissi (alle 1m2)';
                case '3':
                    return 'Iso hisi (1m2 tai isompi)';
                case '4':
                    return 'Uudiskohde';
                default:
                    return '';
            }
        }

        function getStorageName($storage)
        {
            switch ($storage) {
                case '1':
                    return 'Varastoa ei ole';
                case '2':
                    return 'Kellarikaappi / ulkovarasto';
                case '3':
                    return 'Vintti / Ullakko';
                default:
                    return '';
            }
        }

        if ($booking) {
            $payment = $this->addPayment($booking, $booking_number);

            if ($payment['success_url']){
                $email_data['booking_number'] = $booking_number;
                $email_data['first_name'] = $booking->first_name;
                $email_data['last_name'] = $booking->last_name;
                $email_data['email'] = $booking->email;
                $email_data['phone'] = $booking->phone;
                $email_data['start_address'] = $booking->start_address;
                $email_data['end_address'] = $booking->end_address;
                $email_data['start_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->format('d.m.Y');
                $email_data['start_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->format('H:i').'h';
                $end_time = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->addHours(2);
                $email_data['end_time'] = $end_time->format('H:i').'h';
                $email_data['price'] = $booking->price;
                $email_data['start_door_number'] = $booking->start_door_number;
                $email_data['end_door_number'] = $booking->end_door_number;
                $email_data['start_door_code'] = $booking->start_door_code;
                $email_data['end_door_code'] = $booking->end_door_code;
                $email_data['start_comment'] = $booking->start_comment;
                $email_data['end_comment'] = $booking->end_comment;
                $email_data['start_floor'] = getFloorName($booking->start_floor);
                $email_data['end_floor'] = getFloorName($booking->end_floor);
                $email_data['start_elevator'] = getElevatorName($booking->start_elevator);
                $email_data['end_elevator'] = getElevatorName($booking->end_elevator);
                $email_data['start_outdoor_distance'] = $booking->start_outdoor_distance;
                $email_data['end_outdoor_distance'] = $booking->end_outdoor_distance;
                $email_data['start_storage'] = getStorageName($booking->start_storage);
                $email_data['end_storage'] = getStorageName($booking->end_storage);
                $email_data['start_storage_m2'] = $booking->start_storage_m2;
                $email_data['end_storage_m2'] = $booking->end_storage_m2;
                $email_data['start_storage_floor'] = getFloorName($booking->start_storage_floor);
                $email_data['end_storage_floor'] = getFloorName($booking->end_storage_floor);
                $email_data['start_square_meters'] = $booking->start_square_meters;
                $email_data['end_square_meters'] = $booking->end_square_meters;
                $email_data['payment_url'] = $payment['success_url'];

                $product = MsProduct::find($booking->product_id);
                $email_data['product_name'] = $product->name;

                // $isSent = Mail::to($booking->email)->send(new MovingServiceBookingConfirmation($email_data));

                $isSent = Mail::to($booking->email)
                    ->send(new MovingServiceBookingConfirmation($email_data), [], function ($message) {
                        $message->setTimeZone('Europe/Helsinki');
                    });

                if ($isSent){
                    $booking->booking_number = $booking_number;
                    $booking->save();

                    $companyId = $booking->company_id;
                    $company = MsCompany::find($companyId);
                    $companyEmail = $company->email;

                    // Mail::to($companyEmail)->send(new MovingServiceBookingConfirmation($email_data));
                    Mail::to($companyEmail)
                    ->send(new MovingServiceBookingConfirmation($email_data), [], function ($message) {
                        $message->setTimeZone('Europe/Helsinki');
                    });

                    return true;
                }
            }
        }

    }

   /**
     * @param Request $request
     * @return mixed
     */
    public function updatePayment(Request $request){
        $bookingNumber = substr($request->booking_number,0,3);

        if($bookingNumber == 'MSE'){
            $extraServiceObj = MsExtraService::where('booking_number', $request->booking_number)->get();
            $extraServiceId = $extraServiceObj[0]->id;
            $bookingId = $extraServiceObj[0]->booking_id;
            $price = $extraServiceObj[0]->price;
            $description = $extraServiceObj[0]->description;

            $booking = MsBooking::find($bookingId);

            $companyId = $booking->company_id;
            $company = MsCompany::find($companyId);
            $companyEmail = $company->email;

            $booking->save();

            $extraService = MsExtraService::find($extraServiceId);
            $extraService->payment_status = 'paid';

            $isPaid = $extraService->save();

            if ($isPaid){
                $email_data['price'] = $price;
                $email_data['description'] = $description;

                // Mail::to($booking->email)->send(new ExtraServiceBookingPaid($email_data));
                Mail::to($booking->email)
                ->send(new ExtraServiceBookingPaid($email_data), [], function ($message) {
                    $message->setTimeZone('Europe/Helsinki');
                });
                
                // Mail::to($companyEmail)->send(new ExtraServiceBookingPaid($email_data));
                Mail::to($companyEmail)
                ->send(new ExtraServiceBookingPaid($email_data), [], function ($message) {
                    $message->setTimeZone('Europe/Helsinki');
                });
            }
        }else{
            $bookingTmp = MsBooking::where('booking_number', $request->booking_number)->get();

            $bookingId = $bookingTmp[0]->id;
            $booking = MsBooking::find($bookingId);

            $companyId = $booking->company_id;
            $company = MsCompany::find($companyId);
            $companyEmail = $company->email;

            function getFloorName($floor)
            {
                switch ($floor) {
                    case 'a':
                        return 'Maa-taso';
                    case 'b':
                        return 'Rivitalo 1 kerros';
                    case 'c':
                        return 'Omakotitalo 1 kerros';
                    case 'd':
                        return 'Rivitalo 2 kerros';
                    case 'e':
                        return 'Omakotitalo 2 kerros';
                    default:
                        return "Kerros $floor";
                }
            }

            function getElevatorName($elevator)
            {
                switch ($elevator) {
                    case '1':
                        return 'Ei hissiä';
                    case '2':
                        return 'Pieni hissi (alle 1m2)';
                    case '3':
                        return 'Iso hisi (1m2 tai isompi)';
                    case '4':
                        return 'Uudiskohde';
                    default:
                        return '';
                }
            }

            function getStorageName($storage)
            {
                switch ($storage) {
                    case '1':
                        return 'Varastoa ei ole';
                    case '2':
                        return 'Kellarikaappi / ulkovarasto';
                    case '3':
                        return 'Vintti / Ullakko';
                    default:
                        return '';
                }
            }

            $booking->payment_status = 'paid';
            $isPaid = $booking->save();

            if ($isPaid){
                $email_data['booking_number'] = $booking->booking_number;
                $email_data['first_name'] = $booking->first_name;
                $email_data['last_name'] = $booking->last_name;
                $email_data['email'] = $booking->email;
                $email_data['phone'] = $booking->phone;
                $email_data['start_address'] = $booking->start_address;
                $email_data['end_address'] = $booking->end_address;
                $email_data['start_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->format('d.m.Y');
                $email_data['start_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->format('H:i').'h';
                $end_time = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->addHours(2);
                $email_data['end_time'] = $end_time->format('H:i').'h';
                $email_data['price'] = $booking->price;
                $email_data['start_door_number'] = $booking->start_door_number;
                $email_data['end_door_number'] = $booking->end_door_number;
                $email_data['start_door_code'] = $booking->start_door_code;
                $email_data['end_door_code'] = $booking->end_door_code;
                $email_data['start_comment'] = $booking->start_comment;
                $email_data['end_comment'] = $booking->end_comment;
                $email_data['start_floor'] = getFloorName($booking->start_floor);
                $email_data['end_floor'] = getFloorName($booking->end_floor);
                $email_data['start_elevator'] = getElevatorName($booking->start_elevator);
                $email_data['end_elevator'] = getElevatorName($booking->end_elevator);
                $email_data['start_outdoor_distance'] = $booking->start_outdoor_distance;
                $email_data['end_outdoor_distance'] = $booking->end_outdoor_distance;
                $email_data['start_storage'] = getStorageName($booking->start_storage);
                $email_data['end_storage'] = getStorageName($booking->end_storage);
                $email_data['start_storage_m2'] = $booking->start_storage_m2;
                $email_data['end_storage_m2'] = $booking->end_storage_m2;
                $email_data['start_storage_floor'] = getFloorName($booking->start_storage_floor);
                $email_data['end_storage_floor'] = getFloorName($booking->end_storage_floor);
                $email_data['start_square_meters'] = $booking->start_square_meters;
                $email_data['end_square_meters'] = $booking->end_square_meters;

                $product = MsProduct::find($booking->product_id);
                $email_data['product_name'] = $product->name;

                // Mail::to($booking->email)->send(new MsBookingPaid($email_data));
                Mail::to($booking->email)
                ->send(new MsBookingPaid($email_data), [], function ($message) {
                    $message->setTimeZone('Europe/Helsinki');
                });
                // Mail::to($companyEmail)->send(new MsBookingPaid($email_data));
                Mail::to($companyEmail)
                ->send(new MsBookingPaid($email_data), [], function ($message) {
                    $message->setTimeZone('Europe/Helsinki');
                });
            }
        }
    }

    public function createBookingAndSendPaymentLink(Request $request) {
        $booking_number = "MS".time();

        $booking = new MsBooking();
        $booking->company_id = intval($request->company_id);
        $booking->unit_id = intval($request->unit_id);
        $booking->product_id = intval($request->product_id);
        $booking->booking_number = $booking_number;
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->price = $request->price;
        $booking->first_name = $request->first_name;
        $booking->last_name = $request->last_name;
        $booking->email = $request->email;
        $booking->phone = $request->phone;
        $booking->start_address = $request->start_address;
        $booking->end_address = $request->end_address;
        $booking->start_door_number = $request->start_door_number;
        $booking->end_door_number = $request->end_door_number;
        $booking->start_door_code = $request->start_door_code;
        $booking->end_door_code = $request->end_door_code;
        $booking->start_comment = $request->start_comment;
        $booking->end_comment = $request->end_comment;
        $booking->start_floor = $request->start_floor;
        $booking->end_floor = $request->end_floor;
        $booking->start_elevator = $request->start_elevator;
        $booking->end_elevator = $request->end_elevator;
        $booking->start_outdoor_distance = $request->start_outdoor_distance;
        $booking->end_outdoor_distance = $request->end_outdoor_distance;
        $booking->start_storage = $request->start_storage;
        $booking->end_storage = $request->end_storage;
        $booking->start_storage_m2 = $request->start_storage_m2;
        $booking->end_storage_m2 = $request->end_storage_m2;
        $booking->start_storage_floor = $request->start_storage_floor;
        $booking->end_storage_floor = $request->end_storage_floor;
        $booking->start_square_meters = $request->start_square_meters;
        $booking->end_square_meters = $request->end_square_meters;
        $booking->payment_status = 'unpaid';
        $booking->progress_status = 'to-start'; //to-start, started, done

        $isStored = $booking->save();

        function getFloorName($floor)
        {
            switch ($floor) {
                case 'a':
                    return 'Maa-taso';
                case 'b':
                    return 'Rivitalo 1 kerros';
                case 'c':
                    return 'Omakotitalo 1 kerros';
                case 'd':
                    return 'Rivitalo 2 kerros';
                case 'e':
                    return 'Omakotitalo 2 kerros';
                default:
                    return "Kerros $floor";
            }
        }

        function getElevatorName($elevator)
        {
            switch ($elevator) {
                case '1':
                    return 'Ei hissiä';
                case '2':
                    return 'Pieni hissi (alle 1m2)';
                case '3':
                    return 'Iso hisi (1m2 tai isompi)';
                case '4':
                    return 'Uudiskohde';
                default:
                    return '';
            }
        }

        function getStorageName($storage)
        {
            switch ($storage) {
                case '1':
                    return 'Varastoa ei ole';
                case '2':
                    return 'Kellarikaappi / ulkovarasto';
                case '3':
                    return 'Vintti / Ullakko';
                default:
                    return '';
            }
        }

        if ($isStored){
            $payment = $this->addPayment($booking, $booking_number);

            if ($payment['success_url']){
                $email_data = [];
                $email_data['booking_number'] = $booking_number;
                $email_data['first_name'] = $booking->first_name;
                $email_data['last_name'] = $booking->last_name;
                $email_data['email'] = $booking->email;
                $email_data['phone'] = $booking->phone;
                $email_data['start_address'] = $booking->start_address;
                $email_data['end_address'] = $booking->end_address;
                $email_data['start_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->format('d.m.Y');
                $email_data['start_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->format('H:i').'h';
                $end_time = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->addHours(2);
                $email_data['end_time'] = $end_time->format('H:i').'h';
                $email_data['price'] = $booking->price;
                $email_data['start_door_number'] = $booking->start_door_number;
                $email_data['end_door_number'] = $booking->end_door_number;
                $email_data['start_door_code'] = $booking->start_door_code;
                $email_data['end_door_code'] = $booking->end_door_code;
                $email_data['start_comment'] = $booking->start_comment;
                $email_data['end_comment'] = $booking->end_comment;
                if ($booking->start_floor) {
                    $email_data['start_floor'] = getFloorName($booking->start_floor);
                }
                if ($booking->end_floor) {
                    $email_data['end_floor'] = getFloorName($booking->end_floor);
                }
                if ($booking->start_elevator) {
                    $email_data['start_elevator'] = getElevatorName($booking->start_elevator);
                }
                if ($booking->end_elevator) {
                    $email_data['end_elevator'] = getElevatorName($booking->end_elevator);
                }
                $email_data['start_outdoor_distance'] = $booking->start_outdoor_distance;
                $email_data['end_outdoor_distance'] = $booking->end_outdoor_distance;
                if ($booking->start_storage) {
                    $email_data['start_storage'] = getStorageName($booking->start_storage);
                }
                if ($booking->end_storage) {
                    $email_data['end_storage'] = getStorageName($booking->end_storage);
                }
                $email_data['start_storage_m2'] = $booking->start_storage_m2;
                $email_data['end_storage_m2'] = $booking->end_storage_m2;
                if ($booking->start_storage_floor) {
                    $email_data['start_storage_floor'] = getFloorName($booking->start_storage_floor);
                }
                if ($booking->end_storage_floor) {
                    $email_data['end_storage_floor'] = getFloorName($booking->end_storage_floor);
                }
                $email_data['start_square_meters'] = $booking->start_square_meters;
                $email_data['end_square_meters'] = $booking->end_square_meters;
                $email_data['payment_url'] = $payment['success_url'];

                if($booking->product_id){
                    $product = MsProduct::find($booking->product_id);
                    $email_data['product_name'] = $product->name;
                }

                $isSent = Mail::to($booking->email)
                    ->send(new MovingServiceBookingConfirmation($email_data), [], function ($message) {
                        $message->setTimeZone('Europe/Helsinki');
                    });

                if ($isSent){
                    $booking->booking_number = $booking_number;
                    $booking->save();

                    $companyId = $booking->company_id;
                    $company = MsCompany::find($companyId);
                    $companyEmail = $company->email;

                    // Mail::to($companyEmail)->send(new MovingServiceBookingConfirmation($email_data));
                    Mail::to($companyEmail)
                    ->send(new MovingServiceBookingConfirmation($email_data), [], function ($message) {
                        $message->setTimeZone('Europe/Helsinki');
                    });

                    return true;
                }
            }
        }
    }

    public function addABlock(Request $request){
        $booking_number = "MS".time();

        $booking = new MsBooking();
        $booking->company_id = intval($request->company_id);
        $booking->unit_id = intval($request->unit_id);
        $booking->booking_number = $booking_number;
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->start_comment = $request->start_comment;

        return $isStored = $booking->save();
    }

    public function sendCancelEmail(Request $request){
        $bookingNumber = substr($request->booking_number,0,3);

        if($bookingNumber == 'MSE'){
            $extraServiceObj = MsExtraService::where('booking_number', $request->booking_number)->get();
            $bookingId = $extraServiceObj[0]->booking_id;

            $booking = MsBooking::find($bookingId);

            Mail::to($booking->email)
            ->send(new PaymentCanceled(), [], function ($message) {
                $message->setTimeZone('Europe/Helsinki');
            });
        
        }else{
            $bookingTmp = MsBooking::where('booking_number', $request->booking_number)->get();

            $bookingId = $bookingTmp[0]->id;
            $booking = MsBooking::find($bookingId);

            // Mail::to($booking->email)->send(new MsBookingPaid($email_data));
            Mail::to($booking->email)
            ->send(new PaymentCanceled(), [], function ($message) {
                $message->setTimeZone('Europe/Helsinki');
            });   
        }
    }
}
