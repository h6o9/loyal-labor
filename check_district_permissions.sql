-- Raw SQL Commands to Check District Permissions
-- ==================================================

-- 1. Check if admin user has district.view permission
SELECT 
    a.id as admin_id,
    a.email as admin_email,
    p.name as permission_name,
    CASE 
        WHEN p.name = 'district.view' THEN '✓ DISTRICT VIEW PERMISSION FOUND'
        ELSE 'Other permission'
    END as status
FROM admins a
JOIN model_has_permissions mhp ON a.id = mhp.model_id
JOIN permissions p ON mhp.permission_id = p.id
WHERE mhp.model_type = 'App\\Models\\Admin'
AND p.name = 'district.view';

-- 2. Check all district-related permissions for admin
SELECT 
    a.email as admin_email,
    p.name as permission_name,
    CASE 
        WHEN p.name LIKE '%district%' THEN 'DISTRICT PERMISSION'
        ELSE 'OTHER'
    END as permission_type
FROM admins a
JOIN model_has_permissions mhp ON a.id = mhp.model_id
JOIN permissions p ON mhp.permission_id = p.id
WHERE mhp.model_type = 'App\\Models\\Admin'
AND a.id = 1  -- Change admin ID if needed
ORDER BY p.name;

-- 3. Check if district.view permission exists in system
SELECT 
    name,
    guard_name,
    CASE 
        WHEN name = 'district.view' THEN '✓ DISTRICT.VIEW EXISTS'
        ELSE 'Other permission'
    END as status
FROM permissions 
WHERE name LIKE '%district%';

-- 4. Check admin role permissions (if using role-based permissions)
SELECT 
    a.email as admin_email,
    r.name as role_name,
    p.name as permission_name
FROM admins a
JOIN model_has_roles mhr ON a.id = mhr.model_id
JOIN roles r ON mhr.role_id = r.id
JOIN role_has_permissions rhp ON r.id = rhp.role_id
JOIN permissions p ON rhp.permission_id = p.id
WHERE mhr.model_type = 'App\\Models\\Admin'
AND p.name LIKE '%district%';

-- 5. Quick check: Does admin ID 1 have district.view?
SELECT 
    CASE 
        WHEN EXISTS (
            SELECT 1 
            FROM model_has_permissions mhp
            JOIN permissions p ON mhp.permission_id = p.id
            WHERE mhp.model_id = 1 
            AND mhp.model_type = 'App\\Models\\Admin'
            AND p.name = 'district.view'
        ) THEN 'YES - Admin has district.view permission'
        ELSE 'NO - Admin does NOT have district.view permission'
    END as district_permission_status;

-- 6. Check all admins with district permissions
SELECT DISTINCT
    a.id,
    a.email,
    GROUP_CONCAT(p.name) as district_permissions
FROM admins a
JOIN model_has_permissions mhp ON a.id = mhp.model_id
JOIN permissions p ON mhp.permission_id = p.id
WHERE mhp.model_type = 'App\\Models\\Admin'
AND p.name LIKE '%district%'
GROUP BY a.id, a.email;
