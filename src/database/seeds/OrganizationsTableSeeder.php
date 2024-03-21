<?php

use App\Models\Organization;
use App\Models\Industry;
use App\Models\Channel;

class OrganizationsTableSeeder extends BaseSeeder
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

        $linkedIn = $channels->where('name', 'LinkedIn')->first()->id;

        $uploadDir = Organization::$uploadDir;

        factory(Organization::class)->create([
            'published'     => 1,
            'is_fetching'   => 1,
            'name'          => 'Adidas',
            'logo'          => $this->seedFile('logos/logo-' . rand(1, 4) . '.svg', $uploadDir),
        ])->channels()->attach($linkedIn, ['channel_username' => 'adidas']);

        factory(Organization::class)->create([
            'published'     => 1,
            'is_fetching'   => 1,
            'name'          => 'Mars',
            'logo'          => $this->seedFile('logos/logo-' . rand(1, 4) . '.svg', $uploadDir),
        ])->channels()->attach($linkedIn, ['channel_username' => 'mars']);

        factory(Organization::class)->create([
            'published'     => 1,
            'is_fetching'   => 1,
            'name'          => 'Bayer',
            'logo'          => $this->seedFile('logos/logo-' . rand(1, 4) . '.svg', $uploadDir),
        ])->channels()->attach($linkedIn, ['channel_username' => 'bayer']);

        factory(Organization::class)->create([
            'published'     => 1,
            'is_fetching'   => 1,
            'name'          => 'Microsoft',
            'logo'          => $this->seedFile('logos/logo-' . rand(1, 4) . '.svg', $uploadDir),
        ])->channels()->attach($linkedIn, ['channel_username' => 'microsoft']);

        factory(Organization::class)->create([
            'published'     => 1,
            'is_fetching'   => 1,
            'name'          => 'General Electric',
            'logo'          => $this->seedFile('logos/logo-' . rand(1, 4) . '.svg', $uploadDir),
        ])->channels()->attach($linkedIn, ['channel_username' => 'ge']);

        factory(Organization::class)->create([
            'published'     => 1,
            'is_fetching'   => 1,
            'name'          => 'Henkel',
            'logo'          => $this->seedFile('logos/logo-' . rand(1, 4) . '.svg', $uploadDir),
        ])->channels()->attach($linkedIn, ['channel_username' => 'henkel']);

        factory(Organization::class)->create([
            'published'     => 1,
            'is_fetching'   => 1,
            'name'          => 'KOHLER',
            'logo'          => $this->seedFile('logos/logo-' . rand(1, 4) . '.svg', $uploadDir),
        ])->channels()->attach($linkedIn, ['channel_username' => 'kohler']);

        factory(Organization::class)->create([
            'published'     => 1,
            'is_fetching'   => 1,
            'name'          => 'Nestle',
            'logo'          => $this->seedFile('logos/logo-' . rand(1, 4) . '.svg', $uploadDir),
        ])->channels()->attach($linkedIn, ['channel_username' => 'nestle-s-a-']);

        factory(Organization::class)->create([
            'published'     => 1,
            'is_fetching'   => 1,
            'name'          => 'Medtronic',
            'logo'          => $this->seedFile('logos/logo-' . rand(1, 4) . '.svg', $uploadDir),
        ])->channels()->attach($linkedIn, ['channel_username' => 'medtronic']);

        factory(Organization::class)->create([
            'published'     => 1,
            'is_fetching'   => 1,
            'name'          => 'Schneider',
            'logo'          => $this->seedFile('logos/logo-' . rand(1, 4) . '.svg', $uploadDir),
        ])->channels()->attach($linkedIn, ['channel_username' => 'schneider-electric']);


        if ($this->config['organizations'] > 0) {
            $industries = Industry::all();

            factory(Organization::class, $this->config['organizations'])->create([
                'industry_id' => $industries->count() > 0 ? $industries->random()->id : null,
                'logo' => $this->seedFile('logos/logo-' . rand(1, 4) . '.svg', $uploadDir),
            ]);

            Organization::all()->each(function ($organization) use ($channels) {
                $organization->channels()->attach(
                    $this->getRandomChannels($channels)
                );
            });
        }
    }

    private function getRandomChannels($channels)
    {
        $channelsToAttach = [];
        $channelsCount = $channels->count();

        $channels->random(rand(1, $channelsCount))->each(function ($channel) use (&$channelsToAttach) {
            $channelsToAttach[$channel->id] = ['channel_username' => $this->faker->userName];
        });

        return $channelsToAttach;
    }

    private function clearData()
    {
        $organizations = Organization::all();

        foreach ($organizations as $organization) {
            $organization->delete();
        }
    }
}
