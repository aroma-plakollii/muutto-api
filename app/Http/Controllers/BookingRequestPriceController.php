<?php

namespace App\Http\Controllers;
use App\Models\BookingRequestPrice;
use App\Models\BookingRequest;
use App\Mail\BookingRequestConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Mailer;

class BookingRequestPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BookingRequestPrice::all();
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
            'booking_request_id' => 'required',
            'company_id' => 'required',
            'price' => 'required'
        ]);

        $email_data = [];
        $booking_request_price = new BookingRequestPrice();
        $booking_request_price->booking_request_id = intval($request->booking_request_id);
        $booking_request_price->company_id = intval($request->company_id);
        $booking_request_price->price = $request->price;

        $isStored = $booking_request_price->save();

        if($isStored){

            $booking_request = BookingRequest::find($booking_request_price->booking_request_id);
            $email_data['code'] = $booking_request->code;

            Mail::to($booking_request->email)
                    ->send(new BookingRequestConfirmation($email_data), [], function ($message) {
                        $message->setTimeZone('Europe/Helsinki');
                    });
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
        return BookingRequestPrice::find($id);
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
        $booking_request_price = BookingRequestPrice::find($id);

        $booking_request_id = intval($request->booking_request_id);
        $company_id = intval($request->company_id);

        $booking_request_price->update([
            'booking_request_id'=> $booking_request_id,
            'company_id'=> $company_id,
            'price'=> $request->price,
        ]);

        return $booking_request_price;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return BookingRequestPrice::destroy($id);
    }

    public function getBookingRequestPriceByCompany(Request $request)
    {
        $bookingRequestPrice = BookingRequestPrice::where('company_id', $request->company_id)
            ->where('booking_request_id', $request->booking_request_id)
            ->first();

        return $bookingRequestPrice;
    }

    public function getBookingRequestPriceByBookingRequest($code)
    {
        $bookingRequest = BookingRequest::where('code', $code)->first();

        $bookingRequestPrices = BookingRequestPrice::where('booking_request_id', $bookingRequest->id)->get();

        return $bookingRequestPrices;
    }
}