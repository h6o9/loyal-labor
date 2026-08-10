-- Shop Categories Permissions SQL for Live Database
-- Run these queries on your live server to add shop category permissions

-- 1. Add shop category permissions
INSERT INTO `permissions` (`name`, `guard_name`, `group_name`, `created_at`, `updated_at`) 
VALUES 
('shop.category.view', 'admin', 'shop', NOW(), NOW()),
('shop.category.create', 'admin', 'shop', NOW(), NOW()),
('shop.category.edit', 'admin', 'shop', NOW(), NOW()),
('shop.category.delete', 'admin', 'shop', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
`guard_name` = VALUES(`guard_name`), 
`group_name` = VALUES(`group_name`), 
`updated_at` = NOW();

-- 2. Get permission IDs and assign to super admin (usually ID: 1)
-- First, get the permission IDs (you can run this separately to see the IDs)
SELECT id, name FROM permissions WHERE name LIKE 'shop.category.%';

-- 3. Assign permissions to super admin (replace with actual super admin ID if different)
INSERT INTO `model_has_permissions` (`model_id`, `model_type`, `permission_id`) 
SELECT 1, 'App\\Models\\Admin', id FROM permissions WHERE name LIKE 'shop.category.%'
ON DUPLICATE KEY UPDATE 
`model_id` = VALUES(`model_id`), 
`model_type` = VALUES(`model_type`);

-- 4. Verify permissions are assigned
SELECT 
    p.name, 
    p.group_name,
    mhp.model_id,
    mhp.model_type
FROM permissions p
LEFT JOIN model_has_permissions mhp ON p.id = mhp.permission_id 
WHERE p.name LIKE 'shop.category.%'
ORDER BY p.name;

-- 5. If you have other admin roles/permissions, you might want to assign to roles too
-- Assign to super admin role (usually ID: 1)
INSERT INTO `role_has_permissions` (`role_id`, `permission_id`) 
SELECT 1, id FROM permissions WHERE name LIKE 'shop.category.%'
ON DUPLICATE KEY UPDATE `role_id` = VALUES(`role_id`);

-- 6. Clear cache after permissions are added
-- Run these Laravel commands on your server:
-- php artisan cache:clear
-- php artisan config:clear
-- php artisan view:clear
-- php artisan route:clear
