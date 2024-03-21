<?php

namespace App\Console\Commands;

use App\Models\CrawlerLog;
use Illuminate\Support\Facades\Validator;

class ClearCrawlerLog extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'srm:clear-crawler-log {--period= : The period ID for which to clear the crawler log} {--channel= : The channel ID for which to clear the crawler log}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the crawler log for a specified period and channel';

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
        $validator = Validator::make($this->options(), [
            'period' => 'required|integer|exists:periods,id',
            'channel' => 'required|integer|exists:channels,id',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return;
        }

        $crawlerLogs = CrawlerLog::where('period_id', $this->option('period'))
                                ->where('channel_id', $this->option('channel'))
                                ->get();

        foreach($crawlerLogs as $log) {
            $log->delete();
        }

        $this->output('Crawler logs cleared for the selected period and channel');
    }
}
