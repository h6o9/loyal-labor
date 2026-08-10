-- ==========================================
-- RAW SQL COMMANDS FOR LIVE DATABASE
-- COMPLETE DISTRICT SYSTEM SETUP
-- ==========================================

-- This script includes:
-- 1. District data insertion
-- 2. District permissions creation
-- 3. Permission assignments to admin users and roles

-- STEP 1: INSERT DISTRICT DATA
-- ==========================================

-- Insert sample districts
INSERT INTO districts (name, status, created_at, updated_at) VALUES 
('Central District', 'active', NOW(), NOW()),
('North District', 'active', NOW(), NOW()),
('South District', 'active', NOW(), NOW()),
('East District', 'active', NOW(), NOW()),
('West District', 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Verify districts inserted
SELECT id, name, status FROM districts;

-- STEP 2: CREATE DISTRICT PERMISSIONS
-- ==========================================

-- Create district.view permission
INSERT INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('district.view', 'admin', 'district', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Create staff.assign.district permission
INSERT INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('staff.assign.district', 'admin', 'staff', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Create staff.view.district permission
INSERT INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('staff.view.district', 'admin', 'staff', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Create shop.view.district permission
INSERT INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('shop.view.district', 'admin', 'shop', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Create job.assign.district permission
INSERT INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('job.assign.district', 'admin', 'job', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- STEP 3: GET PERMISSION IDs
-- ==========================================

SET @district_view_id = (SELECT id FROM permissions WHERE name = 'district.view' AND guard_name = 'admin');
SET @staff_assign_district_id = (SELECT id FROM permissions WHERE name = 'staff.assign.district' AND guard_name = 'admin');
SET @staff_view_district_id = (SELECT id FROM permissions WHERE name = 'staff.view.district' AND guard_name = 'admin');
SET @shop_view_district_id = (SELECT id FROM permissions WHERE name = 'shop.view.district' AND guard_name = 'admin');
SET @job_assign_district_id = (SELECT id FROM permissions WHERE name = 'job.assign.district' AND guard_name = 'admin');

-- STEP 4: ASSIGN PERMISSIONS TO ADMIN USER (ID 1)
-- ==========================================

-- Remove existing assignments to avoid duplicates
DELETE FROM model_has_permissions 
WHERE permission_id IN (
    @district_view_id, @staff_assign_district_id, @staff_view_district_id, 
    @shop_view_district_id, @job_assign_district_id
) AND model_type = 'App\\Models\\Admin' AND model_id = 1;

-- Assign permissions to admin user (ID 1)
INSERT INTO model_has_permissions (permission_id, model_type, model_id) 
VALUES 
(@district_view_id, 'App\\Models\\Admin', 1),
(@staff_assign_district_id, 'App\\Models\\Admin', 1),
(@staff_view_district_id, 'App\\Models\\Admin', 1),
(@shop_view_district_id, 'App\\Models\\Admin', 1),
(@job_assign_district_id, 'App\\Models\\Admin', 1);

-- STEP 5: ASSIGN PERMISSIONS TO ASSISTANT MANAGER (ID 8)
-- ==========================================

-- Remove existing assignments to avoid duplicates
DELETE FROM model_has_permissions 
WHERE permission_id IN (
    @district_view_id, @staff_assign_district_id, @staff_view_district_id, 
    @shop_view_district_id, @job_assign_district_id
) AND model_type = 'App\\Models\\Admin' AND model_id = 8;

-- Assign permissions to Assistant Manager (ID 8)
INSERT INTO model_has_permissions (permission_id, model_type, model_id) 
VALUES 
(@district_view_id, 'App\\Models\\Admin', 8),
(@staff_assign_district_id, 'App\\Models\\Admin', 8),
(@staff_view_district_id, 'App\\Models\\Admin', 8),
(@shop_view_district_id, 'App\\Models\\Admin', 8),
(@job_assign_district_id, 'App\\Models\\Admin', 8);

-- STEP 6: ASSIGN PERMISSIONS TO ADMIN ROLES
-- ==========================================

-- Remove existing role assignments to avoid duplicates
DELETE FROM role_has_permissions 
WHERE permission_id IN (
    @district_view_id, @staff_assign_district_id, @staff_view_district_id, 
    @shop_view_district_id, @job_assign_district_id
);

-- Assign permissions to admin role (ID 1)
INSERT INTO role_has_permissions (role_id, permission_id) 
VALUES 
(1, @district_view_id),
(1, @staff_assign_district_id),
(1, @staff_view_district_id),
(1, @shop_view_district_id),
(1, @job_assign_district_id);

-- Assign permissions to Assistant Manager role (ID 6)
INSERT INTO role_has_permissions (role_id, permission_id) 
VALUES 
(6, @district_view_id),
(6, @staff_assign_district_id),
(6, @staff_view_district_id),
(6, @shop_view_district_id),
(6, @job_assign_district_id);

-- STEP 7: VERIFICATION QUERIES
-- ==========================================

-- Check districts
SELECT id, name, status FROM districts ORDER BY id;

-- Check permissions
SELECT id, name, guard_name, group_name 
FROM permissions 
WHERE name IN ('district.view', 'staff.assign.district', 'staff.view.district', 'shop.view.district', 'job.assign.district') 
AND guard_name = 'admin'
ORDER BY name;

-- Check admin user (ID 1) permissions
SELECT 
    a.name as admin_name,
    a.email as admin_email,
    p.name as permission_name
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
JOIN admins a ON mhp.model_id = a.id
WHERE p.name IN ('district.view', 'staff.assign.district', 'staff.view.district', 'shop.view.district', 'job.assign.district') 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id = 1
ORDER BY p.name;

-- Check role permissions
SELECT 
    r.name as role_name,
    p.name as permission_name
FROM role_has_permissions rhp
JOIN permissions p ON rhp.permission_id = p.id
JOIN roles r ON rhp.role_id = r.id
WHERE p.name IN ('district.view', 'staff.assign.district', 'staff.view.district', 'shop.view.district', 'job.assign.district') 
AND p.guard_name = 'admin'
ORDER BY r.name, p.name;

-- ==========================================
-- IMPORTANT NOTES
-- ==========================================

-- 1. This SQL script assumes:
--    - districts table already exists (from migration)
--    - staff and shops tables have district_id columns (from migration)
--    - admins table exists with user ID 1 and 8
--    - roles table exists with role ID 1 and 6

-- 2. If districts table doesn't exist, run the migration first:
--    php artisan migrate --path=database/migrations/2026_05_01_073001_create_districts_table.php

-- 3. If district_id columns don't exist, run the migration:
--    php artisan migrate --path=database/migrations/2026_05_01_104238_add_district_id_to_staff_and_shops_tables.php

-- 4. After running this SQL, clear Laravel cache:
--    php artisan cache:clear
--    php artisan permission:cache-reset

-- 5. Upload the following files to live server:
--    - routes/admin.php (district routes)
--    - resources/views/admin/sidebar.blade.php (district menu)
--    - app/Models/Staff.php (district relationship)
--    - app/Models/Shop.php (district relationship)
--    - app/Http/Controllers/Admin/StaffController.php (district logic)
--    - app/Http/Controllers/Admin/ShopManagementController.php (district logic)
--    - app/Http/Controllers/Staff/ShopController.php (auto district)
--    - resources/views/admin/staff/create.blade.php (district dropdown)
--    - resources/views/admin/staff/edit.blade.php (district dropdown)
--    - resources/views/admin/staff/index.blade.php (district filter)
--    - resources/views/admin/shop-management/shopindex.blade.php (district filter)
--    - resources/views/staff/shop/create.blade.php (auto district display)

-- ==========================================
