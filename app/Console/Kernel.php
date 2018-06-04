<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use App\Mail\BudgetMonthSummary;
use App\Repositories\BudgetRepository;
use App\User;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $users = User::all();
            $budgetRepository = new BudgetRepository();
            $lastMonth = date('Y-m', strtotime("-1 months"));
            foreach($users as $user) {  
                $budgetMonthSummary = $budgetRepository->getMonthBudgetSummary($user, $lastMonth);
                if($budgetMonthSummary){
                    Mail::send(new BudgetMonthSummary($budgetMonthSummary, $user));
                    Log::info('Mail sent to: '.$user->email.'.');
                }
            }
         })->monthlyOn(1, '00:00');

        $schedule->exec("truncate -s 0 ".env('LOG_PATH'))->weekly()->sundays('00:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
