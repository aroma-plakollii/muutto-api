<?php

namespace App\Http\Controllers;

use App\Mail\AdditionalDaysBookingConfirmation;
use App\Mail\BookingConfirmation;
use App\Models\MbAdditionalDay;
use App\Models\MbBooking;
use App\Models\MbCompany;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use VismaPay\VismaPay;

class MbAdditionalDayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MbAdditionalDay::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $email_data = [];
        $booking_number = "MBA".time();

        $request->validate([
            'booking_id'=> 'required',
            'date'=> 'required',
            'quantity'=> 'required',
            'price'=> 'required',
        ]);

        $additionalDay = new MbAdditionalDay();

        $additionalDay->booking_id = $request->booking_id;
        $additionalDay->date = $request->date;
        $additionalDay->price = $request->price;
        $additionalDay->quantity = $request->quantity;
        $additionalDay->booking_number = $booking_number;
        $isStored = $additionalDay->save();

        if ($isStored){
            $booking = MbBooking::find($request->booking_id);
            $company = MbCompany::find($booking->company_id);

            $request['booking_number'] = $booking_number;
            $request['company_id'] = $booking->company_id;
            $request['first_name'] = $booking->first_name;
            $request['last_name'] = $booking->last_name;
            $request['address'] = $booking->start_address;
            $request['email'] = $booking->email;

            $payment = $this->addPayment($request);

            if ($payment['success_url']){
                $email_data['booking_number'] = $booking_number;
                $email_data['first_name'] = $booking->first_name;
                $email_data['last_name'] = $booking->last_name;
                $email_data['email'] = $booking->email;
                $email_data['phone'] = $booking->phone;
                $email_data['price'] = $request->price;
                $email_data['date'] = $request->date;
                $email_data['quantity'] = $request->quantity;
                $email_data['payment_url'] = $payment['success_url'];

                Mail::to($booking->email)->send(new AdditionalDaysBookingConfirmation($email_data));

                return true;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return MbAdditionalDay::destroy($id);
    }

    public function getAllByBookingId($id) {

        // $additionalDays = new M
        return MbAdditionalDay::where('booking_id', $id)->where('payment_status', 'paid')->get();
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

//        $order_number = $request->booking_number;
//        $vat = ceil(24 * $request->price / (100 + 24));
//        $prePrice = ceil(intval($request->price) - $vat);
//        $price = str_replace("€","",$request->price)."00";

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
                'title' => 'Moving Boxes - Additional Days',
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

}
