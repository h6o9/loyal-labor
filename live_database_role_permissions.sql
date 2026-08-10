-- ==========================================
-- RAW SQL COMMANDS FOR LIVE DATABASE
-- ROLE UPDATE PERMISSIONS FIX
-- ==========================================

-- This script adds missing role permissions to fix 403 error
-- when updating roles in the admin panel

-- STEP 1: CREATE/UPDATE ROLE PERMISSIONS
-- ==========================================

-- Create role.view permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('role.view', 'admin', 'role', NOW(), NOW());

-- Create role.create permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('role.create', 'admin', 'role', NOW(), NOW());

-- Create role.store permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('role.store', 'admin', 'role', NOW(), NOW());

-- Create role.edit permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('role.edit', 'admin', 'role', NOW(), NOW());

-- Create role.update permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('role.update', 'admin', 'role', NOW(), NOW());

-- Create role.delete permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('role.delete', 'admin', 'role', NOW(), NOW());

-- Create role.assign permission if not exists (MOST IMPORTANT)
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('role.assign', 'admin', 'role', NOW(), NOW());

-- STEP 2: GET PERMISSION IDs
-- ==========================================

SET @role_view_id = (SELECT id FROM permissions WHERE name = 'role.view' AND guard_name = 'admin');
SET @role_create_id = (SELECT id FROM permissions WHERE name = 'role.create' AND guard_name = 'admin');
SET @role_store_id = (SELECT id FROM permissions WHERE name = 'role.store' AND guard_name = 'admin');
SET @role_edit_id = (SELECT id FROM permissions WHERE name = 'role.edit' AND guard_name = 'admin');
SET @role_update_id = (SELECT id FROM permissions WHERE name = 'role.update' AND guard_name = 'admin');
SET @role_delete_id = (SELECT id FROM permissions WHERE name = 'role.delete' AND guard_name = 'admin');
SET @role_assign_id = (SELECT id FROM permissions WHERE name = 'role.assign' AND guard_name = 'admin');

-- STEP 3: ASSIGN PERMISSIONS TO MAIN ADMIN USER (ID 1)
-- ==========================================

-- Remove any existing role permissions to avoid duplicates
DELETE FROM model_has_permissions 
WHERE permission_id IN (
    @role_view_id, @role_create_id, @role_store_id, 
    @role_edit_id, @role_update_id, @role_delete_id, @role_assign_id
) AND model_type = 'App\\Models\\Admin' AND model_id = 1;

-- Assign all role permissions to main admin user
INSERT INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at) 
VALUES 
(@role_view_id, 'App\\Models\\Admin', 1, NOW(), NOW()),
(@role_create_id, 'App\\Models\\Admin', 1, NOW(), NOW()),
(@role_store_id, 'App\\Models\\Admin', 1, NOW(), NOW()),
(@role_edit_id, 'App\\Models\\Admin', 1, NOW(), NOW()),
(@role_update_id, 'App\\Models\\Admin', 1, NOW(), NOW()),
(@role_delete_id, 'App\\Models\\Admin', 1, NOW(), NOW()),
(@role_assign_id, 'App\\Models\\Admin', 1, NOW(), NOW());

-- STEP 4: ASSIGN PERMISSIONS TO ASSISTANT MANAGER (ID 8)
-- ==========================================

-- Remove any existing role permissions to avoid duplicates
DELETE FROM model_has_permissions 
WHERE permission_id IN (
    @role_view_id, @role_create_id, @role_store_id, 
    @role_edit_id, @role_update_id, @role_delete_id, @role_assign_id
) AND model_type = 'App\\Models\\Admin' AND model_id = 8;

-- Assign role permissions to Assistant Manager
INSERT INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at) 
VALUES 
(@role_view_id, 'App\\Models\\Admin', 8, NOW(), NOW()),
(@role_create_id, 'App\\Models\\Admin', 8, NOW(), NOW()),
(@role_store_id, 'App\\Models\\Admin', 8, NOW(), NOW()),
(@role_edit_id, 'App\\Models\\Admin', 8, NOW(), NOW()),
(@role_update_id, 'App\\Models\\Admin', 8, NOW(), NOW()),
(@role_delete_id, 'App\\Models\\Admin', 8, NOW(), NOW()),
(@role_assign_id, 'App\\Models\\Admin', 8, NOW(), NOW());

-- STEP 5: ASSIGN PERMISSIONS TO ADMIN ROLES
-- ==========================================

