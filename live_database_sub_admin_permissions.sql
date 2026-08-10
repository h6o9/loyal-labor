-- ==========================================
-- RAW SQL COMMANDS FOR LIVE DATABASE
-- SUB-ADMIN CREATION PERMISSIONS FIX
-- ==========================================

-- This script adds the missing permissions for sub-admin creation
-- to fix the 403 error when creating sub-admins

-- STEP 1: CREATE/UPDATE ADMIN PERMISSIONS
-- ==========================================

-- Create admin.create permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('admin.create', 'admin', 'admin', NOW(), NOW());

-- Create admin.store permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('admin.store', 'admin', 'admin', NOW(), NOW());

-- Create admin.view permission if not exists (for admin list page)
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('admin.view', 'admin', 'admin', NOW(), NOW());

-- Create admin.edit permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('admin.edit', 'admin', 'admin', NOW(), NOW());

-- Create admin.update permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('admin.update', 'admin', 'admin', NOW(), NOW());

-- Create admin.delete permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('admin.delete', 'admin', 'admin', NOW(), NOW());

-- STEP 2: GET PERMISSION IDs
-- ==========================================

SET @admin_view_id = (SELECT id FROM permissions WHERE name = 'admin.view' AND guard_name = 'admin');
SET @admin_create_id = (SELECT id FROM permissions WHERE name = 'admin.create' AND guard_name = 'admin');
SET @admin_store_id = (SELECT id FROM permissions WHERE name = 'admin.store' AND guard_name = 'admin');
SET @admin_edit_id = (SELECT id FROM permissions WHERE name = 'admin.edit' AND guard_name = 'admin');
SET @admin_update_id = (SELECT id FROM permissions WHERE name = 'admin.update' AND guard_name = 'admin');
SET @admin_delete_id = (SELECT id FROM permissions WHERE name = 'admin.delete' AND guard_name = 'admin');

-- STEP 3: ASSIGN PERMISSIONS TO MAIN ADMIN USER (ID 1)
-- ==========================================

-- Remove any existing admin permissions to avoid duplicates
DELETE FROM model_has_permissions 
WHERE permission_id IN (
    @admin_view_id, @admin_create_id, @admin_store_id, 
    @admin_edit_id, @admin_update_id, @admin_delete_id
) AND model_type = 'App\\Models\\Admin' AND model_id = 1;

-- Assign all admin permissions to main admin user
INSERT INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at) 
VALUES 
(@admin_view_id, 'App\\Models\\Admin', 1, NOW(), NOW()),
(@admin_create_id, 'App\\Models\\Admin', 1, NOW(), NOW()),
(@admin_store_id, 'App\\Models\\Admin', 1, NOW(), NOW()),
(@admin_edit_id, 'App\\Models\\Admin', 1, NOW(), NOW()),
(@admin_update_id, 'App\\Models\\Admin', 1, NOW(), NOW()),
(@admin_delete_id, 'App\\Models\\Admin', 1, NOW(), NOW());

-- STEP 4: ASSIGN PERMISSIONS TO ASSISTANT MANAGER (ID 8)
-- ==========================================

-- Remove any existing admin permissions to avoid duplicates
DELETE FROM model_has_permissions 
WHERE permission_id IN (
    @admin_view_id, @admin_create_id, @admin_store_id, 
    @admin_edit_id, @admin_update_id, @admin_delete_id
) AND model_type = 'App\\Models\\Admin' AND model_id = 8;

-- Assign admin permissions to Assistant Manager
INSERT INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at) 
VALUES 
(@admin_view_id, 'App\\Models\\Admin', 8, NOW(), NOW()),
(@admin_create_id, 'App\\Models\\Admin', 8, NOW(), NOW()),
(@admin_store_id, 'App\\Models\\Admin', 8, NOW(), NOW()),
(@admin_edit_id, 'App\\Models\\Admin', 8, NOW(), NOW()),
(@admin_update_id, 'App\\Models\\Admin', 8, NOW(), NOW()),
(@admin_delete_id, 'App\\Models\\Admin', 8, NOW(), NOW());

-- STEP 5: ASSIGN PERMISSIONS TO ADMIN ROLES
-- ==========================================

