<?php

namespace App\Notifications;

use App\Models\ITRequest;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ITRequestStatusNotification extends Notification
{
    use Queueable;

    protected $itRequest;
    protected $status; // 'approved' or 'rejected'
    protected $hodName;

    /**
     * Create a new notification instance.
     */
    public function __construct(ITRequest $itRequest, string $status, string $hodName)
    {
        $this->itRequest = $itRequest;
        $this->status = $status;
        $this->hodName = $hodName;
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
        $requestUrl = route('employee.my-requests');
        $statusText = $this->status === 'approved' ? 'Approved' : 'Rejected';
        $statusColor = $this->status === 'approved' ? 'green' : 'red';
        
        if ($this->status === 'approved') {
            return (new MailMessage)
                ->subject('Your IT Request Has Been Approved')
                ->greeting('Hello ' . $notifiable->fullName . ',')
                ->line('Your IT request has been **approved** by ' . $this->hodName . ' (HOD).')
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
                ->line('**Status:** Pending IT')
                ->line('Your request has been forwarded to the IT Department for processing.')
                ->action('View Your Requests', $requestUrl)
                ->line('Thank you for your patience. The IT Department will process your request shortly.');
        } else {
            return (new MailMessage)
                ->subject('Your IT Request Has Been Rejected')
                ->greeting('Hello ' . $notifiable->fullName . ',')
                ->line('We regret to inform you that your IT request has been **rejected** by ' . $this->hodName . ' (HOD).')
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
                ->line('**Status:** Rejected')
                ->line('If you have any questions or concerns about this decision, please contact your HOD.')
                ->action('View Your Requests', $requestUrl)
                ->line('Thank you for your understanding.');
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
            'status' => $this->status,
            'hodName' => $this->hodName,
        ];
    }
}

