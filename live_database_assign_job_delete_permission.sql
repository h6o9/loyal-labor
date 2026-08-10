-- ==========================================
-- RAW SQL COMMANDS FOR LIVE DATABASE
-- ADD ASSIGN.JOB.DELETE PERMISSION
-- ==========================================

-- This script adds the assign.job.delete permission to the live database
-- so the permission check in blade file works properly

-- STEP 1: CREATE/UPDATE ASSIGN.JOB.DELETE PERMISSION
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

-- STEP 2: GET PERMISSION ID
-- ==========================================

SET @assign_job_delete_id = (SELECT id FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin');

-- STEP 3: ASSIGN TO ADMIN USER (ID 1)
-- ==========================================

-- Assign assign.job.delete to admin user
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at) 
VALUES (@assign_job_delete_id, 'App\\Models\\Admin', 1, NOW(), NOW());

-- STEP 4: ASSIGN TO ASSISTANT MANAGER (ID 8)
-- ==========================================

-- Assign assign.job.delete to Assistant Manager
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at) 
VALUES (@assign_job_delete_id, 'App\\Models\\Admin', 8, NOW(), NOW());

-- STEP 5: ASSIGN TO ADMIN ROLES
-- ==========================================

-- Assign assign.job.delete to admin role (ID 1)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES (1, @assign_job_delete_id, NOW(), NOW());

-- Assign assign.job.delete to Assistant Manager role (ID 6)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES (6, @assign_job_delete_id, NOW(), NOW());

-- STEP 6: VERIFICATION QUERIES
-- ==========================================

-- Check if assign.job.delete permission exists
SELECT 
    id,
    name,
    guard_name,
    group_name,
    created_at,
    updated_at
FROM permissions 
WHERE name = 'assign.job.delete' AND guard_name = 'admin';

-- Check admin user (ID 1) has the permission
SELECT 
    a.name as admin_name,
    a.email as admin_email,
    p.name as permission_name,
    p.group_name,
    mhp.created_at as assigned_at
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
JOIN admins a ON mhp.model_id = a.id
WHERE p.name = 'assign.job.delete' 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id = 1;

-- Check Assistant Manager user (ID 8) has the permission
SELECT 
    a.name as admin_name,
    a.email as admin_email,
    p.name as permission_name,
    p.group_name,
    mhp.created_at as assigned_at
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
JOIN admins a ON mhp.model_id = a.id
WHERE p.name = 'assign.job.delete' 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id = 8;

-- Check role assignments
SELECT 
    r.name as role_name,
    p.name as permission_name,
    p.group_name
FROM role_has_permissions rhp
JOIN roles r ON rhp.role_id = r.id
JOIN permissions p ON rhp.permission_id = p.id
WHERE p.name = 'assign.job.delete' AND p.guard_name = 'admin';

-- STEP 7: QUICK TEST QUERY
-- ==========================================

-- Test if current admin user has the permission (use this to verify)
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN 'PERMISSION EXISTS - Delete button should show'
        ELSE 'PERMISSION MISSING - Delete button will not show'
    END as result
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
WHERE p.name = 'assign.job.delete' 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id = 1;

-- ==========================================
-- EXECUTION INSTRUCTIONS
-- ==========================================
-- 1. Run this entire SQL script in your live database
-- 2. The permission check in blade file will work properly:
--    @if(auth('admin')->user()->hasPermissionTo('assign.job.delete'))
-- 3. Users with permission will see delete button
-- 4. Users without permission will see "No Action"
-- ==========================================
