<?php

namespace App\Console\Commands;

use App\Models\Period;
use App\Repositories\PeriodRepository;
use App\Recipients\AdminRecipient;
use App\Notifications\CrawlerReport;
use App\Repositories\CrawlerLogRepository;
use Illuminate\Support\Collection;
use App\Exports\CrawlerLogExport;
use Illuminate\Support\Str;

class SendCrawlerReport extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'srm:send-crawler-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends out a crawler report for the current period';

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
        $crawlerRepo = new CrawlerLogRepository();

        $channelsData = $crawlerRepo->getCrawlerStatsPerChannelForCurrentPeriod();
        $currentPeriod = (new PeriodRepository())->getCurrentPeriod();
        $errorsExcelPath = null;

        if ($crawlerRepo->getErrorsForCurrentPeriod()->count() > 0) {
            if ($this->generateErrorsExcel($currentPeriod)) {
                $errorsExcelPath = $this->getErrorsExcelFilePath($currentPeriod);
            }
        }

        $recipient = config('settings.crawler-report-email');

        (new AdminRecipient($recipient))->notify(new CrawlerReport($channelsData, $currentPeriod, $errorsExcelPath));
    }

    protected function generateErrorsExcel(Period $period)
    {
        return (new CrawlerLogExport($period->id))->store($this->getErrorsExcelFilePath($period), 'private', null, [
            'visibility' => 'private',
        ]);
    }

    protected function getErrorsExcelFilePath(Period $period)
    {
        return 'exports/crawler-report-errors-' . Str::kebab($period->name) . '.xlsx';
    }
}
