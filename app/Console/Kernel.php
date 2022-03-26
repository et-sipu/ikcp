<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('backup:clean')->daily()->at('04:30');
        $schedule->command('backup:run')->daily()->at('05:00');
        $schedule->call('App\Http\Controllers\Backend\VesselController@expired_certificate')->daily()->at('05:30');
        $schedule->call('App\Http\Controllers\Backend\CompanyRegistrationsController@document_expiration')->cron('0 0 1,16 * *');
        $schedule->call('App\Http\Controllers\Backend\VesselInsuranceController@Vessel_insurance_expiration')->cron('0 0 1,16 * *');

//        $schedule->call('App\Http\Controllers\Backend\FlightReservationController@mo_report')->daily()->at('05:30');
//        $schedule->call('App\Http\Controllers\Backend\VesselController@expired_certificate')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
