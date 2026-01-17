<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Notifications\NewUserWelcomeNotification;

class ImportUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutes timeout

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $progressId,
        public array $rowData,
        public array $header,
        public int $rowIndex,
        public int $totalRows
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $data = array_combine($this->header, $this->rowData);
            if (!$data) {
                $this->updateProgress('processed', 1);
                $this->updateProgress('skipped', 1);
                return;
            }

            // Trim all values
            $data = array_map('trim', $data);

            // Basic per-row validation
            if (!isset($data['userID'], $data['fullName'], $data['email'], $data['department'], $data['role'])) {
                $this->updateProgress('processed', 1);
                $this->updateProgress('skipped', 1);
                return;
            }
            if (!in_array($data['role'], ['Employee', 'HOD'], true)) {
                $this->updateProgress('processed', 1);
                $this->updateProgress('skipped', 1);
                return;
            }

            // Skip existing users entirely (do not update)
            if (User::where('userID', $data['userID'])->exists()) {
                $this->updateProgress('processed', 1);
                $this->updateProgress('skipped', 1);
                return;
            }

            // Generate secure temporary password
            $temporaryPassword = $this->generateSecureTemporaryPassword();

            $user = new User();
            $user->userID = $data['userID'];
            $user->fullName = $data['fullName'];
            $user->email = $data['email'];
            $user->password = Hash::make($temporaryPassword);
            $user->department = $data['department'];
            $user->role = $data['role'];
            $user->accStat = 'active';
            $user->firstLogin = true;
            $user->save();

            // Send email immediately after user is created
            try {
                $notification = new NewUserWelcomeNotification($temporaryPassword);
                $user->notifyNow($notification);
                $this->updateProgress('emailsSent', 1);
                Log::info("Sent welcome email to user: {$user->userID} ({$user->email})");
            } catch (\Exception $e) {
                $this->updateProgress('emailsFailed', 1);
                Log::error('Failed to send welcome email to user: ' . $user->userID, [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            // Update processed and created counters AFTER user is successfully created
            // This ensures the last job's counters are updated before completion is detected
            $this->updateProgress('processed', 1);
            $this->updateProgress('created', 1);
            
            // Track count by role (Employee/HOD) - use the saved role value to ensure accuracy
            $role = trim($user->role);
            Log::info("Tracking role count for user: {$user->userID}, Role: '{$role}'", [
                'progressId' => $this->progressId,
                'roleValue' => $role,
                'roleLength' => strlen($role)
            ]);
            
            if ($role === 'Employee') {
                $this->updateProgress('createdByRole_Employee', 1);
                Log::info("Incremented Employee count for user: {$user->userID}");
            } elseif ($role === 'HOD') {
                $this->updateProgress('createdByRole_HOD', 1);
                Log::info("Incremented HOD count for user: {$user->userID}");
            } else {
                // Log if role doesn't match (shouldn't happen due to validation, but just in case)
                Log::warning("Unexpected role value in ImportUserJob: '{$role}' (length: " . strlen($role) . ")", [
                    'userID' => $user->userID,
                    'progressId' => $this->progressId,
                    'rawRole' => $data['role'] ?? 'not set'
                ]);
            }

            // Track role for redirect (atomic operation)
            Cache::lock("lock_import_progress_{$this->progressId}_importedRoles", 10)->block(5, function () use ($data) {
                $importedRoles = Cache::get("import_progress_{$this->progressId}_importedRoles", []);
                if (!in_array($data['role'], $importedRoles)) {
                    $importedRoles[] = $data['role'];
                    Cache::put("import_progress_{$this->progressId}_importedRoles", $importedRoles, 3600);
                }
            });

        } catch (\Throwable $e) {
            // Update processed count even on error, so progress tracking is accurate
            $this->updateProgress('processed', 1);
            Log::error('User import job error: ' . $e->getMessage(), [
                'data' => $this->rowData,
                'trace' => $e->getTraceAsString()
            ]);
            $this->updateProgress('errors', 1);
        }
    }

    /**
     * Generate a secure temporary password
     */
    private function generateSecureTemporaryPassword(): string
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*';

        $password = '';
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];

        $allChars = $uppercase . $lowercase . $numbers . $symbols;
        $remainingLength = 8 - strlen($password);

        for ($i = 0; $i < $remainingLength; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        return str_shuffle($password);
    }

    /**
     * Update progress in cache using atomic increment
     */
    private function updateProgress(string $key, int $increment = 1): void
    {
        $cacheKey = "import_progress_{$this->progressId}_{$key}";
        
        // Use lock to ensure atomic operations
        Cache::lock("lock_{$cacheKey}", 10)->block(5, function () use ($cacheKey, $increment) {
            $current = Cache::get($cacheKey, 0);
            Cache::put($cacheKey, $current + $increment, 3600);
        });
    }
}
