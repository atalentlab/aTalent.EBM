<?php

use App\Models\ApiUser;
use App\Models\Channel;

class ApiUsersTableSeeder extends BaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->clearData();

        $channels = Channel::all();

        foreach ($channels as $channel) {
            factory(ApiUser::class)->create([
                'name'  => $channel->name . ' API user',
            ]);
        }

        factory(ApiUser::class)->create([
            'name'  => 'ApiDocs API user',
        ]);
    }

    private function clearData()
    {
        $users = ApiUser::all();

        foreach ($users as $user) {
            $user->delete();
        }
    }
}
