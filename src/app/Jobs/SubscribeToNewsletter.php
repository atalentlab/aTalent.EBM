<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\EdmService;
use App\Contracts\NewsletterSubscribable;

class SubscribeToNewsletter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $model;

    protected $tag;

    protected $service;

    /**
     * Create a new job instance.
     *
     * @param NewsletterSubscribable $model
     * @param string $tag
     * @return void
     */
    public function __construct(NewsletterSubscribable $model, string $tag = null)
    {
        $this->model = $model;
        $this->tag = $tag;
        $this->service = new EdmService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $extraInfo = $this->model->getName() ? ['FNAME' => $this->model->getName()] : [];

        $response = $this->service->subscribe($this->model->getEmail(), $extraInfo);

        if ($response !== false) {
            $this->model->setSubscribed();
        }

        if ($this->tag) {
            $this->service->addTags($this->model->getEmail(), [$this->tag]);
        }
    }
}
