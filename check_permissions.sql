-- ==========================================
-- CHECK PERMISSION TABLES AND CURRENT PERMISSIONS
-- ==========================================

-- 1. Check all permission-related tables
SHOW TABLES LIKE '%permission%';

-- 2. Check current permissions in permissions table
SELECT * FROM permissions WHERE guard_name = 'admin' ORDER BY group_name, name;

-- 3. Check role_has_permissions table
SELECT 
    r.name as role_name,
    p.name as permission_name,
    p.group_name
FROM role_has_permissions rhp
JOIN roles r ON rhp.role_id = r.id
JOIN permissions p ON rhp.permission_id = p.id
WHERE p.guard_name = 'admin'
ORDER BY r.name, p.group_name, p.name;

-- 4. Check model_has_permissions table (for direct user permissions)
SELECT 
    mhp.model_type,
    mhp.model_id,
    p.name as permission_name,
    p.group_name
FROM model_has_permissions mhp
JOIN permissions p ON mhp.permission_id = p.id
WHERE p.guard_name = 'admin'
ORDER BY mhp.model_type, mhp.model_id, p.name;

-- 5. Check if shop.edit permission exists
SELECT * FROM permissions WHERE name = 'shop.edit' AND guard_name = 'admin';

-- 6. Check assign shop-management permission
SELECT * FROM permissions WHERE name = 'assign shop-management' AND guard_name = 'admin';
