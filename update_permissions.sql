-- ==========================================
-- UPDATE PERMISSIONS - REMOVE ASSIGN, USE SHOP.EDIT
-- ==========================================

-- 1. Remove assign shop-management permission from permissions table
DELETE FROM permissions WHERE name = 'assign shop-management' AND guard_name = 'admin';

-- 2. Check if shop.edit permission exists, if not create it
INSERT IGNORE INTO permissions (name, guard_name, group_name) VALUES 
('shop.edit', 'admin', 'Shop Management');

-- 3. Get shop.edit permission ID
SET @shop_edit_perm_id = (SELECT id FROM permissions WHERE name = 'shop.edit' AND guard_name = 'admin');

-- 4. Update role_has_permissions - replace assign with shop.edit
-- First remove any existing shop.edit assignments to avoid duplicates
DELETE rhp FROM role_has_permissions rhp
JOIN permissions p ON rhp.permission_id = p.id
WHERE p.name = 'shop.edit' AND p.guard_name = 'admin';

-- Now assign shop.edit to admin roles
INSERT INTO role_has_permissions (role_id, permission_id)
SELECT r.id, @shop_edit_perm_id
FROM roles r 
WHERE r.name IN ('admin', 'super-admin') AND r.guard_name = 'admin'
AND r.id NOT IN (
    SELECT role_id FROM role_has_permissions WHERE permission_id = @shop_edit_perm_id
);

-- 5. Update model_has_permissions if any direct user assignments exist
-- Remove any existing shop.edit assignments
DELETE mhp FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
WHERE p.name = 'shop.edit' AND p.guard_name = 'admin';

-- Assign shop.edit to admin users who had assign permission
INSERT INTO model_has_permissions (model_type, model_id, permission_id)
SELECT 'App\\Models\\Admin', mhp.model_id, @shop_edit_perm_id
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
WHERE p.name = 'assign shop-management' AND p.guard_name = 'admin'
AND mhp.model_type = 'App\\Models\\Admin'
AND mhp.model_id NOT IN (
    SELECT model_id FROM model_has_permissions WHERE permission_id = @shop_edit_perm_id AND model_type = 'App\\Models\\Admin'
);

-- 6. Remove old assign permission from model_has_permissions
DELETE mhp FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
WHERE p.name = 'assign shop-management' AND p.guard_name = 'admin';

-- 7. Verification - Check current shop.edit assignments
SELECT 
    'role_has_permissions' as table_name,
    r.name as role_name,
    p.name as permission_name
FROM role_has_permissions rhp
JOIN roles r ON rhp.role_id = r.id
JOIN permissions p ON rhp.permission_id = p.id
WHERE p.name = 'shop.edit' AND p.guard_name = 'admin'

UNION ALL

SELECT 
    'model_has_permissions' as table_name,
    CONCAT('Admin ID: ', mhp.model_id) as role_name,
    p.name as permission_name
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
WHERE p.name = 'shop.edit' AND p.guard_name = 'admin';
