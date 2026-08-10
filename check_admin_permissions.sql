-- Check Admin User and Permissions
-- =====================================

-- 1. Check admin users
SELECT id, email, name, is_active FROM admins WHERE is_active = 1;

-- 2. Check admin permissions for a specific admin (replace 1 with actual admin ID)
SELECT 
    a.id as admin_id,
    a.email as admin_email,
    p.name as permission_name,
    p.module,
    p.can_view,
    p.can_create,
    p.can_edit,
    p.can_delete
FROM admins a
LEFT JOIN admin_permissions ap ON a.id = ap.admin_id
LEFT JOIN permissions p ON ap.permission_id = p.id
WHERE a.id = 1  -- Replace with your admin ID
ORDER BY p.module, p.name;

-- 3. Check specifically for district permissions
SELECT 
    a.id as admin_id,
    a.email,
    p.name as permission_name,
    p.module,
    CASE 
        WHEN p.name LIKE '%district%' THEN 'DISTRICT PERMISSION'
        WHEN p.module = 'district' THEN 'DISTRICT MODULE PERMISSION'
        ELSE 'OTHER PERMISSION'
    END as permission_type
FROM admins a
LEFT JOIN admin_permissions ap ON a.id = ap.admin_id
LEFT JOIN permissions p ON ap.permission_id = p.id
WHERE a.id = 1  -- Replace with your admin ID
AND (p.name LIKE '%district%' OR p.module = 'district');

-- 4. Check all available permissions in system
SELECT 
    name,
    module,
    can_view,
    can_create,
    can_edit,
    can_delete
FROM permissions
ORDER BY module, name;

-- 5. Check if district permission exists
SELECT * FROM permissions WHERE name = 'district.view' OR module = 'district';

-- 6. Check all admin-permission mappings
SELECT 
    a.email as admin_email,
    p.name as permission_name,
    p.module
FROM admins a
JOIN admin_permissions ap ON a.id = ap.admin_id
JOIN permissions p ON ap.permission_id = p.id
WHERE p.name LIKE '%district%' OR p.module = 'district'
ORDER BY a.email, p.name;
