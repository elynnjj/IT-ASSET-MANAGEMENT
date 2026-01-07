<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserWelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $temporaryPassword;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $temporaryPassword)
    {
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to ExactAsset - Your Account Details')
            ->greeting('Hello ' . $notifiable->fullName . '!')
            ->line('Your account has been created successfully. Below are your account details:')
            ->line('**Full Name:** ' . $notifiable->fullName)
            ->line('**Email:** ' . $notifiable->email)
            ->line('**Department:** ' . ($notifiable->department ?? 'N/A'))
            ->line('**Role:** ' . ($notifiable->role ?? 'N/A'))
            ->line('**Username:** ' . $notifiable->userID)
            ->line('**Temporary Password:** ' . $this->temporaryPassword)
            ->line('**Important:** You must change your password on your first login for security purposes.')
            ->action('Login to System', route('login'))
            ->line('Please keep your login credentials secure and do not share them with anyone.')
            ->salutation('Regards,' . "\n" . 'ExactAsset Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
