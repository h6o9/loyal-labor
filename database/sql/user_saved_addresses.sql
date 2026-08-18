-- Saved addresses for customer/technician profile
-- Run on LIVE MySQL (phpMyAdmin)

CREATE TABLE IF NOT EXISTS user_saved_addresses (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  label VARCHAR(50) NOT NULL DEFAULT 'Home',
  address VARCHAR(500) NOT NULL,
  city VARCHAR(100) NULL,
  is_default TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT user_saved_addresses_user_id_foreign
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
