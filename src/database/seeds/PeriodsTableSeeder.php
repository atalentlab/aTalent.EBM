<?php

use App\Models\Period;
use Carbon\Carbon;

class PeriodsTableSeeder extends BaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->clearData();

        $startDate = Carbon::instance($this->faker->dateTimeBetween('-3 months', '-2 weeks'))->startOfWeek();

        while ($startDate <= (new Carbon('next week'))) {
            factory(Period::class)->create([
                'name'          => 'Week ' . $startDate->weekOfYear,
                'start_date'    => $startDate,
                'end_date'      => $startDate->copy()->endOfWeek(),
            ]);

            $startDate->addWeek();
        }
    }

    private function clearData()
    {
        $periods = Period::all();

        foreach ($periods as $period) {
            $period->delete();
        }
    }
}
