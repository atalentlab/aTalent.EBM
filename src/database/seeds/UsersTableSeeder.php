<?php

use App\Models\User;
use App\Models\Organization;

class UsersTableSeeder extends BaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->clearData();

        $organizations = Organization::take(20)->get();

        factory(User::class)->create([
            'name'      => 'Jonas',
            'email'     => 'jonas.van.assche@atalent.com',
            'activated' => 1,
            'password'  => bcrypt('abcd1234'),
        ])->assignRole('Super Admin');

        factory(User::class)->create([
            'name'      => 'Prabhu',
            'email'     => 'prabhu.srinivasan@atalent.com',
            'activated' => 1,
            'password'  => bcrypt('abcd1234'),
        ])->assignRole('Super Admin');

        factory(User::class)->create([
            'name'      => 'Partner',
            'email'     => 'partner@atalent.com',
            'activated' => 1,
            'password'  => bcrypt('abcd1234'),
        ])->assignRole('Partner')->organization()->associate($organizations->random())->save();

        factory(User::class)->create([
            'name'      => 'Premium User',
            'email'     => 'premium@atalent.com',
            'activated' => 1,
            'password'  => bcrypt('abcd1234'),
        ])->assignRole('Premium User')->organization()->associate($organizations->random())->save();

        factory(User::class)->create([
            'name'      => 'Basic User',
            'email'     => 'basic@atalent.com',
            'activated' => 1,
            'password'  => bcrypt('abcd1234'),
        ])->assignRole('Basic User')->organization()->associate($organizations->random())->save();

        if ($this->config['users'] > 0) {
            factory(User::class, $this->config['users'])->create();
        }
    }

    private function clearData()
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->delete();
        }
    }
}
