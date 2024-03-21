<?php

namespace App\Notifications;

use App\Models\AdminNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserNotification extends Notification
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
                    ->subject('New user registration on ' . config('app.name'))
                    ->greeting('Hello!')
                    ->line($this->user->name . ' (' . $this->user->email . ') has registered an account on the ' . config('app.name') . '.')
                    ->action('Process registration', route('admin.notification.edit', ['id' => $this->adminNotification->id]));
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
