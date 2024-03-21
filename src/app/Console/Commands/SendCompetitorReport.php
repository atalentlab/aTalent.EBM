<?php

namespace App\Console\Commands;

use App\Notifications\CompetitorReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use App\Recipients\AdminRecipient;
use App\Models\User;
use App\Repositories\PeriodRepository;
use App\Repositories\OrganizationRepository;

class SendCompetitorReport extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'srm:send-competitor-report {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send out competitor reports';

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
            $users = User::permission('receive competitor report')
                ->where('receives_competitor_report', true)
                ->whereHas('organization')
                ->get();
        }

        $this->output('Sending competitor reports...');

        $counter = 0;

        foreach ($users as $user) {
            $organization = $user->organization;
            $competitor = $organization->competitors->first();

            $period = (new PeriodRepository())->getLatestPeriodForOrganization($organization->id);

            if (!$period) continue;

            $data = (new OrganizationRepository())->getOrganizationAndCompetitorWithCountsAndProgressionForPeriod($period, $organization, $competitor);

            if (count($data)) {
                (new AdminRecipient($user->email))->notify(new CompetitorReport($data, $organization, $user, $competitor));

                $counter++;
            }
        }

        $this->output('Sent ' . $counter . ' competitor reports');
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
