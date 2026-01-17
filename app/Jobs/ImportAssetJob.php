<?php

namespace App\Jobs;

use App\Models\Asset;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ImportAssetJob implements ShouldQueue
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
        // Update processed count
        $this->updateProgress('processed', 1);

        try {
            $data = [];
            foreach ($this->header as $index => $headerName) {
                $data[$headerName] = isset($this->rowData[$index]) ? trim($this->rowData[$index]) : '';
            }

            if (empty($data)) {
                $this->updateProgress('errors', 1);
                return;
            }

            // Basic per-row validation
            if (!isset($data['assetType'])) {
                $this->updateProgress('errors', 1);
                return;
            }
            if (!in_array($data['assetType'], ['Laptop', 'Desktop'], true)) {
                $this->updateProgress('errors', 1);
                return;
            }

            // Validate osVer if provided
            if (isset($data['osVer']) && !empty($data['osVer']) && !in_array($data['osVer'], ['Windows 10', 'Windows 11'], true)) {
                $this->updateProgress('errors', 1);
                return;
            }

            // Check for duplicate serial number
            if (!empty($data['serialNum'])) {
                $serialNum = trim($data['serialNum']);
                if (Asset::where('serialNum', $serialNum)->exists()) {
                    $this->updateProgress('duplicateSerial', 1);
                    return;
                }
            }

            // Auto-generate asset ID based on asset type
            $assetID = $this->generateNextAssetID(trim($data['assetType']));

            $asset = new Asset();
            $asset->assetID = $assetID;
            $asset->assetType = trim($data['assetType']);
            $asset->serialNum = !empty($data['serialNum']) ? trim($data['serialNum']) : null;
            $asset->model = !empty($data['model']) ? trim($data['model']) : null;
            $asset->ram = !empty($data['ram']) ? trim($data['ram']) : null;
            $asset->storage = !empty($data['storage']) ? trim($data['storage']) : null;

            // Handle purchaseDate
            if (!empty($data['purchaseDate'])) {
                $date = trim($data['purchaseDate']);
                try {
                    $parsedDate = Carbon::createFromFormat('Y-m-d', $date);
                    $asset->purchaseDate = $parsedDate->toDateString();
                } catch (\Exception $e) {
                    $asset->purchaseDate = Carbon::now()->toDateString();
                }
            } else {
                $asset->purchaseDate = Carbon::now()->toDateString();
            }

            $asset->osVer = !empty($data['osVer']) ? trim($data['osVer']) : null;
            $asset->processor = !empty($data['processor']) ? trim($data['processor']) : null;
            $asset->status = 'Available';
            $asset->save();

            // Track asset type
            $assetType = trim($data['assetType']);
            $this->updateProgress('created', 1);
            $this->updateProgress('createdByType_' . $assetType, 1);

            // Track imported asset types (atomic operation)
            Cache::lock("lock_import_progress_{$this->progressId}_importedTypes", 10)->block(5, function () use ($assetType) {
                $importedTypes = Cache::get("import_progress_{$this->progressId}_importedTypes", []);
                if (!in_array($assetType, $importedTypes)) {
                    $importedTypes[] = $assetType;
                    Cache::put("import_progress_{$this->progressId}_importedTypes", $importedTypes, 3600);
                }
            });

        } catch (\Throwable $e) {
            Log::error('Asset import job error: ' . $e->getMessage(), [
                'data' => $this->rowData,
                'trace' => $e->getTraceAsString()
            ]);
            $this->updateProgress('errors', 1);
        }
    }

    /**
     * Generate the next asset ID based on asset type
     */
    private function generateNextAssetID(string $assetType): string
    {
        $prefix = strtoupper($assetType);

        $assets = Asset::where('assetType', $assetType)
            ->where('assetID', 'like', $prefix . '%')
            ->get();

        $maxNumber = 0;
        $prefixLength = strlen($prefix);

        foreach ($assets as $asset) {
            $numberPart = substr($asset->assetID, $prefixLength);
            if (is_numeric($numberPart)) {
                $num = (int)$numberPart;
                if ($num > $maxNumber) {
                    $maxNumber = $num;
                }
            }
        }

        $nextNumber = $maxNumber + 1;
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
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
