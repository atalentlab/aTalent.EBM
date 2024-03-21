<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UpdateExpiredMemberships extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'srm:update-expired-memberships {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates user roles when their membership has expired.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = $this->argument('date');

        Validator::make(['date' => $date], [
            'date' => 'nullable|date',
        ])->validate();

        $date = $date ? Carbon::createFromFormat('Y-m-d', $date) : now();

        $this->output('Looking for users with a membership that expires on ' . $date->format('Y-m-d'));

        // get the users with memberships that expire on the given $date
        $users = User::whereHas('memberships', function ($query) use ($date) {
            $query->whereDate('expires_at', $date);
        })->with(['memberships' => function ($query) use ($date) {
            $query->whereDate('expires_at', $date)
                    ->ordered();
        }])->get();

        $this->output('Found ' . $users->count() . ' eligible user(s).');

        foreach($users as $user) {
            $this->output('Processing ' . $user->name);

            // remove roles from user related to expired memberships
            foreach($user->memberships as $membership) {
                $user->removeRole($membership->role->name);

                // notify the user about their expired membership
                $membership->sendExpiredNotification();
            }

            // assign new role based on active membership (if any)
            $user->determineUserRole();

            // assign basic user role if the user has no active memberships
            if (!$user->roles()->count()) {
                $user->assignRole('Basic User');
            }
        }

        $this->output('All done');
    }
}
