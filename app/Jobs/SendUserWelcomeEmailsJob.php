<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\NewUserWelcomeNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendUserWelcomeEmailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutes timeout

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $progressId
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Get users to notify from cache
            $usersToNotify = Cache::get("import_progress_{$this->progressId}_usersToNotify", []);
            
            if (empty($usersToNotify)) {
                Log::info("No users to notify for import {$this->progressId}");
                return;
            }

            $emailsSent = 0;
            $emailsFailed = 0;

            foreach ($usersToNotify as $notificationData) {
                try {
                    $user = User::where('userID', $notificationData['userID'])->first();
                    if ($user) {
                        // Send notification synchronously (not queued) to ensure it's sent immediately
                        $notification = new NewUserWelcomeNotification($notificationData['password']);
                        $user->notifyNow($notification);
                        $emailsSent++;
                        
                        Log::info("Sent welcome email to user: {$user->userID} ({$user->email})");
                    } else {
                        Log::warning("User not found for email notification: {$notificationData['userID']}");
                        $emailsFailed++;
                    }
                } catch (\Exception $e) {
                    $emailsFailed++;
                    Log::error('Failed to send welcome email to user: ' . ($notificationData['userID'] ?? 'unknown'), [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            // Log email sending results
            Log::info("Email sending completed for import {$this->progressId}: {$emailsSent} sent, {$emailsFailed} failed");
            
            // Update cache with email sending results
            Cache::put("import_progress_{$this->progressId}_emailsSent", true, 3600);
            Cache::put("import_progress_{$this->progressId}_emailsSentCount", $emailsSent, 3600);
            Cache::put("import_progress_{$this->progressId}_emailsFailedCount", $emailsFailed, 3600);

        } catch (\Throwable $e) {
            Log::error('SendUserWelcomeEmailsJob error: ' . $e->getMessage(), [
                'progressId' => $this->progressId,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
