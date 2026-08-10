-- ==========================================
-- CLEAN SQL SCRIPT FOR LIVE DATABASE
-- DELETE EXISTING JOB DELETE PERMISSIONS & ADD FRESH DATA
-- ==========================================

-- STEP 1: DELETE ALL EXISTING JOB DELETE PERMISSIONS AND ASSIGNMENTS
-- ================================================================

-- First, get the permission IDs to delete
SET @job_delete_id = (SELECT id FROM permissions WHERE name = 'job.delete' AND guard_name = 'admin');
SET @assign_job_delete_id = (SELECT id FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin');

-- Delete user assignments for job.delete
DELETE FROM model_has_permissions 
WHERE permission_id = @job_delete_id 
AND model_type = 'App\\Models\\Admin';

-- Delete user assignments for assign.job.delete
DELETE FROM model_has_permissions 
WHERE permission_id = @assign_job_delete_id 
AND model_type = 'App\\Models\\Admin';

-- Delete role assignments for job.delete
DELETE FROM role_has_permissions 
WHERE permission_id = @job_delete_id;

-- Delete role assignments for assign.job.delete
DELETE FROM role_has_permissions 
WHERE permission_id = @assign_job_delete_id;

-- Delete the permissions themselves
DELETE FROM permissions 
WHERE name IN ('job.delete', 'assign.job.delete') 
AND guard_name = 'admin';

-- STEP 2: CREATE FRESH JOB DELETE PERMISSIONS
-- ==========================================

-- Create job.delete permission
INSERT INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('job.delete', 'admin', 'Shop Management', NOW(), NOW());

-- Create assign.job.delete permission
INSERT INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('assign.job.delete', 'admin', 'Shop Management', NOW(), NOW());

-- STEP 3: GET NEW PERMISSION IDs
-- ==========================================

SET @new_job_delete_id = (SELECT id FROM permissions WHERE name = 'job.delete' AND guard_name = 'admin');
SET @new_assign_job_delete_id = (SELECT id FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin');

-- STEP 4: ASSIGN TO ADMIN USER (ID 1)
-- ==========================================

-- Assign job.delete to admin user
INSERT INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at) 
VALUES (@new_job_delete_id, 'App\\Models\\Admin', 1, NOW(), NOW());

-- Assign assign.job.delete to admin user
INSERT INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at) 
VALUES (@new_assign_job_delete_id, 'App\\Models\\Admin', 1, NOW(), NOW());

-- STEP 5: ASSIGN TO ASSISTANT MANAGER (ID 8)
-- ==========================================

-- Assign job.delete to Assistant Manager
INSERT INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at) 
VALUES (@new_job_delete_id, 'App\\Models\\Admin', 8, NOW(), NOW());

-- Assign assign.job.delete to Assistant Manager
INSERT INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at) 
VALUES (@new_assign_job_delete_id, 'App\\Models\\Admin', 8, NOW(), NOW());

-- STEP 6: ASSIGN TO ADMIN ROLES
-- ==========================================

-- Assign job.delete to admin role (ID 1)
INSERT INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES (1, @new_job_delete_id, NOW(), NOW());

-- Assign assign.job.delete to admin role (ID 1)
INSERT INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES (1, @new_assign_job_delete_id, NOW(), NOW());

-- Assign job.delete to Assistant Manager role (ID 6)
INSERT INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES (6, @new_job_delete_id, NOW(), NOW());

-- Assign assign.job.delete to Assistant Manager role (ID 6)
INSERT INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES (6, @new_assign_job_delete_id, NOW(), NOW());

-- STEP 7: VERIFICATION - CHECK FRESH DATA
-- ==========================================

-- Check if both permissions were created fresh
SELECT 
    id,
    name,
    guard_name,
    group_name,
    created_at,
    updated_at,
    'FRESH PERMISSION' as status
FROM permissions 
WHERE name IN ('job.delete', 'assign.job.delete') 
AND guard_name = 'admin'
ORDER BY name;

-- Check admin user (ID 1) has fresh permissions
SELECT 
    a.name as admin_name,
    a.email as admin_email,
    p.name as permission_name,
    p.group_name,
    mhp.created_at as assigned_at,
    'FRESH USER ASSIGNMENT' as status
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
JOIN admins a ON mhp.model_id = a.id
WHERE p.name IN ('job.delete', 'assign.job.delete') 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id = 1
ORDER BY p.name;

-- Check Assistant Manager user (ID 8) has fresh permissions
SELECT 
    a.name as admin_name,
    a.email as admin_email,
    p.name as permission_name,
    p.group_name,
    mhp.created_at as assigned_at,
    'FRESH USER ASSIGNMENT' as status
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
JOIN admins a ON mhp.model_id = a.id
WHERE p.name IN ('job.delete', 'assign.job.delete') 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id = 8
ORDER BY p.name;

-- Check role assignments
SELECT 
    r.name as role_name,
    p.name as permission_name,
    p.group_name,
    rhp.created_at as assigned_at,
    'FRESH ROLE ASSIGNMENT' as status
FROM role_has_permissions rhp
JOIN roles r ON rhp.role_id = r.id
JOIN permissions p ON rhp.permission_id = p.id
WHERE p.name IN ('job.delete', 'assign.job.delete') 
AND p.guard_name = 'admin'
ORDER BY r.name, p.name;

-- STEP 8: FINAL CLEANUP VERIFICATION
-- ==========================================

-- Show complete summary of fresh job delete permissions
SELECT 
    'JOB DELETE PERMISSIONS SUMMARY' as info,
    COUNT(DISTINCT CASE WHEN p.name = 'job.delete' THEN p.id END) as job_delete_permission_count,
    COUNT(DISTINCT CASE WHEN p.name = 'assign.job.delete' THEN p.id END) as assign_job_delete_permission_count,
    COUNT(DISTINCT mhp.model_id) as total_user_assignments,
    COUNT(DISTINCT rhp.role_id) as total_role_assignments,
    NOW() as execution_time
FROM permissions p
LEFT JOIN model_has_permissions mhp ON p.id = mhp.permission_id AND mhp.model_type = 'App\\Models\\Admin'
LEFT JOIN role_has_permissions rhp ON p.id = rhp.permission_id
WHERE p.name IN ('job.delete', 'assign.job.delete') 
AND p.guard_name = 'admin';

-- ==========================================
-- QUICK EXECUTION PLAN
-- ==========================================
-- 1. Run this entire script in your live database
-- 2. It will delete all existing job.delete and assign.job.delete permissions
-- 3. Create fresh permissions with proper timestamps
-- 4. Assign to Admin user (ID 1) and Assistant Manager (ID 8)
-- 5. Assign to Admin role (ID 1) and Assistant Manager role (ID 6)
-- 6. Verification queries will show the fresh data
-- ==========================================
