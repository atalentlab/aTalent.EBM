<?php

namespace App\Console;

use App\Console\Commands\SendCompetitorReport;
use App\Console\Commands\SendMyOrganizationReport;
use App\Console\Commands\UpdateExpiredMemberships;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CreateNextPeriod;
use App\Console\Commands\CalculateIndices;
use App\Console\Commands\SendCrawlerReport;

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
        $schedule->command('activitylog:clean')->daily();
        $schedule->command(CalculateIndices::class)->weekly()->sundays()->at('22:30');
        $schedule->command(CreateNextPeriod::class)->weekly()->sundays()->at('23:30');
        $schedule->command(UpdateExpiredMemberships::class)->dailyAt('00:01');

        if (app()->environment('production')) {
            $schedule->command(SendCrawlerReport::class)->weekly()->sundays()->at('23:40');
            $schedule->command(SendMyOrganizationReport::class)->monthlyOn(1, '7:00');
            $schedule->command(SendCompetitorReport::class)->weekly()->mondays()->at('14:00');
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
