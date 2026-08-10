-- ==========================================
-- RAW SQL COMMANDS FOR LIVE DATABASE FIX
-- ASSIGN.JOB.DELETE PERMISSION ISSUE
-- ==========================================

-- ISSUE: assign.job.delete permission has null timestamps
-- This causes Laravel to not recognize the permission properly

-- STEP 1: CHECK CURRENT PERMISSION STATUS
-- ==========================================

-- Check assign.job.delete permission current state
SELECT id, name, guard_name, group_name, created_at, updated_at 
FROM permissions 
WHERE name = 'assign.job.delete' AND guard_name = 'admin';

-- STEP 2: FIX PERMISSION TIMESTAMPS
-- ==========================================

-- Update assign.job.delete permission with proper timestamps
UPDATE permissions 
SET 
    created_at = NOW(),
    updated_at = NOW(),
    group_name = 'Shop Management'
WHERE name = 'assign.job.delete' AND guard_name = 'admin';

-- STEP 3: VERIFY PERMISSION EXISTS PROPERLY
-- ==========================================

-- Check if permission is now properly recognized
SELECT * FROM permissions 
WHERE name = 'assign.job.delete' AND guard_name = 'admin';

-- STEP 4: ENSURE PROPER ASSIGNMENTS
-- ==========================================

-- Make sure admin user (ID 1) has this permission
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id) 
VALUES (
    (SELECT id FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin'),
    'App\\Models\\Admin',
    1
);

-- Make sure Assistant Manager user (ID 8) has this permission
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id) 
VALUES (
    (SELECT id FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin'),
    'App\\Models\\Admin',
    8
);

-- STEP 5: ASSIGN TO ADMIN ROLES
-- ==========================================

-- Assign to admin role (ID 1)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id) 
VALUES (
    1,
    (SELECT id FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin')
);

-- Assign to Assistant Manager role (ID 6)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id) 
VALUES (
    6,
    (SELECT id FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin')
);

-- STEP 6: VERIFICATION QUERIES
-- ==========================================

-- Verify permission exists with proper timestamps
SELECT 
    id,
    name,
    guard_name,
    group_name,
    created_at,
    updated_at
FROM permissions 
WHERE name = 'assign.job.delete' AND guard_name = 'admin';

-- Verify admin user (ID 1) has the permission
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

-- Verify Assistant Manager user (ID 8) has the permission
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

-- Verify role assignments
SELECT 
    r.name as role_name,
    p.name as permission_name,
    p.group_name
FROM role_has_permissions rhp
JOIN roles r ON rhp.role_id = r.id
JOIN permissions p ON rhp.permission_id = p.id
WHERE p.name = 'assign.job.delete' AND p.guard_name = 'admin';

-- STEP 7: CLEANUP ANY BROKEN PERMISSIONS
-- ==========================================

-- If there are any duplicate or broken entries, clean them up
DELETE FROM model_has_permissions 
WHERE permission_id NOT IN (SELECT id FROM permissions);

DELETE FROM role_has_permissions 
WHERE permission_id NOT IN (SELECT id FROM permissions);

-- STEP 8: FINAL VERIFICATION
-- ==========================================

-- Show all Shop Management permissions for verification
SELECT 
    p.name as permission_name,
    p.group_name,
    p.created_at,
    p.updated_at,
    COUNT(mhp.model_id) as user_assignments,
    COUNT(rhp.role_id) as role_assignments
FROM permissions p
LEFT JOIN model_has_permissions mhp ON p.id = mhp.permission_id AND mhp.model_type = 'App\\Models\\Admin'
LEFT JOIN role_has_permissions rhp ON p.id = rhp.permission_id
WHERE p.group_name = 'Shop Management' AND p.guard_name = 'admin'
GROUP BY p.id, p.name, p.group_name, p.created_at, p.updated_at
ORDER BY p.name;

-- ==========================================
-- ADDITIONAL FIX: IF PERMISSION DOESN'T EXIST
-- ==========================================

-- If the above doesn't work, create the permission from scratch
-- (Only run if permission doesn't exist after update)

-- First, delete the broken permission if it exists
-- DELETE FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin';

-- Then create it properly
-- INSERT INTO permissions (name, guard_name, group_name, created_at, updated_at)
-- VALUES ('assign.job.delete', 'admin', 'Shop Management', NOW(), NOW());

-- Get the new permission ID
-- SET @assign_job_delete_id = (SELECT id FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin');

-- Assign to admin user
-- INSERT INTO model_has_permissions (permission_id, model_type, model_id)
-- VALUES (@assign_job_delete_id, 'App\\Models\\Admin', 1);

-- Assign to Assistant Manager user
-- INSERT INTO model_has_permissions (permission_id, model_type, model_id)
-- VALUES (@assign_job_delete_id, 'App\\Models\\Admin', 8);

-- Assign to admin role
-- INSERT INTO role_has_permissions (role_id, permission_id)
-- VALUES (1, @assign_job_delete_id);

-- Assign to Assistant Manager role
-- INSERT INTO role_has_permissions (role_id, permission_id)
-- VALUES (6, @assign_job_delete_id);
