<?php

namespace App\Recipients;

class AdminRecipient extends Recipient
{
    public function __construct(string $email = null)
    {
        if ($email) {
            $this->email = $email;
        }
        else {
            $this->email = config('settings.admin-notifications-email');
        }
    }
}
