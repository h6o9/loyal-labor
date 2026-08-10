-- ==========================================
-- RAW SQL COMMANDS FOR LIVE DATABASE
-- STAFF JOB SECTION PERMISSIONS
-- ==========================================

-- This script adds job section permissions to staff members
-- so they can see and manage jobs in their staff dashboard

-- STEP 1: CREATE STAFF JOB PERMISSIONS
-- ==========================================

-- Create staff.jobs.view permission - View jobs list
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('staff.jobs.view', 'admin', 'Staff Management', NOW(), NOW());

-- Create staff.jobs.show permission - View job details
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('staff.jobs.show', 'admin', 'Staff Management', NOW(), NOW());

-- Create staff.jobs.mark-done permission - Mark job as done
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('staff.jobs.mark-done', 'admin', 'Staff Management', NOW(), NOW());

-- Create staff.jobs.mark-undone permission - Mark job as undone
INSERT IGNORE INTO permissions (name, guard_name, group_name, created_at, updated_at) 
VALUES ('staff.jobs.mark-undone', 'admin', 'Staff Management', NOW(), NOW());

-- STEP 2: GET PERMISSION IDs
-- ==========================================

SET @staff_jobs_view_id = (SELECT id FROM permissions WHERE name = 'staff.jobs.view' AND guard_name = 'admin');
SET @staff_jobs_show_id = (SELECT id FROM permissions WHERE name = 'staff.jobs.show' AND guard_name = 'admin');
SET @staff_jobs_mark_done_id = (SELECT id FROM permissions WHERE name = 'staff.jobs.mark-done' AND guard_name = 'admin');
SET @staff_jobs_mark_undone_id = (SELECT id FROM permissions WHERE name = 'staff.jobs.mark-undone' AND guard_name = 'admin');

-- STEP 3: ASSIGN PERMISSIONS TO STAFF ROLES
-- ==========================================

-- Assign to Staff role (assuming role ID 3 - check your actual staff role ID)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES (3, @staff_jobs_view_id, NOW(), NOW());

INSERT IGNORE INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES (3, @staff_jobs_show_id, NOW(), NOW());

INSERT IGNORE INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES (3, @staff_jobs_mark_done_id, NOW(), NOW());

INSERT IGNORE INTO role_has_permissions (role_id, permission_id, created_at, updated_at) 
VALUES (3, @staff_jobs_mark_undone_id, NOW(), NOW());

-- STEP 4: ASSIGN PERMISSIONS TO SPECIFIC STAFF MEMBERS
-- ==========================================

-- Get all active staff members
-- Assign job permissions to all active staff members

-- Assign staff.jobs.view to all active staff
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at)
SELECT @staff_jobs_view_id, 'App\\Models\\Staff', id, NOW(), NOW()
FROM staff 
WHERE is_active = 1;

-- Assign staff.jobs.show to all active staff
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at)
SELECT @staff_jobs_show_id, 'App\\Models\\Staff', id, NOW(), NOW()
FROM staff 
WHERE is_active = 1;

-- Assign staff.jobs.mark-done to all active staff
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at)
SELECT @staff_jobs_mark_done_id, 'App\\Models\\Staff', id, NOW(), NOW()
FROM staff 
WHERE is_active = 1;

-- Assign staff.jobs.mark-undone to all active staff
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at)
SELECT @staff_jobs_mark_undone_id, 'App\\Models\\Staff', id, NOW(), NOW()
FROM staff 
WHERE is_active = 1;

-- STEP 5: ASSIGN TO SPECIFIC STAFF MEMBERS (IF NEEDED)
-- ==========================================

-- If you want to assign to specific staff members, use their IDs
-- Example: Assign to staff member with ID 9
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at) 
VALUES (@staff_jobs_view_id, 'App\\Models\\Staff', 9, NOW(), NOW()),
       (@staff_jobs_show_id, 'App\\Models\\Staff', 9, NOW(), NOW()),
       (@staff_jobs_mark_done_id, 'App\\Models\\Staff', 9, NOW(), NOW()),
       (@staff_jobs_mark_undone_id, 'App\\Models\\Staff', 9, NOW(), NOW());

