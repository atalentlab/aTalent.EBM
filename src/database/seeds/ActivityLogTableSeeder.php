<?php

use App\Models\Activity;

class ActivityLogTableSeeder extends BaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->clearData();
    }

    protected function clearData()
    {
        $activities = Activity::all();

        foreach ($activities as $activity) {
            $activity->delete();
        }
    }
}
