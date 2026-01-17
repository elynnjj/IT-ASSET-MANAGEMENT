<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class QueueHelper
{
    /**
     * Start the queue worker automatically if not already running
     */
    public static function ensureQueueWorkerRunning(): void
    {
        // Check if queue worker is already running
        $workerLockKey = 'queue_worker_running';
        $isRunning = Cache::get($workerLockKey, false);

        if ($isRunning) {
            // Worker might be running, check if it's still active
            $lastHeartbeat = Cache::get('queue_worker_heartbeat', 0);
            $timeSinceHeartbeat = time() - $lastHeartbeat;
            
            // If heartbeat is recent (within 30 seconds), assume worker is running
            if ($timeSinceHeartbeat < 30) {
                return;
            }
        }

        // Start queue worker in background
        self::startQueueWorker();
    }

    /**
     * Start the queue worker process
     */
    private static function startQueueWorker(): void
    {
        $artisanPath = base_path('artisan');
        $phpPath = PHP_BINARY;
        $lockKey = 'queue_worker_running';

        // Mark worker as running
        Cache::put($lockKey, true, 300); // 5 minutes
        Cache::put('queue_worker_heartbeat', time(), 300);

        // Determine OS and start appropriate command
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows - use start command with /B flag to run in background
            $command = "start /B \"\" \"{$phpPath}\" \"{$artisanPath}\" queue:work --stop-when-empty --max-jobs=1000 --timeout=60";
            // Use popen to start process in background
            $handle = popen($command, 'r');
            if ($handle) {
                pclose($handle);
            }
        } else {
            // Linux/Mac - use nohup or background execution
            $command = "nohup {$phpPath} {$artisanPath} queue:work --stop-when-empty --max-jobs=1000 --timeout=60 > /dev/null 2>&1 &";
            exec($command);
        }

        Log::info('Queue worker started automatically');
    }

    /**
     * Update queue worker heartbeat
     */
    public static function updateHeartbeat(): void
    {
        Cache::put('queue_worker_heartbeat', time(), 300);
    }
}
