<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendMail () {
        Mail::to('zahir-ibishi@hotmail.com')->send(new BookingConfirmation());

        return 'Success';
    }
}
