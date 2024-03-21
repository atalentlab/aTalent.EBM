<?php

namespace App\Console\Commands;

use App\Models\Channel;
use App\Models\Organization;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use App\Exports\DataIntegrityCheckExport;
use Illuminate\Support\Str;

class DataIntegrityCheck extends Command
{
    const POST_DATA_FIELDS = [
        'like_count',
        'comment_count',
        'share_count',
        'view_count',
    ];

    const POST_DATA_CORRECTION_OFFSET = 20;

    protected $shouldFix = false;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'srm:data-integrity-check 
                            {--channel_id= : Run checks for a specific channel}
                            {--organization_id= : Run checks for a specific organization}
                            {--fix : Fix data integrity issues if possible}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks EBM data integrity';

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
        if (!$this->validateOptions()) {
            return;
        }

        if ($channelId = $this->option('channel_id')) {
            $channels = Channel::where('id', $channelId)->get();
        }
        else {
            $channels = Channel::all();
        }

        $organization = Organization::find($this->option('organization_id'));

        $this->shouldFix = $this->option('fix');

        foreach ($channels as $channel) {
            $issues = $this->checkPostsIntegrityByChannel($channel, $organization);
            $this->generateExcel($issues, $channel, $organization);
        }

        # TODO: Add period filter
        # TODO: Add more checks (eg for steep follower count drops)
    }

    protected function validateOptions(): bool
    {
        $validator = Validator::make($this->options(), [
            'channel_id' => 'nullable|integer|exists:channels,id',
            'organization_id' => 'nullable|integer|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return false;
        }

        return true;
    }
    
    protected function checkPostsIntegrityByChannel(Channel $channel, ?Organization $organization = null): Collection
    {
        $this->info('Checking post data integrity for channel ' . $channel->name . ($organization ? ' for organization ' . $organization->name : ''));

        $issues = collect();

        $query = Post::has('postData', '>=', 2)
            ->with('postData')
            ->with('postData.period')
            ->where('channel_id', $channel->id)
            ->orderby('created_at', 'desc');

        if ($organization) {
            $query->where('organization_id', $organization->id);
        }

        foreach ($query->cursor() as $post) {
            $this->checkIfPostDataIsValid($post, $issues);
        }

        $this->info('Found ' . $issues->count() . ' post data with integrity issues');

        return $issues;
    }

    protected function checkIfPostDataIsValid(Post $post, Collection &$issues)
    {
        $isValid = true;

        $postData = $post->postData->sortBy('period.start_date');

        $previousData = null;

        foreach($postData as $data) {
            if ($previousData) {
                foreach(self::POST_DATA_FIELDS as $field) {
                    if ($data->$field === 0 && $data->$field < $previousData->$field && $previousData->$field > self::POST_DATA_CORRECTION_OFFSET) {

                        $issues->push([
                            $field . ' dropped from ' . $previousData->$field . ' to 0',
                            (string) $data->$field,
                            (string) $previousData->$field,
                            $post->organization->name,
                            $data->period->name_with_year,
                            $post->id,
                            route('admin.organization.post.edit', ['organization' => $post->organization->id, 'id' => $post->id])
                        ]);

                        // correct our data temporarily so we can root out potential issues for next period post data
                        $data->$field = $previousData->$field;

                        if ($this->shouldFix) {
                            $data->save();
                        }

                        $isValid = false;
                    }
                }
            }

            $previousData = $data;
        }

        return $isValid;
    }

    protected function generateExcel(Collection $issues, Channel $channel, ?Organization $organization = null)
    {
        if ($issues->count() > 0) {
            $path = 'exports/data-integrity-report-' . strtolower($channel->name) . ($organization ? '-' . Str::slug($organization->name) : '') . '-' . now()->timestamp . '.xlsx';

            return (new DataIntegrityCheckExport($issues, $channel->id))->store($path, 'private', null, [
                'visibility' => 'private',
            ]);
        }
    }
}
