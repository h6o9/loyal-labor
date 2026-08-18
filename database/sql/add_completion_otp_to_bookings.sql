-- Run this on LIVE MySQL (phpMyAdmin) if job complete gives:
-- Unknown column 'completion_otp'
-- If a column already exists, skip that statement.

ALTER TABLE `bookings` ADD COLUMN `completion_otp` VARCHAR(6) NULL AFTER `completed_at`;
ALTER TABLE `bookings` ADD COLUMN `completion_otp_expires_at` TIMESTAMP NULL AFTER `completion_otp`;
