-- ==========================================
-- RAW SQL COMMANDS FOR LIVE DATABASE
-- CAN DELETE PERMISSION FOR SHOP MANAGEMENT
-- ==========================================

-- This script ensures the assign.job.delete permission is properly set up
-- so the @can('assign.job.delete') directive works in blade templates

-- STEP 1: CREATE/UPDATE ASSIGN.JOB.DELETE PERMISSION
-- ==========================================

-- Create assign.job.delete permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('assign.job.delete', 'admin', 'Shop Management', NOW(), NOW());

-- Update existing assign.job.delete permission if it has null timestamps or wrong group
UPDATE permissions 
SET 
    created_at = IFNULL(created_at, NOW()),
    updated_at = IFNULL(updated_at, NOW()),
    group_name = 'Shop Management'
WHERE name = 'assign.job.delete' AND guard_name = 'admin';

-- STEP 2: GET PERMISSION ID
-- ==========================================

SET @assign_job_delete_id = (SELECT id FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin');

-- STEP 3: ASSIGN TO ADMIN USER (ID 1) - MAIN ADMIN
-- ==========================================

-- Remove any existing assignments to avoid duplicates
DELETE FROM model_has_permissions 
WHERE permission_id = @assign_job_delete_id 
AND model_type = 'App\\Models\\Admin' 
AND model_id = 1;

-- Assign assign.job.delete to main admin user
INSERT INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at) 
VALUES (@assign_job_delete_id, 'App\\Models\\Admin', 1, NOW(), NOW());

-- STEP 4: ASSIGN TO ASSISTANT MANAGER (ID 8) - IF NEEDED
-- ==========================================

-- Remove any existing assignments to avoid duplicates
DELETE FROM model_has_permissions 
WHERE permission_id = @assign_job_delete_id 
AND model_type = 'App\\Models\\Admin' 
AND model_id = 8;

-- Assign assign.job.delete to Assistant Manager
INSERT INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at) 
VALUES (@assign_job_delete_id, 'App\\Models\\Admin', 8, NOW(), NOW());

-- STEP 5: ASSIGN TO ADMIN ROLES
-- ==========================================

-- Remove any existing role assignments to avoid duplicates
DELETE FROM role_has_permissions 
WHERE permission_id = @assign_job_delete_id;

-- Assign assign.job.delete to admin role (ID 1)
INSERT INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES (1, @assign_job_delete_id, NOW(), NOW());

-- Assign assign.job.delete to Assistant Manager role (ID 6)
INSERT INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES (6, @assign_job_delete_id, NOW(), NOW());

-- STEP 6: CLEAR PERMISSION CACHE (IMPORTANT!)
-- ==========================================

-- This step is crucial for the @can directive to work properly
-- You may need to run this command in your Laravel application:
-- php artisan permission:cache-reset

-- For database-level cache clearing (if applicable)
-- DELETE FROM cache WHERE key LIKE '%permission%';

-- STEP 7: VERIFICATION QUERIES
-- ==========================================

-- Check if assign.job.delete permission exists properly
SELECT 
    id,
    name,
    guard_name,
    group_name,
    created_at,
    updated_at,
    'PERMISSION STATUS' as status
FROM permissions 
WHERE name = 'assign.job.delete' AND guard_name = 'admin';

-- Check admin user (ID 1) has the permission
SELECT 
    a.name as admin_name,
    a.email as admin_email,
    p.name as permission_name,
    p.group_name,
    mhp.created_at as assigned_at,
    CASE 
        WHEN mhp.permission_id IS NOT NULL THEN 'HAS PERMISSION - Delete button will show'
        ELSE 'NO PERMISSION - Delete button will not show'
    END as button_status
FROM admins a
LEFT JOIN model_has_permissions mhp ON a.id = mhp.model_id 
    AND mhp.permission_id = (SELECT id FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin')
    AND mhp.model_type = 'App\\Models\\Admin'
LEFT JOIN permissions p ON mhp.permission_id = p.id
WHERE a.id = 1;

-- Check Assistant Manager user (ID 8) has the permission
SELECT 
    a.name as admin_name,
    a.email as admin_email,
    p.name as permission_name,
    p.group_name,
    mhp.created_at as assigned_at,
    CASE 
        WHEN mhp.permission_id IS NOT NULL THEN 'HAS PERMISSION - Delete button will show'
        ELSE 'NO PERMISSION - Delete button will not show'
    END as button_status
FROM admins a
LEFT JOIN model_has_permissions mhp ON a.id = mhp.model_id 
    AND mhp.permission_id = (SELECT id FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin')
    AND mhp.model_type = 'App\\Models\\Admin'
LEFT JOIN permissions p ON mhp.permission_id = p.id
WHERE a.id = 8;

-- Check role assignments
SELECT 
    r.name as role_name,
    p.name as permission_name,
    p.group_name,
    rhp.created_at as assigned_at
FROM role_has_permissions rhp
JOIN roles r ON rhp.role_id = r.id
JOIN permissions p ON rhp.permission_id = p.id
WHERE p.name = 'assign.job.delete' AND p.guard_name = 'admin'
ORDER BY r.name;

-- STEP 8: FINAL TEST QUERY
-- ==========================================

-- Test if @can('assign.job.delete') will work for admin user (ID 1)
SELECT 
    CASE 
        WHEN COUNT(mhp.permission_id) > 0 THEN 
            '✅ @can(assign.job.delete) WILL WORK - Delete button will be visible'
        ELSE 
            '❌ @can(assign.job.delete) WILL NOT WORK - Delete button will be hidden'
    END as blade_directive_test_result
FROM admins a
LEFT JOIN model_has_permissions mhp ON a.id = mhp.model_id 
    AND mhp.permission_id = (SELECT id FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin')
    AND mhp.model_type = 'App\\Models\\Admin'
WHERE a.id = 1;

-- ==========================================
-- BLADE TEMPLATE USAGE
-- ==========================================
-- After running this SQL, your blade code will work:
-- 
-- @can('assign.job.delete')
--     <x-admin.delete-button :id="$job->id" onclick="deleteData" />
-- @else
--     <span class="text-muted">No Action</span>
-- @endcan
-- 
-- And for the modal:
-- @can('assign.job.delete')
--     <x-admin.delete-modal />
-- @endcan
-- 
-- IMPORTANT: After running SQL, clear permission cache:
-- php artisan permission:cache-reset
-- ==========================================
