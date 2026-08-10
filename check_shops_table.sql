-- Raw SQL Commands to Check Shops Table Structure
-- ==================================================

-- 1. Check shops table structure
DESCRIBE shops;

-- 2. Check if district_id column exists in shops table
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'shops' 
AND COLUMN_NAME = 'district_id';

-- 3. Check all columns in shops table
SHOW COLUMNS FROM shops;

-- 4. Check if there are any shops with district data
SELECT 
    id,
    name,
    shop_name,
    district_id,
    CASE 
        WHEN district_id IS NOT NULL THEN 'Has district_id'
        ELSE 'NO district_id'
    END as district_status
FROM shops 
LIMIT 5;

-- 5. Check if districts table exists and has data
SELECT 
    TABLE_NAME,
    TABLE_ROWS,
    CREATE_TIME
FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'districts';

-- 6. Check districts table structure
DESCRIBE districts;

-- 7. Check sample districts data
SELECT * FROM districts LIMIT 5;

-- 8. Check if there are any foreign key constraints
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'shops';

-- 9. Quick fix: Add district_id column if it doesn't exist
ALTER TABLE shops 
ADD COLUMN district_id BIGINT UNSIGNED NULL 
AFTER phone_number;

-- 10. Add foreign key constraint if districts table exists
ALTER TABLE shops 
ADD CONSTRAINT shops_district_id_foreign 
FOREIGN KEY (district_id) REFERENCES districts(id) 
ON DELETE SET NULL;

-- 11. Create index for better performance
CREATE INDEX idx_shops_district_id ON shops(district_id);
