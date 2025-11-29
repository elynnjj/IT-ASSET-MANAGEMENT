-- Create it_requests table
CREATE TABLE IF NOT EXISTS `it_requests` (
  `requestID` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `requestDate` DATE NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `requestDesc` TEXT NOT NULL,
  `status` VARCHAR(255) NOT NULL,
  `assetID` VARCHAR(255) NULL,
  `requesterID` VARCHAR(255) NOT NULL,
  `approverID` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`requestID`),
  KEY `it_requests_assetid_foreign` (`assetID`),
  KEY `it_requests_requesterid_foreign` (`requesterID`),
  KEY `it_requests_approverid_foreign` (`approverID`),
  CONSTRAINT `it_requests_assetid_foreign` FOREIGN KEY (`assetID`) REFERENCES `assets` (`assetID`) ON DELETE SET NULL,
  CONSTRAINT `it_requests_requesterid_foreign` FOREIGN KEY (`requesterID`) REFERENCES `users` (`userID`) ON DELETE CASCADE,
  CONSTRAINT `it_requests_approverid_foreign` FOREIGN KEY (`approverID`) REFERENCES `users` (`userID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

