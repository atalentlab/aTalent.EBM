<?php

namespace App\Notifications;

use App\Models\Period;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Storage;

class CrawlerReport extends Notification
{
    use Queueable;

    protected $period;

    protected $attachment;

    protected $channelsData;

    /**
     * Create a new notification instance.
     *
     * @param array $channelsData
     * @param Period $period
     * @param string|null $attachment
     *
     * @return void
     */
    public function __construct(array $channelsData, Period $period, string $attachment = null)
    {
        $this->channelsData = $channelsData;
        $this->period = $period;
        $this->attachment = $attachment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('Crawler report for ' . $this->period->name)
            ->markdown('admin.mail.crawler.report', [
                'period'        => $this->period,
                'channelsData'  => $this->channelsData,
                'hasAttachment' => $this->attachment !== null,
            ]);

        if ($this->attachment) {
            $message->attach(Storage::disk('private')->path($this->attachment));
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
