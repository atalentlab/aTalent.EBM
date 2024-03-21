<?php

namespace App\Notifications;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Collection;

class MyOrganizationReport extends Notification
{
    use Queueable;

    protected $data;

    protected $organization;

    protected $user;


    /**
     * MyOrganizationReport constructor.
     * @param Collection $data
     * @param Organization $organization
     * @param User $user
     */
    public function __construct(Collection $data, Organization $organization, User $user)
    {
        $this->organization = $organization;
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
            ->subject(__('email.reports.my-organization.subject'))
            ->markdown('admin.mail.reports.my-organization', [
                'organization'  => $this->organization,
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
