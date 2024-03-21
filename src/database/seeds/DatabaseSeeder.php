<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
  /*      $this->call(IndustriesTableSeeder::class);
        $this->call(ChannelsTableSeeder::class);
        $this->call(OrganizationsTableSeeder::class);
        $this->call(PeriodsTableSeeder::class);
        $this->call(OrganizationDataTableSeeder::class);
        $this->call(PostsTableSeeder::class);
        $this->call(PostDataTableSeeder::class);
        $this->call(ApiUsersTableSeeder::class);



        $this->call(ActivityLogTableSeeder::class);*/

        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
