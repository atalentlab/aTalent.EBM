<?php

use App\Models\OrganizationData;
use App\Models\Period;
use App\Models\Organization;

class OrganizationDataTableSeeder extends BaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->clearData();

        if ($this->config['organization_data']) {
            $periods = Period::all();
            $organizations = Organization::all();

            foreach ($periods as $period) {
                foreach ($organizations as $organization) {
                    foreach ($organization->channels as $channel) {
                        // 1 chance out of 20 that an organization has no data
                        if (rand(1, 20) !== 1) {
                            factory(OrganizationData::class)->create([
                                'period_id'         => $period->id,
                                'organization_id'   => $organization->id,
                                'channel_id'        => $channel->id,
                            ]);
                        }
                    }
                }
            }
        }
    }

    private function clearData()
    {
        $organizationData = OrganizationData::all();

        foreach ($organizationData as $od) {
            $od->delete();
        }
    }
}
