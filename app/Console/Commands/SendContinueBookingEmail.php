<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
// use App\Http\Controllers\MbBookingController;
use App\Mail\ContinueBooking;
use App\Models\MbBooking;
use App\Models\MbCompany;
use Carbon\Carbon;

class SendContinueBookingEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-continue-booking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a continue booking email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = Carbon::now();
        $formattedDate = $today->format('Y/m/d');
        $bookings = MbBooking::where('payment_status', 'paid')->whereDate('end_date', Carbon::today()->addDay()->toDateString())
        ->get();

        foreach ($bookings as $booking) {

            $email_data = [];
            $companyId = $booking->company_id;
            $company = MbCompany::find($companyId);
            $companyEmail = $company->email;

            $email_data['id'] = $booking->id;
            $email_data['first_name'] = $booking->first_name;
            $email_data['last_name'] = $booking->last_name;
            $email_data['end_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $booking->end_date)->format('d.m.Y');

            Mail::to($booking->email)->send(new ContinueBooking($email_data));
            Mail::to($companyEmail)->send(new ContinueBooking($email_data));
        }
    }
}