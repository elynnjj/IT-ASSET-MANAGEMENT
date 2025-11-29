<?php

namespace App\Notifications;

use App\Models\ITRequest;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ITRequestNotification extends Notification
{
    use Queueable;

    protected $itRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(ITRequest $itRequest)
    {
        $this->itRequest = $itRequest;
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
        $approvalUrl = route('hod.approval-request') . '?request=' . $this->itRequest->requestID;
        $deadlineDate = Carbon::parse($this->itRequest->requestDate)->addDays(3)->format('F d, Y');

        return (new MailMessage)
            ->subject('New IT Request Requires Your Approval')
            ->greeting('Hello ' . $notifiable->fullName . ',')
            ->line('A new IT request has been submitted by ' . $this->itRequest->requester->fullName . ' and requires your approval.')
            ->line('**Request Details:**')
            ->line('**Request ID:** ' . $this->itRequest->requestID)
            ->line('**Request Date:** ' . Carbon::parse($this->itRequest->requestDate)->format('F d, Y'))
            ->line('**Title:** ' . $this->itRequest->title)
            ->line('**Description:** ' . $this->itRequest->requestDesc)
            ->when($this->itRequest->asset, function ($mail) {
                return $mail
                    ->line('**Asset ID:** ' . $this->itRequest->asset->assetID)
                    ->line('**Asset Model:** ' . ($this->itRequest->asset->model ?? 'N/A'));
            })
            ->line('**Please approve or reject this request within 3 days.**')
            ->line('**Deadline:** ' . $deadlineDate)
            ->action('Review Request', $approvalUrl)
            ->line('Thank you for your attention to this matter.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'requestID' => $this->itRequest->requestID,
            'title' => $this->itRequest->title,
            'requester' => $this->itRequest->requester->fullName,
        ];
    }
}

