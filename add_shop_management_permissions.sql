-- Add shop_management permissions for all staff members
-- Run these SQL commands directly in your database

-- First, let's see current staff members
SELECT id, name, email FROM staff;

-- Add shop_management permissions for each staff member
-- Replace staff_id with actual IDs from your staff table

-- Example for staff member with ID 1
INSERT INTO staff_permissions (staff_id, module, can_view, can_create, can_edit, can_delete, created_at, updated_at)
VALUES (1, 'shop_management', 1, 0, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
can_view = 1, can_create = 0, can_edit = 1, can_delete = 0, updated_at = NOW();

-- Example for staff member with ID 2
INSERT INTO staff_permissions (staff_id, module, can_view, can_create, can_edit, can_delete, created_at, updated_at)
VALUES (2, 'shop_management', 1, 0, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
can_view = 1, can_create = 0, can_edit = 1, can_delete = 0, updated_at = NOW();

-- Add more staff members as needed - copy and modify the above with actual staff IDs

-- Or run this to add permissions for ALL staff members at once
INSERT INTO staff_permissions (staff_id, module, can_view, can_create, can_edit, can_delete, created_at, updated_at)
SELECT id, 'shop_management', 1, 0, 1, 0, NOW(), NOW()
FROM staff
WHERE id NOT IN (
    SELECT staff_id FROM staff_permissions WHERE module = 'shop_management'
);

-- Verify the permissions were added
SELECT sp.*, s.name, s.email 
FROM staff_permissions sp 
JOIN staff s ON sp.staff_id = s.id 
WHERE sp.module = 'shop_management';
