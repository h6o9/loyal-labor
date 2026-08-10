-- Add my_jobs permissions for all staff members
-- Run these SQL commands directly in your database

-- Add my_jobs permissions for ALL staff members at once
INSERT INTO staff_permissions (staff_id, module, can_view, can_create, can_edit, can_delete, created_at, updated_at)
SELECT id, 'my_jobs', 1, 0, 1, 0, NOW(), NOW()
FROM staff
WHERE id NOT IN (
    SELECT staff_id FROM staff_permissions WHERE module = 'my_jobs'
);

-- For specific staff member (replace ID)
INSERT INTO staff_permissions (staff_id, module, can_view, can_create, can_edit, can_delete, created_at, updated_at)
VALUES (1, 'my_jobs', 1, 0, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
can_view = 1, can_create = 0, can_edit = 1, can_delete = 0, updated_at = NOW();

-- Verify the permissions were added
SELECT sp.*, s.name, s.email 
FROM staff_permissions sp 
JOIN staff s ON sp.staff_id = s.id 
WHERE sp.module = 'my_jobs';
