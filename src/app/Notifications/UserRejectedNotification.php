<?php

namespace App\Notifications;

use App\Enums\UserRejectReason;
use App\Models\AdminNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserRejectedNotification extends Notification
{
    use Queueable;

    protected $user;

    protected $adminNotification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, AdminNotification $adminNotification)
    {
        $this->user = $user;
        $this->adminNotification = $adminNotification;
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
        return (new MailMessage)
                ->subject('You have been rejected upgraded access to the ' . config('app.name'))
                ->greeting('Hello ' . $this->user->name)
                ->line('You have been rejected upgraded access to the ' . config('app.name') . ' for the following reason: ' . UserRejectReason::getValue($this->adminNotification->content['rejection_reason'])['name'] . '.')
                ->line($this->adminNotification->content['rejection_message'])
                ->action('Access your account', url($this->user->getHomepage()));
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
