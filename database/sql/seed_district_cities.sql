-- District cities for dropdown: select district -> load cities by district_id
-- Run on LIVE phpMyAdmin (u896217089_homeservices)

CREATE TABLE IF NOT EXISTS `district_cities` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `district_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `district_city_unique` (`district_id`, `name`),
  KEY `district_cities_district_id_index` (`district_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dummy cities are attached using existing district names/IDs

INSERT IGNORE INTO district_cities (district_id, name, status, created_at, updated_at)
SELECT d.id, c.name, 'active', NOW(), NOW()
FROM districts d
JOIN (
    SELECT 'Lahore' AS district_name, 'Johar Town' AS name UNION ALL
    SELECT 'Lahore', 'Gulberg' UNION ALL
    SELECT 'Lahore', 'DHA' UNION ALL
    SELECT 'Lahore', 'Model Town' UNION ALL
    SELECT 'Lahore', 'Allama Iqbal Town' UNION ALL
    SELECT 'Lahore', 'Township' UNION ALL
    SELECT 'Lahore', 'Cantt' UNION ALL
    SELECT 'Lahore', 'Wapda Town' UNION ALL
    SELECT 'Lahore', 'Bahria Town' UNION ALL
    SELECT 'Lahore', 'Garden Town' UNION ALL
    SELECT 'Karachi', 'Clifton' UNION ALL
    SELECT 'Karachi', 'Gulshan-e-Iqbal' UNION ALL
    SELECT 'Karachi', 'North Nazimabad' UNION ALL
    SELECT 'Karachi', 'PECHS' UNION ALL
    SELECT 'Karachi', 'DHA' UNION ALL
    SELECT 'Karachi', 'Saddar' UNION ALL
    SELECT 'Karachi', 'Korangi' UNION ALL
    SELECT 'Karachi', 'Malir' UNION ALL
    SELECT 'Karachi', 'Gulistan-e-Johar' UNION ALL
    SELECT 'Karachi', 'Lyari' UNION ALL
    SELECT 'Islamabad', 'F-6' UNION ALL
    SELECT 'Islamabad', 'F-7' UNION ALL
    SELECT 'Islamabad', 'F-8' UNION ALL
    SELECT 'Islamabad', 'G-9' UNION ALL
    SELECT 'Islamabad', 'G-10' UNION ALL
    SELECT 'Islamabad', 'G-11' UNION ALL
    SELECT 'Islamabad', 'Blue Area' UNION ALL
    SELECT 'Islamabad', 'I-8' UNION ALL
    SELECT 'Rawalpindi', 'Saddar' UNION ALL
    SELECT 'Rawalpindi', 'Satellite Town' UNION ALL
    SELECT 'Rawalpindi', 'Bahria Town' UNION ALL
    SELECT 'Rawalpindi', 'DHA' UNION ALL
    SELECT 'Faisalabad', 'Madina Town' UNION ALL
    SELECT 'Faisalabad', 'Peoples Colony' UNION ALL
    SELECT 'Faisalabad', 'D Ground' UNION ALL
    SELECT 'Multan', 'Cantt' UNION ALL
    SELECT 'Multan', 'Gulgasht Colony' UNION ALL
    SELECT 'Peshawar', 'Hayatabad' UNION ALL
    SELECT 'Peshawar', 'University Town' UNION ALL
    SELECT 'Peshawar', 'Saddar' UNION ALL
    SELECT 'Quetta', 'Jinnah Town' UNION ALL
    SELECT 'Quetta', 'Satellite Town' UNION ALL
    SELECT 'Central District', 'Downtown' UNION ALL
    SELECT 'Central District', 'City Center' UNION ALL
    SELECT 'North District', 'North Town' UNION ALL
    SELECT 'North District', 'Hill View' UNION ALL
    SELECT 'South District', 'South Town' UNION ALL
    SELECT 'South District', 'Harbor Area' UNION ALL
    SELECT 'East District', 'East Town' UNION ALL
    SELECT 'East District', 'Industrial Area' UNION ALL
    SELECT 'West District', 'West Town' UNION ALL
    SELECT 'West District', 'Garden Area'
) c ON LOWER(d.name) LIKE CONCAT('%', LOWER(c.district_name), '%');

-- Fallback: if a district has no city yet, add 3 dummy areas
INSERT IGNORE INTO district_cities (district_id, name, status, created_at, updated_at)
SELECT d.id, CONCAT(d.name, ' City Center'), 'active', NOW(), NOW()
FROM districts d
WHERE NOT EXISTS (
    SELECT 1 FROM district_cities dc WHERE dc.district_id = d.id
);

INSERT IGNORE INTO district_cities (district_id, name, status, created_at, updated_at)
SELECT d.id, CONCAT(d.name, ' Town'), 'active', NOW(), NOW()
FROM districts d
WHERE (SELECT COUNT(*) FROM district_cities dc WHERE dc.district_id = d.id) < 2;

INSERT IGNORE INTO district_cities (district_id, name, status, created_at, updated_at)
SELECT d.id, CONCAT(d.name, ' Cantt'), 'active', NOW(), NOW()
FROM districts d
WHERE (SELECT COUNT(*) FROM district_cities dc WHERE dc.district_id = d.id) < 3;
