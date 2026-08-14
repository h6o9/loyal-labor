-- Service Categories permissions for Super Admin + Role Assign UI
-- Run on LIVE MySQL (phpMyAdmin). Then assign to sub-admin roles from Admin > Roles > Assign Permissions.
-- After this query: php artisan permission:cache-reset  (or wait until cache expires)

INSERT INTO permissions (name, guard_name, group_name, created_at, updated_at)
SELECT 'service.categories.view', 'admin', 'System Records', NOW(), NOW()
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM permissions WHERE name = 'service.categories.view' AND guard_name = 'admin'
);

INSERT INTO permissions (name, guard_name, group_name, created_at, updated_at)
SELECT 'service.categories.create', 'admin', 'System Records', NOW(), NOW()
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM permissions WHERE name = 'service.categories.create' AND guard_name = 'admin'
);

INSERT INTO permissions (name, guard_name, group_name, created_at, updated_at)
SELECT 'service.categories.edit', 'admin', 'System Records', NOW(), NOW()
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM permissions WHERE name = 'service.categories.edit' AND guard_name = 'admin'
);

INSERT INTO permissions (name, guard_name, group_name, created_at, updated_at)
SELECT 'service.categories.delete', 'admin', 'System Records', NOW(), NOW()
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM permissions WHERE name = 'service.categories.delete' AND guard_name = 'admin'
);

-- Super Admin ROLE ko assign
INSERT INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id
FROM permissions p
INNER JOIN roles r ON r.name = 'Super Admin' AND r.guard_name = 'admin'
WHERE p.guard_name = 'admin'
  AND p.name IN (
      'service.categories.view',
      'service.categories.create',
      'service.categories.edit',
      'service.categories.delete'
  )
  AND NOT EXISTS (
      SELECT 1 FROM role_has_permissions rhp
      WHERE rhp.permission_id = p.id AND rhp.role_id = r.id
  );

-- Super Admin USERS ko assign
INSERT INTO model_has_permissions (permission_id, model_type, model_id)
SELECT p.id, 'App\\Models\\Admin', a.id
FROM permissions p
INNER JOIN admins a ON a.is_super_admin = 1
WHERE p.guard_name = 'admin'
  AND p.name IN (
      'service.categories.view',
      'service.categories.create',
      'service.categories.edit',
      'service.categories.delete'
  )
  AND NOT EXISTS (
      SELECT 1 FROM model_has_permissions mhp
      WHERE mhp.permission_id = p.id
        AND mhp.model_type = 'App\\Models\\Admin'
        AND mhp.model_id = a.id
  );
