<?php

namespace App\Console\Commands;

use App\Http\Controllers\MbBookingController;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class CallSendEmailToBookingsByDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CallSendEmailToBookingsByDate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends emails to bookings by date';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $controller = new MbBookingController();
        $request = new Request();
        $controller->sendEmailToBookingsByDate($request);
    }
}
