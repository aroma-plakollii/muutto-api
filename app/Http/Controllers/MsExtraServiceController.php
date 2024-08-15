<?php

namespace App\Http\Controllers;
use App\Mail\ExtraService;
use App\Models\MsExtraService;
use App\Models\MsBooking;
use App\Models\MsCompany;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use VismaPay\VismaPay;

class MsExtraServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MsExtraService::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id) {
        $email_data = [];
        $booking_number = "MSE".time();

        $extraService = new MsExtraService();

        $extraService->booking_id = $id;
        $extraService->price = $request->price;
        $extraService->description = $request->description;
        $extraService->booking_number = $booking_number;
        $isStored = $extraService->save();

        if($isStored){
            $booking = MsBooking::find($id);

            $request['booking_number'] = $booking_number;
            $request['company_id'] = $booking->company_id;
            $request['first_name'] = $booking->first_name;
            $request['last_name'] = $booking->last_name;
            $request['address'] = $booking->start_address;
            $request['email'] = $booking->email;

            $payment = $this->addPayment($request);

            $email_data['booking_number'] = $booking_number;
            $email_data['first_name'] = $booking->first_name;
            $email_data['last_name'] = $booking->last_name;
            $email_data['extraPrice'] = $extraService->price;
            $email_data['description'] =  $extraService->description;
            $email_data['payment_url'] = $payment['success_url'];

            // $isSent = Mail::to($booking->email)->send(new ExtraService($email_data));

            $isSent = Mail::to($booking->email)
                ->send(new ExtraService($email_data), [], function ($message) {
                    $message->setTimeZone('Europe/Helsinki');
                });

            if ($isSent){
                $extraService->booking_number = $booking_number;
                $extraService->save();

                $companyId = $booking->company_id;
                $company = MsCompany::find($companyId);
                $companyEmail = $company->email;

                // Mail::to($companyEmail)->send(new ExtraService($email_data));
                Mail::to($companyEmail)
                ->send(new ExtraService($email_data), [], function ($message) {
                    $message->setTimeZone('Europe/Helsinki');
                });

                return true;
            }
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function addPayment(Request $request) {
        $res = [];

        $company = MsCompany::find($request->company_id);

        $apiKey = $company->api_key;
        $privateKey = $company->private_key;
        $returnUrl = env('PAYMENT_RETURN_URL');

        $payment = new VismaPay($apiKey, $privateKey);

        $order_number = $request->booking_number;
        $vat = ceil(24 * $request->price / (100 + 24));
        $prePrice = ceil(intval($request->price) - $vat);
        $price = str_replace("€","",$request->price)."00";

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
                'title' => 'Moving Service - Extra Service',
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

    public function getExtraServiceByBooking($id) {

        return MsExtraService::where('booking_id', $id)->get();
    }

}