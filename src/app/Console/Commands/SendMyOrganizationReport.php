<?php

namespace App\Console\Commands;

use App\Models\Period;
use App\Models\User;
use App\Repositories\OrganizationRepository;
use App\Repositories\PeriodRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use App\Recipients\AdminRecipient;
use App\Notifications\MyOrganizationReport;

class SendMyOrganizationReport extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'srm:send-my-organization-report {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send out organization reports';

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
        if (!$this->validateArguments()) {
            return;
        }

        // if an email address is passed as argument, then we send to a user with that email address only
        if ($email = $this->argument('email')) {
            $users = User::where('email', $email)->whereHas('organization')->get();
        }
        else {
            $users = User::permission('receive organization report')
                ->where('receives_my_organization_report', true)
                ->whereHas('organization')
                ->get();
        }

        $this->output('Sending my organization reports...');

        $counter = 0;

        foreach ($users as $user) {
            $organization = $user->organization;

            $period = (new PeriodRepository())->getLatestPeriodForOrganization($organization->id);

            if (!$period) continue;

            $data = (new OrganizationRepository())->getOrganizationWithCountsForPastPeriods($period, $organization);

            if ($data->count()) {
                (new AdminRecipient($user->email))->notify(new MyOrganizationReport($data, $organization, $user));

                $counter++;
            }
        }

        $this->output('Sent ' . $counter . ' my organization reports');
    }

    protected function validateArguments(): bool
    {
        $validator = Validator::make($this->arguments(), [
            'email' => 'nullable|email|max:255|exists:users,email',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return false;
        }

        return true;
    }
}
