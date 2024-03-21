<?php

namespace App\Notifications;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class UserUpgradeAccountRequest extends Notification
{
    use Queueable;

    protected $user;

    protected $organization;

    protected $reason;

    protected $phone;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Organization $organization, string $reason, string $phone)
    {
        $this->user = $user;

        $this->organization = $organization;

        $this->reason = $reason;

        $this->phone = $phone;
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
            ->bcc(config('settings.admin-notifications-email'))
            ->subject($this->user->name . ' wants to upgrade their account')
            ->greeting('Upgrade account')
            ->line($this->user->name . ' (email: ' . $this->user->email . ', phone: ' . $this->phone . ') working for ' . $this->organization->name . ' wants to upgrade their account on the ' . config('app.name') . '.')
            ->line('Their current subscription is ' . ($this->user->roles->count() ? $this->user->roles->implode('name', ', ') : 'None'))
            ->line($this->reason)
            ->action('Access user profile', route('admin.user.edit', ['id' => $this->user->id]));
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
