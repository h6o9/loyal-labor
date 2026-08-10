-- ==========================================
-- RAW SQL COMMANDS FOR LIVE DATABASE
-- DISTRICT SYSTEM DATABASE SETUP
-- ==========================================

-- This script adds district_id fields to staff and shops tables
-- and sets up proper relationships for District functionality

-- STEP 1: ADD DISTRICT_ID FIELD TO STAFF TABLE
-- ==========================================

-- Check if district_id field exists in staff table
SHOW COLUMNS FROM staff LIKE 'district_id';

-- Add district_id field to staff table
ALTER TABLE staff 
ADD COLUMN district_id INT NULL 
AFTER is_active;

-- Add foreign key constraint for district_id field
ALTER TABLE staff 
ADD CONSTRAINT fk_staff_district 
FOREIGN KEY (district_id) REFERENCES districts(id) 
ON DELETE SET NULL;

-- STEP 2: ADD DISTRICT_ID FIELD TO SHOPS TABLE
-- ==========================================

-- Check if district_id field exists in shops table
SHOW COLUMNS FROM shops LIKE 'district_id';

-- Add district_id field to shops table
ALTER TABLE shops 
ADD COLUMN district_id INT NULL 
AFTER staff_id;

-- Add foreign key constraint for district_id field
ALTER TABLE shops 
ADD CONSTRAINT fk_shops_district 
FOREIGN KEY (district_id) REFERENCES districts(id) 
ON DELETE SET NULL;

-- STEP 3: VERIFICATION QUERIES
-- ==========================================

-- Check staff table structure
DESCRIBE staff;

-- Check shops table structure
DESCRIBE shops;

-- Check districts table exists and has data
SELECT * FROM districts LIMIT 5;

-- STEP 4: POPULATE DISTRICT_ID FOR EXISTING DATA (OPTIONAL)
-- ==========================================

-- If you want to assign a default district to existing staff
-- (Replace 1 with the actual district ID you want to set as default)
-- UPDATE staff 
-- SET district_id = 1 
-- WHERE district_id IS NULL;

-- If you want to assign a default district to existing shops
-- (Replace 1 with the actual district ID you want to set as default)
-- UPDATE shops 
-- SET district_id = 1 
-- WHERE district_id IS NULL;

-- STEP 5: FINAL VERIFICATION
-- ==========================================

-- Show summary of district_id field setup
SELECT 
    'DISTRICT_ID FIELD SETUP SUMMARY' as info,
    'staff' as table_name,
    COUNT(district_id) as records_with_district,
    COUNT(*) - COUNT(district_id) as records_without_district
FROM staff
UNION ALL
SELECT 
    'DISTRICT_ID FIELD SETUP SUMMARY' as info,
    'shops' as table_name,
    COUNT(district_id) as records_with_district,
    COUNT(*) - COUNT(district_id) as records_without_district
FROM shops;

-- ==========================================
-- TROUBLESHOOTING NOTES
-- ==========================================

-- If you get foreign key errors:
-- 1. Make sure districts table exists and has records
-- 2. Check if district_id values exist in districts table
-- 3. Remove foreign key constraint and add it later

-- If you need to reset the district_id field:
-- ALTER TABLE staff DROP FOREIGN KEY fk_staff_district;
-- ALTER TABLE staff DROP COLUMN district_id;

-- ALTER TABLE shops DROP FOREIGN KEY fk_shops_district;
-- ALTER TABLE shops DROP COLUMN district_id;

-- ==========================================
-- AFTER RUNNING THIS SQL:
-- ==========================================
-- 1. Update Staff model with district relationship
-- 2. Update Shop model with district relationship
-- 3. Update forms and controllers
-- 4. Add permission checks
-- 5. Test the district functionality
-- ==========================================