-- Remove any existing role assignments to avoid duplicates
DELETE FROM role_has_permissions 
WHERE permission_id IN (
    @role_view_id, @role_create_id, @role_store_id, 
    @role_edit_id, @role_update_id, @role_delete_id, @role_assign_id
);

-- Assign role permissions to admin role (ID 1)
INSERT INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES 
(1, @role_view_id, NOW(), NOW()),
(1, @role_create_id, NOW(), NOW()),
(1, @role_store_id, NOW(), NOW()),
(1, @role_edit_id, NOW(), NOW()),
(1, @role_update_id, NOW(), NOW()),
(1, @role_delete_id, NOW(), NOW()),
(1, @role_assign_id, NOW(), NOW());

-- Assign role permissions to Assistant Manager role (ID 6)
INSERT INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES 
(6, @role_view_id, NOW(), NOW()),
(6, @role_create_id, NOW(), NOW()),
(6, @role_store_id, NOW(), NOW()),
(6, @role_edit_id, NOW(), NOW()),
(6, @role_update_id, NOW(), NOW()),
(6, @role_delete_id, NOW(), NOW()),
(6, @role_assign_id, NOW(), NOW());

-- STEP 6: VERIFICATION QUERIES
-- ==========================================

-- Check if all role permissions exist
SELECT 
    id,
    name,
    guard_name,
    group_name,
    created_at,
    updated_at
FROM permissions 
WHERE name IN ('role.view', 'role.create', 'role.store', 'role.edit', 'role.update', 'role.delete', 'role.assign') 
AND guard_name = 'admin'
ORDER BY name;

-- Check admin user (ID 1) has all role permissions
SELECT 
    a.name as admin_name,
    a.email as admin_email,
    p.name as permission_name,
    p.group_name,
    mhp.created_at as assigned_at
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
JOIN admins a ON mhp.model_id = a.id
WHERE p.name IN ('role.view', 'role.create', 'role.store', 'role.edit', 'role.update', 'role.delete', 'role.assign') 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id = 1
ORDER BY p.name;

-- Check Assistant Manager user (ID 8) has role permissions
SELECT 
    a.name as admin_name,
    a.email as admin_email,
    p.name as permission_name,
    p.group_name,
    mhp.created_at as assigned_at
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
JOIN admins a ON mhp.model_id = a.id
WHERE p.name IN ('role.view', 'role.create', 'role.store', 'role.edit', 'role.update', 'role.delete', 'role.assign') 
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
WHERE p.name IN ('role.view', 'role.create', 'role.store', 'role.edit', 'role.update', 'role.delete', 'role.assign') 
AND p.guard_name = 'admin'
ORDER BY r.name, p.name;

-- STEP 7: FINAL TEST QUERY FOR ROLE UPDATE
-- ==========================================

-- Test if admin user can update roles (most important for 403 fix)
SELECT 
    CASE 
        WHEN COUNT(*) >= 1 THEN '✅ Admin user can update roles (has role.assign permission)'
        WHEN COUNT(*) >= 1 THEN '⚠️  Partial permissions - role.assign permission missing'
        ELSE '❌ No role permissions - 403 error will occur'
    END as role_update_status
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
WHERE p.name = 'role.assign' 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id = 1;

-- ==========================================
-- TROUBLESHOOTING NOTES
-- ==========================================

-- If you still get 403 error when updating roles:
-- 1. Clear Laravel cache: php artisan cache:clear
-- 2. Clear permission cache: php artisan permission:cache-reset
-- 3. Check if user is logged in correctly
-- 4. Verify role.assign permission exists

-- If you need to check what role permissions a specific user has:
-- SELECT p.name FROM permissions p
-- JOIN model_has_permissions mhp ON p.id = mhp.permission_id
-- WHERE mhp.model_type = 'App\\Models\\Admin' AND mhp.model_id = 1
-- AND p.name LIKE 'role.%';

-- ==========================================
-- CONTROLLER METHODS THAT REQUIRE THESE PERMISSIONS:
-- ==========================================

-- RolesController:
-- index() -> role.view
-- create() -> role.create
-- store() -> role.store
-- assignPermissionsForm() -> role.assign
-- getRolePermissions() -> role.assign
-- updateRolePermissions() -> role.assign

-- AssignRoleController:
-- index() -> role.assign
-- assign() -> role.assign

-- ==========================================
-- AFTER RUNNING THIS SQL:
-- ==========================================
-- 1. Clear Laravel cache: php artisan cache:clear
-- 2. Clear permission cache: php artisan permission:cache-reset
-- 3. Try updating a role again
-- 4. The 403 error should be resolved
-- ==========================================
