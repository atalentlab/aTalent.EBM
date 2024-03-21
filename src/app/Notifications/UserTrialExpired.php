<?php

namespace App\Notifications;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Action;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserTrialExpired extends Notification
{
    use Queueable;

    protected $user;

    protected $membership;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Membership $membership)
    {
        $this->user = $user;
        $this->membership = $membership;
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
                ->subject(__('notifications.user-trial-expired.subject', ['app' => config('app.name')]))
                ->greeting(__('notifications.user-trial-expired.greeting', ['name' => $this->user->name]))
                ->line(__('notifications.user-trial-expired.copy', ['app' => config('app.name'), 'role' => $this->membership->role->name, 'new_role' => $this->user->getRoleBasedOnActiveMembership()]))
                ->action(__('notifications.user-trial-expired.cta-1.copy'), route('admin.profile.show') . '#membership-plans');
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
