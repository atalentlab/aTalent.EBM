<?php

namespace App\Traits;

use App\Models\Membership;
use Carbon\Carbon;

trait HasMemberships
{
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function getActiveMemberships()
    {
        return $this->memberships()->active()->ordered()->with('role')->get();
    }

    public function getInActiveMemberships()
    {
        return $this->memberships()->inActive()->ordered()->with('role')->get();
    }

    public function hasActiveMemberships()
    {
        return $this->memberships()->active()->count() > 0;
    }

    public function getActiveMembershipByRole(string $roleName)
    {
        return $this->memberships()
                        ->active()
                        ->ordered()
                        ->whereHas('role', function ($query) use ($roleName) {
                            $query->where('name', $roleName);
                        })
                        ->with('role')->get();
    }

    public function canApplyForMembership()
    {
        // Pending users cant apply for memberships or trials
        if ($this->roles->whereIn('name', ['Pending User'])->count()) {
            return false;
        }

        // Max 2 active memberships per user
        if ($this->getActiveMemberships()->count() >= 2) {
            return false;
        }

        return true;
    }

    public function syncMemberships(array $membershipsData)
    {
        $memberships = $this->memberships()->get();

        $ids = [];

        foreach($membershipsData as $membershipData) {
            if (!isset($membershipData['is_trial'])) $membershipData['is_trial'] = '0';

            $membership = $memberships->where('id', $membershipData['id'])->first() ?? new Membership;

            $membershipData['user_id'] = $this->id;
            $membershipData['expires_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $membershipData['expires_at'] . ' 00:00:00');
            $membership->fill($membershipData);
            $membership->save();

            //\Log::info($membership);
            //\Log::info(Membership::find($membership->id));
            $ids[] = $membership->id;
        }

        $toDelete = $this->memberships()->whereNotIn('id', $ids)->active()->get();

        foreach($toDelete as $membership) {
            $membership->delete();
        }

        $this->determineUserRole();
    }

    public function getRoleBasedOnActiveMembership()
    {
        if ($activeMembership = $this->getActiveMemberships()->first()) {
            return $activeMembership->role->name;
        }

        return 'Basic User';
    }

    public function determineUserRole()
    {
        if ($activeMembership = $this->getActiveMemberships()->first()) {
            $this->syncRoles([$activeMembership->role_id]);
        }
    }

    public static function bootHasMemberships()
    {

    }
}