-- Remove any existing role assignments to avoid duplicates
DELETE FROM role_has_permissions 
WHERE permission_id IN (
    @admin_view_id, @admin_create_id, @admin_store_id, 
    @admin_edit_id, @admin_update_id, @admin_delete_id
);

-- Assign admin permissions to admin role (ID 1)
INSERT INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES 
(1, @admin_view_id, NOW(), NOW()),
(1, @admin_create_id, NOW(), NOW()),
(1, @admin_store_id, NOW(), NOW()),
(1, @admin_edit_id, NOW(), NOW()),
(1, @admin_update_id, NOW(), NOW()),
(1, @admin_delete_id, NOW(), NOW());

-- Assign admin permissions to Assistant Manager role (ID 6)
INSERT INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES 
(6, @admin_view_id, NOW(), NOW()),
(6, @admin_create_id, NOW(), NOW()),
(6, @admin_store_id, NOW(), NOW()),
(6, @admin_edit_id, NOW(), NOW()),
(6, @admin_update_id, NOW(), NOW()),
(6, @admin_delete_id, NOW(), NOW());

-- STEP 6: VERIFICATION QUERIES
-- ==========================================

-- Check if all admin permissions exist
SELECT 
    id,
    name,
    guard_name,
    group_name,
    created_at,
    updated_at
FROM permissions 
WHERE name IN ('admin.view', 'admin.create', 'admin.store', 'admin.edit', 'admin.update', 'admin.delete') 
AND guard_name = 'admin'
ORDER BY name;

-- Check admin user (ID 1) has all admin permissions
SELECT 
    a.name as admin_name,
    a.email as admin_email,
    p.name as permission_name,
    p.group_name,
    mhp.created_at as assigned_at
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
JOIN admins a ON mhp.model_id = a.id
WHERE p.name IN ('admin.view', 'admin.create', 'admin.store', 'admin.edit', 'admin.update', 'admin.delete') 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id = 1
ORDER BY p.name;

-- Check Assistant Manager user (ID 8) has admin permissions
SELECT 
    a.name as admin_name,
    a.email as admin_email,
    p.name as permission_name,
    p.group_name,
    mhp.created_at as assigned_at
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
JOIN admins a ON mhp.model_id = a.id
WHERE p.name IN ('admin.view', 'admin.create', 'admin.store', 'admin.edit', 'admin.update', 'admin.delete') 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id = 8
ORDER BY p.name;

-- Check role assignments
SELECT 
    r.name as role_name,
    p.name as permission_name,
    p.group_name,
    rhp.created_at as assigned_at
FROM role_has_permissions rhp
JOIN roles r ON rhp.role_id = r.id
JOIN permissions p ON rhp.permission_id = p.id
WHERE p.name IN ('admin.view', 'admin.create', 'admin.store', 'admin.edit', 'admin.update', 'admin.delete') 
AND p.guard_name = 'admin'
ORDER BY r.name, p.name;

-- STEP 7: FINAL TEST QUERY
-- ==========================================

-- Test if admin user can create sub-admins
SELECT 
    CASE 
        WHEN COUNT(*) >= 2 THEN '✅ Admin user can create sub-admins (has admin.create and admin.store)'
        WHEN COUNT(*) >= 1 THEN '⚠️  Partial permissions - some admin permissions missing'
        ELSE '❌ No admin permissions - 403 error will occur'
    END as sub_admin_creation_status
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
WHERE p.name IN ('admin.create', 'admin.store') 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id = 1;

-- ==========================================
-- TROUBLESHOOTING NOTES
-- ==========================================

-- If you still get 403 error after running this SQL:
-- 1. Clear Laravel cache: php artisan cache:clear
-- 2. Clear permission cache: php artisan permission:cache-reset
-- 3. Check if the user is logged in correctly
-- 4. Verify the checkAdminHasPermissionAndThrowException function exists

-- If you need to check what permissions a specific user has:
-- SELECT p.name FROM permissions p
-- JOIN model_has_permissions mhp ON p.id = mhp.permission_id
-- WHERE mhp.model_type = 'App\\Models\\Admin' AND mhp.model_id = 1;

-- ==========================================
-- AFTER RUNNING THIS SQL:
-- ==========================================
-- 1. Clear Laravel cache: php artisan cache:clear
-- 2. Clear permission cache: php artisan permission:cache-reset
-- 3. Try creating a sub-admin again
-- 4. The 403 error should be resolved
-- ==========================================
