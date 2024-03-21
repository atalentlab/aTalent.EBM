<?php

namespace App\Contracts;

interface NewsletterSubscribable
{
    public function getName();

    public function getEmail();

    public function setSubscribed();
}
