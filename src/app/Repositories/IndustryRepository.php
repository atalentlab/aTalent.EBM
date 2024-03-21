<?php

namespace App\Repositories;

use App\Models\Industry;

class IndustryRepository
{
    public function getPublishedIndustryList()
    {
        $locale = app()->getLocale();

        return Industry::where('published', 1)
            ->orderBy('name->'.$locale)
            ->orderBy('order')
            ->select('name->'.$locale.' as industry_name', 'id')->get();
    }
}
