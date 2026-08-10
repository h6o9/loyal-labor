-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

SET FOREIGN_KEY_CHECKS=0;

-- Dumping structure for table topcommerce.addresses
DROP TABLE IF EXISTS `addresses`;
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `country_id` varchar(255) DEFAULT NULL,
  `state_id` varchar(255) DEFAULT NULL,
  `city_id` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `walk_in_customer` tinyint(1) DEFAULT NULL,
  `type` enum('home','office') NOT NULL DEFAULT 'home',
  `default` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.addresses: ~2 rows (approximately)
DELETE FROM `addresses`;

-- Dumping structure for table topcommerce.admins
DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_super_admin` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `forget_password_token` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.admins: ~1 rows (approximately)
DELETE FROM `admins`;

-- Dumping structure for table topcommerce.admin_notifications
DROP TABLE IF EXISTS `admin_notifications`;
CREATE TABLE IF NOT EXISTS `admin_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `type` enum('info','success','danger','warning','order') NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.admin_notifications: ~44 rows (approximately)
DELETE FROM `admin_notifications`;
INSERT INTO `admin_notifications` (`id`, `title`, `message`, `link`, `type`, `is_read`, `created_at`, `updated_at`) VALUES
	(1, 'Installed Successfully', 'Welcome to TopCommerce(3.0.0)', NULL, 'success', 0, '2025-08-07 03:49:14', '2025-08-07 03:49:14');

-- Dumping structure for table topcommerce.attributes
DROP TABLE IF EXISTS `attributes`;
CREATE TABLE IF NOT EXISTS `attributes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attributes_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.attributes: ~0 rows (approximately)
DELETE FROM `attributes`;

-- Dumping structure for table topcommerce.attribute_images
DROP TABLE IF EXISTS `attribute_images`;
CREATE TABLE IF NOT EXISTS `attribute_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `attribute_id` bigint(20) unsigned NOT NULL,
  `attribute_value_id` bigint(20) unsigned NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.attribute_images: ~0 rows (approximately)
DELETE FROM `attribute_images`;

-- Dumping structure for table topcommerce.attribute_translations
DROP TABLE IF EXISTS `attribute_translations`;
CREATE TABLE IF NOT EXISTS `attribute_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `lang_code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_translations_attribute_id_foreign` (`attribute_id`),
  CONSTRAINT `attribute_translations_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.attribute_translations: ~0 rows (approximately)
DELETE FROM `attribute_translations`;

-- Dumping structure for table topcommerce.attribute_values
DROP TABLE IF EXISTS `attribute_values`;
CREATE TABLE IF NOT EXISTS `attribute_values` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `has_thumbnail` tinyint(1) DEFAULT 0,
  `thumbnail` varchar(255) DEFAULT NULL,
  `attribute_id` bigint(20) unsigned NOT NULL,
  `order` int(11) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_values_attribute_id_foreign` (`attribute_id`),
  CONSTRAINT `attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.attribute_values: ~0 rows (approximately)
DELETE FROM `attribute_values`;

-- Dumping structure for table topcommerce.attribute_value_translations
DROP TABLE IF EXISTS `attribute_value_translations`;
CREATE TABLE IF NOT EXISTS `attribute_value_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_value_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `lang_code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_value_translations_attribute_value_id_foreign` (`attribute_value_id`),
  CONSTRAINT `attribute_value_translations_attribute_value_id_foreign` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.attribute_value_translations: ~0 rows (approximately)
DELETE FROM `attribute_value_translations`;

-- Dumping structure for table topcommerce.banned_histories
DROP TABLE IF EXISTS `banned_histories`;
CREATE TABLE IF NOT EXISTS `banned_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `reasone` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.banned_histories: ~0 rows (approximately)
DELETE FROM `banned_histories`;

-- Dumping structure for table topcommerce.basic_payments
DROP TABLE IF EXISTS `basic_payments`;
CREATE TABLE IF NOT EXISTS `basic_payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.basic_payments: ~24 rows (approximately)
DELETE FROM `basic_payments`;
INSERT INTO `basic_payments` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
	(1, 'stripe_key', 'pk_test_33mdngCLuLsmECXOe8mbde9f00pZGT4uu9', '2025-08-07 03:49:14', '2025-08-07 04:19:00'),
	(2, 'stripe_secret', 'sk_test_MroTZzRZRv2KJ9Hmaro73SE800UOR90Q9u', '2025-08-07 03:49:14', '2025-08-07 04:19:00'),
	(3, 'stripe_currency_id', '1', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(4, 'stripe_status', 'active', '2025-08-07 03:49:14', '2025-08-07 04:19:00'),
	(5, 'stripe_charge', '0', '2025-08-07 03:49:14', '2025-08-07 04:19:00'),
	(6, 'stripe_image', 'website/images/gateways/stripe.webp', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(7, 'paypal_app_id', 'APP-80W284485P519543T', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(8, 'paypal_client_id', 'paypal_client_id', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(9, 'paypal_secret_key', 'paypal_secret_key', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(10, 'paypal_account_mode', 'sandbox', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(11, 'paypal_currency_id', '1', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(12, 'paypal_charge', '0', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(13, 'paypal_status', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(14, 'paypal_image', 'website/images/gateways/paypal.webp', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(15, 'bank_information', 'Bank Name => Your bank name\r\nAccount Number =>  Your bank account number\r\nRouting Number => Your bank routing number\r\nBranch => Your bank branch name', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(16, 'bank_status', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(17, 'bank_image', 'website/images/gateways/bank-pay.webp', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(18, 'bank_charge', '0', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(19, 'bank_currency_id', '1', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(20, 'hand_cash_information', 'Hand Cash Information', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(21, 'hand_cash_image', 'website/images/gateways/cash-on-delivery.webp', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(22, 'hand_cash_charge', '0', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(23, 'hand_cash_status', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(24, 'hand_cash_currency_id', '1', '2025-08-07 03:49:14', '2025-08-07 03:49:14');

-- Dumping structure for table topcommerce.blogs
DROP TABLE IF EXISTS `blogs`;
CREATE TABLE IF NOT EXISTS `blogs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `blog_category_id` bigint(20) unsigned NOT NULL,
  `slug` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `views` bigint(20) NOT NULL DEFAULT 0,
  `show_homepage` tinyint(1) NOT NULL DEFAULT 0,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `tags` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.blogs: ~0 rows (approximately)
DELETE FROM `blogs`;

-- Dumping structure for table topcommerce.blog_categories
DROP TABLE IF EXISTS `blog_categories`;
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `position` int(11) NOT NULL DEFAULT 0,
  `parent_id` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.blog_categories: ~0 rows (approximately)
DELETE FROM `blog_categories`;

-- Dumping structure for table topcommerce.blog_category_translations
DROP TABLE IF EXISTS `blog_category_translations`;
CREATE TABLE IF NOT EXISTS `blog_category_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog_category_id` bigint(20) unsigned NOT NULL,
  `lang_code` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blog_category_translations_blog_category_id_foreign` (`blog_category_id`),
  CONSTRAINT `blog_category_translations_blog_category_id_foreign` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.blog_category_translations: ~10 rows (approximately)
DELETE FROM `blog_category_translations`;

-- Dumping structure for table topcommerce.blog_comments
DROP TABLE IF EXISTS `blog_comments`;
CREATE TABLE IF NOT EXISTS `blog_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `blog_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `comment` text NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.blog_comments: ~0 rows (approximately)
DELETE FROM `blog_comments`;

-- Dumping structure for table topcommerce.blog_translations
DROP TABLE IF EXISTS `blog_translations`;
CREATE TABLE IF NOT EXISTS `blog_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` bigint(20) unsigned NOT NULL,
  `lang_code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `seo_title` text DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.blog_translations: ~0 rows (approximately)
DELETE FROM `blog_translations`;

-- Dumping structure for table topcommerce.brands
DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `icon` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.brands: ~0 rows (approximately)
DELETE FROM `brands`;

-- Dumping structure for table topcommerce.brand_translations
DROP TABLE IF EXISTS `brand_translations`;
CREATE TABLE IF NOT EXISTS `brand_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` bigint(20) unsigned NOT NULL,
  `lang_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `seo_title` text DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brand_translations_brand_id_lang_code_unique` (`brand_id`,`lang_code`),
  CONSTRAINT `brand_translations_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.brand_translations: ~0 rows (approximately)
DELETE FROM `brand_translations`;

-- Dumping structure for table topcommerce.carts
DROP TABLE IF EXISTS `carts`;
CREATE TABLE IF NOT EXISTS `carts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `rowid` varchar(255) NOT NULL,
  `items` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_foreign` (`user_id`),
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.carts: ~2 rows (approximately)
DELETE FROM `carts`;

-- Dumping structure for table topcommerce.categories
DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `type` enum('physical','digital') NOT NULL DEFAULT 'physical',
  `icon` varchar(255) DEFAULT NULL,
  `position` bigint(20) unsigned DEFAULT NULL,
  `is_searchable` tinyint(1) NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_top` tinyint(1) NOT NULL DEFAULT 0,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `is_trending` tinyint(1) NOT NULL DEFAULT 0,
  `theme` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.categories: ~0 rows (approximately)
DELETE FROM `categories`;

-- Dumping structure for table topcommerce.category_translations
DROP TABLE IF EXISTS `category_translations`;
CREATE TABLE IF NOT EXISTS `category_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `lang_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `seo_title` text DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_translations_category_id_lang_code_unique` (`category_id`,`lang_code`),
  CONSTRAINT `category_translations_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.category_translations: ~0 rows (approximately)
DELETE FROM `category_translations`;

-- Dumping structure for table topcommerce.cities
DROP TABLE IF EXISTS `cities`;
CREATE TABLE IF NOT EXISTS `cities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `state_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cities_state_id_foreign` (`state_id`),
  CONSTRAINT `cities_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.cities: ~0 rows (approximately)
DELETE FROM `cities`;

-- Dumping structure for table topcommerce.configurations
DROP TABLE IF EXISTS `configurations`;
CREATE TABLE IF NOT EXISTS `configurations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `config` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.configurations: ~2 rows (approximately)
DELETE FROM `configurations`;
INSERT INTO `configurations` (`id`, `config`, `value`, `created_at`, `updated_at`) VALUES
	(1, 'setup_stage', '1', '2024-12-03 17:13:51', '2025-08-07 03:51:48'),
	(2, 'setup_complete', '0', '2024-12-03 17:13:51', '2025-08-07 03:51:48');

-- Dumping structure for table topcommerce.contact_messages
DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.contact_messages: ~0 rows (approximately)
DELETE FROM `contact_messages`;

-- Dumping structure for table topcommerce.countries
DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.countries: ~0 rows (approximately)
DELETE FROM `countries`;

-- Dumping structure for table topcommerce.coupons
DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `author_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `coupon_code` varchar(255) NOT NULL,
  `discount` decimal(8,2) NOT NULL,
  `apply_for` enum('product','category','all') NOT NULL DEFAULT 'all',
  `minimum_spend` decimal(18,4) unsigned DEFAULT NULL,
  `usage_limit_per_coupon` bigint(20) unsigned DEFAULT NULL,
  `usage_limit_per_customer` int(10) unsigned DEFAULT NULL,
  `can_use_with_campaign` tinyint(1) NOT NULL DEFAULT 0,
  `free_shipping` tinyint(1) NOT NULL DEFAULT 0,
  `is_percent` tinyint(1) NOT NULL DEFAULT 1,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expired_date` timestamp NULL DEFAULT NULL,
  `is_never_expired` tinyint(1) NOT NULL DEFAULT 0,
  `used` int(10) unsigned NOT NULL DEFAULT 0,
  `show_homepage` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.coupons: ~0 rows (approximately)
DELETE FROM `coupons`;

-- Dumping structure for table topcommerce.coupon_categories
DROP TABLE IF EXISTS `coupon_categories`;
CREATE TABLE IF NOT EXISTS `coupon_categories` (
  `coupon_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `exclude` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`coupon_id`,`category_id`,`exclude`),
  KEY `coupon_categories_category_id_foreign` (`category_id`),
  CONSTRAINT `coupon_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `coupon_categories_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.coupon_categories: ~0 rows (approximately)
DELETE FROM `coupon_categories`;

-- Dumping structure for table topcommerce.coupon_histories
DROP TABLE IF EXISTS `coupon_histories`;
CREATE TABLE IF NOT EXISTS `coupon_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `author_id` int(11) DEFAULT 0,
  `user_id` int(11) DEFAULT 0,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `coupon_code` varchar(255) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `discount_amount` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.coupon_histories: ~0 rows (approximately)
DELETE FROM `coupon_histories`;

-- Dumping structure for table topcommerce.coupon_products
DROP TABLE IF EXISTS `coupon_products`;
CREATE TABLE IF NOT EXISTS `coupon_products` (
  `coupon_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `exclude` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`coupon_id`,`product_id`),
  KEY `coupon_products_product_id_foreign` (`product_id`),
  CONSTRAINT `coupon_products_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `coupon_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.coupon_products: ~0 rows (approximately)
DELETE FROM `coupon_products`;

