<?php

namespace App\Notifications;

use App\Models\Asset;
use App\Models\AssignAsset;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssetAssignmentNotification extends Notification
{
    use Queueable;

    protected $asset;
    protected $assignment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Asset $asset, AssignAsset $assignment)
    {
        $this->asset = $asset;
        $this->assignment = $assignment;
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
        $checkoutDate = Carbon::parse($this->assignment->checkoutDate)->format('F d, Y');

        return (new MailMessage)
            ->subject('Asset Assignment Notification')
            ->greeting('Hello ' . $notifiable->fullName . ',')
            ->line('You have been assigned a new company asset. Please find the details below:')
            ->line('**Asset Details:**')
            ->line('**Asset ID:** ' . $this->asset->assetID)
            ->when($this->asset->serialNum, function ($mail) {
                return $mail->line('**Serial Number:** ' . $this->asset->serialNum);
            })
            ->when($this->asset->model, function ($mail) {
                return $mail->line('**Model:** ' . $this->asset->model);
            })
            ->when($this->asset->ram, function ($mail) {
                return $mail->line('**RAM:** ' . $this->asset->ram);
            })
            ->when($this->asset->storage, function ($mail) {
                return $mail->line('**Storage:** ' . $this->asset->storage);
            })
            ->line('**Checkout Date:** ' . $checkoutDate)
            ->line('**Important Reminder:** Please ensure you sign the AGREEMENT ACCEPTANCE COMPANY PROPERTIES FORM before using this asset.')
            ->line('Please ensure that you take good care of this asset and return it to the IT Department when no longer needed.')
            ->line('If you have any questions or concerns regarding this asset, please contact the IT Department.')
            ->line('Regards,')
            ->line('**IT Department**')
            ->salutation('');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'assetID' => $this->asset->assetID,
            'assetType' => $this->asset->assetType,
            'checkoutDate' => $this->assignment->checkoutDate,
        ];
    }
}

