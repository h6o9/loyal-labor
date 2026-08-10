-- ==========================================
-- RAW SQL COMMANDS FOR LIVE DATABASE
-- JOB DELETE PERMISSIONS (SAME AS LOCAL DB) - FIXED FORMATTING
-- ==========================================

-- Local DB has 2 job delete permissions:
-- 1. job.delete (ID: 229)
-- 2. assign.job.delete (ID: 230)

-- STEP 1: CREATE/UPDATE JOB.DELETE PERMISSION
-- ==========================================

-- Create job.delete permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('job.delete', 'admin', 'Shop Management', NOW(), NOW());

-- Update existing job.delete permission if it has null timestamps
UPDATE permissions 
SET 
    created_at = IFNULL(created_at, NOW()),
    updated_at = IFNULL(updated_at, NOW()),
    group_name = 'Shop Management'
WHERE name = 'job.delete' AND guard_name = 'admin';

-- STEP 2: CREATE/UPDATE ASSIGN.JOB.DELETE PERMISSION
-- ==========================================

-- Create assign.job.delete permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('assign.job.delete', 'admin', 'Shop Management', NOW(), NOW());

-- Update existing assign.job.delete permission if it has null timestamps
UPDATE permissions 
SET 
    created_at = IFNULL(created_at, NOW()),
    updated_at = IFNULL(updated_at, NOW()),
    group_name = 'Shop Management'
WHERE name = 'assign.job.delete' AND guard_name = 'admin';

-- STEP 3: GET PERMISSION IDs
-- ==========================================

SET @job_delete_id = (SELECT id FROM permissions WHERE name = 'job.delete' AND guard_name = 'admin');
SET @assign_job_delete_id = (SELECT id FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin');

-- STEP 4: ASSIGN PERMISSIONS TO ADMIN USER (ID 1)
-- ==========================================

-- Assign job.delete to admin user
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id) 
VALUES (@job_delete_id, 'App\\Models\\Admin', 1);

-- Assign assign.job.delete to admin user
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id) 
VALUES (@assign_job_delete_id, 'App\\Models\\Admin', 1);

-- STEP 5: ASSIGN PERMISSIONS TO ASSISTANT MANAGER (ID 8)
-- ==========================================

-- Assign job.delete to Assistant Manager
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id) 
VALUES (@job_delete_id, 'App\\Models\\Admin', 8);

-- Assign assign.job.delete to Assistant Manager
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id) 
VALUES (@assign_job_delete_id, 'App\\Models\\Admin', 8);

-- STEP 6: ASSIGN PERMISSIONS TO ADMIN ROLES
-- ==========================================

-- Assign job.delete to admin role (ID 1)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id) 
VALUES (1, @job_delete_id);

-- Assign assign.job.delete to admin role (ID 1)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id) 
VALUES (1, @assign_job_delete_id);

-- Assign job.delete to Assistant Manager role (ID 6)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id) 
VALUES (6, @job_delete_id);

-- Assign assign.job.delete to Assistant Manager role (ID 6)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id) 
VALUES (6, @assign_job_delete_id);

-- STEP 7: VERIFICATION QUERIES
-- ==========================================

-- Check if both permissions exist
SELECT 
    id,
    name,
    guard_name,
    group_name,
    created_at,
    updated_at
FROM permissions 
WHERE name IN ('job.delete', 'assign.job.delete') 
AND guard_name = 'admin'
ORDER BY name;

-- Check admin user (ID 1) has both permissions - FIXED FORMATTING
SELECT 
    a.name as admin_name,
    a.email as admin_email,
    p.name as permission_name,
    p.group_name,
    mhp.created_at as assigned_at
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
JOIN admins a ON mhp.model_id = a.id
WHERE p.name IN ('job.delete', 'assign.job.delete') 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id = 1
ORDER BY p.name;

-- Check Assistant Manager user (ID 8) has both permissions - FIXED FORMATTING
SELECT 
    a.name as admin_name,
    a.email as admin_email,
    p.name as permission_name,
    p.group_name,
    mhp.created_at as assigned_at
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
    p.group_name
FROM role_has_permissions rhp
JOIN roles r ON rhp.role_id = r.id
JOIN permissions p ON rhp.permission_id = p.id
WHERE p.name IN ('job.delete', 'assign.job.delete') 
AND p.guard_name = 'admin'
ORDER BY r.name, p.name;

-- STEP 8: CLEANUP BROKEN ENTRIES
-- ==========================================

-- Remove any model_has_permissions entries for non-existent permissions
DELETE FROM model_has_permissions 
WHERE permission_id NOT IN (SELECT id FROM permissions);

-- Remove any role_has_permissions entries for non-existent permissions
DELETE FROM role_has_permissions 
WHERE permission_id NOT IN (SELECT id FROM permissions);

-- STEP 9: FINAL VERIFICATION - ALL SHOP MANAGEMENT PERMISSIONS
-- ==========================================

-- Show all Shop Management permissions for complete verification
SELECT 
    p.name as permission_name,
    p.group_name,
    p.created_at,
    p.updated_at,
    COUNT(DISTINCT mhp.model_id) as user_assignments,
    COUNT(DISTINCT rhp.role_id) as role_assignments
FROM permissions p
LEFT JOIN model_has_permissions mhp ON p.id = mhp.permission_id AND mhp.model_type = 'App\\Models\\Admin'
LEFT JOIN role_has_permissions rhp ON p.id = rhp.permission_id
WHERE p.group_name = 'Shop Management' AND p.guard_name = 'admin'
GROUP BY p.id, p.name, p.group_name, p.created_at, p.updated_at
ORDER BY p.name;

-- ==========================================
-- QUICK FIX QUERY FOR LIVE DATABASE
-- ==========================================

-- If you just need to fix the formatting issue, run this:
-- This is the fixed version of your query with proper spacing

SELECT 
    a.name as admin_name,
    a.email as admin_email,
    p.name as permission_name,
    p.group_name,
    mhp.created_at as assigned_at
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
JOIN admins a ON mhp.model_id = a.id
WHERE p.name IN ('job.delete', 'assign.job.delete') 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id = 1
ORDER BY p.name
LIMIT 0, 25;

-- ==========================================
-- SUMMARY
-- ==========================================
-- This script creates/updates 2 permissions:
-- 1. job.delete - For general job deletion
-- 2. assign.job.delete - For assigned job deletion
-- 
-- Assigns to:
-- - Admin user (ID 1)
-- - Assistant Manager user (ID 8)
-- - Admin role (ID 1)
-- - Assistant Manager role (ID 6)
-- 
-- Both permissions will be in "Shop Management" group
-- ==========================================
