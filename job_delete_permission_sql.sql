-- ==========================================
-- RAW SQL COMMANDS FOR JOB.DELETE PERMISSION
-- LIVE DATABASE USAGE
-- ==========================================

-- STEP 1: CREATE JOB.DELETE PERMISSION
-- ==========================================

-- Create job.delete permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name) 
VALUES ('job.delete', 'admin', 'Shop Management');

-- STEP 2: GET PERMISSION ID
-- ==========================================

-- Get job.delete permission ID for assignment
SET @job_delete_id = (SELECT id FROM permissions WHERE name = 'job.delete' AND guard_name = 'admin');

-- STEP 3: ASSIGN TO ADMIN ROLES
-- ==========================================

-- Assign job.delete to admin role (assuming role ID 1)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id) 
VALUES (1, @job_delete_id);

-- Assign job.delete to super-admin role if exists (assuming role ID 2)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id) 
VALUES (2, @job_delete_id);

-- STEP 4: ASSIGN DIRECTLY TO ADMIN USER (ID 1)
-- ==========================================

-- Assign job.delete to admin user directly
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id) 
VALUES (@job_delete_id, 'App\\Models\\Admin', 1);

-- STEP 5: VERIFICATION QUERIES
-- ==========================================

-- Check if job.delete permission exists
SELECT * FROM permissions 
WHERE name = 'job.delete' AND guard_name = 'admin';

-- Check role assignments for job.delete
SELECT 
    r.name as role_name,
    p.name as permission_name,
    p.group_name
FROM role_has_permissions rhp
JOIN roles r ON rhp.role_id = r.id
JOIN permissions p ON rhp.permission_id = p.id
WHERE p.name = 'job.delete' AND p.guard_name = 'admin';

-- Check direct user assignments for job.delete
SELECT 
    mhp.model_id as admin_id,
    p.name as permission_name,
    p.group_name
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
WHERE p.name = 'job.delete' 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin';

-- Check all admin user permissions including job.delete
SELECT 
    p.name as permission_name,
    p.group_name,
    'Direct User Permission' as assignment_type
FROM permissions p
JOIN model_has_permissions mhp ON p.id = mhp.permission_id
WHERE mhp.model_id = 1 
AND mhp.model_type = 'App\\Models\\Admin'
AND p.guard_name = 'admin'
ORDER BY p.group_name, p.name;

-- ==========================================
-- OPTIONAL: CLEAN UP IF NEEDED
-- ==========================================

-- If you need to remove job.delete permission (use with caution)
/*
DELETE FROM model_has_permissions 
WHERE permission_id = (SELECT id FROM permissions WHERE name = 'job.delete' AND guard_name = 'admin');

DELETE FROM role_has_permissions 
WHERE permission_id = (SELECT id FROM permissions WHERE name = 'job.delete' AND guard_name = 'admin');

DELETE FROM permissions 
WHERE name = 'job.delete' AND guard_name = 'admin';
*/

-- ==========================================
-- COMPLETE VERIFICATION
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
