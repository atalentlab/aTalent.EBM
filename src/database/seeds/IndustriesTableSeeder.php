<?php

use App\Models\Industry;

class IndustriesTableSeeder extends BaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->clearData();

        if ($this->config['industries'] > 0) {
            factory(Industry::class, $this->config['industries'])->create();
        }
    }

    private function clearData()
    {
        $industries = Industry::all();

        foreach ($industries as $industry) {
            $industry->delete();
        }
    }
}
