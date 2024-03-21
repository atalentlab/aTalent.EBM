<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class DataIntegrityCheckExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected $postDataIssues;

    protected $channelId;

    public function __construct(Collection $postDataIssues, string $channelId = null)
    {
        $this->postDataIssues = $postDataIssues;
        $this->channelId = $channelId;
    }

    public function collection()
    {
        return $this->postDataIssues;
    }

    public function headings(): array
    {
        return [
            'Message',
            'Data',
            'Previous data',
            'Organization',
            'Period',
            'Post ID',
            'Post URL',
        ];
    }
}
