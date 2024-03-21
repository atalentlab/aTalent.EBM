<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Faker\Factory as Faker;

class LayoutTestController extends Controller
{
    protected $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker->create();
    }

    /**
     * Dev route that lists all CSS/JS components
     */
    public function layoutComponents()
    {
        return view('admin.layout-components')->with([
            'dataTablesData'    => $this->generateDataTablesData(),
            'namesList'         => $this->generateRandomList(),
        ]);
    }

    private function generateDataTablesData($limit = 50)
    {
        $dataTablesData = [];

        for ($i = 1; $i <= $limit; $i++) {
            $dataTablesData[] = [
                'order'     => $i,
                'name'      => $this->faker->name,
                'published' => rand(1, 3) ==! 1,
            ];
        }

        return $dataTablesData;
    }

    private function generateRandomList(int $limit = 30)
    {
        $names = [];

        for ($i = 1; $i <= $limit; $i++) {
            $names[] = $this->faker->name;
        }

        return $names;
    }
}
