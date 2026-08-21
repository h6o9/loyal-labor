ALTER TABLE `bookings`
  MODIFY `status` ENUM(
    'pending',
    'accepted',
    'on_the_way',
    'work_started',
    'work_in_progress',
    'rejected',
    'completed',
    'cancelled',
    'expired'
  ) NOT NULL DEFAULT 'pending';

ALTER TABLE `bookings`
  ADD COLUMN `work_in_progress_at` TIMESTAMP NULL DEFAULT NULL AFTER `work_started_at`;
