CREATE TABLE IF NOT EXISTS `maintenances` (
    `mainID` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `mainDate` date NOT NULL,
    `mainDesc` text NOT NULL,
    `assetID` varchar(255) NULL,
    `requestID` bigint UNSIGNED NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`mainID`),
    KEY `maintenances_assetid_foreign` (`assetID`),
    KEY `maintenances_requestid_foreign` (`requestID`),
    CONSTRAINT `maintenances_assetid_foreign` FOREIGN KEY (`assetID`) REFERENCES `assets` (`assetID`) ON DELETE SET NULL,
    CONSTRAINT `maintenances_requestid_foreign` FOREIGN KEY (`requestID`) REFERENCES `it_requests` (`requestID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert migration record (adjust batch number as needed)
INSERT INTO `migrations` (`migration`, `batch`) 
SELECT '2025_11_18_222624_create_maintenances_table', COALESCE(MAX(`batch`), 0) + 1 
FROM `migrations`
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations` WHERE `migration` = '2025_11_18_222624_create_maintenances_table'
);

