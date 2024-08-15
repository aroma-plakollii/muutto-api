<?php

namespace App\Http\Controllers;

use App\Mail\AdditionalDaysBookingConfirmation;
use App\Mail\AdditionalDaysBookingPaid;
use App\Mail\BookingConfirmation;
use App\Mail\BookingPaid;
use App\Mail\ContinueBooking;
use App\Mail\PaymentCanceled;
use App\Models\MbAdditionalDay;
use App\Models\MbBooking;
use App\Models\MbCompany;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use VismaPay\VismaPay;
use Illuminate\Support\Facades\Log;

class MbBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MbBooking::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payment_data = [];
        $email_data = [];
        $booking_number = "MB".time();

        $request->validate([
            'first_name'=> 'required',
            'last_name'=> 'required',
            'email'=> 'required',
            'phone'=> 'required',
            'start_date'=> 'required',
            'end_date'=> 'required',
            'price'=> 'required',
            'rent_price'=> 'required',
        ]);

        $booking = new MbBooking();
        $booking->booking_number = $booking_number;
        $booking->company_id = $request->company_id;
        $booking->first_name = $request->first_name;
        $booking->last_name = $request->last_name;
        $booking->email = $request->email;
        $booking->phone = $request->phone;
        $booking->start_address = $request->start_address;
        $booking->end_address = $request->end_address;
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->price = $request->price;
        $booking->start_price = $request->start_price;
        $booking->end_price = $request->end_price;
        $booking->rent_price = $request->rent_price;
        $booking->type = $request->type;
        $booking->quantity = $request->quantity;
        $booking->start_door_number = $request->start_door_number;
        $booking->end_door_number = $request->end_door_number;
        $booking->start_door_code = $request->start_door_code;
        $booking->end_door_code = $request->end_door_code;
        $booking->start_comment = $request->start_comment;
        $booking->end_comment = $request->end_comment;
        $booking->payment_status = 'unpaid';
        $booking->progress_status = 'to-deliver';

        $couponValid = null;

        // check if coupon code is provided
        if (!empty($request->coupon_code)) {
            $code = $request->coupon_code;

            $coupon = Coupon::where('status', true)
                ->whereRaw("FIND_IN_SET('$code', code)")
                ->first();

            $available_at = explode(',', $coupon->available_at);

            if ($coupon) {
                // check if coupon is used
                if (in_array($request->coupon_code, explode(',', $coupon->used))) {
                    $couponValid = false;
                }
                // check if coupon is valid for this booking
                else if (substr($booking_number, 0, 2) === 'MB' && !in_array('movingboxes', $available_at)) {
                    $couponValid = false;
                }
                else if ($coupon->is_unlimited) {
                    // apply coupon discount
                    $booking->price -= ($coupon->is_percentage ? ($coupon->price / 100 * $booking->price) : $coupon->price);
        
                    $couponValid = true;
                }
                else{
                    // apply coupon discount
                    $booking->price -= ($coupon->is_percentage ? ($coupon->price / 100 * $booking->price) : $coupon->price);

                    // update coupon usage
                    $coupon->used = $coupon->used ? $coupon->used . ',' . $code : $code;
                    $coupon->checkAndUpdateStatus();
                    $couponValid = true;
                }
                
            } else {
                $couponValid = false;
            }
        }

        $isStored = $booking->save();

        if ($isStored){
            $request['booking_number'] = $booking_number;

            $payment = $this->addPayment($request);

            if ($payment['success_url']){
                $email_data['id'] = $booking->id;
                $email_data['booking_number'] = $booking_number;
                $email_data['first_name'] = $request->first_name;
                $email_data['last_name'] = $request->last_name;
                $email_data['email'] = $request->email;
                $email_data['phone'] = $request->phone;
                $email_data['start_address'] = $request->start_address;
                $email_data['end_address'] = $request->end_address;
                $email_data['start_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $request->start_date)->format('d.m.Y');
                $email_data['end_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $request->end_date)->format('d.m.Y');
                $email_data['start_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $request->start_date)->format('H:i').'h';
                $email_data['end_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $request->end_date)->format('H:i').'h';
                $email_data['price'] = $request->price;
                $email_data['start_price'] = $request->start_price;
                $email_data['end_price'] = $request->end_price;
                $email_data['rent_price'] = $request->rent_price;
                $email_data['quantity'] = $request->quantity;
                $email_data['start_door_number'] = $request->start_door_number;
                $email_data['end_door_number'] = $request->end_door_number;
                $email_data['start_door_code'] = $request->start_door_code;
                $email_data['end_door_code'] = $request->end_door_code;
                $email_data['start_comment'] = $request->start_comment;
                $email_data['end_comment'] = $request->end_comment;
                $email_data['payment_url'] = $payment['success_url'];

                $companyId = $booking->company_id;
                $company = MbCompany::find($companyId);
                $companyEmail = $company->email;

                Mail::to($companyEmail)->send(new BookingConfirmation($email_data));
                Mail::to($request->email)->send(new BookingConfirmation($email_data));

                return true;
            }
        }
    }

    public function createBooking(Request $request)
    {
        $booking_number = "MB".time();

        $request->validate([
            'first_name'=> 'required',
            'last_name'=> 'required',
            'email'=> 'required',
            'phone'=> 'required',
            'start_date'=> 'required',
            'end_date'=> 'required',
            'price'=> 'required',
            'rent_price'=> 'required',
        ]);

        $booking = new MbBooking();
        $booking->booking_number = $booking_number;
        $booking->company_id = $request->company_id;
        $booking->first_name = $request->first_name;
        $booking->last_name = $request->last_name;
        $booking->email = $request->email;
        $booking->phone = $request->phone;
        $booking->start_address = $request->start_address;
        $booking->end_address = $request->end_address;
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->price = $request->price;
        $booking->start_price = $request->start_price;
        $booking->end_price = $request->end_price;
        $booking->rent_price = $request->rent_price;
        $booking->type = $request->type;
        $booking->quantity = $request->quantity;
        $booking->start_door_number = $request->start_door_number;
        $booking->end_door_number = $request->end_door_number;
        $booking->start_door_code = $request->start_door_code;
        $booking->end_door_code = $request->end_door_code;
        $booking->start_comment = $request->start_comment;
        $booking->end_comment = $request->end_comment;
        $booking->payment_status = 'unpaid';
        $booking->progress_status = 'to-deliver';

        return $booking->save();
    }

    public function update(Request $request, $id)
    {
        $booking = MbBooking::find($id);

        $booking->update([
            // 'company_id' => $request->company_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'start_address' => $request->start_address,
            'end_address' => $request->end_address,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'price' => $request->price,
            'start_price' => $request->start_price,
            'end_price' => $request->end_price,
            'rent_price' => $request->rent_price,
            'quantity' => $request->quantity,
            'start_door_number' => $request->start_door_number,
            'end_door_number' => $request->end_door_number,
            'start_door_code' => $request->start_door_code,
            'end_door_code' => $request->end_door_code,
            'payment_status' => $request->payment_status,
            'progress_status' => $request->progress_status,
        ]);

        return $booking;
    }

    public function test(){
        $date = '2022-11-15 00:00:00';
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d.m.Y');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function addPayment(Request $request) {
        $res = [];

        $company = MbCompany::find($request->company_id);

        $apiKey = $company->api_key;
        $privateKey = $company->private_key;
        $returnUrl = env('PAYMENT_RETURN_URL');

        $payment = new VismaPay($apiKey, $privateKey);

        $order_number = $request->booking_number;
        $price = intval($request->price . "00");

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
                'title' => 'Moving Boxes',
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

            if($response->result == 0)
            {
                $url = $payment::API_URL . '/token/' . $response->token;
                $res['success_url'] = $url;
            }
            else
            {
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MbBooking::find($id);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getContinueBooking($id){
//        return MbBooking::find($id);
        return MbBooking::where('id', $id)->with('days')->get(['id', 'end_date', 'quantity', 'company_id']);

        //$user = User::where('username', 'bobbyiliev')->get(['name']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $booking = MbBooking::find($id);
    //     $booking->update($request->all());

    //     return $booking;
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return MbBooking::destroy($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $bookingNumber
     * @return \Illuminate\Http\Response
     */
    public function search($bookingNumber)
    {
        return MbBooking::where('booking_number', $bookingNumber)->get();
    }

    /**
     * @param $date
     * @return void
     */
    public function getBookingsByDay(Request $request) {
        $date = Carbon::createFromFormat('Y-m-d', $request->date);

        return MbBooking::with(['days' => function ($query) {
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
    public function getBookingsByMonth(Request $request) {
        $date = Carbon::createFromFormat('Y-m-d', $request->date);
        $month = $date->format('m');
        $year = $date->format('Y');

        $bookings = MbBooking::with(['days' => function ($query) {
            $query->where('payment_status', 'paid');
        }])
            ->where('payment_status', 'paid')
           ->where('company_id', $request->company_id)
            ->where(function($query) use ($month, $year) {
                $query->where(function($query) use ($month, $year) {
                    $query->whereYear('start_date', $year)
                        ->whereMonth('start_date', $month);
                })->orWhere(function($query) use ($month, $year) {
                    $query->whereYear('end_date', $year)
                        ->whereMonth('end_date', $month);
                });
            })
            ->orderByDesc('end_date')
            ->get();

        return $bookings;
    }

    public function getUnpaidBookingsByMonth(Request $request) {
        $date = Carbon::createFromFormat('Y-m-d', $request->date);
        $month = $date->format('m');
        $year = $date->format('Y');

        return $bookings = MbBooking::with(['days' => function ($query) {
            $query->where('payment_status', 'unpaid');
        }])
            ->where('payment_status', 'unpaid')
            ->where('company_id', $request->company_id)
            ->where(function($booking) use ($month, $year) {
                $booking->whereMonth('start_date', $month);
                $booking->orWhereMonth('end_date', $month);
                $booking->whereYear('start_date', $year);
                $booking->orWhereYear('end_date', $year);
            })
            ->get();
    }
    

    /**
     * @param $date
     * @return void
     */
    public function getBookingsByMonthNoTransport(Request $request) {
        $date = Carbon::createFromFormat('Y-m-d', $request->date);
        $month = $date->format('m');

        return MbBooking::where('payment_status', 'paid')
            ->where('start_address', '')
            ->whereMonth('start_date', $month)
            ->orWhere('end_address', '')
            ->orWhereMonth('end_date', $month)
            ->get();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updatePayment(Request $request){
        $bookingNumber = substr($request->booking_number,0,3);

        if($bookingNumber == 'MBA'){
            $additionalDayObj = MbAdditionalDay::where('booking_number', $request->booking_number)->get();
            $additionalDayId = $additionalDayObj[0]->id;
            $bookingId = $additionalDayObj[0]->booking_id;
            $additionalDayDate = $additionalDayObj[0]->date;
            $price = $additionalDayObj[0]->price;

            $booking = MbBooking::find($bookingId);
            $endDateOld = $booking->end_date;

            $companyId = $booking->company_id;
            $company = MbCompany::find($companyId);
            $companyEmail = $company->email;

            $booking->end_date_old = $endDateOld;
            $booking->end_date = $additionalDayDate;
            $booking->save();

            $additionalDay = MbAdditionalDay::find($additionalDayId);
            $additionalDay->payment_status = 'paid';

            $isPaid = $additionalDay->save();

            if ($isPaid){
                $email_data['booking_number'] = $booking->booking_number;
                $email_data['first_name'] = $booking->first_name;
                $email_data['last_name'] = $booking->last_name;
                $formatted_date = date('d.m.Y', strtotime($additionalDayDate));
                $email_data['date'] = $formatted_date;
                $email_data['price'] = $price;

                Mail::to($booking->email)->send(new AdditionalDaysBookingPaid($email_data));
                Mail::to($companyEmail)->send(new AdditionalDaysBookingPaid($email_data));
            }
        }else{
            $bookingTmp = MbBooking::where('booking_number', $request->booking_number)->get();

            $bookingId = $bookingTmp[0]->id;
            $booking = MbBooking::find($bookingId);

            $companyId = $booking->company_id;
            $company = MbCompany::find($companyId);
            $companyEmail = $company->email;

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
                $email_data['end_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $booking->end_date)->format('d.m.Y');
                $email_data['start_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->format('H:i').'h';
                $email_data['end_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $booking->end_date)->format('H:i').'h';
                $email_data['price'] = $booking->price;
                $email_data['start_price'] = $booking->start_price;
                $email_data['end_price'] = $booking->end_price;
                $email_data['rent_price'] = $booking->rent_price;
                $email_data['quantity'] = $booking->quantity;
                $email_data['start_door_number'] = $booking->start_door_number;
                $email_data['end_door_number'] = $booking->end_door_number;
                $email_data['start_door_code'] = $booking->start_door_code;
                $email_data['end_door_code'] = $booking->end_door_code;
                $email_data['start_comment'] = $booking->start_comment;
                $email_data['end_comment'] = $booking->end_comment;

                Mail::to($booking->email)->send(new BookingPaid($email_data));
                Mail::to($companyEmail)->send(new BookingPaid($email_data));
            }
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    public function updateStatus(Request $request) {
        $booking = MbBooking::find($request->id);

        return $request->progress_status == 'to-deliver' ?  $booking->update([ 'progress_status' => 'to-collect']) : $booking->update([ 'progress_status' => 'done']);
    }

    public function sendCancelEmail(Request $request){
        $bookingNumber = substr($request->booking_number,0,3);

        if($bookingNumber == 'MBA'){
            $additionalDayObj = MbAdditionalDay::where('booking_number', $request->booking_number)->get();
            $bookingId = $additionalDayObj[0]->booking_id;

            $booking = MbBooking::find($bookingId);

            Mail::to($booking->email)
            ->send(new PaymentCanceled(), [], function ($message) {
                $message->setTimeZone('Europe/Helsinki');
            });
        
        }else{
            $bookingTmp = MbBooking::where('booking_number', $request->booking_number)->get();

            $bookingId = $bookingTmp[0]->id;
            $booking = MbBooking::find($bookingId);

            // Mail::to($booking->email)->send(new MsBookingPaid($email_data));
            Mail::to($booking->email)
            ->send(new PaymentCanceled(), [], function ($message) {
                $message->setTimeZone('Europe/Helsinki');
            });   
        }
    }

}
