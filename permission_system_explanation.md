# Laravel Permission System Explanation

## 📊 **Permission Tables Structure**

### 1. **permissions** Table
```sql
- id (Primary Key)
- name (Permission name, e.g., 'shop.view', 'shop.edit')
- guard_name (Guard name, e.g., 'admin', 'staff')
- group_name (Permission group for UI organization)
- created_at, updated_at
```

### 2. **roles** Table
```sql
- id (Primary Key)
- name (Role name, e.g., 'admin', 'super-admin')
- guard_name (Guard name)
- created_at, updated_at
```

### 3. **role_has_permissions** Table
```sql
- role_id (Foreign key to roles.id)
- permission_id (Foreign key to permissions.id)
```
**Purpose**: Assigns permissions to roles

### 4. **model_has_permissions** Table
```sql
- permission_id (Foreign key to permissions.id)
- model_type (Model class, e.g., 'App\\Models\\Admin')
- model_id (User ID)
```
**Purpose**: Direct permission assignment to users

### 5. **staff_permissions** Table (Custom Staff System)
```sql
- id (Primary Key)
- staff_id (Foreign key to staff.id)
- module (Module name, e.g., 'shop_management', 'my_jobs')
- can_view (Boolean)
- can_create (Boolean)
- can_edit (Boolean)
- can_delete (Boolean)
- permissable (Boolean for job assign)
- created_at, updated_at
```

## 🔐 **How Permissions Work**

### **For Admin Users (Spatie Package)**
1. **Role-based**: Admin users get permissions via roles
2. **Direct Assignment**: Admin users can also have direct permissions
3. **Permission Check**: `auth('admin')->user()->hasPermissionTo('permission.name')`

### **For Staff Users (Custom System)**
1. **Module-based**: Staff permissions are organized by modules
2. **Permission Check**: `auth('staff')->user()->hasPermission('module', 'action')`

## 📝 **How Permissions Are Added**

### **Method 1: Via Database Seeders**
```php
// Create permission
Permission::firstOrCreate([
    'name' => 'shop.edit',
    'guard_name' => 'admin',
    'group_name' => 'Shop Management'
]);

// Assign to role
$role = Role::where('name', 'admin')->first();
$role->givePermissionTo('shop.edit');

// Assign to user directly
$admin = Admin::find(1);
$admin->givePermissionTo('shop.edit');
```

### **Method 2: Via Raw SQL**
```sql
-- Create permission
INSERT INTO permissions (name, guard_name, group_name) 
VALUES ('shop.edit', 'admin', 'Shop Management');

-- Assign to role
INSERT INTO role_has_permissions (role_id, permission_id)
VALUES (1, (SELECT id FROM permissions WHERE name = 'shop.edit'));

-- Assign to user directly
INSERT INTO model_has_permissions (permission_id, model_type, model_id)
VALUES ((SELECT id FROM permissions WHERE name = 'shop.edit'), 'App\\Models\\Admin', 1);
```

### **Method 3: Via Admin Panel**
1. Go to `/admin/assign-permissions`
2. Select role
3. Check/uncheck permissions
4. Save

## 🎯 **Current Permission Structure**

### **Admin Permissions**
- `shop.view` - View shop list
- `shop.edit` - Edit shops and assign jobs

### **Staff Permissions**
- `shop_management` module with can_view, can_create, can_edit, can_delete, permissable
- `my_jobs` module with can_view, can_edit

## 🔍 **How to Check Permissions in Code**

### **Blade Templates**
```php
// Admin permissions
@if(auth('admin')->user()->hasPermissionTo('shop.edit'))
    <!-- Show assign button -->
@endif

// Staff permissions
@if(auth('staff')->user()->hasPermission('my_jobs', 'can_view'))
    <!-- Show My Jobs menu -->
@endif
```

### **Controllers**
```php
// Admin permissions
if (!auth('admin')->user()->hasPermissionTo('shop.view')) {
    abort(403, 'Unauthorized action.');
}

// Staff permissions
if (!auth('staff')->user()->hasPermission('shop_management', 'can_edit')) {
    abort(403, 'Unauthorized action.');
}
```

## 📋 **Common Permission Names**

### **Shop Management**
- `shop.view` - View shop list
- `shop.edit` - Edit shops and assign jobs
- `shop.create` - Create new shops
- `shop.delete` - Delete shops

### **Staff Management**
- `staff.view` - View staff list
- `staff.edit` - Edit staff details
- `staff.create` - Create new staff
- `staff.delete` - Delete staff

### **Role Management**
- `role.view` - View roles
- `role.assign` - Assign permissions to roles

## 🚨 **Important Notes**

1. **Guard Name**: Always specify correct guard ('admin' or 'staff')
2. **Permission Names**: Use consistent naming convention
3. **Cache**: Clear permission cache after changes: `php artisan cache:clear`
4. **Super Admin**: Users with `is_super_admin = 1` typically have all permissions
5. **Module Names**: Staff permissions use module names, not permission names
