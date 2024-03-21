<?php

namespace App\Notifications;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Collection;

class CompetitorReport extends Notification
{
    use Queueable;

    protected $headers;

    protected $data;

    protected $organization;

    protected $competitor;

    protected $user;

    /**
     * MyOrganizationReport constructor.
     * @param array $data
     * @param Organization $organization
     * @param Organization $competitor
     * @param User $user
     */
    public function __construct(array $data, Organization $organization, User $user, ?Organization $competitor = null)
    {
        $this->organization = $organization;
        $this->competitor = $competitor;
        $this->headers = $data['headers'];
        unset($data['headers']);
        $this->data = $data;
        $this->user = $user;
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
            ->from(config('mail.from.address'), __('email.general.from.name'))
            ->subject(__('email.reports.competitor.subject'))
            ->markdown('admin.mail.reports.competitor', [
                'organization'  => $this->organization,
                'competitor'    => $this->competitor,
                'headers'       => $this->headers,
                'data'          => $this->data,
                'user'          => $this->user,
            ]);

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
