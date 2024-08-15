<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\BookingRequestPrice;
use App\Models\MsBooking;
use App\Models\MsCompany;
use App\Models\MsProduct;
use App\Mail\BookingReceivedConfirmation;
use App\Mail\BookingRequestReceivedConfirmation;
use App\Models\MsUnitProductPrice;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Mailer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BookingRequest::all();
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
            'product_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
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
        ]);

        $booking_number = "MS".time();

        $booking_request = new BookingRequest();
        $booking_request->product_id = intval($request->product_id);
        $booking_request->booking_number = $booking_number;
        $booking_request->start_date = $request->start_date;
        $booking_request->end_date = $request->end_date;
        $booking_request->first_name = $request->first_name;
        $booking_request->last_name = $request->last_name;
        $booking_request->email = $request->email;
        $booking_request->phone = $request->phone;
        $booking_request->start_address = $request->start_address;
        $booking_request->end_address = $request->end_address;
        $booking_request->start_door_number = $request->start_door_number;
        $booking_request->end_door_number = $request->end_door_number;
        $booking_request->start_door_code = $request->start_door_code;
        $booking_request->end_door_code = $request->end_door_code;
        $booking_request->start_floor = $request->start_floor;
        $booking_request->end_floor = $request->end_floor;
        $booking_request->start_elevator = $request->start_elevator;
        $booking_request->end_elevator = $request->end_elevator;
        $booking_request->start_outdoor_distance = $request->start_outdoor_distance;
        $booking_request->end_outdoor_distance = $request->end_outdoor_distance;
        $booking_request->start_storage = $request->start_storage;
        $booking_request->end_storage = $request->end_storage;
        $booking_request->start_storage_m2 = $request->start_storage_m2;
        $booking_request->end_storage_m2 = $request->end_storage_m2;
        $booking_request->start_storage_floor = $request->start_storage_floor;
        $booking_request->end_storage_floor = $request->end_storage_floor;
        $booking_request->start_square_meters = $request->start_square_meters;
        $booking_request->end_square_meters = $request->end_square_meters;

        $code = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5);
 
        $booking_request->code = $code;

        return $booking_request->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return BookingRequest::find($id);
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
        $booking_request = BookingRequest::find($id);

        $product_id = intval($request->product_id);

        $booking_request->update([
            'product_id' => $product_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
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
            'status' => $request->status,
        ]);

        return $booking_request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return BookingRequest::destroy($id);
    }

    public function getBookingRequestsByMonth(Request $request){
        $date = Carbon::createFromFormat('Y-m-d', $request->date);
        $month = $date->format('m');
        $year = $date->format('Y');

        return $booking_request = BookingRequest::where(function ($booking_request) use ($month, $year) {
                $booking_request->whereYear('start_date', $year)
                    ->whereMonth('start_date', $month);
            })
            ->orderByDesc('start_date')
            ->get();
    }

    public function createBookingfromBookingRequest($id){

        $booking_request_price = BookingRequestPrice::where('id', $id)->first();

        $booking_request = BookingRequest::where('id', $booking_request_price->booking_request_id)->first();

        $booking_number = "MS".time();

        $booking = new MsBooking();
        $booking->company_id = intval($booking_request_price->company_id);
        $booking->product_id = intval($booking_request->product_id);
        $booking->booking_number = $booking_number;
        $booking->start_date = $booking_request->start_date;
        $booking->end_date = $booking_request->end_date;
        $booking->price = $booking_request_price->price;
        $booking->first_name = $booking_request->first_name;
        $booking->last_name = $booking_request->last_name;
        $booking->email = $booking_request->email;
        $booking->phone = $booking_request->phone;
        $booking->start_address = $booking_request->start_address;
        $booking->end_address = $booking_request->end_address;
        $booking->start_door_number = $booking_request->start_door_number;
        $booking->end_door_number = $booking_request->end_door_number;
        $booking->start_door_code = $booking_request->start_door_code;
        $booking->end_door_code = $booking_request->end_door_code;
        $booking->start_floor = $booking_request->start_floor;
        $booking->end_floor = $booking_request->end_floor;
        $booking->start_elevator = $booking_request->start_elevator;
        $booking->end_elevator = $booking_request->end_elevator;
        $booking->start_outdoor_distance = $booking_request->start_outdoor_distance;
        $booking->end_outdoor_distance = $booking_request->end_outdoor_distance;
        $booking->start_storage = $booking_request->start_storage;
        $booking->end_storage = $booking_request->end_storage;
        $booking->start_storage_m2 = $booking_request->start_storage_m2;
        $booking->end_storage_m2 = $booking_request->end_storage_m2;
        $booking->start_storage_floor = $booking_request->start_storage_floor;
        $booking->end_storage_floor = $booking_request->end_storage_floor;
        $booking->start_square_meters = $booking_request->start_square_meters;
        $booking->end_square_meters = $booking_request->end_square_meters;
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

            $booking_request->status = 0;
            $booking_request->save();

            $companyId = $booking->company_id;
            $company = MsCompany::find($companyId);
            $companyEmail = $company->email;
            $companyName = $company->name;

            $email_data = [];
            $email_data['booking_number'] = $booking_number;
            $email_data['company_name'] = $companyName;
            $email_data['first_name'] = $booking->first_name;
            $email_data['last_name'] = $booking->last_name;
            $email_data['email'] = $booking->email;
            $email_data['phone'] = $booking->phone;
            $email_data['start_address'] = $booking->start_address;
            $email_data['end_address'] = $booking->end_address;
            $email_data['start_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_date)->format('d.m.Y');
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

            $isSent = Mail::to($booking->email)
                    ->send(new BookingRequestReceivedConfirmation($email_data), [], function ($message) {
                        $message->setTimeZone('Europe/Helsinki');
                    });

            if ($isSent){
                Mail::to($companyEmail)
                    ->send(new BookingRequestReceivedConfirmation($email_data), [], function ($message) {
                        $message->setTimeZone('Europe/Helsinki');
                    });
           
                return true;
            }
        }
    }

    public function getBookingRequestsByCode($code){

        return BookingRequest::where('code', $code)->first();
        
    }
}