-- Dumping structure for table topcommerce.coupon_translations
DROP TABLE IF EXISTS `coupon_translations`;
CREATE TABLE IF NOT EXISTS `coupon_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `lang_code` varchar(3) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.coupon_translations: ~30 rows (approximately)
DELETE FROM `coupon_translations`;

-- Dumping structure for table topcommerce.cross_sell_products
DROP TABLE IF EXISTS `cross_sell_products`;
CREATE TABLE IF NOT EXISTS `cross_sell_products` (
  `product_id` bigint(20) unsigned NOT NULL,
  `cross_sell_product_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`cross_sell_product_id`),
  KEY `cross_sell_products_cross_sell_product_id_foreign` (`cross_sell_product_id`),
  CONSTRAINT `cross_sell_products_cross_sell_product_id_foreign` FOREIGN KEY (`cross_sell_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cross_sell_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.cross_sell_products: ~0 rows (approximately)
DELETE FROM `cross_sell_products`;

-- Dumping structure for table topcommerce.customizable_page_translations
DROP TABLE IF EXISTS `customizable_page_translations`;
CREATE TABLE IF NOT EXISTS `customizable_page_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customizeable_page_id` bigint(20) unsigned NOT NULL,
  `lang_code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customizable_page_translations_customizeable_page_id_index` (`customizeable_page_id`),
  KEY `customizable_page_translations_lang_code_index` (`lang_code`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.customizable_page_translations: ~10 rows (approximately)
DELETE FROM `customizable_page_translations`;
INSERT INTO `customizable_page_translations` (`id`, `customizeable_page_id`, `lang_code`, `title`, `description`, `created_at`, `updated_at`) VALUES
	(1, 1, 'en', 'Terms & Conditions', '<h3 class="title">Who we are</h3>\n                    <p><b>Suggested text:</b> Our website address is: https://yourwebsite.com</p>\n                    <h3 class="title">Comments</h3>\n                    <p><b>Suggested text:</b> When visitors leave comments on the site we collect the data shown\n                        in the comments form, and also the visitor’s IP address and browser user agent string to\n                        help spam detection.</p>\n                    <p>An anonymized string created from your email address (also called a hash) may be provided\n                        to the Gravatar service to see if you are using it. The Gravatar service privacy policy\n                        is available here: https://automattic.com/privacy/. After approval of your comment, your\n                        profile picture is visible to the public in the context of your comment.</p>\n                    <h3 class="title">Media</h3>\n                    <p><b>Suggested text:</b> If you upload images to the website, you should avoid uploading\n                        images with embedded location data (EXIF GPS) included. Visitors to the website can\n                        download and extract any location data from images on the website.</p>\n                    <h3 class="title">Cookies</h3>\n                    <p><b>Suggested text:</b> If you leave a comment on our site you may opt-in to saving your\n                        name, email address and website in\n                        cookies. These are for your convenience so that you do not have to fill in your details\n                        again when you leave another\n                        comment. These cookies will last for one year.</p>\n                    <p>If you visit our login page, we will set a temporary cookie to determine if your browser\n                        accepts cookies. This cookie\n                        contains no personal data and is discarded when you close your browser.</p>\n                    <p>When you log in, we will also set up several cookies to save your login information and\n                        your screen display choices.\n                        Login cookies last for two days, and screen options cookies last for a year. If you\n                        select "Remember Me", your login\n                        will persist for two weeks. If you log out of your account, the login cookies will be\n                        removed.</p>\n                    <p>If you edit or publish an article, an additional cookie will be saved in your browser.\n                        This cookie includes no personal\n                        data and simply indicates the post ID of the article you just edited. It expires after 1\n                        day.</p>\n                    <h3 class="title">Embedded content from other websites</h3>\n                    <p><b>Suggested text:</b> Articles on this site may include embedded content (e.g. videos,\n                        images, articles, etc.). Embedded\n                        content from other websites behaves in the exact same way as if the visitor has visited\n                        the other website.</p>\n                    <p>These websites may collect data about you, use cookies, embed additional third-party\n                        tracking, and monitor your\n                        interaction with that embedded content, including tracking your interaction with the\n                        embedded content if you have an\n                        account and are logged in to that website.</p>\n                    <p>For users that register on our website (if any), we also store the personal information\n                        they provide in their user\n                        profile. All users can see, edit, or delete their personal information at any time\n                        (except they cannot change their\n                        username). Website administrators can also see and edit that information. browser user\n                        agent string to help spam detection.</p>', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(2, 1, 'ar', 'Terms & Conditions', '<h3 class="title">Who we are</h3>\n                    <p><b>Suggested text:</b> Our website address is: https://yourwebsite.com</p>\n                    <h3 class="title">Comments</h3>\n                    <p><b>Suggested text:</b> When visitors leave comments on the site we collect the data shown\n                        in the comments form, and also the visitor’s IP address and browser user agent string to\n                        help spam detection.</p>\n                    <p>An anonymized string created from your email address (also called a hash) may be provided\n                        to the Gravatar service to see if you are using it. The Gravatar service privacy policy\n                        is available here: https://automattic.com/privacy/. After approval of your comment, your\n                        profile picture is visible to the public in the context of your comment.</p>\n                    <h3 class="title">Media</h3>\n                    <p><b>Suggested text:</b> If you upload images to the website, you should avoid uploading\n                        images with embedded location data (EXIF GPS) included. Visitors to the website can\n                        download and extract any location data from images on the website.</p>\n                    <h3 class="title">Cookies</h3>\n                    <p><b>Suggested text:</b> If you leave a comment on our site you may opt-in to saving your\n                        name, email address and website in\n                        cookies. These are for your convenience so that you do not have to fill in your details\n                        again when you leave another\n                        comment. These cookies will last for one year.</p>\n                    <p>If you visit our login page, we will set a temporary cookie to determine if your browser\n                        accepts cookies. This cookie\n                        contains no personal data and is discarded when you close your browser.</p>\n                    <p>When you log in, we will also set up several cookies to save your login information and\n                        your screen display choices.\n                        Login cookies last for two days, and screen options cookies last for a year. If you\n                        select "Remember Me", your login\n                        will persist for two weeks. If you log out of your account, the login cookies will be\n                        removed.</p>\n                    <p>If you edit or publish an article, an additional cookie will be saved in your browser.\n                        This cookie includes no personal\n                        data and simply indicates the post ID of the article you just edited. It expires after 1\n                        day.</p>\n                    <h3 class="title">Embedded content from other websites</h3>\n                    <p><b>Suggested text:</b> Articles on this site may include embedded content (e.g. videos,\n                        images, articles, etc.). Embedded\n                        content from other websites behaves in the exact same way as if the visitor has visited\n                        the other website.</p>\n                    <p>These websites may collect data about you, use cookies, embed additional third-party\n                        tracking, and monitor your\n                        interaction with that embedded content, including tracking your interaction with the\n                        embedded content if you have an\n                        account and are logged in to that website.</p>\n                    <p>For users that register on our website (if any), we also store the personal information\n                        they provide in their user\n                        profile. All users can see, edit, or delete their personal information at any time\n                        (except they cannot change their\n                        username). Website administrators can also see and edit that information. browser user\n                        agent string to help spam detection.</p>', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(3, 2, 'en', 'Privacy Policy', '<h3 class="title">Who we are</h3>\n                    <p><b>Suggested text:</b> Our website address is: https://yourwebsite.com</p>\n                    <h3 class="title">Comments</h3>\n                    <p><b>Suggested text:</b> When visitors leave comments on the site we collect the data shown\n                        in the comments form, and also the visitor’s IP address and browser user agent string to\n                        help spam detection.</p>\n                    <p>An anonymized string created from your email address (also called a hash) may be provided\n                        to the Gravatar service to see if you are using it. The Gravatar service privacy policy\n                        is available here: https://automattic.com/privacy/. After approval of your comment, your\n                        profile picture is visible to the public in the context of your comment.</p>\n                    <h3 class="title">Media</h3>\n                    <p><b>Suggested text:</b> If you upload images to the website, you should avoid uploading\n                        images with embedded location data (EXIF GPS) included. Visitors to the website can\n                        download and extract any location data from images on the website.</p>\n                    <h3 class="title">Cookies</h3>\n                    <p><b>Suggested text:</b> If you leave a comment on our site you may opt-in to saving your\n                        name, email address and website in\n                        cookies. These are for your convenience so that you do not have to fill in your details\n                        again when you leave another\n                        comment. These cookies will last for one year.</p>\n                    <p>If you visit our login page, we will set a temporary cookie to determine if your browser\n                        accepts cookies. This cookie\n                        contains no personal data and is discarded when you close your browser.</p>\n                    <p>When you log in, we will also set up several cookies to save your login information and\n                        your screen display choices.\n                        Login cookies last for two days, and screen options cookies last for a year. If you\n                        select "Remember Me", your login\n                        will persist for two weeks. If you log out of your account, the login cookies will be\n                        removed.</p>\n                    <p>If you edit or publish an article, an additional cookie will be saved in your browser.\n                        This cookie includes no personal\n                        data and simply indicates the post ID of the article you just edited. It expires after 1\n                        day.</p>\n                    <h3 class="title">Embedded content from other websites</h3>\n                    <p><b>Suggested text:</b> Articles on this site may include embedded content (e.g. videos,\n                        images, articles, etc.). Embedded\n                        content from other websites behaves in the exact same way as if the visitor has visited\n                        the other website.</p>\n                    <p>These websites may collect data about you, use cookies, embed additional third-party\n                        tracking, and monitor your\n                        interaction with that embedded content, including tracking your interaction with the\n                        embedded content if you have an\n                        account and are logged in to that website.</p>\n                    <p>For users that register on our website (if any), we also store the personal information\n                        they provide in their user\n                        profile. All users can see, edit, or delete their personal information at any time\n                        (except they cannot change their\n                        username). Website administrators can also see and edit that information. browser user\n                        agent string to help spam detection.</p>', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(4, 2, 'ar', 'Privacy Policy', '<h3 class="title">Who we are</h3>\n                    <p><b>Suggested text:</b> Our website address is: https://yourwebsite.com</p>\n                    <h3 class="title">Comments</h3>\n                    <p><b>Suggested text:</b> When visitors leave comments on the site we collect the data shown\n                        in the comments form, and also the visitor’s IP address and browser user agent string to\n                        help spam detection.</p>\n                    <p>An anonymized string created from your email address (also called a hash) may be provided\n                        to the Gravatar service to see if you are using it. The Gravatar service privacy policy\n                        is available here: https://automattic.com/privacy/. After approval of your comment, your\n                        profile picture is visible to the public in the context of your comment.</p>\n                    <h3 class="title">Media</h3>\n                    <p><b>Suggested text:</b> If you upload images to the website, you should avoid uploading\n                        images with embedded location data (EXIF GPS) included. Visitors to the website can\n                        download and extract any location data from images on the website.</p>\n                    <h3 class="title">Cookies</h3>\n                    <p><b>Suggested text:</b> If you leave a comment on our site you may opt-in to saving your\n                        name, email address and website in\n                        cookies. These are for your convenience so that you do not have to fill in your details\n                        again when you leave another\n                        comment. These cookies will last for one year.</p>\n                    <p>If you visit our login page, we will set a temporary cookie to determine if your browser\n                        accepts cookies. This cookie\n                        contains no personal data and is discarded when you close your browser.</p>\n                    <p>When you log in, we will also set up several cookies to save your login information and\n                        your screen display choices.\n                        Login cookies last for two days, and screen options cookies last for a year. If you\n                        select "Remember Me", your login\n                        will persist for two weeks. If you log out of your account, the login cookies will be\n                        removed.</p>\n                    <p>If you edit or publish an article, an additional cookie will be saved in your browser.\n                        This cookie includes no personal\n                        data and simply indicates the post ID of the article you just edited. It expires after 1\n                        day.</p>\n                    <h3 class="title">Embedded content from other websites</h3>\n                    <p><b>Suggested text:</b> Articles on this site may include embedded content (e.g. videos,\n                        images, articles, etc.). Embedded\n                        content from other websites behaves in the exact same way as if the visitor has visited\n                        the other website.</p>\n                    <p>These websites may collect data about you, use cookies, embed additional third-party\n                        tracking, and monitor your\n                        interaction with that embedded content, including tracking your interaction with the\n                        embedded content if you have an\n                        account and are logged in to that website.</p>\n                    <p>For users that register on our website (if any), we also store the personal information\n                        they provide in their user\n                        profile. All users can see, edit, or delete their personal information at any time\n                        (except they cannot change their\n                        username). Website administrators can also see and edit that information. browser user\n                        agent string to help spam detection.</p>', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(5, 3, 'en', 'Return Policy', '<h3 class="title">Who we are</h3>\n                    <p><b>Suggested text:</b> Our website address is: https://yourwebsite.com</p>\n                    <h3 class="title">Comments</h3>\n                    <p><b>Suggested text:</b> When visitors leave comments on the site we collect the data shown\n                        in the comments form, and also the visitor’s IP address and browser user agent string to\n                        help spam detection.</p>\n                    <p>An anonymized string created from your email address (also called a hash) may be provided\n                        to the Gravatar service to see if you are using it. The Gravatar service privacy policy\n                        is available here: https://automattic.com/privacy/. After approval of your comment, your\n                        profile picture is visible to the public in the context of your comment.</p>\n                    <h3 class="title">Media</h3>\n                    <p><b>Suggested text:</b> If you upload images to the website, you should avoid uploading\n                        images with embedded location data (EXIF GPS) included. Visitors to the website can\n                        download and extract any location data from images on the website.</p>\n                    <h3 class="title">Cookies</h3>\n                    <p><b>Suggested text:</b> If you leave a comment on our site you may opt-in to saving your\n                        name, email address and website in\n                        cookies. These are for your convenience so that you do not have to fill in your details\n                        again when you leave another\n                        comment. These cookies will last for one year.</p>\n                    <p>If you visit our login page, we will set a temporary cookie to determine if your browser\n                        accepts cookies. This cookie\n                        contains no personal data and is discarded when you close your browser.</p>\n                    <p>When you log in, we will also set up several cookies to save your login information and\n                        your screen display choices.\n                        Login cookies last for two days, and screen options cookies last for a year. If you\n                        select "Remember Me", your login\n                        will persist for two weeks. If you log out of your account, the login cookies will be\n                        removed.</p>\n                    <p>If you edit or publish an article, an additional cookie will be saved in your browser.\n                        This cookie includes no personal\n                        data and simply indicates the post ID of the article you just edited. It expires after 1\n                        day.</p>\n                    <h3 class="title">Embedded content from other websites</h3>\n                    <p><b>Suggested text:</b> Articles on this site may include embedded content (e.g. videos,\n                        images, articles, etc.). Embedded\n                        content from other websites behaves in the exact same way as if the visitor has visited\n                        the other website.</p>\n                    <p>These websites may collect data about you, use cookies, embed additional third-party\n                        tracking, and monitor your\n                        interaction with that embedded content, including tracking your interaction with the\n                        embedded content if you have an\n                        account and are logged in to that website.</p>\n                    <p>For users that register on our website (if any), we also store the personal information\n                        they provide in their user\n                        profile. All users can see, edit, or delete their personal information at any time\n                        (except they cannot change their\n                        username). Website administrators can also see and edit that information. browser user\n                        agent string to help spam detection.</p>', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(6, 3, 'ar', 'Return Policy', '<h3 class="title">Who we are</h3>\n                    <p><b>Suggested text:</b> Our website address is: https://yourwebsite.com</p>\n                    <h3 class="title">Comments</h3>\n                    <p><b>Suggested text:</b> When visitors leave comments on the site we collect the data shown\n                        in the comments form, and also the visitor’s IP address and browser user agent string to\n                        help spam detection.</p>\n                    <p>An anonymized string created from your email address (also called a hash) may be provided\n                        to the Gravatar service to see if you are using it. The Gravatar service privacy policy\n                        is available here: https://automattic.com/privacy/. After approval of your comment, your\n                        profile picture is visible to the public in the context of your comment.</p>\n                    <h3 class="title">Media</h3>\n                    <p><b>Suggested text:</b> If you upload images to the website, you should avoid uploading\n                        images with embedded location data (EXIF GPS) included. Visitors to the website can\n                        download and extract any location data from images on the website.</p>\n                    <h3 class="title">Cookies</h3>\n                    <p><b>Suggested text:</b> If you leave a comment on our site you may opt-in to saving your\n                        name, email address and website in\n                        cookies. These are for your convenience so that you do not have to fill in your details\n                        again when you leave another\n                        comment. These cookies will last for one year.</p>\n                    <p>If you visit our login page, we will set a temporary cookie to determine if your browser\n                        accepts cookies. This cookie\n                        contains no personal data and is discarded when you close your browser.</p>\n                    <p>When you log in, we will also set up several cookies to save your login information and\n                        your screen display choices.\n                        Login cookies last for two days, and screen options cookies last for a year. If you\n                        select "Remember Me", your login\n                        will persist for two weeks. If you log out of your account, the login cookies will be\n                        removed.</p>\n                    <p>If you edit or publish an article, an additional cookie will be saved in your browser.\n                        This cookie includes no personal\n                        data and simply indicates the post ID of the article you just edited. It expires after 1\n                        day.</p>\n                    <h3 class="title">Embedded content from other websites</h3>\n                    <p><b>Suggested text:</b> Articles on this site may include embedded content (e.g. videos,\n                        images, articles, etc.). Embedded\n                        content from other websites behaves in the exact same way as if the visitor has visited\n                        the other website.</p>\n                    <p>These websites may collect data about you, use cookies, embed additional third-party\n                        tracking, and monitor your\n                        interaction with that embedded content, including tracking your interaction with the\n                        embedded content if you have an\n                        account and are logged in to that website.</p>\n                    <p>For users that register on our website (if any), we also store the personal information\n                        they provide in their user\n                        profile. All users can see, edit, or delete their personal information at any time\n                        (except they cannot change their\n                        username). Website administrators can also see and edit that information. browser user\n                        agent string to help spam detection.</p>', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(7, 4, 'en', 'Join a seller', '<h3>Become an seller</h3>\n                        <p>It is a long established fact that a reader will be distracted by the readable content of a\n                            page when looking at its layout. The point of using Lorem Ipsum is that it has a\n                            more-or-less normal distribution of letters, as opposed to using \'Content here, content\n                            here, making it look like readable English.</p>\n\n                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia\n                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam\n                            est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non\n                            numquam eius modi tempora incidunt ut labore.</p>\n\n                        <h3>Seller Rules</h3>\n                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia\n                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam\n                            est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non\n                            numquam eius modi tempora incidunt ut labore.</p>\n                        <ul>\n                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>\n                            <li>Details Idea about HTMLS, Creating Basic Web Pages using HTMLS</li>\n                            <li>Web Page Layout Design and Slider Creation</li>\n                            <li>Image Insert method af web site</li>\n                            <li>Creating Styling Web Pages Using CSS3</li>\n                        </ul>\n                        <h3>Start With Courses</h3>\n                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia\n                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam\n                            est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non\n                            numquam eius modi tempora incidunt ut labore.</p>\n                        <ul>\n                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>\n                            <li>Details Idea about HTMLS, Creating Basic Web Pages using HTMLS</li>\n                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>\n                            <li>Web Page Layout Design and Slider Creation</li>\n                        </ul>\n                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia\n                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>\n                        <h3>vendor Rules</h3>\n                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia\n                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam\n                            est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non\n                            numquam eius modi tempora incidunt ut labore.</p>\n                        <ul>\n                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>\n                            <li>Details Idea about HTMLS, Creating Basic Web Pages using HTMLS</li>\n                            <li>Web Page Layout Design and Slider Creation</li>\n                            <li>Image Insert method af web site</li>\n                            <li>Creating Styling Web Pages Using CSS3</li>\n                        </ul>\n                        <h3>Start With Products</h3>\n                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia\n                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam\n                            est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non\n                            numquam eius modi tempora incidunt ut labore.</p>\n                        <ul>\n                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>\n                            <li>Details Idea about HTMLS, Creating Basic Web Pages using HTMLS</li>\n                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>\n                            <li>Web Page Layout Design and Slider Creation</li>\n                        </ul>\n                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia\n                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(8, 4, 'ar', 'Join a seller', '<h3>Become an seller</h3>\n                        <p>It is a long established fact that a reader will be distracted by the readable content of a\n                            page when looking at its layout. The point of using Lorem Ipsum is that it has a\n                            more-or-less normal distribution of letters, as opposed to using \'Content here, content\n                            here, making it look like readable English.</p>\n\n                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia\n                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam\n                            est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non\n                            numquam eius modi tempora incidunt ut labore.</p>\n\n                        <h3>Seller Rules</h3>\n                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia\n                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam\n                            est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non\n                            numquam eius modi tempora incidunt ut labore.</p>\n                        <ul>\n                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>\n                            <li>Details Idea about HTMLS, Creating Basic Web Pages using HTMLS</li>\n                            <li>Web Page Layout Design and Slider Creation</li>\n                            <li>Image Insert method af web site</li>\n                            <li>Creating Styling Web Pages Using CSS3</li>\n                        </ul>\n                        <h3>Start With Courses</h3>\n                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia\n                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam\n                            est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non\n                            numquam eius modi tempora incidunt ut labore.</p>\n                        <ul>\n                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>\n                            <li>Details Idea about HTMLS, Creating Basic Web Pages using HTMLS</li>\n                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>\n                            <li>Web Page Layout Design and Slider Creation</li>\n                        </ul>\n                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia\n                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>\n                        <h3>vendor Rules</h3>\n                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia\n                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam\n                            est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non\n                            numquam eius modi tempora incidunt ut labore.</p>\n                        <ul>\n                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>\n                            <li>Details Idea about HTMLS, Creating Basic Web Pages using HTMLS</li>\n                            <li>Web Page Layout Design and Slider Creation</li>\n                            <li>Image Insert method af web site</li>\n                            <li>Creating Styling Web Pages Using CSS3</li>\n                        </ul>\n                        <h3>Start With Products</h3>\n                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia\n                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam\n                            est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non\n                            numquam eius modi tempora incidunt ut labore.</p>\n                        <ul>\n                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>\n                            <li>Details Idea about HTMLS, Creating Basic Web Pages using HTMLS</li>\n                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>\n                            <li>Web Page Layout Design and Slider Creation</li>\n                        </ul>\n                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia\n                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>', '2025-08-07 03:49:19', '2025-08-07 03:49:19');

-- Dumping structure for table topcommerce.customizeable_pages
DROP TABLE IF EXISTS `customizeable_pages`;
CREATE TABLE IF NOT EXISTS `customizeable_pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customizeable_pages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.customizeable_pages: ~5 rows (approximately)
DELETE FROM `customizeable_pages`;
INSERT INTO `customizeable_pages` (`id`, `slug`, `icon`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'terms-contidions', NULL, 1, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(2, 'privacy-policy', NULL, 1, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(3, 'return-policy', NULL, 1, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(4, 'join-as-seller', NULL, 1, '2025-08-07 03:49:19', '2025-08-07 03:49:19');

-- Dumping structure for table topcommerce.custom_addons
DROP TABLE IF EXISTS `custom_addons`;
CREATE TABLE IF NOT EXISTS `custom_addons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `isPaid` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `author` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`author`)),
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `icon` varchar(255) DEFAULT NULL,
  `license` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `last_update` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_addons_name_index` (`name`),
  KEY `idx_custom_addons_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.custom_addons: ~0 rows (approximately)
DELETE FROM `custom_addons`;

-- Dumping structure for table topcommerce.custom_codes
DROP TABLE IF EXISTS `custom_codes`;
CREATE TABLE IF NOT EXISTS `custom_codes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `css` text DEFAULT NULL,
  `header_javascript` text DEFAULT NULL,
  `body_javascript` text DEFAULT NULL,
  `footer_javascript` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.custom_codes: ~0 rows (approximately)
DELETE FROM `custom_codes`;

-- Dumping structure for table topcommerce.custom_paginations
DROP TABLE IF EXISTS `custom_paginations`;
CREATE TABLE IF NOT EXISTS `custom_paginations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section_name` varchar(255) NOT NULL,
  `item_qty` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.custom_paginations: ~11 rows (approximately)
DELETE FROM `custom_paginations`;
INSERT INTO `custom_paginations` (`id`, `section_name`, `item_qty`, `created_at`, `updated_at`) VALUES
	(1, 'Blog List', 10, '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(2, 'Blog Comment', 10, '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(3, 'Language List', 10, '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(4, 'category_list', 24, '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(5, 'brand_list', 24, '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(6, 'product_list', 18, '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(7, 'shop_list', 18, '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(8, 'shop_product_list', 9, '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(9, 'user_wishlist', 9, '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(10, 'user_reviews', 9, '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(11, 'user_orders', 10, '2025-08-07 03:49:14', '2025-08-07 03:49:14');

-- Dumping structure for table topcommerce.email_templates
DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.email_templates: ~22 rows (approximately)
DELETE FROM `email_templates`;
INSERT INTO `email_templates` (`id`, `name`, `subject`, `message`, `created_at`, `updated_at`) VALUES
	(1, 'password_changed', 'Password Changed', '<p>Dear {{user_name}},</p>\n                <p>Your password has been changed successfully.</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(2, 'email_changed', 'Email Changed', '<p>Dear {{user_name}},</p>\n                <p>Your email has been changed successfully. Please Click the following link and Verified Your Email. Your new email is {{email}}</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(3, 'password_reset', 'Password Reset', '<p>Dear {{user_name}},</p>\n                <p>Do you want to reset your password? Please Click the following link and Reset Your Password.</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(4, 'contact_mail', 'Contact Email', '<p>Hello there,</p>\n                <p>&nbsp;Mr. {{name}} has sent a new message. you can see the message details below.&nbsp;</p>\n                <p>Email: {{email}}</p>\n                <p>Phone: {{phone}}</p>\n                <p>Subject: {{subject}}</p>\n                <p>Message: {{message}}</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(5, 'subscribe_notification', 'Subscribe Notification', '<p>Hi there, Congratulations! Your Subscription has been created successfully. Please Click the following link and Verified Your Subscription. If you will not approve this link, you can not get any newsletter from us.</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(6, 'social_login', 'Social Login', '<p>Hello {{user_name}},</p>\n                <p>Welcome to {{app_name}}! Your account has been created successfully.</p>\n                <p>Your password: {{password}}</p>\n                <p>You can log in to your account at <a href="https://websolutionus.com">https://websolutionus.com</a></p>\n                <p>Thank you for joining us.</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(7, 'user_verification', 'User Verification', '<p>Dear {{user_name}},</p>\n                <p>Congratulations! Your account has been created successfully. Please click the following link to activate your account.</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(8, 'shop_verification', 'Vendor Shop Verification', '<p>Dear {{shop_name}},</p>\n                <p>Congratulations! Your shop has been created successfully. Please click the following link to activate your shop.</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(9, 'shop_verification_complete', 'Shop Verification Completed', '<p>Dear {{shop_name}},</p>\n                <p>Congratulations! Your shop has been verified successfully. Now you can start selling your products.</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(10, 'order_placed', 'Order Placed', '<p>Hello {{user_name}},</p>\n                <p>Your order has been placed successfully. Your order id is: #{{order_id}}</p>\n                <p>Order Status: {{order_status}}</p>\n                <p>Amount To Pay: {{amount}} {{amount_currency}}</p>\n                <p>Payment Method: {{payment_method}}</p>\n                <p>Payment Status: {{payment_status}}</p>\n                <p>Shipping Address: {{shipping_address}}</p>\n                <p>Thanks &amp; Regards</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(11, 'order_status_update', 'Order Status Update', '<p>Hello {{user_name}},</p>\n                <p>Your order <b>#{{order_id}}</b> status has been changed to {{order_status}}</p>\n                <p>Payment: {{amount}} {{amount_currency}}</p>\n                <p>Payment Method: {{payment_method}}</p>\n                <p>Payment Status: {{payment_status}}</p>\n                <p>Shipping Address: {{shipping_address}}</p>\n                <p>Thanks &amp; Regards</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(12, 'order_payment_status_update', 'Order Payment Status Update', '<p>Hello {{user_name}},</p>\n                <p>Your order <b>#{{order_id}}</b> payment status has been changed to {{payment_status}}</p>\n                <p>Order Status: {{order_status}}</p>\n                <p>Payment: {{amount}} {{amount_currency}}</p>\n                <p>Payment Method: {{payment_method}}</p>\n                <p>Shipping Address: {{shipping_address}}</p>\n                <p>Thanks &amp; Regards</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(13, 'seller_order_status_update', 'Order Status Update by Admin', '<p>Hello {{user_name}},</p>\n                <p>A order on your shop with order id <b>#{{order_id}}</b> status has been changed to {{order_status}} by admin</p>\n                <p>Payment: {{amount}} {{amount_currency}}</p>\n                <p>Payment Method: {{payment_method}}</p>\n                <p>Payment Status: {{payment_status}}</p>\n                <p>Shipping Address: {{shipping_address}}</p>\n                <p>Thanks &amp; Regards</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(14, 'order_payment_confirmed', 'Order Payment Confirmed', '<p>Hello {{user_name}},</p>\n                <p>Your order <b>#{{order_id}}</b> payment has been confirmed</p>\n                <p>Payment: {{amount}} {{amount_currency}}</p>\n                <p>Payment Method: {{payment_method}}</p>\n                <p>Payment Status: {{payment_status}}</p>\n                <p>Shipping Address: {{shipping_address}}</p>\n                <p>Billing Address: {{billing_address}}</p>\n                <p>Thanks &amp; Regards</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(15, 'requested_withdraw', 'Withdraw Request Received', '<p>Dear {{user_name}},</p>\n                <p>We are happy to say that, we have received your withdraw request.</p>\n                <p>Thanks &amp; Regards</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(16, 'approved_withdraw', 'Withdraw Request Approval', '<p>Dear {{user_name}},</p>\n                <p>We are happy to say that, we have send a withdraw amount {{amount}} to your provided {{method}} payment method information.</p>\n                <p>Thanks &amp; Regards</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(17, 'product_approved', 'Your Product Approved', '<p>Dear {{shop_name}},</p>\n                <p>Congratulations! Your product <b>{{product_name}}</b> has been approved successfully. Now you can start selling your products. Check the product details at from the links below.</p>\n                <p>Thanks &amp; Regards</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(18, 'kyc_approved', 'Your ID Verification Approved', '<p>Dear {{shop_name}},</p>\n                <p>Congratulations! Your ID verification has been approved successfully. Now you can start selling your products.</p>\n                <p>Thanks &amp; Regards</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(19, 'wallet_request_approved', 'Wallet Request Approved', '<p>Dear {{shop_name}},</p>\n                <p>Congratulations! We have added {{amount}} to your wallet. Check your wallet balance at from the links below.</p>\n                <p>Thanks &amp; Regards</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(20, 'commission_received', 'Commission Received', '<p>Dear {{shop_name}},</p>\n                <p>Congratulations! You received a commission of {{amount}} for your product <b>{{product_name}}</b>. Check the product details at from the links below.</p>\n                <p>Thanks &amp; Regards</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(21, 'seller_deleted', 'Seller Profile Deleted', '<p>Dear {{shop_name}},</p>\n                <p>Your seller profile has been deleted successfully. Now you can not sell your products. But you can use this id as a regular user.</p>\n                <p>Thanks &amp; Regards</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(22, 'order_placed_vendor', 'New Order Placed on Your Shop', '<p>Hello {{user_name}},</p>\n                <p>Your order has been placed to your {{shop_name}}. Your order id is: #{{order_id}}</p>\n                <p>Order Status: {{order_status}}</p>\n                <p>Amount To Pay: {{amount}} {{amount_currency}}</p>\n                <p>Payment Method: {{payment_method}}</p>\n                <p>Payment Status: {{payment_status}}</p>\n                <p>Shipping Address: {{shipping_address}}</p>\n                <p>Thanks &amp; Regards</p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14');

-- Dumping structure for table topcommerce.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table topcommerce.faqs
DROP TABLE IF EXISTS `faqs`;
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `group` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.faqs: ~0 rows (approximately)
DELETE FROM `faqs`;

-- Dumping structure for table topcommerce.faq_translations
DROP TABLE IF EXISTS `faq_translations`;
CREATE TABLE IF NOT EXISTS `faq_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `faq_id` bigint(20) unsigned NOT NULL,
  `lang_code` varchar(255) NOT NULL,
  `question` varchar(255) DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.faq_translations: ~60 rows (approximately)
DELETE FROM `faq_translations`;

-- Dumping structure for table topcommerce.galleries
DROP TABLE IF EXISTS `galleries`;
CREATE TABLE IF NOT EXISTS `galleries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `galleries_product_id_foreign` (`product_id`),
  CONSTRAINT `galleries_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.galleries: ~0 rows (approximately)
DELETE FROM `galleries`;

-- Dumping structure for table topcommerce.homes
DROP TABLE IF EXISTS `homes`;
CREATE TABLE IF NOT EXISTS `homes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `homes_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.homes: ~4 rows (approximately)
DELETE FROM `homes`;
INSERT INTO `homes` (`id`, `slug`, `created_at`, `updated_at`) VALUES
	(1, '1', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(2, '2', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(3, '3', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(4, '4', '2025-08-07 03:49:15', '2025-08-07 03:49:15');

-- Dumping structure for table topcommerce.jobs
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table topcommerce.kyc_information
DROP TABLE IF EXISTS `kyc_information`;
CREATE TABLE IF NOT EXISTS `kyc_information` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kyc_type_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `vendor_id` bigint(20) unsigned DEFAULT NULL,
  `admin_id` bigint(20) unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0: Pending, 1: Approved, 2: Rejected',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kyc_information_user_id_foreign` (`user_id`),
  CONSTRAINT `kyc_information_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table topcommerce.kyc_types
DROP TABLE IF EXISTS `kyc_types`;
CREATE TABLE IF NOT EXISTS `kyc_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kyc_types_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.kyc_types: ~0 rows (approximately)
DELETE FROM `kyc_types`;

-- Dumping structure for table topcommerce.languages
DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `direction` varchar(255) NOT NULL DEFAULT 'ltr',
  `status` varchar(255) NOT NULL DEFAULT '1',
  `is_default` varchar(255) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `languages_name_unique` (`name`),
  UNIQUE KEY `languages_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.languages: ~2 rows (approximately)
DELETE FROM `languages`;
INSERT INTO `languages` (`id`, `name`, `code`, `direction`, `status`, `is_default`, `created_at`, `updated_at`) VALUES
	(1, 'English', 'en', 'ltr', '1', '1', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(2, 'Arabic', 'ar', 'rtl', '1', '0', '2025-08-07 03:49:14', '2025-08-07 03:49:14');

-- Dumping structure for table topcommerce.menus
DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.menus: ~5 rows (approximately)
DELETE FROM `menus`;
INSERT INTO `menus` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
	(1, 'Main Menu', 'main-menu', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(2, 'Footer Menu One', 'footer-menu-1', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(3, 'Footer Menu Two', 'footer-menu-2', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(4, 'Footer User Menu', 'footer-menu-3', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(5, 'Footer Vendor Menu', 'footer-menu-4', '2025-08-07 03:49:19', '2025-08-07 03:49:19');

-- Dumping structure for table topcommerce.menu_items
DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `parent_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0,
  `menu_id` bigint(20) unsigned NOT NULL,
  `custom_item` tinyint(1) NOT NULL DEFAULT 0,
  `open_new_tab` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_items_menu_id_foreign` (`menu_id`),
  CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.menu_items: ~37 rows (approximately)
DELETE FROM `menu_items`;
INSERT INTO `menu_items` (`id`, `label`, `link`, `parent_id`, `sort`, `menu_id`, `custom_item`, `open_new_tab`, `created_at`, `updated_at`) VALUES
	(1, 'Home', '/', 0, 0, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(2, 'Shops', '/shops', 0, 1, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(3, 'Products', '/products', 0, 3, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(4, 'Categories', '/categories', 0, 4, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(5, 'Contact Us', '/contact-us', 0, 5, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(6, 'Pages', '#', 0, 6, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(7, 'About Us', '/about-us', 6, 1, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(8, 'Privacy Policy', '/privacy-policy', 6, 2, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(9, 'Terms and Conditions', '/terms-and-conditions', 6, 3, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(10, 'Return Policy', '/return-policy', 6, 4, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(11, 'FAQ', '/faq', 6, 5, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(12, 'Join as Seller', '/join-as-seller', 6, 5, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(13, 'Brands', '/brands', 6, 6, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(14, 'Blogs', '/blogs', 6, 6, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(15, 'Track Order', '/track-order', 6, 7, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(16, 'Flash Deals', '/flash-deals', 6, 8, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(17, 'Gift Cards', '/gift-cards', 6, 9, 1, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(18, 'About Us', '/about-us', 0, 0, 2, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(19, 'Privacy Policy', '/privacy-policy', 0, 2, 2, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(20, 'Terms and Conditions', '/terms-and-conditions', 0, 3, 2, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(21, 'Return Policy', '/return-policy', 0, 4, 2, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(22, 'FAQ', '/faq', 0, 5, 2, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(23, 'Login', '/login', 0, 1, 3, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(24, 'Contact Us', '/contact-us', 0, 2, 3, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(25, 'Brands', '/brands', 0, 3, 3, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(26, 'Flash Deals', '/flash-deals', 0, 4, 3, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(27, 'Gift Cards', '/gift-cards', 0, 5, 3, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(28, 'Dashboard', '/user/dashboard', 0, 1, 4, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(29, 'Orders', '/user/orders', 0, 2, 4, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(30, 'Track Order', '/track-order', 0, 3, 4, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(31, 'Wish List', '/user/wishlist', 0, 4, 4, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(32, 'Change Password', '/user/change-password', 0, 5, 4, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(33, 'Dashboard', '/seller/dashboard', 0, 1, 5, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(34, 'Products', '/seller/products', 0, 2, 5, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(35, 'Orders', '/seller/orders', 0, 3, 5, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(36, 'Shop Profile', '/seller/shop-profile', 0, 4, 5, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(37, 'My Withdraw', '/seller/my-withdraw', 0, 5, 5, 0, 0, '2025-08-07 03:49:19', '2025-08-07 03:49:19');

-- Dumping structure for table topcommerce.menu_item_translations
DROP TABLE IF EXISTS `menu_item_translations`;
CREATE TABLE IF NOT EXISTS `menu_item_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `menu_item_id` bigint(20) unsigned NOT NULL,
  `lang_code` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_item_translations_menu_item_id_foreign` (`menu_item_id`),
  CONSTRAINT `menu_item_translations_menu_item_id_foreign` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.menu_item_translations: ~74 rows (approximately)
DELETE FROM `menu_item_translations`;
INSERT INTO `menu_item_translations` (`id`, `menu_item_id`, `lang_code`, `label`, `created_at`, `updated_at`) VALUES
	(1, 1, 'en', 'Home', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(2, 1, 'ar', 'بيت', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(3, 2, 'en', 'Shops', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(4, 2, 'ar', 'متاجر', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(5, 3, 'en', 'Products', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(6, 3, 'ar', 'منتجات', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(7, 4, 'en', 'Categories', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(8, 4, 'ar', 'فئات', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(9, 5, 'en', 'Contact Us', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(10, 5, 'ar', 'اتصل بنا', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(11, 6, 'en', 'Pages', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(12, 6, 'ar', 'صفحات مخصصة', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(13, 7, 'en', 'About Us', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(14, 7, 'ar', 'من نحن', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(15, 8, 'en', 'Privacy Policy', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(16, 8, 'ar', 'سياسة الخصوصية', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(17, 9, 'en', 'Terms and Conditions', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(18, 9, 'ar', 'الشروط والأحكام', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(19, 10, 'en', 'Return Policy', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(20, 10, 'ar', 'سياسة الإرجاع', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(21, 11, 'en', 'FAQ', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(22, 11, 'ar', 'الأسئلة الشائعة', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(23, 12, 'en', 'Join as Seller', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(24, 12, 'ar', 'انضم كبائع', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(25, 13, 'en', 'Brands', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(26, 13, 'ar', 'علامات تجارية', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(27, 14, 'en', 'Blogs', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(28, 14, 'ar', 'مدونات', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(29, 15, 'en', 'Track Order', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(30, 15, 'ar', 'تتبع الطلب', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(31, 16, 'en', 'Flash Deals', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(32, 16, 'ar', 'عروض فلاش', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(33, 17, 'en', 'Gift Cards', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(34, 17, 'ar', 'بطاقات الهدايا', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(35, 18, 'en', 'About Us', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(36, 18, 'ar', 'About Us', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(37, 19, 'en', 'Privacy Policy', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(38, 19, 'ar', 'سياسة الخصوصية', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(39, 20, 'en', 'Terms and Conditions', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(40, 20, 'ar', 'الشروط والأحكام', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(41, 21, 'en', 'Return Policy', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(42, 21, 'ar', 'سياسة الإرجاع', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(43, 22, 'en', 'FAQ', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(44, 22, 'ar', 'الأسئلة الشائعة', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(45, 23, 'en', 'Login', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(46, 23, 'ar', 'تسجيل الدخول', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(47, 24, 'en', 'Contact Us', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(48, 24, 'ar', 'Contact Us', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(49, 25, 'en', 'Brands', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(50, 25, 'ar', 'علامات تجارية', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(51, 26, 'en', 'Flash Deals', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(52, 26, 'ar', 'عروض فلاش', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(53, 27, 'en', 'Gift Cards', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(54, 27, 'ar', 'بطاقات الهدايا', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(55, 28, 'en', 'Dashboard', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(56, 28, 'ar', 'لوحة القيادة', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(57, 29, 'en', 'Orders', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(58, 29, 'ar', 'الطلبات', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(59, 30, 'en', 'Track Order', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(60, 30, 'ar', 'تتبع الطلب', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(61, 31, 'en', 'Wish List', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(62, 31, 'ar', 'قائمة الرغبات', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(63, 32, 'en', 'Change Password', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(64, 32, 'ar', 'تغيير كلمة المرور', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(65, 33, 'en', 'Dashboard', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(66, 33, 'ar', 'لوحة القيادة', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(67, 34, 'en', 'Products', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(68, 34, 'ar', 'منتجات', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(69, 35, 'en', 'Orders', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(70, 35, 'ar', 'الطلبات', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(71, 36, 'en', 'Shop Profile', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(72, 36, 'ar', 'ملف تعريف المتجر', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(73, 37, 'en', 'My Withdraw', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(74, 37, 'ar', 'سحب الأموال', '2025-08-07 03:49:19', '2025-08-07 03:49:19');

-- Dumping structure for table topcommerce.menu_translations
DROP TABLE IF EXISTS `menu_translations`;
CREATE TABLE IF NOT EXISTS `menu_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint(20) unsigned NOT NULL,
  `lang_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_translations_menu_id_foreign` (`menu_id`),
  CONSTRAINT `menu_translations_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.menu_translations: ~10 rows (approximately)
DELETE FROM `menu_translations`;
INSERT INTO `menu_translations` (`id`, `menu_id`, `lang_code`, `name`, `created_at`, `updated_at`) VALUES
	(1, 1, 'en', 'Main Menu', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(2, 1, 'ar', 'القائمة الرئيسية', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(3, 2, 'en', 'Footer Menu One', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(4, 2, 'ar', 'القائمة السفلية الأولى', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(5, 3, 'en', 'Footer Menu Two', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(6, 3, 'ar', 'القائمة السفلية الثانية', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(7, 4, 'en', 'Footer User Menu', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(8, 4, 'ar', 'القائمة السفلية الثانية', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(9, 5, 'en', 'Footer Vendor Menu', '2025-08-07 03:49:19', '2025-08-07 03:49:19'),
	(10, 5, 'ar', 'القائمة السفلية الثانية', '2025-08-07 03:49:19', '2025-08-07 03:49:19');

-- Dumping structure for table topcommerce.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.migrations: ~102 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2014_10_12_100000_create_password_resets_table', 1),
	(4, '2019_08_19_000000_create_failed_jobs_table', 1),
	(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(6, '2021_12_14_084759_create_vendors_table', 1),
	(7, '2022_01_11_103950_create_wishlists_table', 1),
	(8, '2023_11_05_045432_create_admins_table', 1),
	(9, '2023_11_05_114814_create_languages_table', 1),
	(10, '2023_11_06_043247_create_settings_table', 1),
	(11, '2023_11_06_054251_create_seo_settings_table', 1),
	(12, '2023_11_06_094842_create_custom_paginations_table', 1),
	(13, '2023_11_06_115856_create_email_templates_table', 1),
	(14, '2023_11_07_051924_create_multi_currencies_table', 1),
	(15, '2023_11_07_103108_create_basic_payments_table', 1),
	(16, '2023_11_07_104315_create_blog_categories_table', 1),
	(17, '2023_11_07_104328_create_blog_category_translations_table', 1),
	(18, '2023_11_07_104336_create_blogs_table', 1),
	(19, '2023_11_07_104343_create_blog_translations_table', 1),
	(20, '2023_11_07_104546_create_blog_comments_table', 1),
	(21, '2023_11_09_035236_create_payment_gateways_table', 1),
	(22, '2023_11_09_100621_create_jobs_table', 1),
	(23, '2023_11_19_064341_create_banned_histories_table', 1),
	(24, '2023_11_19_091457_create_customizeable_pages_table', 1),
	(25, '2023_11_21_043030_create_news_letters_table', 1),
	(26, '2023_11_21_094702_create_contact_messages_table', 1),
	(27, '2023_11_22_105539_create_permission_tables', 1),
	(28, '2023_11_29_055540_create_orders_table', 1),
	(29, '2023_11_29_095126_create_coupons_table', 1),
	(30, '2023_11_29_104658_create_testimonials_table', 1),
	(31, '2023_11_29_104704_create_testimonial_translations_table', 1),
	(32, '2023_11_29_105234_create_coupon_histories_table', 1),
	(33, '2023_11_30_044838_create_faqs_table', 1),
	(34, '2023_11_30_044844_create_faq_translations_table', 1),
	(35, '2023_11_30_095404_add_wallet_balance_to_users', 1),
	(36, '2023_11_30_101249_create_wallet_histories_table', 1),
	(37, '2023_12_04_071839_create_withraw_methods_table', 1),
	(38, '2023_12_04_095319_create_withdraw_requests_table', 1),
	(39, '2024_01_01_054644_create_socialite_credentials_table', 1),
	(40, '2024_01_03_030857_create_customizable_page_translations_table', 1),
	(41, '2024_01_03_092007_create_custom_codes_table', 1),
	(42, '2024_01_06_110546_create_countries_table', 1),
	(43, '2024_01_06_110613_create_states_table', 1),
	(44, '2024_01_06_110643_create_cities_table', 1),
	(45, '2024_02_10_060044_create_configurations_table', 1),
	(46, '2024_02_13_172901_create_unit_types_table', 1),
	(47, '2024_03_04_062506_create_categories_table', 1),
	(48, '2024_03_04_062507_create_category_translations_table', 1),
	(49, '2024_03_06_105415_create_brands_table', 1),
	(50, '2024_03_06_105416_create_brand_translations_table', 1),
	(51, '2024_03_06_105420_create_tags_table', 1),
	(52, '2024_03_06_105421_create_tag_translations_table', 1),
	(53, '2024_03_06_105424_create_products_table', 1),
	(54, '2024_03_06_105425_create_product_categories_table', 1),
	(55, '2024_03_06_105425_create_product_translations_table', 1),
	(56, '2024_03_06_105430_create_cross_sell_products_table', 1),
	(57, '2024_03_06_105447_create_product_attributes_table', 1),
	(58, '2024_03_07_105225_create_attributes_table', 1),
	(59, '2024_03_07_105225_create_related_products_table', 1),
	(60, '2024_03_07_105727_create_attribute_values_table', 1),
	(61, '2024_03_07_105728_create_product_tags_table', 1),
	(62, '2024_03_07_111404_create_variants_table', 1),
	(63, '2024_03_28_095206_create_custom_addons_table', 1),
	(64, '2024_03_28_095207_create_menus_wp_table', 1),
	(65, '2024_03_28_095208_create_menu_translations_table', 1),
	(66, '2024_03_28_095209_create_menu_items_wp_table', 1),
	(67, '2024_03_28_095210_create_menu_item_translations_table', 1),
	(68, '2024_04_07_055856_create_variant_options_table', 1),
	(69, '2024_04_07_063211_add_status_to_attributes_table', 1),
	(70, '2024_04_21_070132_create_addresses_table', 1),
	(71, '2024_07_10_122236_create_taxes_table', 1),
	(72, '2024_07_10_122445_create_tax_translations_table', 1),
	(73, '2024_08_14_163434_create_admin_notifications_table', 1),
	(74, '2024_10_08_060425_create_homes_table', 1),
	(75, '2024_10_08_060618_create_sections_table', 1),
	(76, '2024_10_08_060636_create_section_translations_table', 1),
	(77, '2025_02_20_045449_create_galleries_table', 1),
	(78, '2025_02_21_113032_create_stocks_table', 1),
	(79, '2025_02_23_042828_create_order_details_table', 1),
	(80, '2025_02_24_070404_create_attribute_translations_table', 1),
	(81, '2025_02_24_070421_create_attribute_value_translations_table', 1),
	(82, '2025_02_27_082428_create_attribute_images_table', 1),
	(83, '2025_03_02_095129_create_coupon_products_table', 1),
	(84, '2025_03_02_095130_create_coupon_categories_table', 1),
	(85, '2025_03_03_025917_create_coupon_translations_table', 1),
	(86, '2025_03_04_031353_create_shipping_settings_table', 1),
	(87, '2025_03_04_034047_create_shipping_rules_table', 1),
	(88, '2025_03_04_034826_create_shipping_rule_items_table', 1),
	(89, '2025_03_06_054227_create_carts_table', 1),
	(90, '2025_04_09_072312_create_product_labels_table', 1),
	(91, '2025_04_09_072400_create_product_label_translations_table', 1),
	(92, '2025_04_10_084715_create_product_label_product_table', 1),
	(93, '2025_04_22_045807_create_product_tax_table', 1),
	(94, '2025_04_22_061627_create_product_reviews_table', 1),
	(95, '2025_04_24_042822_create_product_tos_table', 1),
	(96, '2025_05_18_073442_create_order_shipping_addresses_table', 1),
	(97, '2025_05_18_073450_create_order_billing_addresses_table', 1),
	(98, '2025_05_19_080828_create_order_status_change_histories_table', 1),
	(99, '2025_05_24_095051_create_transaction_histories_table', 1),
	(100, '2025_05_27_092528_create_kyc_types_table', 1),
	(101, '2025_05_27_092619_create_kyc_information_table', 1),
	(102, '2025_06_21_105207_create_order_payment_details_table', 1);

-- Dumping structure for table topcommerce.model_has_permissions
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.model_has_permissions: ~0 rows (approximately)
DELETE FROM `model_has_permissions`;

-- Dumping structure for table topcommerce.model_has_roles
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.model_has_roles: ~1 rows (approximately)
DELETE FROM `model_has_roles`;
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\Admin', 1);

-- Dumping structure for table topcommerce.multi_currencies
DROP TABLE IF EXISTS `multi_currencies`;
CREATE TABLE IF NOT EXISTS `multi_currencies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(255) NOT NULL,
  `country_code` varchar(255) NOT NULL,
  `currency_code` varchar(255) NOT NULL,
  `currency_icon` varchar(255) NOT NULL,
  `is_default` varchar(255) NOT NULL DEFAULT 'no',
  `currency_rate` double(8,2) NOT NULL,
  `currency_position` varchar(255) NOT NULL DEFAULT 'before_price',
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.multi_currencies: ~6 rows (approximately)
DELETE FROM `multi_currencies`;
INSERT INTO `multi_currencies` (`id`, `currency_name`, `country_code`, `currency_code`, `currency_icon`, `is_default`, `currency_rate`, `currency_position`, `status`, `created_at`, `updated_at`) VALUES
	(1, '$-USD', 'US', 'USD', '$', 'yes', 1.00, 'before_price', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(2, '₦-Naira', 'NG', 'NGN', '₦', 'no', 417.35, 'before_price', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(3, '₹-Rupee', 'IN', 'INR', '₹', 'no', 74.66, 'before_price', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(4, '₱-Peso', 'PH', 'PHP', '₱', 'no', 55.07, 'before_price', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(5, '$-CAD', 'CA', 'CAD', '$', 'no', 1.27, 'before_price', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(6, '৳-Taka', 'BD', 'BDT', '৳', 'no', 80.00, 'before_price', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14');

-- Dumping structure for table topcommerce.news_letters
DROP TABLE IF EXISTS `news_letters`;
CREATE TABLE IF NOT EXISTS `news_letters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'not_verified',
  `verify_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.news_letters: ~0 rows (approximately)
DELETE FROM `news_letters`;

-- Dumping structure for table topcommerce.orders
DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `is_guest_order` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `shipping` decimal(12,2) NOT NULL DEFAULT 0.00,
  `gateway_fee` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sub_total` decimal(12,2) NOT NULL,
  `coupon_code` varchar(255) DEFAULT NULL,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL,
  `order_payment_details_id` bigint(20) unsigned NOT NULL,
  `order_status` enum('pending','approved','processing','packed','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_uuid_unique` (`uuid`),
  UNIQUE KEY `orders_order_id_unique` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.orders: ~0 rows (approximately)
DELETE FROM `orders`;

-- Dumping structure for table topcommerce.order_billing_addresses
DROP TABLE IF EXISTS `order_billing_addresses`;
CREATE TABLE IF NOT EXISTS `order_billing_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.order_billing_addresses: ~0 rows (approximately)
DELETE FROM `order_billing_addresses`;

-- Dumping structure for table topcommerce.order_details
DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `vendor_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `tax_amount` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `options` text DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_thumbnail` varchar(255) DEFAULT NULL,
  `product_sku` varchar(255) NOT NULL,
  `commission_rate` int(11) DEFAULT NULL,
  `commission` decimal(15,2) NOT NULL DEFAULT 0.00,
  `is_variant` tinyint(1) NOT NULL DEFAULT 0,
  `is_flash_deal` tinyint(1) NOT NULL DEFAULT 0,
  `measurement` varchar(255) DEFAULT NULL,
  `weight` double(8,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.order_details: ~0 rows (approximately)
DELETE FROM `order_details`;

-- Dumping structure for table topcommerce.order_payment_details
DROP TABLE IF EXISTS `order_payment_details`;
CREATE TABLE IF NOT EXISTS `order_payment_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `coupon_code` varchar(255) DEFAULT NULL,
  `total_discount` decimal(12,2) DEFAULT NULL,
  `payable_amount_without_rate` decimal(12,2) NOT NULL,
  `payable_amount` decimal(12,2) NOT NULL,
  `payable_currency` varchar(255) DEFAULT NULL,
  `paid_amount` varchar(255) NOT NULL DEFAULT '0',
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_details` text DEFAULT NULL,
  `payment_method` varchar(255) NOT NULL,
  `payment_status` enum('pending','processing','completed','failed','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_payment_details_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.order_payment_details: ~0 rows (approximately)
DELETE FROM `order_payment_details`;

-- Dumping structure for table topcommerce.order_shipping_addresses
DROP TABLE IF EXISTS `order_shipping_addresses`;
CREATE TABLE IF NOT EXISTS `order_shipping_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `walk_in_customer` tinyint(1) DEFAULT NULL,
  `type` enum('home','office') NOT NULL DEFAULT 'home',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.order_shipping_addresses: ~0 rows (approximately)
DELETE FROM `order_shipping_addresses`;

-- Dumping structure for table topcommerce.order_status_change_histories
DROP TABLE IF EXISTS `order_status_change_histories`;
CREATE TABLE IF NOT EXISTS `order_status_change_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `type` enum('payment_status','order_status') NOT NULL DEFAULT 'order_status',
  `from_status` varchar(255) NOT NULL DEFAULT 'pending',
  `to_status` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `change_by` enum('admin','user','system') NOT NULL DEFAULT 'system',
  `changed_by_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.order_status_change_histories: ~0 rows (approximately)
DELETE FROM `order_status_change_histories`;

-- Dumping structure for table topcommerce.password_resets
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.password_resets: ~0 rows (approximately)
DELETE FROM `password_resets`;

-- Dumping structure for table topcommerce.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table topcommerce.payment_gateways
DROP TABLE IF EXISTS `payment_gateways`;
CREATE TABLE IF NOT EXISTS `payment_gateways` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.payment_gateways: ~49 rows (approximately)
DELETE FROM `payment_gateways`;
INSERT INTO `payment_gateways` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
	(1, 'razorpay_status', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(2, 'razorpay_key', 'razorpay_key', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(3, 'razorpay_secret', 'razorpay_secret', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(4, 'razorpay_name', 'WebSolutionUs', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(5, 'razorpay_description', 'This is test payment window', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(6, 'razorpay_charge', '0', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(7, 'razorpay_theme_color', '#6d0ce4', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(8, 'razorpay_currency_id', '3', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(9, 'razorpay_image', 'website/images/gateways/razorpay.webp', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(10, 'flutterwave_status', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(11, 'flutterwave_public_key', 'flutterwave_public_key', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(12, 'flutterwave_secret_key', 'flutterwave_secret_key', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(13, 'flutterwave_app_name', 'WebSolutionUs', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(14, 'flutterwave_charge', '0', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(15, 'flutterwave_currency_id', '2', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(16, 'flutterwave_image', 'website/images/gateways/flutterwave.webp', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(17, 'paystack_status', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(18, 'paystack_public_key', 'paystack_public_key', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(19, 'paystack_secret_key', 'paystack_secret_key', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(20, 'paystack_charge', '0', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(21, 'paystack_image', 'website/images/gateways/paystack.webp', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(22, 'paystack_currency_id', '2', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(23, 'mollie_status', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(24, 'mollie_key', 'mollie_key', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(25, 'mollie_charge', '0', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(26, 'mollie_image', 'website/images/gateways/mollie.webp', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(27, 'mollie_currency_id', '5', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(28, 'instamojo_status', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(29, 'instamojo_account_mode', 'Sandbox', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(30, 'instamojo_client_id', 'instamojo_client_id', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(31, 'instamojo_client_secret', 'instamojo_client_secret', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(32, 'instamojo_charge', '0', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(33, 'instamojo_image', 'website/images/gateways/instamojo.webp', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(34, 'instamojo_currency_id', '3', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(35, 'sslcommerz_status', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(36, 'sslcommerz_store_id', 'test669499013b632', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(37, 'sslcommerz_store_password', 'test669499013b632@ssl', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(38, 'sslcommerz_image', 'website/images/gateways/sslcommerz.webp', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(39, 'sslcommerz_test_mode', '1', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(40, 'sslcommerz_localhost', '1', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(41, 'sslcommerz_charge', '0', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(42, 'sslcommerz_currency_id', '6', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(43, 'crypto_status', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(44, 'crypto_sandbox', '1', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(45, 'crypto_api_key', 'WzrKM5s3vzWKj4wDGrz6uJzG81Hdf35pe7ov7Wyv', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(46, 'crypto_image', 'website/images/gateways/crypto.webp', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(47, 'crypto_charge', '0', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(48, 'crypto_currency_id', '1', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(49, 'crypto_receive_currency', 'BTC', '2025-08-07 03:49:14', '2025-08-07 03:49:14');

-- Dumping structure for table topcommerce.permissions
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.permissions: ~189 rows (approximately)
DELETE FROM `permissions`;
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `group_name`, `created_at`, `updated_at`) VALUES
	(1, 'dashboard.view', 'admin', 'dashboard', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(2, 'admin.profile.view', 'admin', 'admin profile', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(3, 'admin.profile.update', 'admin', 'admin profile', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(4, 'coupon.management', 'admin', 'coupon management', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(5, 'order.management', 'admin', 'order management', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(6, 'order.status.update', 'admin', 'order management', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(7, 'order.payment.update', 'admin', 'order management', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(8, 'order.edit-update', 'admin', 'order management', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(9, 'order.delete', 'admin', 'order management', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(10, 'wallet.management', 'admin', 'wallet management', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(11, 'clubpoint.management', 'admin', 'clubpoint management', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(12, 'payment.withdraw.management', 'admin', 'payment withdraw management', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(13, 'product.view', 'admin', 'product', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(14, 'product.create', 'admin', 'product', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(15, 'product.edit', 'admin', 'product', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(16, 'product.delete', 'admin', 'product', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(17, 'product.status', 'admin', 'product', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(18, 'product.bulk.import', 'admin', 'product', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(19, 'product.barcode.print', 'admin', 'product', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(20, 'product.seller.view', 'admin', 'product', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(21, 'product.attribute.view', 'admin', 'Product attribute', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(22, 'product.attribute.create', 'admin', 'Product attribute', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(23, 'product.attribute.store', 'admin', 'Product attribute', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(24, 'product.attribute.edit', 'admin', 'Product attribute', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(25, 'product.attribute.update', 'admin', 'Product attribute', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(26, 'product.attribute.delete', 'admin', 'Product attribute', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(27, 'product.category.view', 'admin', 'product category', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(28, 'product.category.create', 'admin', 'product category', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(29, 'product.category.edit', 'admin', 'product category', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(30, 'product.category.update', 'admin', 'product category', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(31, 'product.category.delete', 'admin', 'product category', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(32, 'product.brand.view', 'admin', 'product brand', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(33, 'product.brand.create', 'admin', 'product brand', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(34, 'product.brand.edit', 'admin', 'product brand', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(35, 'product.brand.update', 'admin', 'product brand', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(36, 'product.brand.delete', 'admin', 'product brand', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(37, 'product.tags.view', 'admin', 'product tags', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(38, 'product.tags.create', 'admin', 'product tags', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(39, 'product.tags.edit', 'admin', 'product tags', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(40, 'product.tags.update', 'admin', 'product tags', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(41, 'product.tags.delete', 'admin', 'product tags', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(42, 'product.label.view', 'admin', 'product label', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(43, 'product.label.create', 'admin', 'product label', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(44, 'product.label.edit', 'admin', 'product label', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(45, 'product.label.update', 'admin', 'product label', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(46, 'product.label.delete', 'admin', 'product label', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(47, 'product.unit.view', 'admin', 'product unit', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(48, 'product.unit.create', 'admin', 'product unit', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(49, 'product.unit.edit', 'admin', 'product unit', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(50, 'product.unit.update', 'admin', 'product unit', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(51, 'product.unit.delete', 'admin', 'product unit', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(52, 'product.reviews.view', 'admin', 'product reviews', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(53, 'product.reviews.update', 'admin', 'product reviews', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(54, 'product.reviews.delete', 'admin', 'product reviews', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(55, 'admin.view', 'admin', 'admin', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(56, 'admin.create', 'admin', 'admin', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(57, 'admin.store', 'admin', 'admin', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(58, 'admin.edit', 'admin', 'admin', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(59, 'admin.update', 'admin', 'admin', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(60, 'admin.delete', 'admin', 'admin', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(61, 'blog.category.view', 'admin', 'blog category', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(62, 'blog.category.create', 'admin', 'blog category', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(63, 'blog.category.translate', 'admin', 'blog category', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(64, 'blog.category.store', 'admin', 'blog category', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(65, 'blog.category.edit', 'admin', 'blog category', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(66, 'blog.category.update', 'admin', 'blog category', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(67, 'blog.category.delete', 'admin', 'blog category', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(68, 'blog.view', 'admin', 'blog', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(69, 'blog.create', 'admin', 'blog', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(70, 'blog.translate', 'admin', 'blog', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(71, 'blog.store', 'admin', 'blog', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(72, 'blog.edit', 'admin', 'blog', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(73, 'blog.update', 'admin', 'blog', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(74, 'blog.delete', 'admin', 'blog', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(75, 'blog.comment.view', 'admin', 'blog comment', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(76, 'blog.comment.update', 'admin', 'blog comment', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(77, 'blog.comment.replay', 'admin', 'blog comment', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(78, 'blog.comment.delete', 'admin', 'blog comment', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(79, 'role.view', 'admin', 'role', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(80, 'role.create', 'admin', 'role', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(81, 'role.store', 'admin', 'role', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(82, 'role.assign', 'admin', 'role', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(83, 'role.edit', 'admin', 'role', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(84, 'role.update', 'admin', 'role', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(85, 'role.delete', 'admin', 'role', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(86, 'setting.view', 'admin', 'setting', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(87, 'setting.update', 'admin', 'setting', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(88, 'basic.payment.view', 'admin', 'basic payment', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(89, 'basic.payment.update', 'admin', 'basic payment', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(90, 'contact.message.view', 'admin', 'contact message', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(91, 'contact.message.delete', 'admin', 'contact message', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(92, 'currency.view', 'admin', 'currency', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(93, 'currency.create', 'admin', 'currency', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(94, 'currency.store', 'admin', 'currency', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(95, 'currency.edit', 'admin', 'currency', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(96, 'currency.update', 'admin', 'currency', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(97, 'currency.delete', 'admin', 'currency', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(98, 'customer.view', 'admin', 'customer', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(99, 'customer.bulk.mail', 'admin', 'customer', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(100, 'customer.create', 'admin', 'customer', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(101, 'customer.store', 'admin', 'customer', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(102, 'customer.edit', 'admin', 'customer', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(103, 'customer.update', 'admin', 'customer', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(104, 'customer.delete', 'admin', 'customer', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(105, 'language.view', 'admin', 'language', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(106, 'language.create', 'admin', 'language', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(107, 'language.store', 'admin', 'language', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(108, 'language.edit', 'admin', 'language', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(109, 'language.update', 'admin', 'language', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(110, 'language.delete', 'admin', 'language', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(111, 'language.translate', 'admin', 'language', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(112, 'language.single.translate', 'admin', 'language', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(113, 'menu.view', 'admin', 'menu builder', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(114, 'menu.create', 'admin', 'menu builder', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(115, 'menu.update', 'admin', 'menu builder', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(116, 'menu.delete', 'admin', 'menu builder', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(117, 'page.view', 'admin', 'page builder', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(118, 'page.create', 'admin', 'page builder', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(119, 'page.store', 'admin', 'page builder', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(120, 'page.edit', 'admin', 'page builder', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(121, 'page.component.add', 'admin', 'page builder', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(122, 'page.update', 'admin', 'page builder', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(123, 'page.delete', 'admin', 'page builder', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(124, 'subscription.view', 'admin', 'subscription', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(125, 'subscription.create', 'admin', 'subscription', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(126, 'subscription.store', 'admin', 'subscription', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(127, 'subscription.edit', 'admin', 'subscription', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(128, 'subscription.update', 'admin', 'subscription', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(129, 'subscription.delete', 'admin', 'subscription', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(130, 'payment.view', 'admin', 'payment', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(131, 'payment.update', 'admin', 'payment', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(132, 'location.view', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(133, 'country.create', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(134, 'country.list', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(135, 'country.store', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(136, 'country.edit', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(137, 'country.update', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(138, 'country.delete', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(139, 'state.list', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(140, 'state.create', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(141, 'state.store', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(142, 'state.edit', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(143, 'state.update', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(144, 'state.delete', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(145, 'city.list', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(146, 'city.create', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(147, 'city.store', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(148, 'city.edit', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(149, 'city.update', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(150, 'city.delete', 'admin', 'location', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(151, 'social.link.management', 'admin', 'social link management', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(152, 'sitemap.management', 'admin', 'sitemap management', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(153, 'shipping.management', 'admin', 'shipping management', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(154, 'kyc.management', 'admin', 'kyc management', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(155, 'tax.view', 'admin', 'tax management', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(156, 'tax.create', 'admin', 'tax management', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(157, 'tax.translate', 'admin', 'tax management', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(158, 'tax.store', 'admin', 'tax management', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(159, 'tax.edit', 'admin', 'tax management', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(160, 'tax.update', 'admin', 'tax management', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(161, 'tax.delete', 'admin', 'tax management', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(162, 'newsletter.view', 'admin', 'newsletter', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(163, 'newsletter.mail', 'admin', 'newsletter', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(164, 'newsletter.delete', 'admin', 'newsletter', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(165, 'testimonial.view', 'admin', 'testimonial', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(166, 'testimonial.create', 'admin', 'testimonial', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(167, 'testimonial.translate', 'admin', 'testimonial', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(168, 'testimonial.store', 'admin', 'testimonial', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(169, 'testimonial.edit', 'admin', 'testimonial', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(170, 'testimonial.update', 'admin', 'testimonial', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(171, 'testimonial.delete', 'admin', 'testimonial', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(172, 'faq.view', 'admin', 'faq', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(173, 'faq.create', 'admin', 'faq', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(174, 'faq.translate', 'admin', 'faq', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(175, 'faq.store', 'admin', 'faq', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(176, 'faq.edit', 'admin', 'faq', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(177, 'faq.update', 'admin', 'faq', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(178, 'faq.delete', 'admin', 'faq', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(179, 'addon.view', 'admin', 'Addons', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(180, 'addon.install', 'admin', 'Addons', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(181, 'addon.update', 'admin', 'Addons', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(182, 'addon.status.change', 'admin', 'Addons', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(183, 'addon.remove', 'admin', 'Addons', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(184, 'frontend.view', 'admin', 'Frontend', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(185, 'frontend.update', 'admin', 'Frontend', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(186, 'sellers.view', 'admin', 'Sellers', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(187, 'sellers.update', 'admin', 'Sellers', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(188, 'sellers.delete', 'admin', 'Sellers', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(189, 'sellers.status', 'admin', 'Sellers', '2025-08-07 03:49:15', '2025-08-07 03:49:15');

-- Dumping structure for table topcommerce.personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.personal_access_tokens: ~0 rows (approximately)
DELETE FROM `personal_access_tokens`;

-- Dumping structure for table topcommerce.products
DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `brand_id` bigint(20) unsigned DEFAULT NULL,
  `vendor_id` bigint(20) unsigned DEFAULT NULL,
  `unit_type_id` bigint(20) unsigned DEFAULT NULL,
  `thumbnail_image` varchar(255) DEFAULT NULL,
  `video_link` varchar(255) DEFAULT NULL,
  `is_cash_delivery` tinyint(4) NOT NULL DEFAULT 0,
  `is_return` tinyint(4) NOT NULL DEFAULT 0,
  `return_policy_id` int(11) DEFAULT NULL,
  `is_featured` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `is_popular` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `is_best_selling` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `allow_checkout_when_out_of_stock` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `price` decimal(10,2) unsigned DEFAULT NULL,
  `offer_price` decimal(10,2) unsigned DEFAULT NULL,
  `offer_price_type` enum('fixed','percentage') DEFAULT 'fixed',
  `offer_price_start` date DEFAULT NULL,
  `offer_price_end` date DEFAULT NULL,
  `is_flash_deal` tinyint(1) NOT NULL DEFAULT 0,
  `flash_deal_image` varchar(255) DEFAULT NULL,
  `flash_deal_start` date DEFAULT NULL,
  `flash_deal_end` date DEFAULT NULL,
  `flash_deal_price` decimal(10,2) DEFAULT 0.00,
  `flash_deal_qty` int(11) DEFAULT 0,
  `manage_stock` int(11) NOT NULL DEFAULT 0,
  `stock_status` enum('in_stock','out_of_stock') NOT NULL DEFAULT 'in_stock',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `tax_id` bigint(20) unsigned DEFAULT NULL,
  `length` double(8,2) DEFAULT NULL,
  `wide` double(8,2) DEFAULT NULL,
  `height` double(8,2) DEFAULT NULL,
  `weight` double(8,2) DEFAULT NULL,
  `viewed` bigint(20) unsigned NOT NULL DEFAULT 0,
  `theme` smallint(5) unsigned NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.products: ~0 rows (approximately)
DELETE FROM `products`;

-- Dumping structure for table topcommerce.product_attributes
DROP TABLE IF EXISTS `product_attributes`;
CREATE TABLE IF NOT EXISTS `product_attributes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_attributes_slug_unique` (`slug`),
  KEY `product_attributes_product_id_foreign` (`product_id`),
  CONSTRAINT `product_attributes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.product_attributes: ~0 rows (approximately)
DELETE FROM `product_attributes`;

-- Dumping structure for table topcommerce.product_categories
DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE IF NOT EXISTS `product_categories` (
  `product_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`category_id`),
  KEY `product_categories_category_id_foreign` (`category_id`),
  CONSTRAINT `product_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_categories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.product_categories: ~0 rows (approximately)
DELETE FROM `product_categories`;

-- Dumping structure for table topcommerce.product_labels
DROP TABLE IF EXISTS `product_labels`;
CREATE TABLE IF NOT EXISTS `product_labels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_labels_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.product_labels: ~0 rows (approximately)
DELETE FROM `product_labels`;

-- Dumping structure for table topcommerce.product_label_product
DROP TABLE IF EXISTS `product_label_product`;
CREATE TABLE IF NOT EXISTS `product_label_product` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `product_label_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_label_product_product_id_foreign` (`product_id`),
  KEY `product_label_product_product_label_id_foreign` (`product_label_id`),
  CONSTRAINT `product_label_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_label_product_product_label_id_foreign` FOREIGN KEY (`product_label_id`) REFERENCES `product_labels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.product_label_product: ~0 rows (approximately)
DELETE FROM `product_label_product`;

-- Dumping structure for table topcommerce.product_label_translations
DROP TABLE IF EXISTS `product_label_translations`;
CREATE TABLE IF NOT EXISTS `product_label_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_label_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `lang_code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_label_translations_product_label_id_foreign` (`product_label_id`),
  CONSTRAINT `product_label_translations_product_label_id_foreign` FOREIGN KEY (`product_label_id`) REFERENCES `product_labels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.product_label_translations: ~0 rows (approximately)
DELETE FROM `product_label_translations`;

-- Dumping structure for table topcommerce.product_reviews
DROP TABLE IF EXISTS `product_reviews`;
CREATE TABLE IF NOT EXISTS `product_reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `order_details_id` bigint(20) unsigned DEFAULT NULL,
  `vendor_id` bigint(20) unsigned DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  `options` text DEFAULT NULL,
  `product_sku` varchar(255) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_reviews_product_id_foreign` (`product_id`),
  KEY `product_reviews_user_id_foreign` (`user_id`),
  CONSTRAINT `product_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.product_reviews: ~0 rows (approximately)
DELETE FROM `product_reviews`;

-- Dumping structure for table topcommerce.product_tags
DROP TABLE IF EXISTS `product_tags`;
CREATE TABLE IF NOT EXISTS `product_tags` (
  `product_id` bigint(20) unsigned NOT NULL,
  `tag_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`tag_id`),
  KEY `product_tags_tag_id_foreign` (`tag_id`),
  CONSTRAINT `product_tags_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.product_tags: ~0 rows (approximately)
DELETE FROM `product_tags`;

-- Dumping structure for table topcommerce.product_tax
DROP TABLE IF EXISTS `product_tax`;
CREATE TABLE IF NOT EXISTS `product_tax` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `tax_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_tax_product_id_foreign` (`product_id`),
  KEY `product_tax_tax_id_foreign` (`tax_id`),
  CONSTRAINT `product_tax_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_tax_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.product_tax: ~0 rows (approximately)
DELETE FROM `product_tax`;

-- Dumping structure for table topcommerce.product_tos
DROP TABLE IF EXISTS `product_tos`;
CREATE TABLE IF NOT EXISTS `product_tos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint(20) unsigned DEFAULT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.product_tos: ~0 rows (approximately)
DELETE FROM `product_tos`;

-- Dumping structure for table topcommerce.product_translations
DROP TABLE IF EXISTS `product_translations`;
CREATE TABLE IF NOT EXISTS `product_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `lang_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `short_description` text DEFAULT NULL,
  `seo_title` text DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_translations_product_id_foreign` (`product_id`),
  CONSTRAINT `product_translations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.product_translations: ~0 rows (approximately)
DELETE FROM `product_translations`;

-- Dumping structure for table topcommerce.related_products
DROP TABLE IF EXISTS `related_products`;
CREATE TABLE IF NOT EXISTS `related_products` (
  `product_id` bigint(20) unsigned NOT NULL,
  `related_product_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`related_product_id`),
  KEY `related_products_related_product_id_foreign` (`related_product_id`),
  CONSTRAINT `related_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `related_products_related_product_id_foreign` FOREIGN KEY (`related_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.related_products: ~0 rows (approximately)
DELETE FROM `related_products`;

-- Dumping structure for table topcommerce.roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.roles: ~1 rows (approximately)
DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'Super Admin', 'admin', '2025-08-07 03:49:14', '2025-08-07 03:49:14');

-- Dumping structure for table topcommerce.role_has_permissions
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.role_has_permissions: ~189 rows (approximately)
DELETE FROM `role_has_permissions`;
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(10, 1),
	(11, 1),
	(12, 1),
	(13, 1),
	(14, 1),
	(15, 1),
	(16, 1),
	(17, 1),
	(18, 1),
	(19, 1),
	(20, 1),
	(21, 1),
	(22, 1),
	(23, 1),
	(24, 1),
	(25, 1),
	(26, 1),
	(27, 1),
	(28, 1),
	(29, 1),
	(30, 1),
	(31, 1),
	(32, 1),
	(33, 1),
	(34, 1),
	(35, 1),
	(36, 1),
	(37, 1),
	(38, 1),
	(39, 1),
	(40, 1),
	(41, 1),
	(42, 1),
	(43, 1),
	(44, 1),
	(45, 1),
	(46, 1),
	(47, 1),
	(48, 1),
	(49, 1),
	(50, 1),
	(51, 1),
	(52, 1),
	(53, 1),
	(54, 1),
	(55, 1),
	(56, 1),
	(57, 1),
	(58, 1),
	(59, 1),
	(60, 1),
	(61, 1),
	(62, 1),
	(63, 1),
	(64, 1),
	(65, 1),
	(66, 1),
	(67, 1),
	(68, 1),
	(69, 1),
	(70, 1),
	(71, 1),
	(72, 1),
	(73, 1),
	(74, 1),
	(75, 1),
	(76, 1),
	(77, 1),
	(78, 1),
	(79, 1),
	(80, 1),
	(81, 1),
	(82, 1),
	(83, 1),
	(84, 1),
	(85, 1),
	(86, 1),
	(87, 1),
	(88, 1),
	(89, 1),
	(90, 1),
	(91, 1),
	(92, 1),
	(93, 1),
	(94, 1),
	(95, 1),
	(96, 1),
	(97, 1),
	(98, 1),
	(99, 1),
	(100, 1),
	(101, 1),
	(102, 1),
	(103, 1),
	(104, 1),
	(105, 1),
	(106, 1),
	(107, 1),
	(108, 1),
	(109, 1),
	(110, 1),
	(111, 1),
	(112, 1),
	(113, 1),
	(114, 1),
	(115, 1),
	(116, 1),
	(117, 1),
	(118, 1),
	(119, 1),
	(120, 1),
	(121, 1),
	(122, 1),
	(123, 1),
	(124, 1),
	(125, 1),
	(126, 1),
	(127, 1),
	(128, 1),
	(129, 1),
	(130, 1),
	(131, 1),
	(132, 1),
	(133, 1),
	(134, 1),
	(135, 1),
	(136, 1),
	(137, 1),
	(138, 1),
	(139, 1),
	(140, 1),
	(141, 1),
	(142, 1),
	(143, 1),
	(144, 1),
	(145, 1),
	(146, 1),
	(147, 1),
	(148, 1),
	(149, 1),
	(150, 1),
	(151, 1),
	(152, 1),
	(153, 1),
	(154, 1),
	(155, 1),
	(156, 1),
	(157, 1),
	(158, 1),
	(159, 1),
	(160, 1),
	(161, 1),
	(162, 1),
	(163, 1),
	(164, 1),
	(165, 1),
	(166, 1),
	(167, 1),
	(168, 1),
	(169, 1),
	(170, 1),
	(171, 1),
	(172, 1),
	(173, 1),
	(174, 1),
	(175, 1),
	(176, 1),
	(177, 1),
	(178, 1),
	(179, 1),
	(180, 1),
	(181, 1),
	(182, 1),
	(183, 1),
	(184, 1),
	(185, 1),
	(186, 1),
	(187, 1),
	(188, 1),
	(189, 1);

-- Dumping structure for table topcommerce.sections
DROP TABLE IF EXISTS `sections`;
CREATE TABLE IF NOT EXISTS `sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `home_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `global_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`global_content`)),
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sections_home_id_foreign` (`home_id`),
  CONSTRAINT `sections_home_id_foreign` FOREIGN KEY (`home_id`) REFERENCES `homes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.sections: ~53 rows (approximately)
DELETE FROM `sections`;
INSERT INTO `sections` (`id`, `home_id`, `name`, `global_content`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'top_header_section', '{"offer_start_time":{"type":"date","value":"2025-08-07"},"offer_end_time":{"type":"date","value":"2025-08-14"},"offer_link":{"type":"text","value":"\\/products"},"offer_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"mega_menu_offer_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"mega_menu_offer_link":{"type":"text","value":"\\/products"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:25:43'),
	(2, 1, 'hero_section', '{"action_button_url":{"type":"text","value":"\\/products"},"banner_image":{"type":"file","value":"website\\/images\\/banner_img.webp"},"price":{"type":"number","value":"39.00"},"discount_price":{"type":"number","value":"29.00"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:23:07'),
	(3, 1, 'featured_category_section', '{"limit":{"type":"number","value":"10"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:23:12'),
	(4, 1, 'quick_shopping_section', '{"left_product_price":{"type":"number","value":"56.00"},"left_product_image":{"type":"file","value":"website\\/images\\/quick_shop_img_1.webp"},"left_product_action_url":{"type":"text","value":"\\/products"},"center_product_price":{"type":"number","value":"46.00"},"center_product_image":{"type":"file","value":"website\\/images\\/quick_shop_img_2.webp"},"center_product_action_url":{"type":"text","value":"\\/products"},"right_product_price":{"type":"number","value":"66.00"},"right_product_image":{"type":"file","value":"website\\/images\\/quick_shop_img_3.webp"},"right_product_action_url":{"type":"text","value":"\\/products"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:23:17'),
	(5, 1, 'best_selling_section', '{"limit":{"type":"number","value":"10"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:23:21'),
	(6, 1, 'flash_product_section', '{}', 0, '2025-08-07 03:49:15', '2025-08-07 05:23:25'),
	(7, 1, 'bundle_combo_section', '{"limit":{"type":"number","value":"10"},"collection_type":{"type":"select","options":{"best_seller":"Best Seller Products","popular":"Popular Products","featured":"Featured Products"},"value":"best_seller"},"banner_product_image":{"type":"file","value":"website\\/images\\/product_combo_1.webp"},"banner_combo_image":{"type":"file","value":"website\\/images\\/product_combo_shape.webp"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:23:30'),
	(8, 1, 'brand_section', '{"limit":{"type":"number","value":"10"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:23:34'),
	(9, 1, 'call_to_action_section', '{"cta_image_one":{"type":"file","value":"website\\/images\\/cta_img1.webp"},"cta_image_two":{"type":"file","value":"website\\/images\\/cta_img2.webp"},"action_url":{"type":"text","value":"\\/products"},"product_price":{"type":"number","value":"58.00"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:23:39'),
	(10, 1, 'feature_products_section', '{"limit":{"type":"number","value":"10"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:23:44'),
	(11, 1, 'flash_deal_section', '{"flash_deal_start_date":{"type":"date","value":"2025-08-05T09:49:15.615439Z"},"flash_deal_end_date":{"type":"date","value":"2027-08-07T09:49:15.615449Z"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:23:48'),
	(12, 1, 'testimonials_section', '{}', 0, '2025-08-07 03:49:15', '2025-08-07 05:23:53'),
	(13, 1, 'blog_section', '{"limit":{"type":"number","value":"3"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:23:58'),
	(14, 1, 'footer_section', '{"facebook_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"facebook_link":{"type":"text","value":"https:\\/\\/www.facebook.com\\/"},"x_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"x_link":{"type":"text","value":"https:\\/\\/www.twitter.com\\/"},"linkedin_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"linkedin_link":{"type":"text","value":"https:\\/\\/www.linkedin.com\\/"},"pinterest_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"pinterest_link":{"type":"text","value":"https:\\/\\/www.pinterest.com\\/"},"apple_store_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"apple_store_link":{"type":"text","value":"https:\\/\\/www.appstore.com\\/"},"google_store_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"google_store_link":{"type":"text","value":"https:\\/\\/www.appstore.com\\/"},"contact_email":{"type":"text","value":"contact@topcommerce.com"},"contact_number":{"type":"text","value":"+670 413 90 762"},"useful_pages_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"help_center_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"payment_gateway_image":{"type":"file","value":"website\\/images\\/footer_2_payment_1.webp"}}', 1, '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(15, 2, 'top_header_section', '{"offer_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:25:35'),
	(16, 2, 'hero_section', '{"item_one_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"item_one_action_button_url":{"type":"text","value":"\\/products"},"item_one_image":{"type":"file","value":"website\\/images\\/banner_2_img_1.webp"},"item_one_price":{"type":"number","value":"134.00"},"item_two_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"item_two_action_button_url":{"type":"text","value":"\\/products"},"item_two_image":{"type":"file","value":"website\\/images\\/banner_2_img_2.webp"},"item_two_price":{"type":"number","value":"144.00"},"item_three_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"item_three_action_button_url":{"type":"text","value":"\\/products"},"item_three_image":{"type":"file","value":"website\\/images\\/banner_2_img_3.webp"},"item_three_price":{"type":"number","value":"140.00"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:24:23'),
	(17, 2, 'featured_category_section', '{"limit":{"type":"number","value":"10"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:24:26'),
	(18, 2, 'flash_deal_section', '{"flash_deal_start_date":{"type":"date","value":"2025-08-05T09:49:15.615674Z"},"flash_deal_end_date":{"type":"date","value":"2027-08-07T09:49:15.615683Z"},"limit":{"type":"number","value":"10"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:24:31'),
	(19, 2, 'new_arrival_section', '{"new_arrival_image_one":{"type":"file","value":"website\\/images\\/new_arraival_2_img_1.webp"},"new_arrival_one_action_url":{"type":"text","value":"\\/products"},"new_arrival_image_two":{"type":"file","value":"website\\/images\\/new_arraival_2_img_2.webp"},"new_arrival_two_action_url":{"type":"text","value":"\\/products"},"new_arrival_image_three":{"type":"file","value":"website\\/images\\/new_arraival_2_img_3.webp"},"new_arrival_three_action_url":{"type":"text","value":"\\/products"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:24:35'),
	(20, 2, 'favorite_products_section', '{"limit":{"type":"number","value":"8"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:24:40'),
	(21, 2, 'discount_banner_section', '{"offer_start_time":{"type":"date","value":"2025-08-07T09:49:15.615691Z"},"offer_end_time":{"type":"date","value":"2025-08-14T09:49:15.615692Z"},"image_one":{"type":"file","value":"website\\/images\\/discount_2_img_1.webp"},"image_two":{"type":"file","value":"website\\/images\\/discount_2_img_2.webp"},"action_url":{"type":"text","value":"\\/products"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:24:44'),
	(22, 2, 'feature_products_section', '{"limit":{"type":"number","value":"4"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:24:48'),
	(23, 2, 'call_to_action_section', '{"cta_image_one":{"type":"file","value":"website\\/images\\/summer_collection_1.webp"},"cta_image_two":{"type":"file","value":"website\\/images\\/summer_collection_2.webp"},"cta_image_discount":{"type":"file","value":"website\\/images\\/discount_percentige.webp"},"action_url":{"type":"text","value":"\\/products"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:24:53'),
	(24, 2, 'hot_deal_section', '{"limit":{"type":"number","value":"3"},"collection_type":{"type":"select","options":{"best_seller":"Best Seller Products","popular":"Popular Products","featured":"Featured Products"},"value":"best_seller"},"hot_deal_image":{"type":"file","value":"website\\/images\\/product_combo_1.webp"},"hot_deal_action_url":{"type":"text","value":"\\/products"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:24:58'),
	(25, 2, 'blog_section', '{"limit":{"type":"number","value":"3"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:25:02'),
	(26, 2, 'benefit_section', '{"area_one_icon":{"type":"file","value":"website\\/images\\/benefit_icon_1.webp"},"area_two_icon":{"type":"file","value":"website\\/images\\/benefit_icon_2.webp"},"area_three_icon":{"type":"file","value":"website\\/images\\/benefit_icon_3.webp"},"area_four_icon":{"type":"file","value":"website\\/images\\/benefit_icon_4.webp"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:25:07'),
	(27, 2, 'footer_section', '{"facebook_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"facebook_link":{"type":"text","value":"https:\\/\\/www.facebook.com\\/"},"x_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"x_link":{"type":"text","value":"https:\\/\\/www.twitter.com\\/"},"linkedin_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"linkedin_link":{"type":"text","value":"https:\\/\\/www.linkedin.com\\/"},"pinterest_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"pinterest_link":{"type":"text","value":"https:\\/\\/www.pinterest.com\\/"},"contact_email":{"type":"text","value":"contact@topcommerce.com"},"contact_number":{"type":"text","value":"+670 413 90 762"},"shop_pages_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"help_center_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"newsletter_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"payment_gateway_image":{"type":"file","value":"website\\/images\\/footer_2_payment_1.webp"}}', 1, '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(28, 3, 'top_header_section', '{"offer_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:25:56'),
	(29, 3, 'hero_section', '{"action_button_url":{"type":"text","value":"\\/products"},"banner_image":{"type":"file","value":"website\\/images\\/banner_3_img.webp"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:25:20'),
	(30, 3, 'featured_category_section', '{"limit":{"type":"number","value":"10"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:26:01'),
	(31, 3, 'summer_sale_section', '{"image_one":{"type":"file","value":"website\\/images\\/summersale_3_img_1.webp"},"image_two":{"type":"file","value":"website\\/images\\/summersale_3_img_2.webp"},"action_link_one":{"type":"text","value":"\\/products"},"action_link_two":{"type":"text","value":"\\/products"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:26:05'),
	(32, 3, 'feature_products_section', '{"limit":{"type":"number","value":"8"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:26:09'),
	(33, 3, 'flash_deal_section', '{"limit":{"type":"number","value":"10"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:26:13'),
	(34, 3, 'top_products_section', '{"limit":{"type":"number","value":"10"},"banner_image":{"type":"file","value":"website\\/images\\/top_product_3_bg.webp"},"banner_link":{"type":"text","value":"\\/products"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:26:17'),
	(35, 3, 'popular_products_section', '{"limit":{"type":"number","value":"10"},"banner_image":{"type":"file","value":"website\\/images\\/popular_product_3_img.webp"},"banner_link":{"type":"text","value":"\\/products"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:26:22'),
	(36, 3, 'blog_section', '{"limit":{"type":"number","value":"3"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:26:26'),
	(37, 3, 'benefit_section', '{"area_one_icon":{"type":"file","value":"website\\/images\\/benefit_icon_5.webp"},"area_two_icon":{"type":"file","value":"website\\/images\\/benefit_icon_6.webp"},"area_three_icon":{"type":"file","value":"website\\/images\\/benefit_icon_7.webp"},"area_four_icon":{"type":"file","value":"website\\/images\\/benefit_icon_8.webp"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:26:31'),
	(38, 3, 'footer_section', '{"facebook_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"facebook_link":{"type":"text","value":"https:\\/\\/www.facebook.com\\/"},"x_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"x_link":{"type":"text","value":"https:\\/\\/www.twitter.com\\/"},"linkedin_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"linkedin_link":{"type":"text","value":"https:\\/\\/www.linkedin.com\\/"},"pinterest_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"pinterest_link":{"type":"text","value":"https:\\/\\/www.pinterest.com\\/"},"apple_store_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"apple_store_link":{"type":"text","value":"https:\\/\\/www.appstore.com\\/"},"google_store_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"google_store_link":{"type":"text","value":"https:\\/\\/www.appstore.com\\/"},"contact_email":{"type":"text","value":"contact@topcommerce.com"},"contact_number":{"type":"text","value":"+670 413 90 762"},"useful_pages_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"help_center_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"payment_gateway_image":{"type":"file","value":"website\\/images\\/footer_2_payment_1.webp"}}', 1, '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(39, 4, 'top_header_section', '{"offer_status": {"type": "select", "options": {"active": "Active", "inactive": "Inactive"}, "value": "active"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:27:57'),
	(40, 4, 'hero_section', '{"product_one_image": {"type": "file", "value": "website\\/images\\/banner_4_img_1.webp"}, "product_one_label_image": {"type": "file", "value": "website\\/images\\/offer_shape.webp"}, "product_two_image": {"type": "file", "value": "website\\/images\\/banner_4_img_2.webp"}, "product_two_label_image": {"type": "file", "value": "website\\/images\\/offer_shape_2.webp"}, "action_link": {"type": "text", "value": "\\/products"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:28:02'),
	(41, 4, 'featured_category_section', '{"limit":{"type":"number","value":"6"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:28:05'),
	(42, 4, 'flash_deal_section', '{"limit":{"type":"number","value":"10"},"flash_deal_start_date":{"type":"date","value":"2025-08-05T09:49:15.616022Z"},"flash_deal_end_date":{"type":"date","value":"2027-08-07T09:49:15.616030Z"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:28:09'),
	(43, 4, 'hot_deal_section', '{"deal_one_image":{"type":"file","value":"website\\/images\\/hot_deals_4_img_1.webp"},"deal_one_button_url":{"type":"text","value":"\\/products"},"deal_two_image":{"type":"file","value":"website\\/images\\/hot_deals_4_img_2.webp"},"deal_two_label_image":{"type":"file","value":"website\\/images\\/offer_shape_3.webp"},"deal_two_start_time":{"type":"date","value":"2025-08-06"},"deal_two_end_time":{"type":"date","value":"2025-08-29"},"deal_two_button_url":{"type":"text","value":"\\/products"},"deal_three_image":{"type":"file","value":"website\\/images\\/hot_deals_4_img_3.webp"},"deal_three_button_url":{"type":"text","value":"\\/products"},"deal_four_image":{"type":"file","value":"website\\/images\\/hot_deals_4_img_4.webp"},"deal_four_button_url":{"type":"text","value":"\\/products"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:28:24'),
	(44, 4, 'best_sales_section', '{"limit":{"type":"number","value":"8"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:28:31'),
	(45, 4, 'discount_section', '{"video_thumbnail": {"type": "file", "value": "website\\/images\\/process_4_bg_2.webp"}, "video_one_status": {"type": "select", "value": "active", "options": {"active": "Active", "inactive": "Inactive"}}, "video_one_link": {"type": "text", "value": "https:\\/\\/youtu.be\\/6h6b4LPq1Vw?si=gn8f4hZXpSKZr55e"}, "video_two_status": {"type": "select", "value": "active", "options": {"active": "Active", "inactive": "Inactive"}}, "video_two_link": {"type": "text", "value": "https:\\/\\/youtu.be\\/6h6b4LPq1Vw?si=gn8f4hZXpSKZr55e"}, "product_image": {"type": "file", "value": "website\\/images\\/process_4_img_1.webp"}, "product_label_image": {"type": "file", "value": "website\\/images\\/offer_shape_3.webp"}, "process_one_icon": {"type": "file", "value": "website\\/images\\/benefit_icon_1.webp"}, "process_two_icon": {"type": "file", "value": "website\\/images\\/benefit_icon_2.webp"}, "process_three_icon": {"type": "file", "value": "website\\/images\\/benefit_icon_3.webp"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:28:37'),
	(46, 4, 'top_selling_section', '{"action_url": {"type": "text", "value": "\\/products"}, "top_selling_price": {"type": "number", "value": "58.00"}, "top_selling_image": {"type": "file", "value": "website\\/images\\/top_selling_img.webp"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:28:44'),
	(47, 4, 'filtered_product_section', '{"product_image": {"type": "file", "value": "website\\/images\\/buy_product_img.webp"}, "product_label_image": {"type": "file", "value": "website\\/images\\/chair_shape.webp"}, "product_sku": {"type": "text", "value": "SKU-00000063"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:28:49'),
	(48, 4, 'blog_section', '{"limit":{"type":"number","value":"8"}}', 0, '2025-08-07 03:49:15', '2025-08-07 05:28:53'),
	(49, 4, 'footer_section', '{"facebook_status": {"type": "select", "options": {"active": "Active", "inactive": "Inactive"}, "value": "active"}, "facebook_link": {"type": "text", "value": "https:\\/\\/www.facebook.com\\/"}, "x_status": {"type": "select", "options": {"active": "Active", "inactive": "Inactive"}, "value": "active"}, "x_link": {"type": "text", "value": "https:\\/\\/www.twitter.com\\/"}, "linkedin_status": {"type": "select", "options": {"active": "Active", "inactive": "Inactive"}, "value": "active"}, "linkedin_link": {"type": "text", "value": "https:\\/\\/www.linkedin.com\\/"}, "pinterest_status": {"type": "select", "options": {"active": "Active", "inactive": "Inactive"}, "value": "active"}, "pinterest_link": {"type": "text", "value": "https:\\/\\/www.pinterest.com\\/"}, "contact_email": {"type": "text", "value": "contact@topcommerce.com"}, "contact_number": {"type": "text", "value": "+670 413 90 762"}, "shop_pages_status": {"type": "select", "options": {"active": "Active", "inactive": "Inactive"}, "value": "active"}, "help_center_status": {"type": "select", "options": {"active": "Active", "inactive": "Inactive"}, "value": "active"}, "newsletter_status": {"type": "select", "options": {"active": "Active", "inactive": "Inactive"}, "value": "active"}, "payment_gateway_image": {"type": "file", "value": "website\\/images\\/footer_2_payment_1.webp"}, "shop_time": {"type": "textarea", "value": "Monday - Friday : {8:00 AM - 6:00 PM}\\n                        Saturday : {10:00 AM - 6:00 PM}\\n                        Sunday: {Close}"}}', 1, '2025-08-07 03:49:15', '2025-08-07 03:49:26'),
	(50, NULL, 'login_page', '{"social_login_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"logo_image":{"type":"file","value":"\\/website\\/images\\/logo_3.webp"},"login_image":{"type":"file","value":"website\\/images\\/login_img.webp"}}', 1, '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(51, NULL, 'register_page', '{"social_login_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"active"},"logo_image":{"type":"file","value":"\\/website\\/images\\/logo_3.webp"},"register_image":{"type":"file","value":"website\\/images\\/signup_img.webp"}}', 1, '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(52, NULL, 'about_us_page', '{"mission_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"inactive"},"mission_image":{"type":"file","value":"website\\/images\\/mission_img_1.webp"},"mission_preview_image":{"type":"file","value":"website\\/images\\/mission_img_2.webp"},"vision_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"inactive"},"vision_image":{"type":"file","value":"website\\/images\\/vission_img_1.webp"},"vision_preview_image":{"type":"file","value":"website\\/images\\/vission_img_2.webp"},"testimonial_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"inactive"},"testimonial_limit":{"type":"number","value":"10"},"counter_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"inactive"},"blog_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"inactive"},"blog_limit":{"type":"number","value":"3"},"benefit_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"inactive"},"area_one_icon":{"type":"file","value":"website\\/images\\/benefit_icon_1.webp"},"area_two_icon":{"type":"file","value":"website\\/images\\/benefit_icon_2.webp"},"area_three_icon":{"type":"file","value":"website\\/images\\/benefit_icon_3.webp"},"area_four_icon":{"type":"file","value":"website\\/images\\/benefit_icon_4.webp"}}', 1, '2025-08-07 03:49:15', '2025-08-07 05:29:47'),
	(53, NULL, 'contact_us_page', '{"contact_info_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"inactive"},"contact_us_email":{"type":"text","value":"contact@topcommerce.com"},"contact_us_phone":{"type":"text","value":"Phone: 088 6578 654 87"},"contact_us_form_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"inactive"},"contact_image":{"type":"file","value":"website\\/images\\/contact_form_img.webp"},"map_status":{"type":"select","options":{"active":"Active","inactive":"Inactive"},"value":"inactive"},"map_link":{"type":"text","value":"https:\\/\\/www.google.com\\/maps\\/embed?pb=!1m18!1m12!1m3!1d58955.86762247907!2d88.3391639282542!3d22.551345723020553!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a0277a2e8448a01%3A0xfc7031bafe756ae4!2sMillennium%20Park%2C%20Kolkata!5e0!3m2!1sen!2sbd!4v1710672733871!5m2!1sen!2sbd"}}', 1, '2025-08-07 03:49:15', '2025-08-07 05:29:58');

-- Dumping structure for table topcommerce.section_translations
DROP TABLE IF EXISTS `section_translations`;
CREATE TABLE IF NOT EXISTS `section_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section_id` bigint(20) unsigned NOT NULL,
  `lang_code` varchar(255) NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`content`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `section_translations_section_id_foreign` (`section_id`),
  CONSTRAINT `section_translations_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.section_translations: ~94 rows (approximately)
DELETE FROM `section_translations`;
INSERT INTO `section_translations` (`id`, `section_id`, `lang_code`, `content`, `created_at`, `updated_at`) VALUES
	(1, 1, 'en', '{"title":"Step into a fashion wonderland","offer_link_text":"Get Your Order","address":"25+G6 London, UK","mega_menu_title":"Up To - 35% Off","mega_menu_subtitle":"Hot Deals","mega_menu_offer_link_text":"Shop Now"}', '2025-08-07 03:49:15', '2025-08-07 05:25:43'),
	(2, 1, 'ar', '{"title":"\\u0627\\u0628\\u062f\\u0627 \\u0641\\u064a \\u0639\\u0627\\u0644\\u0645 \\u0627\\u0644\\u0627\\u062b\\u0627\\u0626\\u0631","offer_link_text":"\\u0627\\u062d\\u0635\\u0644 \\u0639\\u0644\\u0649 \\u0637\\u0644\\u0628\\u0643","address":"25+G6 \\u0644\\u0646\\u062f\\u0646\\u060c \\u0627\\u0644\\u0648\\u0644\\u0627\\u064a\\u0627\\u062a \\u0627\\u0644\\u0645\\u062a\\u062d\\u062f\\u0629","mega_menu_title":"\\u062d\\u062a\\u0649 - 35% \\u062e\\u0635\\u0645","mega_menu_subtitle":"\\u0639\\u0631\\u0648\\u0636 \\u0633\\u0639\\u0631\\u064a\\u0629","mega_menu_offer_link_text":"\\u062a\\u0633\\u0648\\u0642 \\u0627\\u0644\\u0627\\u0653\\u0646"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(3, 2, 'en', '{"subtitle":"Giant Discount Blitz!","title":"Fresh Men\\u2019s Trends For The Season.","details":"Quisque condimentum ante eu convallis sagittis sapien sapien orci nunc erat felis quam ex.","action_button_text":"Shop Now"}', '2025-08-07 03:49:15', '2025-08-07 05:23:07'),
	(4, 2, 'ar', '{"subtitle":"\\u062e\\u0635\\u0645 \\u0636\\u062e\\u0645!","title":"\\u0627\\u062a\\u062c\\u0627\\u0647\\u0627\\u062a \\u0631\\u062c\\u0627\\u0644\\u064a\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629 \\u0644\\u0647\\u0630\\u0627 \\u0627\\u0644\\u0645\\u0648\\u0633\\u0645.","details":"\\u0648\\u0643\\u0627\\u0644\\u0629 \\u0647\\u0630\\u0627 \\u0627\\u0644\\u0639\\u0627\\u0645 \\u0641\\u064a \\u062c\\u0645\\u064a\\u0639 \\u0623\\u0646\\u062d\\u0627\\u0621 \\u0627\\u0644\\u0639\\u0627\\u0644\\u0645","action_button_text":"\\u062a\\u0633\\u0648\\u0642 \\u0627\\u0644\\u0627\\u0653\\u0646"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(5, 3, 'en', '{"sub_title":"Shop by Categories","title":"Featured {Categories}"}', '2025-08-07 03:49:15', '2025-08-07 05:23:12'),
	(6, 3, 'ar', '{"sub_title":"\\u062a\\u0633\\u0648\\u0642 \\u062d\\u0633\\u0628 \\u0627\\u0644\\u0641\\u064a\\u0654\\u0627\\u062a","title":"\\u0627\\u0644\\u0641\\u064a\\u0654\\u0627\\u062a {\\u0627\\u0644\\u0645\\u0645\\u064a\\u0632\\u0629}"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(7, 4, 'en', '{"left_product_title":"Fashion Cotton Lightweight Jacket.","left_product_price_text":"Starting At :","left_product_action_text":"Shop Now","center_product_title":"Stgaubron Gaming Desktop PC","center_product_subtitle":"Desktop PC","center_product_price_text":"Starting At :","center_product_action_text":"Shop Now","right_product_title":"Fashion Cotton Lightweight Jacket.","right_product_price_text":"Starting At :","right_product_action_text":"Shop Now"}', '2025-08-07 03:49:15', '2025-08-07 05:23:17'),
	(8, 4, 'ar', '{"left_product_title":"\\u062c\\u0627\\u0643\\u064a\\u062a \\u0633\\u0644\\u064a\\u0645 \\u0644\\u064a\\u0645\\u0648\\u0646 \\u0648\\u0631\\u062f","left_product_price_text":"\\u0628\\u062f\\u0627\\u0654 \\u0645\\u0646 :","left_product_action_text":"\\u062a\\u0633\\u0648\\u0642 \\u0627\\u0644\\u0627\\u0653\\u0646","center_product_title":"\\u0643\\u0645\\u0628\\u064a\\u0648\\u062a\\u0631 \\u0645\\u0643\\u062a\\u0628\\u064a \\u0644\\u0644\\u0623\\u0644\\u0639\\u0627\\u0628 Stgaubron","center_product_price_text":"\\u0628\\u062f\\u0627\\u0654 \\u0645\\u0646 :","center_product_action_text":"\\u062a\\u0633\\u0648\\u0642 \\u0627\\u0644\\u0627\\u0653\\u0646","right_product_title":"\\u062c\\u0627\\u0643\\u064a\\u062a \\u0633\\u0644\\u064a\\u0645 \\u0644\\u064a\\u0645\\u0648\\u0646 \\u0648\\u0631\\u062f","right_product_price_text":"\\u0628\\u062f\\u0627\\u0654 \\u0645\\u0646 :","right_product_action_text":"\\u062a\\u0633\\u0648\\u0642 \\u0627\\u0644\\u0627\\u0653\\u0646"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(9, 5, 'en', '{"title":"Best Selling {Products}","sub_title":"Shop By Best Product"}', '2025-08-07 03:49:15', '2025-08-07 05:23:21'),
	(10, 5, 'ar', '{"title":"\\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a {\\u0627\\u0644\\u0645\\u0628\\u064a\\u0639\\u0629}","sub_title":"\\u062a\\u0633\\u0648\\u0642 \\u062d\\u0633\\u0628 \\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a \\u0627\\u0644\\u0645\\u0628\\u064a\\u0639\\u0629"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(11, 7, 'en', '{"title":"Product {collections}","sub_title":"Get Best sellers products"}', '2025-08-07 03:49:15', '2025-08-07 05:23:30'),
	(12, 7, 'ar', '{"title":"\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a {\\u0627\\u0644\\u0645\\u062c\\u0645\\u0648\\u0639\\u0627\\u062a}","sub_title":"\\u062d\\u0635\\u0644 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a \\u0627\\u0644\\u0645\\u0628\\u064a\\u0639\\u0629 \\u0627\\u0644\\u0627\\u0641\\u0636\\u0644"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(13, 9, 'en', '{"cta_one_title":"Brand: Apple Watch","cta_one_subtitle":"Fitness Oxygen","action_text":"Shop Collection","product_title":"Stgaubron Gaming Desktop PC","product_price_text":"Starting At :"}', '2025-08-07 03:49:15', '2025-08-07 05:23:39'),
	(14, 9, 'ar', '{"cta_one_title":"\\u0645\\u0627\\u0631\\u0643\\u0629: \\u0633\\u0627\\u0639\\u0629 \\u0627\\u0628\\u0644","cta_one_subtitle":"\\u0641\\u064a\\u062a\\u0646\\u0633","action_text":"\\u062a\\u0633\\u0648\\u0642 \\u0627\\u0644\\u0645\\u062c\\u0645\\u0648\\u0639\\u0629","product_title":"\\u0643\\u0645\\u0628\\u064a\\u0648\\u062a\\u0631 \\u0645\\u0643\\u062a\\u0628\\u064a \\u0644\\u0644\\u0623\\u0644\\u0639\\u0627\\u0628 Stgaubron","product_price_text":"\\u0628\\u062f\\u0627\\u0654 \\u0645\\u0646 :"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(15, 10, 'en', '{"title":"Featured {Products}","sub_title":"Shop By Best Product"}', '2025-08-07 03:49:15', '2025-08-07 05:23:44'),
	(16, 10, 'ar', '{"title":"\\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a {\\u0627\\u0644\\u0645\\u0645\\u064a\\u0632\\u0629}","sub_title":"\\u062a\\u0633\\u0648\\u0642 \\u062d\\u0633\\u0628 \\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a \\u0627\\u0644\\u0645\\u0645\\u064a\\u0632\\u0629"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(17, 11, 'en', '{"title":"Flash {Deals}","sub_title":"Today\\u2019s Deals"}', '2025-08-07 03:49:15', '2025-08-07 05:23:48'),
	(18, 11, 'ar', '{"title":"\\u0635\\u0641\\u0642\\u0627\\u062a {\\u0641\\u0644\\u0627\\u0634}","sub_title":"\\u0627\\u0644\\u0635\\u0641\\u0642\\u0627\\u062a \\u0627\\u0644\\u0627\\u062b\\u0646\\u064a \\u0639\\u0634\\u0631\\u064a\\u0629"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(19, 12, 'en', '{"sub_title":"Testimonials","title":"Clients {Feedback}"}', '2025-08-07 03:49:15', '2025-08-07 05:23:53'),
	(20, 12, 'ar', '{"sub_title":"\\u0645\\u0631\\u0627\\u062c\\u0639\\u0627\\u062a \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621","title":"\\u0639\\u0645\\u0644\\u0627\\u0648\\u0654\\u0646\\u0627 {\\u0645\\u0644\\u0627\\u062d\\u0638\\u0627\\u062a}"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(21, 13, 'en', '{"title":"Latest From {Blogs}","sub_title":"Our Latest News"}', '2025-08-07 03:49:15', '2025-08-07 05:23:58'),
	(22, 13, 'ar', '{"title":"\\u0627\\u062d\\u062f\\u062b \\u0645\\u0646 {\\u0627\\u0644\\u0645\\u062f\\u0648\\u0646\\u0629}","sub_title":"\\u0627\\u062d\\u062f\\u062b \\u0627\\u0644\\u0627\\u062e\\u0628\\u0627\\u0631"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(23, 14, 'en', '{"useful_pages_title":"Useful Pages","help_center_title":"Help Center","footer_subtitle":"Nula element eulimid olio nec zeugita celestas arco quis lobortis.","address":"1000 5th Ave, New York, NY 10028, United States."}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(24, 14, 'ar', '{"useful_pages_title":"\\u0635\\u0641\\u062d\\u0627\\u062a \\u0645\\u0641\\u064a\\u062f\\u0629","help_center_title":"\\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0645\\u0633\\u0627\\u0639\\u062f\\u0629","footer_subtitle":"Nula element eulimid olio nec zeugita celestas arco quis lobortis.","address":"1000 5th Ave, New York, NY 10028, United States."}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(25, 15, 'en', '{"offer_label":"24% OFF","offer_text":"Free shipping for orders over $99","address":"25+G6 London, UK"}', '2025-08-07 03:49:15', '2025-08-07 05:25:35'),
	(26, 15, 'ar', '{"offer_label":"24% OFF","offer_text":"\\u0627\\u0644\\u0634\\u062d\\u0646 \\u0645\\u062c\\u0627\\u0646\\u064a \\u0644\\u0644\\u0637\\u0644\\u0628\\u0627\\u062a \\u0627\\u0654\\u0643\\u062b\\u0631 \\u0645\\u0646 $99","address":"25+G6 \\u0644\\u0646\\u062f\\u0646\\u060c \\u0627\\u0644\\u0648\\u0644\\u0627\\u064a\\u0627\\u062a \\u0627\\u0644\\u0645\\u062a\\u062d\\u062f\\u0629"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(27, 16, 'en', '{"item_one_subtitle":"Hot Collection 2025","item_one_title":"Streamlined & Resilient Design.","item_one_action_button_text":"Shop Now","item_two_subtitle":"New Collection 2025","item_two_title":"Streamlined & Resilient Design.","item_two_action_button_text":"Shop Now","item_three_subtitle":"Super Collection 202","item_three_title":"Streamlined & Resilient Design.","item_three_action_button_text":"Shop Now"}', '2025-08-07 03:49:15', '2025-08-07 05:24:23'),
	(28, 16, 'ar', '{"item_one_subtilte":"\\u0645\\u062c\\u0645\\u0648\\u0639\\u0629 \\u0633\\u0627\\u0639\\u062f\\u0629 2025","item_one_title":"\\u062a\\u0635\\u0645\\u064a\\u0645 \\u0645\\u062a\\u0642\\u062f\\u0645 \\u0648\\u0645\\u0642\\u0627\\u0628\\u0644\\u0629.","item_one_action_button_text":"\\u062a\\u0633\\u0648\\u0642 \\u0627\\u0644\\u0627\\u0653\\u0646","item_two_subtilte":"\\u0645\\u062c\\u0645\\u0648\\u0639\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629 2025","item_two_title":"\\u062a\\u0635\\u0645\\u064a\\u0645 \\u0645\\u062a\\u0642\\u062f\\u0645 \\u0648\\u0645\\u0642\\u0627\\u0628\\u0644\\u0629.","item_two_action_button_text":"\\u062a\\u0633\\u0648\\u0642 \\u0627\\u0644\\u0627\\u0653\\u0646","item_three_subtilte":"\\u0645\\u062c\\u0645\\u0648\\u0639\\u0629 \\u0633\\u0648\\u0628\\u0631 202","item_three_title":"\\u062a\\u0635\\u0645\\u064a\\u0645 \\u0645\\u062a\\u0642\\u062f\\u0645 \\u0648\\u0645\\u0642\\u0627\\u0628\\u0644\\u0629."}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(29, 17, 'en', '{"sub_title":"Shop by Categories","title":"Featured Categories"}', '2025-08-07 03:49:15', '2025-08-07 05:24:26'),
	(30, 17, 'ar', '{"sub_title":"\\u062a\\u0633\\u0648\\u0642 \\u062d\\u0633\\u0628 \\u0627\\u0644\\u0641\\u064a\\u0654\\u0627\\u062a","title":"\\u0627\\u0644\\u0641\\u064a\\u0654\\u0627\\u062a \\u0627\\u0644\\u0645\\u0645\\u064a\\u0632\\u0629"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(31, 18, 'en', '{"title":"Flash Deals","sub_title":"Today\\u2019s Deals"}', '2025-08-07 03:49:15', '2025-08-07 05:24:31'),
	(32, 18, 'ar', '{"title":"\\u0635\\u0641\\u0642\\u0627\\u062a \\u0641\\u0644\\u0627\\u0634","sub_title":"\\u0627\\u0644\\u0635\\u0641\\u0642\\u0627\\u062a \\u0627\\u0644\\u0627\\u062b\\u0646\\u064a \\u0639\\u0634\\u0631\\u064a\\u0629"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(33, 19, 'en', '{"new_arrival_one_title":"ICEMOOD Men\'s Shirt Casual 3 Button Basic Tee Quick T-Short Sleeve Fit T-shirt.","new_arrival_one_subtitle":"Collection 2024","new_arrival_one_action_text":"Shop Collection","new_arrival_two_title":"Edinburgh Green Top Hooded Raincoat.","new_arrival_two_subtitle":"Upcoming 2025","new_arrival_two_label":"56% OFF","new_arrival_two_action_text":"Shop Collection","new_arrival_three_title":"Child\'s Green Pique Cotton Polo Tee.","new_arrival_three_subtitle":"Up To 44% OFF","new_arrival_three_action_text":"Shop Collection"}', '2025-08-07 03:49:15', '2025-08-07 05:24:35'),
	(34, 19, 'ar', '{"new_arrival_one_title":"\\u062c\\u0648\\u062f\\u0629 \\u0645\\u0627\\u0621 \\u0645\\u0627\\u0646\\u0634\\u0633\\u062a\\u0631 \\u0645\\u0627\\u0646\\u0634\\u062a\\u0631 \\u0645\\u0627\\u0646\\u0634\\u0646\\u0634\\u0633\\u062a\\u0631 \\u0645\\u0627\\u0646\\u0634\\u0633\\u062a\\u0631 \\u0645\\u0627\\u0646\\u0634\\u0633\\u062a\\u0631 \\u0645\\u0627\\u0646\\u0634\\u0633\\u062a\\u0631 \\u0645  ","new_arrival_one_subtitle":"\\u0645\\u062c\\u0645\\u0648\\u0639\\u0629 2024","new_arrival_one_action_text":"\\u062a\\u0633\\u0648\\u0642 \\u0627\\u0644\\u0645\\u062c\\u0645\\u0648\\u0639\\u0629","new_arrival_two_title":"\\u0625\\u0646\\u062f\\u0648\\u0646\\u064a\\u0633\\u0648\\u0646 \\u063a \\u063a\\u0631\\u0641\\u0629 \\u063a\\u0631\\u0641\\u0629 \\u063a\\u0631\\u0641\\u0629 \\u063a\\u0631\\u0641\\u0629 \\u063a","new_arrival_two_subtitle":"\\u0642\\u0627\\u062f\\u0645\\u0629 2025","new_arrival_two_label":"56% OFF","new_arrival_two_action_text":"\\u062a\\u0633\\u0648\\u0642 \\u0627\\u0644\\u0645\\u062c\\u0645\\u0648\\u0639\\u0629","new_arrival_three_title":"\\u0635\\u063a\\u064a\\u0631 \\u062c\\u0632\\u0631 \\u0632\\u0631 \\u0632\\u0631 \\u0632\\u0631 \\u0632\\u0631 \\u0632\\u0631 \\u0632\\u0631 \\u0632","new_arrival_three_subtitle":"\\u062d\\u062a\\u0649 44% OFF","new_arrival_three_action_text":"\\u062a\\u0633\\u0648\\u0642 \\u0627\\u0644\\u0645\\u062c\\u0645\\u0648\\u0639\\u0629"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(35, 20, 'en', '{"sub_title":"Customer Favorites","title":"Best Selling Products"}', '2025-08-07 03:49:15', '2025-08-07 05:24:40'),
	(36, 20, 'ar', '{"sub_title":"\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621 \\u0627\\u0644\\u0645\\u0641\\u0636\\u0644\\u0629","title":"\\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a \\u0627\\u0644\\u0645\\u0628\\u064a\\u0639\\u0629"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(37, 21, 'en', '{"title":"Monthly discounts","sub_title":"Seasonal Closeout Sale Up to 30% Savings.","description":"Limited time offer. The deal will expires on {March 18,2024} HURRY UP!","action_text":"Shop Collection"}', '2025-08-07 03:49:15', '2025-08-07 05:24:44'),
	(38, 21, 'ar', '{"title":"\\u0639\\u0631\\u0648\\u0636 \\u0627\\u0644\\u0634\\u0647\\u0631\\u064a\\u0629","sub_title":"\\u0639\\u0637\\u0644\\u0629 \\u0627\\u0644\\u062e\\u0635\\u0645 \\u0627\\u0644\\u0634\\u0647\\u0631\\u064a\\u0629 \\u062d\\u062a\\u0649 30% \\u0627\\u0644\\u062e\\u0635\\u0645.","description":"Limited time offer. The deal will expires on {March 18,2024} HURRY UP! ","action_text":"\\u062a\\u0633\\u0648\\u0642 \\u0627\\u0644\\u0645\\u062c\\u0645\\u0648\\u0639\\u0629"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(39, 22, 'en', '{"title":"Featured Products","sub_title":"Shop By Best Product"}', '2025-08-07 03:49:15', '2025-08-07 05:24:48'),
	(40, 22, 'ar', '{"title":"\\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a \\u0627\\u0644\\u0645\\u0645\\u064a\\u0632\\u0629","sub_title":"\\u062a\\u0633\\u0648\\u0642 \\u062d\\u0633\\u0628 \\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a \\u0627\\u0644\\u0645\\u0645\\u064a\\u0632\\u0629"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(41, 23, 'en', '{"cta_one_title":"Member Special: 24% OFF","cta_one_subtitle":"THE SUMMER 2023 COLLECTION","action_text":"Shop Collection","description":"Seasonal charm captured in summer\\u2019s latest collection."}', '2025-08-07 03:49:15', '2025-08-07 05:24:53'),
	(42, 23, 'ar', '{"cta_one_title":"\\u0645\\u0645\\u064a\\u0632\\u0627\\u062a \\u0639\\u0636\\u0648: 24% OFF","cta_one_subtitle":"\\u0645\\u062c\\u0645\\u0648\\u0639\\u0629 \\u0627\\u0644\\u0633\\u0641\\u0631\\u0629 2023","action_text":"\\u062a\\u0633\\u0648\\u0642 \\u0627\\u0644\\u0645\\u062c\\u0645\\u0648\\u0639\\u0629","description":"\\u0645\\u062c\\u0645\\u0648\\u0639\\u0629 \\u0633\\u0627\\u0639\\u062f\\u0629 \\u0645\\u0627\\u0646\\u0634\\u0633\\u062a\\u0631 \\u0645\\u0627\\u0646\\u0634\\u062a\\u0631 \\u0645\\u0627\\u0646\\u0634\\u0633\\u062a\\u0631 \\u0645\\u0627\\u0646\\u0634\\u0633\\u062a\\u0631 \\u0645\\u0627\\u0646\\u0634\\u0633\\u062a\\u0631 \\u0645\\u0627\\u0646\\u0634\\u0633\\u062a\\u0631 \\u0645  "}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(43, 24, 'en', '{"hot_deal_title":"Denton Hidden Snap Ultra Stretch.","hot_deal_sub_title":"Collection 2024","hot_deal_action_text":"Shop Collection","title":"Today\'s Hot Deals","subtitle":"Best Featured Deals"}', '2025-08-07 03:49:15', '2025-08-07 05:24:58'),
	(44, 24, 'ar', '{"hot_deal_title":"Denton Hidden Snap Ultra Stretch.","hot_deal_sub_title":"\\u0645\\u062c\\u0645\\u0648\\u0639\\u0629 2024","hot_deal_action_text":"\\u062a\\u0633\\u0648\\u0642 \\u0627\\u0644\\u0645\\u062c\\u0645\\u0648\\u0639\\u0629","title":"\\u0627\\u0644\\u0639\\u0631\\u0648\\u0636 \\u0627\\u0644\\u0645\\u0645\\u064a\\u0632\\u0629","subtitle":"\\u0627\\u0644\\u0639\\u0631\\u0648\\u0636 \\u0627\\u0644\\u0645\\u0645\\u064a\\u0632\\u0629"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(45, 25, 'en', '{"title":"Latest From Blogs","sub_title":"Our Latest News"}', '2025-08-07 03:49:15', '2025-08-07 05:25:02'),
	(46, 25, 'ar', '{"title":"\\u0627\\u062d\\u062f\\u062b \\u0645\\u0646 \\u0627\\u0644\\u0645\\u062f\\u0648\\u0646\\u0629","sub_title":"\\u0627\\u062d\\u062f\\u062b \\u0627\\u0644\\u0627\\u062e\\u0628\\u0627\\u0631"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(47, 26, 'en', '{"area_one_title":"Free Shipping","area_one_sub_title":"Standard shipping for orders","area_two_title":"Flexible Payment","area_two_sub_title":"Pay with Credit Cards","area_three_title":"14 Day Returns","area_three_sub_title":"30 Days for an Exchange","area_four_title":"Premium Support","area_four_sub_title":"All Outstanding Support"}', '2025-08-07 03:49:15', '2025-08-07 05:25:07'),
	(48, 26, 'ar', '{"area_one_title":"\\u0634\\u062d\\u0646 \\u0645\\u062c\\u0627\\u0646\\u064a","area_one_sub_title":"\\u0634\\u062d\\u0646 \\u0633\\u0639\\u0631\\u064a \\u0644\\u0637\\u0644\\u0628\\u0627\\u062a","area_two_title":"\\u0627\\u0644\\u062f\\u0641\\u0639 \\u0627\\u0644\\u0645\\u0641\\u062a\\u0648\\u062d","area_two_sub_title":"\\u062f\\u0641\\u0639 \\u0628\\u0628\\u0637\\u0627\\u0642\\u0627\\u062a \\u0627\\u0644\\u0627\\u0626\\u062a\\u0645\\u0627\\u0646","area_three_title":"14 \\u064a\\u0648\\u0645\\u064b\\u0627 \\u0644\\u0644\\u0645\\u0631\\u062a\\u062c\\u0639","area_three_sub_title":"30 \\u064a\\u0648\\u0645\\u064b\\u0627 \\u0644\\u0627\\u0633\\u062a\\u0628\\u062f\\u0627\\u0644","area_four_title":"\\u062f\\u0639\\u0645 Premium","area_four_sub_title":"\\u062c\\u0645\\u064a\\u0639 \\u0627\\u0644\\u062f\\u0639\\u0645 \\u0627\\u0644\\u0645\\u0645\\u064a\\u0632"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(49, 27, 'en', '{"shop_pages_title":"Shop","help_center_title":"Information","address":"1000 5th Ave, New York, NY 10028, United States.","newsletter_subtitle":"Nula element eulimid olio nec zeugita celestas arco quis lobortis."}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(50, 27, 'ar', '{"shop_pages_title":"Shop","help_center_title":"\\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a","address":"1000 5th Ave, New York, NY 10028, \\u0627\\u0644\\u0648\\u0644\\u0627\\u064a\\u0627\\u062a \\u0627\\u0644\\u0645\\u062a\\u062d\\u062f\\u0629.","newsletter_subtitle":"Nula element eulimid olio nec zeugita celestas arco quis lobortis."}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(51, 28, 'en', '{"offer_label":"24% OFF","offer_text":"Free shipping for orders over $99","address":"25+G6 London, UK"}', '2025-08-07 03:49:15', '2025-08-07 05:25:56'),
	(52, 28, 'ar', '{"offer_label":"24% OFF","offer_text":"\\u0627\\u0644\\u0634\\u062d\\u0646 \\u0645\\u062c\\u0627\\u0646\\u064a \\u0644\\u0644\\u0637\\u0644\\u0628\\u0627\\u062a \\u0627\\u0654\\u0643\\u062b\\u0631 \\u0645\\u0646 $99","address":"25+G6 \\u0644\\u0646\\u062f\\u0646\\u060c \\u0627\\u0644\\u0648\\u0644\\u0627\\u064a\\u0627\\u062a \\u0627\\u0644\\u0645\\u062a\\u062d\\u062f\\u0629"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(53, 29, 'en', '{"subtitle":"New Collection","title":"Shopping Online Place Fresh Produce.","details":"Nullam element eulimid olio nec zeugita mauri\'s celestas arco quis lobortis ipsum fringilla                        Cras pellentesque nisl diam faucibus, at accumsan enim congue.","action_button_text":"Explore Products"}', '2025-08-07 03:49:15', '2025-08-07 05:25:20'),
	(54, 29, 'ar', '{"subtitle":"\\u0646\\u0648\\u0639\\u064a\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629","title":"\\u0645\\u0648\\u0642\\u0639 \\u0634\\u062d\\u0646 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0625\\u0646\\u062a\\u0631\\u0646\\u062a \\u0645\\u0645\\u062a\\u0627\\u0632\\u0629 \\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a.","details":"Nullam element eulimid olio nec zeugita mauri\'s celestas arco quis lobortis ipsum fringilla\\n                        Cras pellentesque nisl diam faucibus, at accumsan enim congue.","action_button_text":"\\u062a\\u062c\\u0631\\u0628\\u0629 \\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(55, 30, 'en', '{"sub_title":"Shop by Categories","title":"Featured Categories"}', '2025-08-07 03:49:15', '2025-08-07 05:26:01'),
	(56, 30, 'ar', '{"sub_title":"\\u062a\\u0633\\u0648\\u0642 \\u062d\\u0633\\u0628 \\u0627\\u0644\\u0641\\u064a\\u0654\\u0627\\u062a","title":"\\u0627\\u0644\\u0641\\u064a\\u0654\\u0627\\u062a \\u0627\\u0644\\u0645\\u0645\\u064a\\u0632\\u0629"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(57, 31, 'en', '{"sub_title_one":"Fresh Vegetables","title_one":"Home Delivery of Fresh Fruits & Vegetables.","action_link_text_one":"Shop Now","sub_title_two":"Fresh Vegetables","title_two":"Delicious Steaks From Our Top Chef.","action_link_text_two":"Shop Now"}', '2025-08-07 03:49:15', '2025-08-07 05:26:06'),
	(58, 31, 'ar', '{"sub_title_one":"Fresh Vegetables","title_one":"Home Delivery of Fresh Fruits & Vegetables.","action_link_text_one":"Shop Now","sub_title_two":"Fresh Vegetables","title_two":"Delicious Steaks From Our Top Chef.","action_link_text_two":"Shop Now"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(59, 32, 'en', '{"title":"Featured Products","sub_title":"Best Seller"}', '2025-08-07 03:49:15', '2025-08-07 05:26:09'),
	(60, 32, 'ar', '{"title":"\\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a \\u0627\\u0644\\u0645\\u0645\\u064a\\u0632\\u0629","sub_title":"\\u062a\\u0633\\u0648\\u0642 \\u062d\\u0633\\u0628 \\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a \\u0627\\u0644\\u0645\\u0645\\u064a\\u0632\\u0629"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(61, 33, 'en', '{"title":"Flash Deals","sub_title":"Today\\u2019s Deals","description":"Nullam element eulimid olio nec zeugita mauri\'s celestas arco quis lobortis                        Cras pellentesque nisl diam at accumsan."}', '2025-08-07 03:49:15', '2025-08-07 05:26:13'),
	(62, 33, 'ar', '{"title":"\\u0635\\u0641\\u0642\\u0627\\u062a \\u0641\\u0644\\u0627\\u0634","sub_title":"\\u0627\\u0644\\u0635\\u0641\\u0642\\u0627\\u062a \\u0627\\u0644\\u0627\\u062b\\u0646\\u064a \\u0639\\u0634\\u0631\\u064a\\u0629","description":"Nullam element eulimid olio nec zeugita mauri\'s celestas arco quis lobortis\\n                        Cras pellentesque nisl diam at accumsan."}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(63, 34, 'en', '{"section_one_title":"Top Rated","section_two_title":"Top Sales","banner_title":"Home Delivery of Fresh Fruits & Vegetables.","banner_subtitle":"Fresh Vegetables","banner_link_text":"Shop Now"}', '2025-08-07 03:49:15', '2025-08-07 05:26:18'),
	(64, 34, 'ar', '{"section_one_title":"Top Rated","section_two_title":"Top Sales","banner_title":"Home Delivery of Fresh Fruits & Vegetables.","banner_subtitle":"Fresh Vegetables","banner_link_text":"Shop Now"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(65, 35, 'en', '{"title":"Trending Products","sub_title":"Most Popular","banner_title":"Leek Autumn Giant 45 Heirloom & Organic Canadian.","banner_subtitle":"Get 35% off","banner_link_text":"Shop Now"}', '2025-08-07 03:49:15', '2025-08-07 05:26:22'),
	(66, 35, 'ar', '{"title":"Trending Products","sub_title":"Most Popular","banner_title":"Leek Autumn Giant 45 Heirloom & Organic Canadian.","banner_subtitle":"Get35% off","banner_link_text":"Shop Now"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(67, 36, 'en', '{"title":"Latest Blog & News","sub_title":"Read Our Blog"}', '2025-08-07 03:49:15', '2025-08-07 05:26:26'),
	(68, 36, 'ar', '{"title":"\\u0627\\u062d\\u062f\\u062b \\u0645\\u0646 \\u0627\\u0644\\u0645\\u062f\\u0648\\u0646\\u0629","sub_title":"\\u0627\\u062d\\u062f\\u062b \\u0627\\u0644\\u0627\\u062e\\u0628\\u0627\\u0631"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(69, 37, 'en', '{"area_one_title":"Free Shipping","area_one_sub_title":"Standard shipping for orders","area_two_title":"Helpline","area_two_sub_title":"+354 5875 547","area_three_title":"24x7 Support","area_three_sub_title":"Free For Customers","area_four_title":"Returns","area_four_sub_title":"30 Days Free Exchanges"}', '2025-08-07 03:49:15', '2025-08-07 05:26:31'),
	(70, 37, 'ar', '{"area_one_title":"Free Shipping","area_one_sub_title":"Standard shipping for orders","area_two_title":"Helpline","area_two_sub_title":"+354 5875 547","area_three_title":"24x7 Support","area_three_sub_title":"Free For Customers","area_four_title":"Returns","area_four_sub_title":"30 Days Free Exchanges"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(71, 38, 'en', '{"useful_pages_title":"Useful Pages","help_center_title":"Help Center","footer_subtitle":"Nula element eulimid olio nec zeugita celestas arco quis lobortis.","address":"1000 5th Ave, New York, NY 10028, United States."}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(72, 38, 'ar', '{"useful_pages_title":"\\u0635\\u0641\\u062d\\u0627\\u062a \\u0645\\u0641\\u064a\\u062f\\u0629","help_center_title":"\\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0645\\u0633\\u0627\\u0639\\u062f\\u0629","footer_subtitle":"Nula element eulimid olio nec zeugita celestas arco quis lobortis.","address":"1000 5th Ave, New York, NY 10028, United States."}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(73, 39, 'en', '{"offer_label":"24% OFF","offer_text":"Free shipping for orders over $99","address":"25+G6 London, UK"}', '2025-08-07 03:49:15', '2025-08-07 05:27:57'),
	(74, 39, 'ar', '{"offer_label":"24% OFF","offer_text":"\\u0627\\u0644\\u0634\\u062d\\u0646 \\u0645\\u062c\\u0627\\u0646\\u064a \\u0644\\u0644\\u0637\\u0644\\u0628\\u0627\\u062a \\u0627\\u0654\\u0643\\u062b\\u0631 \\u0645\\u0646 $99","address":"25+G6 \\u0644\\u0646\\u062f\\u0646\\u060c \\u0627\\u0644\\u0648\\u0644\\u0627\\u064a\\u0627\\u062a \\u0627\\u0644\\u0645\\u062a\\u062d\\u062f\\u0629"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(75, 40, 'en', '{"title":"Enhance a Seating {Configuration.}","label_one":"Our Materials","label_one_text":"Our Materials","label_two":"Product Size","label_two_text":"60X138X22","label_three":"Available In","label_three_text":"Greay,Yellow","action_button_text":"Shop Collection"}', '2025-08-07 03:49:15', '2025-08-07 05:28:02'),
	(76, 40, 'ar', '{"title":"Enhance a Seating {Configuration.}","label_one":"Our Materials","label_one_text":"Our Materials","label_two":"Product Size","label_two_text":"60X138X22","label_three":"Available In","label_three_text":"Greay,Yellow","action_button_text":"Shop Collection"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(77, 42, 'en', '{"title":"Flash Deals","sub_title":"Today\\u2019s Deals"}', '2025-08-07 03:49:15', '2025-08-07 05:28:09'),
	(78, 42, 'ar', '{"title":"\\u0635\\u0641\\u0642\\u0627\\u062a \\u0641\\u0644\\u0627\\u0634","sub_title":"\\u0627\\u0644\\u0635\\u0641\\u0642\\u0627\\u062a \\u0627\\u0644\\u0627\\u062b\\u0646\\u064a \\u0639\\u0634\\u0631\\u064a\\u0629"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(79, 43, 'en', '{"deal_one_title":"Office Home Office Dining Solid Material Sofa Frame Purple.","deal_one_subtitle":"Hot Deal In Week","deal_one_button_text":"Shop Now","deal_two_title":"Seasonal Blowout Up To 30% Savings","deal_two_button_text":"Shop Now","deal_three_title":"Homco Modern Touch Fabric Leisure Club.","deal_three_subtitle":"Club Chair","deal_three_button_text":"Shop Now","deal_four_title":"Homco Modern Touch Fabric Leisure Club.","deal_four_subtitle":"Club Chair","deal_four_button_text":"Shop Now"}', '2025-08-07 03:49:15', '2025-08-07 05:28:24'),
	(80, 43, 'ar', '{"deal_one_title":"Office Home Office Dining Solid Material Sofa Frame Purple.","deal_one_subtitle":"Hot Deal In Week","deal_one_button_text":"Shop Now","deal_two_title":"Seasonal Blowout Up To 30% Savings","deal_two_button_text":"Shop Now","deal_three_title":"Homco Modern Touch Fabric Leisure Club.","deal_three_subtitle":"Club Chair","deal_three_button_text":"Shop Now","deal_four_title":"Homco Modern Touch Fabric Leisure Club.","deal_four_subtitle":"Club Chair","deal_four_button_text":"Shop Now"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(81, 44, 'en', '{"title":"Best Selling Products","sub_title":"New Products"}', '2025-08-07 03:49:15', '2025-08-07 05:28:31'),
	(82, 44, 'ar', '{"title":"Best Selling Products","sub_title":"New Products"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(83, 45, 'en', '{"title":"The Future You Have Yet to Experience...!","process_one_title":"Free Shipping","process_one_subtitle":"Extra Shipping Benefit to Ensure Swift, Reliable and Stress-Free Deliveries.","process_two_title":"Flexible Payment","process_two_subtitle":"Extra Shipping Benefit to Ensure Swift, Reliable and Stress-Free Deliveries.","process_three_title":"Premium Support","process_three_subtitle":"Extra Shipping Benefit to Ensure Swift, Reliable and Stress-Free Deliveries."}', '2025-08-07 03:49:15', '2025-08-07 05:28:37'),
	(84, 45, 'ar', '{"title":"The Future You Have Yet to Experience...!","process_one_title":"Free Shipping","process_one_subtitle":"Extra Shipping Benefit to Ensure Swift, Reliable and Stress-Free Deliveries.","process_two_title":"Flexible Payment","process_two_subtitle":"Extra Shipping Benefit to Ensure Swift, Reliable and Stress-Free Deliveries.","process_three_title":"Premium Support","process_three_subtitle":"Extra Shipping Benefit to Ensure Swift, Reliable and Stress-Free Deliveries."}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(85, 46, 'en', '{"title":"HomCom 34\\" Loveseat Sofa To Tufted Back.","sub_title":"Top Selling Products","top_selling_price_label":"Starting price","action_button_text":"Shop Now"}', '2025-08-07 03:49:15', '2025-08-07 05:28:44'),
	(86, 46, 'ar', '{"title":"HomCom 34\\" Loveseat Sofa To Tufted Back.","sub_title":"Top Selling Products","top_selling_price_label":"Starting price","action_button_text":"Shop Now"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(87, 48, 'en', '{"title":"Latest Blog & News","sub_title":"Read Our Blog"}', '2025-08-07 03:49:15', '2025-08-07 05:28:53'),
	(88, 48, 'ar', '{"title":"\\u0627\\u062d\\u062f\\u062b \\u0645\\u0646 \\u0627\\u0644\\u0645\\u062f\\u0648\\u0646\\u0629","sub_title":"\\u0627\\u062d\\u062f\\u062b \\u0627\\u0644\\u0627\\u062e\\u0628\\u0627\\u0631"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(89, 49, 'en', '{"hot_categories_title":"Useful Links","help_center_title":"Place of interest","address":"1000 5th Ave, New York, NY 10028, United States.","newsletter_subtitle":"Nula element eulimid olio nec zeugita celestas arco quis lobortis."}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(90, 49, 'ar', '{"shop_pages_title":"Hot Categories","help_center_title":"Place of interest","address":"1000 5th Ave, New York, NY 10028, \\u0627\\u0644\\u0648\\u0644\\u0627\\u064a\\u0627\\u062a \\u0627\\u0644\\u0645\\u062a\\u062d\\u062f\\u0629.","newsletter_subtitle":"Nula element eulimid olio nec zeugita celestas arco quis lobortis.","shop_time":"Monday - Friday : {8:00 AM - 6:00 PM}\\n                        Saturday : {10:00 AM - 6:00 PM}\\n                        Sunday: {Close}"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(91, 52, 'en', '{"mission_subtitle":"Our Mission","mission_title":"Responsible & {Long-lasting} Development.","mission_description":"Suspendisse ultrices vel ipsum tristique iaculis Suspendisse ehicula nibh non sapien dictum ultrices Etiam pellentesque egestas leo.","mission_1":"Nulla ex leo gravida eget consequat et tempus nitae risus","mission_2":"Nunc in enim sed nunc eleifend facilisis","mission_3":"Donec in enim sed nunc eleifend facilisis","mission_4":"Donec in enim sed nunc eleifend facilisis","mission_background_text":"Mission","vision_subtitle":"Our Vission","vision_title":"Principled & {Eco-Conscious} Development.","vision_description":"Suspendisse ultrices vel ipsum tristique iaculis Suspendisse ehicula nibh non sapien dictum ultrices Etiam pellentesque egestas leo.","vision_1":"Nulla ex leo gravida eget consequat et tempus nitae risus","vision_2":"Zaecenas accumsan dui et nisi ziverra suscipit eget lectus quis","vision_3":"Tliquam nec ziverra nibh quis nulla quarries ullamcorpe","vision_4":"Curabitu nunc nisl placerat sit amet arcu consectetur aliquet","vision_background_text":"VISSION","testimonial_sub_title":"Testimonials","testimonial_title":"Clients {Feedback}","blog_title":"Latest From {Blogs}","blog_sub_title":"Our Latest News","area_one_title":"Free Shipping","area_one_sub_title":"Standard shipping for orders","area_two_title":"Flexible Payment","area_two_sub_title":"Pay with Credit Cards","area_three_title":"14 Day Returns","area_three_sub_title":"30 Days for an Exchange","area_four_title":"Premium Support","area_four_sub_title":"All Outstanding Support"}', '2025-08-07 03:49:15', '2025-08-07 05:29:47'),
	(92, 52, 'ar', '{"mission_subtitle":"\\u0645\\u0647\\u0645\\u062a\\u0646\\u0627","mission_title":"\\u062a\\u0637\\u0648\\u064a\\u0631 \\u0645\\u0633\\u0624\\u0648\\u0644 \\u0648{\\u0637\\u0648\\u064a\\u0644 \\u0627\\u0644\\u0623\\u0645\\u062f}.","mission_description":"\\u062a\\u0639\\u0644\\u064a\\u0642 \\u0627\\u0644\\u062a\\u0631\\u0627\\u0643\\u064a\\u0628 \\u0639\\u0644\\u0649 \\u0634\\u0643\\u0644 \\u0634\\u0628\\u0643\\u0629\\u060c \\u0648\\u062a\\u0639\\u0644\\u064a\\u0642 \\u0627\\u0644\\u0645\\u0631\\u0643\\u0628\\u0629 \\u0623\\u0633\\u0641\\u0644\\u0647\\u0627 \\u0628\\u0634\\u0643\\u0644 \\u0644\\u0627 \\u064a\\u063a\\u064a\\u0631 \\u0645\\u0646 \\u0627\\u0644\\u0634\\u0643\\u0644 \\u0627\\u0644\\u0639\\u0627\\u0645. \\u0643\\u0630\\u0644\\u0643\\u060c \\u0641\\u0625\\u0646 \\u0627\\u062e\\u062a\\u064a\\u0627\\u0631 \\u0627\\u0644\\u0646\\u0645\\u0648\\u0630\\u062c \\u0627\\u0644\\u0645\\u0646\\u0627\\u0633\\u0628 \\u0644\\u0644\\u062d\\u0631\\u0643\\u0629 \\u064a\\u0639\\u062f \\u0623\\u0645\\u0631\\u0627\\u064b \\u0645\\u0647\\u0645\\u0627\\u064b.","mission_1":"\\u0644\\u0627 \\u0634\\u064a\\u0621 \\u0625\\u0644\\u0627 \\u0627\\u0644\\u0646\\u0645\\u0648 \\u0627\\u0644\\u0645\\u062a\\u0633\\u0642 \\u0648\\u0627\\u0644\\u0648\\u0642\\u062a \\u0627\\u0644\\u0645\\u0646\\u0627\\u0633\\u0628","mission_2":"\\u0627\\u0644\\u0622\\u0646 \\u0641\\u064a \\u0627\\u0644\\u062f\\u0627\\u062e\\u0644\\u060c \\u062b\\u0645 \\u062a\\u0628\\u0633\\u064a\\u0637 \\u0627\\u0644\\u062a\\u0646\\u0641\\u064a\\u0630","mission_3":"\\u062a\\u0645 \\u0627\\u0644\\u062a\\u0646\\u0641\\u064a\\u0630 \\u0628\\u0628\\u0633\\u0627\\u0637\\u0629 \\u0648\\u0633\\u0644\\u0627\\u0633\\u0629 \\u062f\\u0627\\u062e\\u0644 \\u0627\\u0644\\u0646\\u0638\\u0627\\u0645","mission_4":"\\u062a\\u0645 \\u0627\\u0644\\u062a\\u0646\\u0641\\u064a\\u0630 \\u0628\\u0628\\u0633\\u0627\\u0637\\u0629 \\u0648\\u0633\\u0644\\u0627\\u0633\\u0629 \\u062f\\u0627\\u062e\\u0644 \\u0627\\u0644\\u0646\\u0638\\u0627\\u0645","mission_background_text":"\\u0645\\u0647\\u0645\\u0629","vision_subtitle":"\\u0631\\u0624\\u064a\\u062a\\u0646\\u0627","vision_title":"\\u062a\\u0637\\u0648\\u064a\\u0631 \\u0642\\u0627\\u0626\\u0645 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0645\\u0628\\u0627\\u062f\\u0626 \\u0648{\\u0635\\u062f\\u064a\\u0642 \\u0644\\u0644\\u0628\\u064a\\u0626\\u0629}.","vision_description":"\\u062a\\u0639\\u0644\\u064a\\u0642 \\u0627\\u0644\\u062a\\u0631\\u0627\\u0643\\u064a\\u0628 \\u0639\\u0644\\u0649 \\u0634\\u0643\\u0644 \\u0634\\u0628\\u0643\\u0629\\u060c \\u0648\\u062a\\u0639\\u0644\\u064a\\u0642 \\u0627\\u0644\\u0645\\u0631\\u0643\\u0628\\u0629 \\u0623\\u0633\\u0641\\u0644\\u0647\\u0627 \\u0628\\u0634\\u0643\\u0644 \\u0644\\u0627 \\u064a\\u063a\\u064a\\u0631 \\u0645\\u0646 \\u0627\\u0644\\u0634\\u0643\\u0644 \\u0627\\u0644\\u0639\\u0627\\u0645. \\u0643\\u0630\\u0644\\u0643\\u060c \\u0641\\u0625\\u0646 \\u0627\\u062e\\u062a\\u064a\\u0627\\u0631 \\u0627\\u0644\\u0646\\u0645\\u0648\\u0630\\u062c \\u0627\\u0644\\u0645\\u0646\\u0627\\u0633\\u0628 \\u0644\\u0644\\u062d\\u0631\\u0643\\u0629 \\u064a\\u0639\\u062f \\u0623\\u0645\\u0631\\u0627\\u064b \\u0645\\u0647\\u0645\\u0627\\u064b.","vision_1":"\\u0644\\u0627 \\u0634\\u064a\\u0621 \\u0625\\u0644\\u0627 \\u0627\\u0644\\u0646\\u0645\\u0648 \\u0627\\u0644\\u0645\\u062a\\u0633\\u0642 \\u0648\\u0627\\u0644\\u0648\\u0642\\u062a \\u0627\\u0644\\u0645\\u0646\\u0627\\u0633\\u0628","vision_2":"\\u0627\\u0644\\u0627\\u062a\\u0633\\u0627\\u0642 \\u0648\\u0627\\u0644\\u0627\\u0639\\u062a\\u0645\\u0627\\u062f \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0630\\u0627\\u062a \\u0647\\u0648 \\u0645\\u0627 \\u0646\\u0633\\u0639\\u0649 \\u0625\\u0644\\u064a\\u0647","vision_3":"\\u0627\\u0644\\u062a\\u0631\\u0643\\u064a\\u0632 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0635\\u062f\\u0627\\u0642\\u0629 \\u0627\\u0644\\u0628\\u064a\\u0626\\u064a\\u0629 \\u0648\\u0627\\u0644\\u062c\\u0648\\u062f\\u0629 \\u0627\\u0644\\u0639\\u0627\\u0644\\u064a\\u0629","vision_4":"\\u0627\\u0644\\u062a\\u0637\\u0648\\u064a\\u0631 \\u0645\\u0646 \\u062e\\u0644\\u0627\\u0644 \\u0627\\u0644\\u062a\\u062e\\u0637\\u064a\\u0637 \\u0627\\u0644\\u0641\\u0639\\u0627\\u0644 \\u0648\\u0627\\u0644\\u0627\\u0628\\u062a\\u0643\\u0627\\u0631","vision_background_text":"\\u0631\\u0624\\u064a\\u0629","testimonial_sub_title":"\\u0622\\u0631\\u0627\\u0621 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621","testimonial_title":"{\\u062a\\u0639\\u0644\\u064a\\u0642\\u0627\\u062a} \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621","blog_title":"\\u0622\\u062e\\u0631 \\u0645\\u0627 \\u0646\\u064f\\u0634\\u0631 \\u0645\\u0646 {\\u0627\\u0644\\u0645\\u062f\\u0648\\u0646\\u0627\\u062a}","blog_sub_title":"\\u0623\\u062d\\u062f\\u062b \\u0623\\u062e\\u0628\\u0627\\u0631\\u0646\\u0627","area_one_title":"\\u0634\\u062d\\u0646 \\u0645\\u062c\\u0627\\u0646\\u064a","area_one_sub_title":"\\u0634\\u062d\\u0646 \\u0642\\u064a\\u0627\\u0633\\u064a \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0644\\u0628\\u0627\\u062a","area_two_title":"\\u062f\\u0641\\u0639 \\u0645\\u0631\\u0646","area_two_sub_title":"\\u0627\\u062f\\u0641\\u0639 \\u0628\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0628\\u0637\\u0627\\u0642\\u0627\\u062a \\u0627\\u0644\\u0627\\u0626\\u062a\\u0645\\u0627\\u0646","area_three_title":"\\u0625\\u0631\\u062c\\u0627\\u0639 \\u062e\\u0644\\u0627\\u0644 14 \\u064a\\u0648\\u0645\\u064b\\u0627","area_three_sub_title":"30 \\u064a\\u0648\\u0645\\u064b\\u0627 \\u0644\\u0644\\u062a\\u0628\\u062f\\u064a\\u0644","area_four_title":"\\u062f\\u0639\\u0645 \\u0645\\u0645\\u064a\\u0632","area_four_sub_title":"\\u062f\\u0639\\u0645 \\u0645\\u0645\\u062a\\u0627\\u0632 \\u0644\\u0644\\u062c\\u0645\\u064a\\u0639"}', '2025-08-07 03:49:15', '2025-08-07 03:49:15'),
	(93, 53, 'en', '{"contact_office_address":"7232 Broadway Suite 3087 Madison Heights, 57256","tema_up_message":"Sed nec libero ante odio mauris pellentesque eget et neque."}', '2025-08-07 03:49:15', '2025-08-07 05:29:58'),
	(94, 53, 'ar', '{"contact_office_address":"7232 Broadway Suite 3087 Madison Heights, 57256","tema_up_message":"Sed nec libero ante odio mauris pellentesque eget et neque."}', '2025-08-07 03:49:15', '2025-08-07 03:49:15');

-- Dumping structure for table topcommerce.seo_settings
DROP TABLE IF EXISTS `seo_settings`;
CREATE TABLE IF NOT EXISTS `seo_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_name` varchar(255) NOT NULL,
  `route` varchar(255) NOT NULL,
  `seo_title` text NOT NULL,
  `seo_description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.seo_settings: ~19 rows (approximately)
DELETE FROM `seo_settings`;
INSERT INTO `seo_settings` (`id`, `page_name`, `route`, `seo_title`, `seo_description`, `created_at`, `updated_at`) VALUES
	(1, 'Home Page', 'website.home', 'Home || WebSolutionUS', 'Home || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(2, 'Product Categories', 'website.categories', 'Product Categories || WebSolutionUS', 'Product Categories || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(3, 'Products', 'website.products', 'Products || WebSolutionUS', 'Products || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(4, 'Flash Deal Products', 'website.flash.deals', 'Flash Deal Products || WebSolutionUS', 'Flash Deal Products || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(5, 'Brands', 'website.brands', 'Brands || WebSolutionUS', 'Brands || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(6, 'Shops', 'website.shops', 'Shops || WebSolutionUS', 'Shops || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(7, 'Gift Cards || Coupons', 'website.gift.cards', 'Gift Cards || Coupons || WebSolutionUS', 'Gift Cards || Coupons || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(8, 'Frequently Asked Questions', 'website.faq', 'Frequently Asked Questions || WebSolutionUS', 'Frequently Asked Questions || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(9, 'Privacy Policy Page', 'website.privacy.policy', 'Privacy Policy || WebSolutionUS', 'Privacy Policy || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(10, 'Terms and conditions Page', 'website.terms.and.conditions', 'Terms and conditions Page || WebSolutionUS', 'Terms and conditions Page || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(11, 'Return-policy Page', 'website.return.policy', 'Return-policy Page || WebSolutionUS', 'Return-policy Page || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(12, 'Contact Page', 'website.contact.us', 'Contact || WebSolutionUS', 'Contact || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(13, 'Track Orders', 'website.track.order', 'Track Orders || WebSolutionUS', 'Track Orders || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(14, 'About Page', 'website.about.us', 'About || WebSolutionUS', 'About || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(15, 'Blog Page', 'website.blogs', 'Blog || WebSolutionUS', 'Blog || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(16, 'Join as Seller', 'website.join-as-seller', 'Join as Seller || WebSolutionUS', 'Join as Seller || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(17, 'Login', 'login', 'Login || WebSolutionUS', 'Login || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(18, 'Account Register', 'register', 'Account Register || WebSolutionUS', 'Account Register || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(19, 'Cart', 'website.cart', 'Cart || WebSolutionUS', 'Cart || WebSolutionUS', '2025-08-07 03:49:14', '2025-08-07 03:49:14');

-- Dumping structure for table topcommerce.settings
DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.settings: ~73 rows (approximately)
DELETE FROM `settings`;
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
	(1, 'app_name', 'Topcommerce', '2025-08-07 03:49:13', '2025-08-07 03:51:39'),
	(2, 'version', '3.0.0', '2025-08-07 03:49:13', '2025-08-07 03:49:13'),
	(3, 'logo', 'website/images/logo.webp', '2025-08-07 03:49:13', '2025-08-07 03:49:13'),
	(4, 'logo_dark', 'website/images/logo_2.webp', '2025-08-07 03:49:13', '2025-08-07 03:49:13'),
	(5, 'timezone', 'Asia/Dhaka', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(6, 'date_format', 'Y-m-d', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(7, 'time_format', 'h:i A', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(8, 'favicon', 'uploads/website-images/favicon.png', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(9, 'cookie_status', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(10, 'border', 'normal', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(11, 'corners', 'thin', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(12, 'background_color', '#184dec', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(13, 'text_color', '#fafafa', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(14, 'border_color', '#0a58d6', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(15, 'btn_bg_color', '#fffceb', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(16, 'btn_text_color', '#222758', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(17, 'link_text', 'Privacy Policy', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(18, 'btn_text', 'Yes', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(19, 'message', 'This website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it. The latter will be set only upon approval.', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(20, 'copyright_text', '©2025 WebSolutionUS. All rights reserved.', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(21, 'recaptcha_site_key', 'recaptcha_site_key', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(22, 'recaptcha_secret_key', 'recaptcha_secret_key', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(23, 'recaptcha_status', 'inactive', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(24, 'tawk_status', 'inactive', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(25, 'tawk_chat_link', 'tawk_chat_link', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(26, 'googel_tag_status', 'inactive', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(27, 'googel_tag_id', 'google_tag_id', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(28, 'google_analytic_status', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(29, 'google_analytic_id', 'google_analytic_id', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(30, 'pixel_status', 'inactive', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(31, 'pixel_app_id', 'pixel_app_id', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(32, 'google_login_status', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(33, 'google_client_id', 'google_client_id', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(34, 'google_secret_id', 'google_secret_id', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(35, 'default_avatar', 'uploads/website-images/default-avatar.png', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(36, 'default_user_image', 'uploads/website-images/default-user-image.png', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(37, 'breadcrumb_image', 'website/images/breadcrumbs_bg.webp', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(38, 'admin_auth_bg', 'backend/img/admin-auth-bg.webp', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(39, 'admin_login_prefix', 'admin', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(40, 'mail_host', 'sandbox.smtp.mailtrap.io', '2025-08-07 03:49:14', '2025-08-07 05:03:40'),
	(41, 'mail_sender_email', 'sender@gmail.com', '2025-08-07 03:49:14', '2025-08-07 05:03:40'),
	(42, 'mail_username', 'mail_username', '2025-08-07 03:49:14', '2025-08-07 05:03:40'),
	(43, 'mail_password', 'mail_password', '2025-08-07 03:49:14', '2025-08-07 05:03:40'),
	(44, 'mail_port', '2525', '2025-08-07 03:49:14', '2025-08-07 05:03:40'),
	(45, 'mail_encryption', 'ssl', '2025-08-07 03:49:14', '2025-08-07 05:03:40'),
	(46, 'mail_sender_name', 'WebSolutionUs', '2025-08-07 03:49:14', '2025-08-07 05:03:40'),
	(47, 'contact_message_receiver_mail', 'receiver@gmail.com', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(48, 'pusher_status', 'inactive', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(49, 'pusher_app_id', 'pusher_app_id', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(50, 'pusher_app_key', 'pusher_app_key', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(51, 'pusher_app_secret', 'pusher_app_secret', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(52, 'pusher_app_cluster', 'pusher_app_cluster', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(53, 'maintenance_mode', '0', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(54, 'maintenance_image', 'uploads/website-images/maintenance.jpg', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(55, 'maintenance_title', 'Website Under maintenance', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(56, 'maintenance_description', '<p>We are currently performing maintenance on our website to<br>improve your experience. Please check back later.</p>\n            <p><a title="Websolutions" href="https://websolutionus.com/">Websolutions</a></p>', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(57, 'last_update_date', '2024-03-12 12:00:00', '2025-08-07 03:49:14', '2025-08-07 03:51:39'),
	(58, 'is_queueable', 'inactive', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(59, 'comments_auto_approved', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(60, 'search_engine_indexing', 'active', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(61, 'theme', '4', '2025-08-07 03:49:14', '2025-08-07 05:27:52'),
	(62, 'has_vendor', '1', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(63, 'has_app', '0', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(64, 'can_guest_checkout', '1', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(65, 'sku_prefix', 'SKU-', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(66, 'invoice_prefix', 'INV-', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(67, 'sku_length', '8', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(68, 'invoice_length', '8', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(69, 'wallet_amount_auto_approve', '1', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(70, 'product_commission_rate', '0', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(71, 'order_cancel_minutes_before', '35', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(72, 'show_all_homepage', '0', '2025-08-07 03:49:14', '2025-08-07 03:49:14'),
	(73, 'marketing_status', '1', '2025-08-07 03:49:14', '2025-08-07 03:49:14');

-- Dumping structure for table topcommerce.shipping_rules
DROP TABLE IF EXISTS `shipping_rules`;
CREATE TABLE IF NOT EXISTS `shipping_rules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `type` varchar(60) DEFAULT 'based_on_price',
  `currency_id` bigint(20) unsigned DEFAULT NULL,
  `from` decimal(15,2) DEFAULT 0.00,
  `to` decimal(15,2) DEFAULT 0.00,
  `price` decimal(15,2) DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.shipping_rules: ~0 rows (approximately)
DELETE FROM `shipping_rules`;

-- Dumping structure for table topcommerce.shipping_rule_items
DROP TABLE IF EXISTS `shipping_rule_items`;
CREATE TABLE IF NOT EXISTS `shipping_rule_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shipping_rule_id` bigint(20) unsigned NOT NULL,
  `country_id` text DEFAULT NULL,
  `state_id` text DEFAULT NULL,
  `city_id` text DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.shipping_rule_items: ~0 rows (approximately)
DELETE FROM `shipping_rule_items`;

-- Dumping structure for table topcommerce.shipping_settings
DROP TABLE IF EXISTS `shipping_settings`;
CREATE TABLE IF NOT EXISTS `shipping_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hide_other_shipping` tinyint(1) NOT NULL DEFAULT 0,
  `hide_shipping_option` tinyint(1) NOT NULL DEFAULT 0,
  `sort_shipping_direction` varchar(255) NOT NULL DEFAULT 'asc',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.shipping_settings: ~1 rows (approximately)
DELETE FROM `shipping_settings`;
INSERT INTO `shipping_settings` (`id`, `hide_other_shipping`, `hide_shipping_option`, `sort_shipping_direction`, `created_at`, `updated_at`) VALUES
	(1, 0, 0, 'asc', '2025-08-07 03:49:26', '2025-08-07 03:49:26');

-- Dumping structure for table topcommerce.socialite_credentials
DROP TABLE IF EXISTS `socialite_credentials`;
CREATE TABLE IF NOT EXISTS `socialite_credentials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `provider_name` varchar(255) NOT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `refresh_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.socialite_credentials: ~0 rows (approximately)
DELETE FROM `socialite_credentials`;

-- Dumping structure for table topcommerce.states
DROP TABLE IF EXISTS `states`;
CREATE TABLE IF NOT EXISTS `states` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `country_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `states_country_id_foreign` (`country_id`),
  CONSTRAINT `states_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.states: ~0 rows (approximately)
DELETE FROM `states`;

-- Dumping structure for table topcommerce.stocks
DROP TABLE IF EXISTS `stocks`;
CREATE TABLE IF NOT EXISTS `stocks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stocks_sku_unique` (`sku`),
  KEY `stocks_product_id_foreign` (`product_id`),
  CONSTRAINT `stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table topcommerce.tags
DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.tags: ~0 rows (approximately)
DELETE FROM `tags`;

-- Dumping structure for table topcommerce.tag_translations
DROP TABLE IF EXISTS `tag_translations`;
CREATE TABLE IF NOT EXISTS `tag_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` bigint(20) unsigned NOT NULL,
  `lang_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_translations_tag_id_lang_code_unique` (`tag_id`,`lang_code`),
  CONSTRAINT `tag_translations_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.tag_translations: ~0 rows (approximately)
DELETE FROM `tag_translations`;

-- Dumping structure for table topcommerce.taxes
DROP TABLE IF EXISTS `taxes`;
CREATE TABLE IF NOT EXISTS `taxes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `percentage` decimal(8,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.taxes: ~0 rows (approximately)
DELETE FROM `taxes`;

-- Dumping structure for table topcommerce.tax_translations
DROP TABLE IF EXISTS `tax_translations`;
CREATE TABLE IF NOT EXISTS `tax_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tax_id` bigint(20) unsigned NOT NULL,
  `lang_code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tax_translations_tax_id_foreign` (`tax_id`),
  CONSTRAINT `tax_translations_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table topcommerce.testimonials
DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  `rating` varchar(255) DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.testimonials: ~0 rows (approximately)
DELETE FROM `testimonials`;

-- Dumping structure for table topcommerce.testimonial_translations
DROP TABLE IF EXISTS `testimonial_translations`;
CREATE TABLE IF NOT EXISTS `testimonial_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `testimonial_id` bigint(20) unsigned NOT NULL,
  `lang_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `testimonial_translations_lang_code_index` (`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.testimonial_translations: ~0 rows (approximately)
DELETE FROM `testimonial_translations`;

-- Dumping structure for table topcommerce.transaction_histories
DROP TABLE IF EXISTS `transaction_histories`;
CREATE TABLE IF NOT EXISTS `transaction_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `vendor_id` bigint(20) unsigned DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_details` text DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'success',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.transaction_histories: ~0 rows (approximately)
DELETE FROM `transaction_histories`;

-- Dumping structure for table topcommerce.unit_types
DROP TABLE IF EXISTS `unit_types`;
CREATE TABLE IF NOT EXISTS `unit_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `ShortName` varchar(192) NOT NULL,
  `base_unit` int(11) DEFAULT NULL,
  `operator` char(192) DEFAULT '*',
  `operator_value` double DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `base_unit` (`base_unit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.unit_types: ~0 rows (approximately)
DELETE FROM `unit_types`;

-- Dumping structure for table topcommerce.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `country_id` varchar(255) DEFAULT NULL,
  `state_id` varchar(255) DEFAULT NULL,
  `city_id` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `forget_password_token` varchar(255) DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `is_banned` varchar(255) NOT NULL DEFAULT 'no',
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `wallet_balance` decimal(8,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.users: ~0 rows (approximately)
DELETE FROM `users`;

-- Dumping structure for table topcommerce.variants
DROP TABLE IF EXISTS `variants`;
CREATE TABLE IF NOT EXISTS `variants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `offer_price` decimal(10,2) DEFAULT NULL,
  `offer_price_type` enum('fixed','percentage') DEFAULT 'fixed',
  `offer_price_start` date DEFAULT NULL,
  `offer_price_end` date DEFAULT NULL,
  `is_flash_deal` tinyint(1) NOT NULL DEFAULT 0,
  `flash_deal_start` date DEFAULT NULL,
  `flash_deal_end` date DEFAULT NULL,
  `flash_deal_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `flash_deal_qty` int(11) NOT NULL DEFAULT 0,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `variants_sku_unique` (`sku`),
  KEY `variants_product_id_foreign` (`product_id`),
  CONSTRAINT `variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.variants: ~0 rows (approximately)
DELETE FROM `variants`;

-- Dumping structure for table topcommerce.variant_options
DROP TABLE IF EXISTS `variant_options`;
CREATE TABLE IF NOT EXISTS `variant_options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `variant_id` bigint(20) unsigned NOT NULL,
  `attribute_id` bigint(20) unsigned NOT NULL,
  `attribute_value_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `variant_options_variant_id_foreign` (`variant_id`),
  KEY `variant_options_attribute_id_foreign` (`attribute_id`),
  KEY `variant_options_attribute_value_id_foreign` (`attribute_value_id`),
  CONSTRAINT `variant_options_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `variant_options_attribute_value_id_foreign` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE,
  CONSTRAINT `variant_options_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table topcommerce.vendors
DROP TABLE IF EXISTS `vendors`;
CREATE TABLE IF NOT EXISTS `vendors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `logo_image` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `shop_name` varchar(255) NOT NULL,
  `shop_slug` varchar(255) NOT NULL,
  `open_at` varchar(255) DEFAULT NULL,
  `closed_at` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `seo_title` text DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `is_featured` int(11) NOT NULL DEFAULT 0,
  `top_rated` int(11) NOT NULL DEFAULT 0,
  `verification_token` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.vendors: ~0 rows (approximately)
DELETE FROM `vendors`;

-- Dumping structure for table topcommerce.wallet_histories
DROP TABLE IF EXISTS `wallet_histories`;
CREATE TABLE IF NOT EXISTS `wallet_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `vendor_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `order_details_id` bigint(20) unsigned DEFAULT NULL,
  `withdraw_request_id` bigint(20) unsigned DEFAULT NULL,
  `transaction_type` enum('credit','debit') NOT NULL DEFAULT 'credit',
  `amount` decimal(8,2) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `payment_gateway` varchar(255) NOT NULL,
  `payment_status` enum('pending','processing','completed','failed','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wallet_histories_user_id_foreign` (`user_id`),
  CONSTRAINT `wallet_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping structure for table topcommerce.wishlists
DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE IF NOT EXISTS `wishlists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.wishlists: ~0 rows (approximately)
DELETE FROM `wishlists`;

-- Dumping structure for table topcommerce.withdraw_methods
DROP TABLE IF EXISTS `withdraw_methods`;
CREATE TABLE IF NOT EXISTS `withdraw_methods` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `min_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `max_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `withdraw_charge` decimal(8,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table topcommerce.withdraw_methods: ~0 rows (approximately)
DELETE FROM `withdraw_methods`;

-- Dumping structure for table topcommerce.withdraw_requests
DROP TABLE IF EXISTS `withdraw_requests`;
CREATE TABLE IF NOT EXISTS `withdraw_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `vendor_id` bigint(20) unsigned DEFAULT NULL,
  `method` varchar(255) NOT NULL,
  `total_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `withdraw_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `withdraw_charge` decimal(8,2) NOT NULL DEFAULT 0.00,
  `account_info` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_date` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `withdraw_requests_user_id_foreign` (`user_id`),
  CONSTRAINT `withdraw_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

SET FOREIGN_KEY_CHECKS=1;
