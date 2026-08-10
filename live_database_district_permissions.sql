-- ==========================================
-- RAW SQL COMMANDS FOR LIVE DATABASE
-- DISTRICT SYSTEM PERMISSIONS SETUP
-- ==========================================

-- This script adds district-related permissions for both admin and staff panels
-- to ensure proper permission checks for district functionality

-- STEP 1: CREATE ADMIN PANEL DISTRICT PERMISSIONS
-- ==========================================

-- Create district.view permission
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('district.view', 'admin', 'district', NOW(), NOW());

-- Create staff.assign.district permission
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('staff.assign.district', 'admin', 'staff', NOW(), NOW());

-- Create staff.view.district permission
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('staff.view.district', 'admin', 'staff', NOW(), NOW());

-- Create shop.view.district permission
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('shop.view.district', 'admin', 'shop', NOW(), NOW());

-- Create job.assign.district permission
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('job.assign.district', 'admin', 'job', NOW(), NOW());

-- STEP 2: GET PERMISSION IDs
-- ==========================================

SET @district_view_id = (SELECT id FROM permissions WHERE name = 'district.view' AND guard_name = 'admin');
SET @staff_assign_district_id = (SELECT id FROM permissions WHERE name = 'staff.assign.district' AND guard_name = 'admin');
SET @staff_view_district_id = (SELECT id FROM permissions WHERE name = 'staff.view.district' AND guard_name = 'admin');
SET @shop_view_district_admin_id = (SELECT id FROM permissions WHERE name = 'shop.view.district' AND guard_name = 'admin');
SET @job_assign_district_id = (SELECT id FROM permissions WHERE name = 'job.assign.district' AND guard_name = 'admin');

-- STEP 4: ASSIGN ADMIN PERMISSIONS TO MAIN ADMIN USER (ID 1)
-- ==========================================

-- Remove any existing district permissions to avoid duplicates
DELETE FROM model_has_permissions 
WHERE permission_id IN (
    @district_view_id, @staff_assign_district_id, @staff_view_district_id, 
    @shop_view_district_admin_id, @job_assign_district_id
) AND model_type = 'App\\Models\\Admin' AND model_id = 1;

-- Assign all admin district permissions to main admin user
INSERT INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at) 
VALUES 
(@district_view_id, 'App\\Models\\Admin', 1, NOW(), NOW()),
(@staff_assign_district_id, 'App\\Models\\Admin', 1, NOW(), NOW()),
(@staff_view_district_id, 'App\\Models\\Admin', 1, NOW(), NOW()),
(@shop_view_district_admin_id, 'App\\Models\\Admin', 1, NOW(), NOW()),
(@job_assign_district_id, 'App\\Models\\Admin', 1, NOW(), NOW());

-- STEP 5: ASSIGN ADMIN PERMISSIONS TO ASSISTANT MANAGER (ID 8)
-- ==========================================

-- Remove any existing district permissions to avoid duplicates
DELETE FROM model_has_permissions 
WHERE permission_id IN (
    @district_view_id, @staff_assign_district_id, @staff_view_district_id, 
    @shop_view_district_admin_id, @job_assign_district_id
) AND model_type = 'App\\Models\\Admin' AND model_id = 8;

-- Assign admin district permissions to Assistant Manager
INSERT INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at) 
VALUES 
(@district_view_id, 'App\\Models\\Admin', 8, NOW(), NOW()),
(@staff_assign_district_id, 'App\\Models\\Admin', 8, NOW(), NOW()),
(@staff_view_district_id, 'App\\Models\\Admin', 8, NOW(), NOW()),
(@shop_view_district_admin_id, 'App\\Models\\Admin', 8, NOW(), NOW()),
(@job_assign_district_id, 'App\\Models\\Admin', 8, NOW(), NOW());

-- STEP 6: ASSIGN ADMIN PERMISSIONS TO ADMIN ROLES
-- ==========================================

-- Remove any existing role assignments to avoid duplicates
DELETE FROM role_has_permissions 
WHERE permission_id IN (
    @district_view_id, @staff_assign_district_id, @staff_view_district_id, 
    @shop_view_district_admin_id, @job_assign_district_id
);

-- Assign admin district permissions to admin role (ID 1)
INSERT INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES 
(1, @district_view_id, NOW(), NOW()),
(1, @staff_assign_district_id, NOW(), NOW()),
(1, @staff_view_district_id, NOW(), NOW()),
(1, @shop_view_district_admin_id, NOW(), NOW()),
(1, @job_assign_district_id, NOW(), NOW());

-- Assign admin district permissions to Assistant Manager role (ID 6)
INSERT INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES 
(6, @district_view_id, NOW(), NOW()),
(6, @staff_assign_district_id, NOW(), NOW()),
(6, @staff_view_district_id, NOW(), NOW()),
(6, @shop_view_district_admin_id, NOW(), NOW()),
(6, @job_assign_district_id, NOW(), NOW());

-- STEP 7: VERIFICATION QUERIES
-- ==========================================

-- Check if all admin district permissions exist
SELECT 
    id,
    name,
    guard_name,
    group_name,
    created_at,
    updated_at
FROM permissions 
WHERE name IN ('district.view', 'staff.assign.district', 'staff.view.district', 'shop.view.district', 'job.assign.district') 
AND guard_name = 'admin'
ORDER BY name;

-- Check admin user (ID 1) has all admin district permissions
SELECT 
    a.name as admin_name,
    a.email as admin_email,
    p.name as permission_name,
    p.group_name,
    mhp.created_at as assigned_at
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
JOIN admins a ON mhp.model_id = a.id
WHERE p.name IN ('district.view', 'staff.assign.district', 'staff.view.district', 'shop.view.district', 'job.assign.district') 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id = 1
ORDER BY p.name;

-- STEP 8: FINAL TEST QUERIES
-- ==========================================

-- Test if admin user can assign districts
SELECT 
    CASE 
        WHEN COUNT(*) >= 1 THEN '✅ Admin user can assign districts (has staff.assign.district permission)'
        ELSE '❌ No district assign permission - 403 error may occur'
    END as district_assign_status
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
WHERE p.name = 'staff.assign.district' 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id = 1;

-- ==========================================
-- PERMISSION USAGE IN CONTROLLERS:
-- ==========================================

-- ADMIN PANEL:
-- StaffController:
-- - create() -> staff.assign.district
-- - edit() -> staff.assign.district
-- - index() -> staff.view.district

-- ShopManagementController:
-- - shopList() -> shop.view.district
-- - assignJob() -> job.assign.district

-- STAFF PANEL:
-- ShopController:
-- - create() -> No district permission check (auto-assigned)
-- - index() -> No district permission check

-- ==========================================
-- AFTER RUNNING THIS SQL:
-- ==========================================
-- 1. Clear Laravel cache: php artisan cache:clear
-- 2. Clear permission cache: php artisan permission:cache-reset
-- 3. Add permission checks to controllers
-- 4. Test district functionality
-- ==========================================
