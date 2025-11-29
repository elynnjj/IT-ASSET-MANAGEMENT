<?php

namespace App\Notifications;

use App\Models\ITRequest;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ITRequestITDeptNotification extends Notification
{
    use Queueable;

    protected $itRequest;
    protected $notificationType; // 'new_request', 'approved_request'

    /**
     * Create a new notification instance.
     */
    public function __construct(ITRequest $itRequest, string $notificationType = 'new_request')
    {
        $this->itRequest = $itRequest;
        $this->notificationType = $notificationType;
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
        $requestUrl = route('itdept.it-requests') . '?request=' . $this->itRequest->requestID;
        
        if ($this->notificationType === 'approved_request') {
            return (new MailMessage)
                ->subject('New IT Request Approved - Action Required')
                ->greeting('Hello ' . $notifiable->fullName . ',')
                ->line('An IT request has been approved by HOD and requires your attention.')
                ->line('**Request Details:**')
                ->line('**Request ID:** ' . $this->itRequest->requestID)
                ->line('**Request Date:** ' . Carbon::parse($this->itRequest->requestDate)->format('F d, Y'))
                ->line('**Requester:** ' . $this->itRequest->requester->fullName)
                ->line('**Department:** ' . ($this->itRequest->requester->department ?? 'N/A'))
                ->line('**Title:** ' . $this->itRequest->title)
                ->line('**Description:** ' . $this->itRequest->requestDesc)
                ->when($this->itRequest->asset, function ($mail) {
                    return $mail
                        ->line('**Asset ID:** ' . $this->itRequest->asset->assetID)
                        ->line('**Asset Model:** ' . ($this->itRequest->asset->model ?? 'N/A'));
                })
                ->line('**Status:** Pending IT')
                ->action('View Request', $requestUrl)
                ->line('Please process this request accordingly.');
        } else {
            // New request from HOD
            return (new MailMessage)
                ->subject('New IT Request from HOD')
                ->greeting('Hello ' . $notifiable->fullName . ',')
                ->line('A new IT request has been submitted by ' . $this->itRequest->requester->fullName . ' (HOD).')
                ->line('**Request Details:**')
                ->line('**Request ID:** ' . $this->itRequest->requestID)
                ->line('**Request Date:** ' . Carbon::parse($this->itRequest->requestDate)->format('F d, Y'))
                ->line('**Department:** ' . ($this->itRequest->requester->department ?? 'N/A'))
                ->line('**Title:** ' . $this->itRequest->title)
                ->line('**Description:** ' . $this->itRequest->requestDesc)
                ->when($this->itRequest->asset, function ($mail) {
                    return $mail
                        ->line('**Asset ID:** ' . $this->itRequest->asset->assetID)
                        ->line('**Asset Model:** ' . ($this->itRequest->asset->model ?? 'N/A'));
                })
                ->action('View Request', $requestUrl)
                ->line('Please process this request accordingly.');
        }
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
            'notificationType' => $this->notificationType,
        ];
    }
}

