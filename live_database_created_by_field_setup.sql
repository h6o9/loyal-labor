-- ==========================================
-- RAW SQL COMMANDS FOR LIVE DATABASE
-- ADD AND SETUP CREATED_BY FIELD FOR SHOPS
-- ==========================================

-- This script adds the created_by field to shops table
-- and sets up proper relationships for Created By functionality

-- STEP 1: CHECK IF CREATED_BY FIELD EXISTS
-- ==========================================

-- Check if created_by field already exists in shops table
-- (Run this query first to verify)
SHOW COLUMNS FROM shops LIKE 'created_by';

-- STEP 2: ADD CREATED_BY FIELD IF NOT EXISTS
-- ==========================================

-- Add created_by field to shops table
ALTER TABLE shops 
ADD COLUMN created_by INT NULL 
AFTER staff_id;

-- Add foreign key constraint for created_by field
ALTER TABLE shops 
ADD CONSTRAINT fk_shops_created_by 
FOREIGN KEY (created_by) REFERENCES staff(id) 
ON DELETE SET NULL;

-- STEP 3: POPULATE CREATED_BY FIELD FOR EXISTING SHOPS
-- ==========================================

-- For shops that have staff_id, copy that to created_by
-- This assumes the staff member assigned to the shop is also the creator
UPDATE shops 
SET created_by = staff_id 
WHERE staff_id IS NOT NULL AND created_by IS NULL;

-- If you want to set a specific staff member as creator for all shops
-- (Replace 1 with the actual staff ID you want to set as default)
-- UPDATE shops 
-- SET created_by = 1 
-- WHERE created_by IS NULL;

-- STEP 4: VERIFICATION QUERIES
-- ==========================================

-- Check shops table structure
DESCRIBE shops;

-- Check created_by field values
SELECT 
    id,
    shop_name,
    staff_id,
    created_by,
    created_at
FROM shops 
ORDER BY id;

-- Check staff members who created shops
SELECT 
    s.id as shop_id,
    s.shop_name,
    s.created_by,
    st.name as staff_name,
    st.email as staff_email
FROM shops s
LEFT JOIN staff st ON s.created_by = st.id
ORDER BY s.id;

-- STEP 5: TEST RELATIONSHIP (FOR DEBUGGING)
-- ==========================================

-- This query simulates what the Laravel relationship would return
-- You can use this to verify the data is correct
SELECT 
    s.id,
    s.shop_name,
    s.owner_name,
    s.phone,
    CASE 
        WHEN st.name IS NOT NULL THEN st.name
        ELSE 'System'
    END as created_by_name,
    CASE 
        WHEN st.name IS NOT NULL THEN CONCAT('badge-info: ', st.name)
        ELSE 'text-muted: System'
    END as display_format
FROM shops s
LEFT JOIN staff st ON s.created_by = st.id
ORDER BY s.id;

-- STEP 6: CLEANUP AND FINAL VERIFICATION
-- ==========================================

-- Show summary of created_by field setup
SELECT 
    'CREATED_BY FIELD SETUP SUMMARY' as info,
    COUNT(*) as total_shops,
    COUNT(created_by) as shops_with_creator,
    COUNT(*) - COUNT(created_by) as shops_without_creator,
    CASE 
        WHEN COUNT(created_by) > 0 THEN '✅ Created By functionality should work'
        ELSE '❌ No shops have creator assigned'
    END as status
FROM shops;

-- ==========================================
-- TROUBLESHOOTING NOTES
-- ==========================================

-- If the Created By column still shows "System" for all shops:
-- 1. Check if created_by field exists: SHOW COLUMNS FROM shops LIKE 'created_by';
-- 2. Check if data exists: SELECT created_by FROM shops;
-- 3. Check staff table: SELECT * FROM staff LIMIT 5;
-- 4. Verify foreign key: SHOW CREATE TABLE shops;

-- If you get foreign key errors:
-- 1. Make sure staff table exists and has records
-- 2. Check if staff_id values exist in staff table
-- 3. Remove foreign key constraint and add it later

-- If you need to reset the created_by field:
-- ALTER TABLE shops DROP FOREIGN KEY fk_shops_created_by;
-- ALTER TABLE shops DROP COLUMN created_by;

-- ==========================================
-- AFTER RUNNING THIS SQL:
-- ==========================================
-- 1. Upload the 3 files to live server:
--    - app/Models/Shop.php
--    - app/Http/Controllers/Admin/ShopManagementController.php
--    - resources/views/admin/shop-management/shopindex.blade.php
-- 2. Clear Laravel cache: php artisan cache:clear
-- 3. Test the shop management page
-- ==========================================
