<?php

namespace App\Console\Commands;

use App\Repositories\PeriodRepository;
use App\Models\Period;
use Carbon\Carbon;

class CreateNextPeriod extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'srm:create-next-period';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create next week\'s period';

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
        if (!(new PeriodRepository())->getNextPeriod()) {
            $startDate = (new Carbon('next week'))->startOfWeek();

            $period = Period::updateOrCreate([
                    'name'          => 'Week ' . $startDate->weekOfYear,
                    'start_date'    => $startDate,
                    'end_date'      => $startDate->copy()->endOfWeek(),
                ], [
                    'published'     => 0,
                ]
            );

            $this->output('Period ' . $period->name . ' created.');
        }
    }
}
