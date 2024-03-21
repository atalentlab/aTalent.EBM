<?php

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends BaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->clearData();

        $permissions = [
            'view my organization',
            'view my organization.company info',
            'view my organization.rank info',
            'view my organization.cross channel stats',
            'view my organization.competition analysis',
            'manage my organization',
            'manage competitors',
            'manage multiple competitors',
            'view statistics dashboard',
            'view statistics dashboard.top performers',
            'view statistics dashboard.top 5 performers',
            'view statistics dashboard.top performing content',
            'view statistics dashboard.index table',
            'view indices dashboard',
            'view users',
            'manage users',
            'view api users',
            'manage api users',
            'view activity log',
            'view crawler dashboard',
            'view organizations',
            'manage organizations',
            'view organization data',
            'manage organization data',
            'view posts',
            'manage posts',
            'view post data',
            'manage post data',
            'view industries',
            'manage industries',
            'view channels',
            'manage channels',
            'view notifications',
            'manage notifications',
            'receive organization report',
            'receive competitor report',
            'change my organization',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $partnerRole = Role::create(['name' => 'Partner']);
        $enterpriseUserRole = Role::create(['name' => 'Enterprise User']);
        $premiumUserRole = Role::create(['name' => 'Premium User']);
        $basicUserRole = Role::create(['name' => 'Basic User']);
        $pendingUserRole = Role::create(['name' => 'Pending User']);

        $superAdminRole->givePermissionTo([
            'view my organization.company info',
            'view my organization.rank info',
            'view my organization.cross channel stats',
            'view my organization.competition analysis',
            'manage competitors',
            'manage multiple competitors',
            'view statistics dashboard',
            'view statistics dashboard.top performers',
            'view statistics dashboard.top 5 performers',
            'view statistics dashboard.top performing content',
            'view statistics dashboard.index table',
            'view indices dashboard',
            'view users',
            'manage users',
            'view api users',
            'manage api users',
            'view activity log',
            'view crawler dashboard',
            'view organizations',
            'manage organizations',
            'view organization data',
            'manage organization data',
            'view posts',
            'manage posts',
            'view post data',
            'manage post data',
            'view industries',
            'manage industries',
            'view channels',
            'manage channels',
            'view notifications',
            'manage notifications',
            'change my organization',
        ]);

        $partnerRole->givePermissionTo([
            'view my organization.company info',
            'view my organization.rank info',
            'view my organization.cross channel stats',
            'view my organization.competition analysis',
            'manage competitors',
            'manage multiple competitors',
            'view statistics dashboard',
            'view statistics dashboard.top performers',
            'view statistics dashboard.top 5 performers',
            'view statistics dashboard.top performing content',
            'view statistics dashboard.index table',
            'view organizations',
            'view organization data',
            'view posts',
            'view post data',
            'view industries',
            'view channels',
        ]);

        $enterpriseUserRole->givePermissionTo([
            'view my organization',
            'view my organization.company info',
            'view my organization.rank info',
            'view my organization.cross channel stats',
            'view my organization.competition analysis',
            'manage competitors',
            'manage multiple competitors',
            'manage my organization',
            'view statistics dashboard',
            'view statistics dashboard.top performers',
            'view statistics dashboard.top 5 performers',
            'view statistics dashboard.top performing content',
            'view statistics dashboard.index table',
            'receive organization report',
            'receive competitor report',
        ]);

        $premiumUserRole->givePermissionTo([
            'view my organization',
            'view my organization.company info',
            'view my organization.rank info',
            'view my organization.cross channel stats',
            'view my organization.competition analysis',
            'manage competitors',
            'manage my organization',
            'view statistics dashboard',
            'view statistics dashboard.top performers',
            'view statistics dashboard.top 5 performers',
            'view statistics dashboard.top performing content',
            'view statistics dashboard.index table',
            'receive organization report',
            'receive competitor report',
        ]);

        $basicUserRole->givePermissionTo([
            'view my organization',
            'view my organization.company info',
            'view my organization.rank info',
            'view my organization.cross channel stats',
            'view my organization.competition analysis',
            'manage my organization',
            'view statistics dashboard',
            'view statistics dashboard.top performers',
            'view statistics dashboard.top 5 performers',
            'view statistics dashboard.top performing content',
            'view statistics dashboard.index table',
        ]);

        $pendingUserRole->givePermissionTo([
            'view statistics dashboard',
            'view statistics dashboard.top performers',
            'view statistics dashboard.index table',
        ]);
    }

    private function clearData()
    {
        foreach (Role::all() as $item) {
            $item->delete();
        }

        foreach (Permission::all() as $item) {
            $item->delete();
        }
    }
}
