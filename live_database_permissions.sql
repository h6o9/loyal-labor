-- ==========================================
-- RAW SQL COMMANDS FOR LIVE DATABASE
-- SHOP MANAGEMENT PERMISSIONS
-- ==========================================

-- STEP 1: CREATE/UPDATE PERMISSIONS
-- ==========================================

-- Create shop.view permission if not exists
INSERT IGNORE INTO permissions (name, guard_name, group_name) 
VALUES ('shop.view', 'admin', 'Shop Management');

-- Create shop.edit permission if not exists  
INSERT IGNORE INTO permissions (name, guard_name, group_name) 
VALUES ('shop.edit', 'admin', 'Shop Management');

-- STEP 2: GET PERMISSION IDs
-- ==========================================

-- Get shop.view permission ID
SET @shop_view_id = (SELECT id FROM permissions WHERE name = 'shop.view' AND guard_name = 'admin');

-- Get shop.edit permission ID  
SET @shop_edit_id = (SELECT id FROM permissions WHERE name = 'shop.edit' AND guard_name = 'admin');

-- STEP 3: ASSIGN PERMISSIONS TO ADMIN ROLES
-- ==========================================

-- Assign shop.view to admin role (assuming role ID 1)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id) 
VALUES (1, @shop_view_id);

-- Assign shop.edit to admin role (assuming role ID 1)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id) 
VALUES (1, @shop_edit_id);

-- Assign to super-admin role if exists (assuming role ID 2)
INSERT IGNORE INTO role_has_permissions (role_id, permission_id) 
VALUES (2, @shop_view_id);

INSERT IGNORE INTO role_has_permissions (role_id, permission_id) 
VALUES (2, @shop_edit_id);

-- STEP 4: ASSIGN PERMISSIONS DIRECTLY TO ADMIN USER (ID 1)
-- ==========================================

-- Assign shop.view to admin user directly
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id) 
VALUES (@shop_view_id, 'App\\Models\\Admin', 1);

-- Assign shop.edit to admin user directly
INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id) 
VALUES (@shop_edit_id, 'App\\Models\\Admin', 1);

-- STEP 5: CLEAN UP OLD PERMISSIONS (OPTIONAL)
-- ==========================================

-- Remove old shop-management permissions if they exist
DELETE FROM role_has_permissions 
WHERE permission_id IN (
    SELECT id FROM permissions 
    WHERE name IN ('view shop-management', 'edit shop-management', 'assign shop-management') 
    AND guard_name = 'admin'
);

DELETE FROM model_has_permissions 
WHERE permission_id IN (
    SELECT id FROM permissions 
    WHERE name IN ('view shop-management', 'edit shop-management', 'assign shop-management') 
    AND guard_name = 'admin'
);

DELETE FROM permissions 
WHERE name IN ('view shop-management', 'edit shop-management', 'assign shop-management') 
AND guard_name = 'admin';

-- STEP 6: VERIFICATION QUERIES
-- ==========================================

-- Check current shop permissions
SELECT 
    p.name as permission_name,
    p.group_name,
    r.name as role_name
FROM permissions p
LEFT JOIN role_has_permissions rhp ON p.id = rhp.permission_id
LEFT JOIN roles r ON rhp.role_id = r.id
WHERE p.name IN ('shop.view', 'shop.edit') 
AND p.guard_name = 'admin'
ORDER BY p.name, r.name;

-- Check admin user permissions
SELECT 
    p.name as permission_name,
    p.group_name,
    'Direct User Permission' as assignment_type
FROM permissions p
JOIN model_has_permissions mhp ON p.id = mhp.permission_id
WHERE p.name IN ('shop.view', 'shop.edit') 
AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id = 1;

-- ==========================================
-- STAFF PERMISSIONS (IF NEEDED)
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
