-- ==========================================
-- RAW SQL COMMANDS FOR LIVE DATABASE
-- ==========================================

-- 1. JOB ASSIGN PERMISSIONS FOR ADMIN ROLES
-- ==========================================
-- First, make sure permissions exist
INSERT IGNORE INTO permissions (name, guard_name, group_name) VALUES 
('view shop-management', 'admin', 'Shop Management'),
('edit shop-management', 'admin', 'Shop Management'),
('assign shop-management', 'admin', 'Shop Management');

-- Get permission IDs (you may need to adjust these IDs based on your database)
SET @view_perm_id = (SELECT id FROM permissions WHERE name = 'view shop-management' AND guard_name = 'admin');
SET @edit_perm_id = (SELECT id FROM permissions WHERE name = 'edit shop-management' AND guard_name = 'admin');
SET @assign_perm_id = (SELECT id FROM permissions WHERE name = 'assign shop-management' AND guard_name = 'admin');

-- Assign permissions to admin role (assuming role ID 1 for 'admin')
-- Check your roles table to get the correct role ID
INSERT IGNORE INTO role_has_permissions (role_id, permission_id) VALUES 
(1, @view_perm_id),
(1, @edit_perm_id),
(1, @assign_perm_id);

-- If you have a super-admin role, assign to it too (assuming role ID 2)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id) VALUES 
(2, @view_perm_id),
(2, @edit_perm_id),
(2, @assign_perm_id);

-- ==========================================
-- 2. STAFF PERMISSIONS
-- ==========================================

-- Add shop_management permissions for all staff
INSERT IGNORE INTO staff_permissions (staff_id, module, can_view, can_create, can_edit, can_delete, created_at, updated_at)
SELECT id, 'shop_management', 1, 0, 1, 0, NOW(), NOW()
FROM staff
WHERE id NOT IN (SELECT staff_id FROM staff_permissions WHERE module = 'shop_management');

-- Add my_jobs permissions for all staff  
INSERT IGNORE INTO staff_permissions (staff_id, module, can_view, can_create, can_edit, can_delete, created_at, updated_at)
SELECT id, 'my_jobs', 1, 0, 1, 0, NOW(), NOW()
FROM staff
WHERE id NOT IN (SELECT staff_id FROM staff_permissions WHERE module = 'my_jobs');

-- For specific staff member (replace STAFF_ID with actual ID)
INSERT IGNORE INTO staff_permissions (staff_id, module, can_view, can_create, can_edit, can_delete, created_at, updated_at)
VALUES (STAFF_ID, 'shop_management', 1, 0, 1, 0, NOW(), NOW());

INSERT IGNORE INTO staff_permissions (staff_id, module, can_view, can_create, can_edit, can_delete, created_at, updated_at)
VALUES (STAFF_ID, 'my_jobs', 1, 0, 1, 0, NOW(), NOW());

-- ==========================================
-- 3. VERIFY PERMISSIONS
-- ==========================================

-- Check admin permissions
SELECT 
    r.name as role_name,
    p.name as permission_name,
    p.group_name
FROM role_has_permissions rhp
JOIN roles r ON rhp.role_id = r.id
JOIN permissions p ON rhp.permission_id = p.id
WHERE p.group_name = 'Shop Management';

-- Check staff permissions
SELECT 
    s.name as staff_name,
    s.email as staff_email,
    sp.module,
    sp.can_view,
    sp.can_edit,
    sp.permissable
FROM staff_permissions sp
JOIN staff s ON sp.staff_id = s.id
ORDER BY s.name, sp.module;
