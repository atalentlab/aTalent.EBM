<?php

namespace App\Services;

use Newsletter;
use App\Services\SentryService;

class EdmService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('settings');
    }

    public function subscribe(string $email, array $parameters = [])
    {
        if ($this->isEnabled()) {
            try {
                return Newsletter::subscribe($email, $parameters);
            } catch (\Exception $e) {
                SentryService::captureError($e);
                return false;
            }
        }
    }

    public function addTags(string $email, array $tags)
    {
        if ($this->isEnabled()) {
            try {
                return Newsletter::addTags($tags, $email);
            } catch (\Exception $e) {
                SentryService::captureError($e);
                return false;
            }
        }
    }

    protected function isEnabled()
    {
        return $this->config['enable-edm-api'] == true;
    }
}
