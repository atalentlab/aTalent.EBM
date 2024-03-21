<?php

namespace App\Exports;

use App\Models\CrawlerLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CrawlerLogExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $periodId;

    protected $channelId;

    public function __construct(string $periodId, string $channelId = null)
    {
        $this->periodId = $periodId;
        $this->channelId = $channelId;
    }

    public function query()
    {
        $query = CrawlerLog::query()
            ->where('status', 'error')
            ->where('period_id', $this->periodId)
            ->orderBy('updated_at', 'desc')
            ->with(['channel', 'organization']);

        if ($this->channelId) {
            $query->where('channel_id', $this->channelId);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Timestamp',
            'Channel',
            'Organization',
            'Error message',
        ];
    }

    public function map($crawlerLog): array
    {
        return [
            $crawlerLog->created_at,
            $crawlerLog->channel->name,
            $crawlerLog->organization->name,
            $crawlerLog->message,
        ];
    }
}
