-- Live SQL for broadcast radius + individual select
-- If a column already exists, skip that ALTER and continue.

ALTER TABLE `bookings` ADD `latitude` DECIMAL(10,8) NULL;
ALTER TABLE `bookings` ADD `longitude` DECIMAL(11,8) NULL;
ALTER TABLE `bookings` ADD `current_radius_km` INT UNSIGNED NULL;
ALTER TABLE `bookings` ADD `last_expand_prompt_at` TIMESTAMP NULL;

CREATE TABLE IF NOT EXISTS `booking_broadcast_notified` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `booking_id` BIGINT UNSIGNED NOT NULL,
    `technician_id` BIGINT UNSIGNED NOT NULL,
    `radius_km` INT UNSIGNED NULL,
    `distance_km` DECIMAL(8,2) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `booking_tech_unique` (`booking_id`, `technician_id`)
);

CREATE TABLE IF NOT EXISTS `booking_individual_offers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `booking_id` BIGINT UNSIGNED NOT NULL,
    `technician_id` BIGINT UNSIGNED NOT NULL,
    `status` VARCHAR(40) NOT NULL DEFAULT 'pending',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
);
