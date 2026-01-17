<?php

namespace App\Console\Commands;

use App\Helpers\QueueHelper;
use Illuminate\Console\Command;
use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;
use Illuminate\Support\Facades\Cache;

class QueueWorkerWithHeartbeat extends Command
{
    protected $signature = 'queue:work-heartbeat 
                            {--queue=default : The queue to work on}
                            {--max-jobs=1000 : Number of jobs to process before stopping}
                            {--timeout=60 : The number of seconds a child process can run}';

    protected $description = 'Start queue worker with heartbeat tracking';

    public function handle(Worker $worker): int
    {
        $this->info('Queue worker started with heartbeat tracking');

        $options = new WorkerOptions(
            $this->option('timeout'),
            0, // memory
            $this->option('max-jobs'),
            0, // sleep
            3, // maxTries
            false, // force
            false, // stopWhenEmpty
            $this->option('queue')
        );

        // Update heartbeat every 10 seconds
        $heartbeatInterval = 10;
        $lastHeartbeat = time();

        try {
            while (true) {
                // Update heartbeat periodically
                if (time() - $lastHeartbeat >= $heartbeatInterval) {
                    QueueHelper::updateHeartbeat();
                    $lastHeartbeat = time();
                }

                // Process queue
                $worker->runNextJob(
                    $this->option('queue'),
                    $options
                );

                // Small delay to prevent CPU spinning
                usleep(100000); // 0.1 seconds
            }
        } catch (\Exception $e) {
            $this->error('Queue worker error: ' . $e->getMessage());
            Cache::forget('queue_worker_running');
            return 1;
        }
    }
}