-- Example: Assign to staff member with ID 20
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id, created_at, updated_at) 
VALUES (@staff_jobs_view_id, 'App\\Models\\Staff', 20, NOW(), NOW()),
       (@staff_jobs_show_id, 'App\\Models\\Staff', 20, NOW(), NOW()),
       (@staff_jobs_mark_done_id, 'App\\Models\\Staff', 20, NOW(), NOW()),
       (@staff_jobs_mark_undone_id, 'App\\Models\\Staff', 20, NOW(), NOW());

-- STEP 6: VERIFICATION QUERIES
-- ==========================================

-- Check if staff job permissions exist
SELECT 
    id,
    name,
    guard_name,
    group_name,
    created_at,
    updated_at
FROM permissions 
WHERE name LIKE 'staff.jobs.%' AND guard_name = 'admin'
ORDER BY name;

-- Check staff role assignments
SELECT 
    r.name as role_name,
    p.name as permission_name,
    p.group_name,
    rhp.created_at as assigned_at
FROM role_has_permissions rhp
JOIN roles r ON rhp.role_id = r.id
JOIN permissions p ON rhp.permission_id = p.id
WHERE p.name LIKE 'staff.jobs.%' AND p.guard_name = 'admin'
ORDER BY r.name, p.name;

-- Check staff member assignments
SELECT 
    s.name as staff_name,
    s.email as staff_email,
    p.name as permission_name,
    p.group_name,
    mhp.created_at as assigned_at
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
JOIN staff s ON mhp.model_id = s.id
WHERE p.name LIKE 'staff.jobs.%' 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Staff'
ORDER BY s.name, p.name;

-- Check specific staff member (ID 9) permissions
SELECT 
    s.name as staff_name,
    s.email as staff_email,
    p.name as permission_name,
    p.group_name,
    mhp.created_at as assigned_at
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
JOIN staff s ON mhp.model_id = s.id
WHERE p.name LIKE 'staff.jobs.%' 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Staff'
AND s.id = 9
ORDER BY p.name;

-- STEP 7: CLEANUP AND FINAL VERIFICATION
-- ==========================================

-- Show summary of staff job permissions
SELECT 
    'STAFF JOB PERMISSIONS SUMMARY' as info,
    COUNT(DISTINCT CASE WHEN p.name = 'staff.jobs.view' THEN p.id END) as view_permission_count,
    COUNT(DISTINCT CASE WHEN p.name = 'staff.jobs.show' THEN p.id END) as show_permission_count,
    COUNT(DISTINCT CASE WHEN p.name = 'staff.jobs.mark-done' THEN p.id END) as mark_done_permission_count,
    COUNT(DISTINCT CASE WHEN p.name = 'staff.jobs.mark-undone' THEN p.id END) as mark_undone_permission_count,
    COUNT(DISTINCT mhp.model_id) as total_staff_assignments,
    COUNT(DISTINCT rhp.role_id) as total_role_assignments,
    NOW() as execution_time
FROM permissions p
LEFT JOIN model_has_permissions mhp ON p.id = mhp.permission_id AND mhp.model_type = 'App\\Models\\Staff'
LEFT JOIN role_has_permissions rhp ON p.id = rhp.permission_id
WHERE p.name LIKE 'staff.jobs.%' AND p.guard_name = 'admin';

-- ==========================================
-- NOTES FOR CUSTOMIZATION
-- ==========================================
-- 1. Check your actual staff role ID in the roles table
-- 2. Update the role ID in STEP 3 if needed
-- 3. Add specific staff IDs in STEP 5 if you want to assign to specific members
-- 4. Run the verification queries to confirm assignments
-- 
-- Expected permissions created:
-- - staff.jobs.view (View jobs list)
-- - staff.jobs.show (View job details)  
-- - staff.jobs.mark-done (Mark job as completed)
-- - staff.jobs.mark-undone (Mark job as pending)
-- ==========================================
