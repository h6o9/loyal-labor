-- ==========================================
-- RAW SQL COMMANDS FOR ASSIGN.JOB.DELETE PERMISSION
-- LIVE DATABASE USAGE
-- ==========================================

-- STEP 1: CREATE ASSIGN.JOB.DELETE PERMISSION
-- ==========================================

-- Create assign.job.delete permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name) 
VALUES ('assign.job.delete', 'admin', 'Shop Management');

-- STEP 2: GET PERMISSION ID
-- ==========================================

-- Get assign.job.delete permission ID for assignment
SET @assign_job_delete_id = (SELECT id FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin');

-- STEP 3: ASSIGN TO ADMIN ROLES
-- ==========================================

-- Assign assign.job.delete to admin role (assuming role ID 1)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id) 
VALUES (1, @assign_job_delete_id);

-- Assign assign.job.delete to super-admin role if exists (assuming role ID 2)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id) 
VALUES (2, @assign_job_delete_id);

-- STEP 4: ASSIGN DIRECTLY TO ADMIN USERS
-- ==========================================

-- Assign assign.job.delete to admin user (ID 1) directly
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id) 
VALUES (@assign_job_delete_id, 'App\\Models\\Admin', 1);

-- Assign assign.job.delete to Assistant Manager (ID 8) if needed
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id) 
VALUES (@assign_job_delete_id, 'App\\Models\\Admin', 8);

-- STEP 5: VERIFICATION QUERIES
-- ==========================================

-- Check if assign.job.delete permission exists
SELECT * FROM permissions 
WHERE name = 'assign.job.delete' AND guard_name = 'admin';

-- Check role assignments for assign.job.delete
SELECT 
    r.name as role_name,
    p.name as permission_name,
    p.group_name
FROM role_has_permissions rhp
JOIN roles r ON rhp.role_id = r.id
JOIN permissions p ON rhp.permission_id = p.id
WHERE p.name = 'assign.job.delete' AND p.guard_name = 'admin';

-- Check direct user assignments for assign.job.delete
SELECT 
    mhp.model_id as admin_id,
    a.name as admin_name,
    a.email as admin_email,
    p.name as permission_name,
    p.group_name
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
JOIN admins a ON mhp.model_id = a.id
WHERE p.name = 'assign.job.delete' 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin';

-- Check all permissions for current user (ID 8 - Assistant Manager)
SELECT 
    p.name as permission_name,
    p.group_name,
    'Direct User Permission' as assignment_type
FROM permissions p
JOIN model_has_permissions mhp ON p.id = mhp.permission_id
WHERE mhp.model_id = 8 
AND mhp.model_type = 'App\\Models\\Admin'
AND p.guard_name = 'admin'
ORDER BY p.group_name, p.name;

-- STEP 6: ADD PERMISSION TO ASSISTANT MANAGER ROLE IF NEEDED
-- ==========================================

-- Check if Assistant Manager role (ID 6) has the permission
-- First, let's see what role ID 6 currently has
SELECT 
    r.name as role_name,
    p.name as permission_name,
    p.group_name
FROM role_has_permissions rhp
JOIN roles r ON rhp.role_id = r.id
JOIN permissions p ON rhp.permission_id = p.id
WHERE r.id = 6 AND p.guard_name = 'admin'
ORDER BY p.name;

-- If needed, assign to Assistant Manager role (ID 6)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id) 
VALUES (6, @assign_job_delete_id);

-- ==========================================
-- COMPLETE VERIFICATION - ALL SHOP MANAGEMENT PERMISSIONS
-- ==========================================

-- Show all Shop Management related permissions
SELECT 
    p.name as permission_name,
    p.group_name,
    r.name as role_name
FROM permissions p
LEFT JOIN role_has_permissions rhp ON p.id = rhp.permission_id
LEFT JOIN roles r ON rhp.role_id = r.id
WHERE p.group_name = 'Shop Management' AND p.guard_name = 'admin'
ORDER BY p.name, r.name;

-- ==========================================
-- OPTIONAL: CLEAN UP IF NEEDED
-- ==========================================

-- If you need to remove assign.job.delete permission (use with caution)
/*
DELETE FROM model_has_permissions 
WHERE permission_id = (SELECT id FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin');

DELETE FROM role_has_permissions 
WHERE permission_id = (SELECT id FROM permissions WHERE name = 'assign.job.delete' AND guard_name = 'admin');

DELETE FROM permissions 
WHERE name = 'assign.job.delete' AND guard_name = 'admin';
*/
