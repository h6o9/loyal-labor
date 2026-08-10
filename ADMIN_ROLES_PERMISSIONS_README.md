# Admin Roles & Permissions System

## 📋 Overview

This document explains the complete Admin Roles & Permissions system for the Home Services application. The system allows administrators to manage roles, assign permissions to roles, and assign roles to sub-admins.

## 🎯 Key Features

### 1. **Manage Roles**
- Create, Edit, Delete roles
- Simple role management without permissions
- Clean interface for role CRUD operations

### 2. **Assign Permissions**
- Select a role from dropdown
- Grant permissions by section (Dashboard, Roles, Staff, Activity Logs, Settings)
- Fine-grained control with checkboxes for each permission
- Section-level selection for quick permission assignment

### 3. **Assign Roles**
- Select sub-admin from dropdown
- Select role from dropdown
- One-to-one role assignment (one admin = one role)
- View current role assignments

### 4. **Activity Logs**
- Monitor all admin activities
- Filter by specific admin or all sub-admins
- Filter by action type and date range
- Track who did what and when

## 🔐 Permission System

### Permission Names (Dot Notation)
```
dashboard.view          - View dashboard
role.view              - View roles
role.create            - Create roles
role.edit              - Edit roles
role.delete            - Delete roles
role.assign            - Assign permissions/roles
staff.view             - View staff
staff.create           - Create staff
staff.edit             - Edit staff
staff.delete           - Delete staff
activity.logs.view     - View activity logs
setting.view           - View settings
setting.update         - Update settings
admin.create           - Create sub-admins
```

### Role Structure
- **Super Admin**: Has all permissions (cannot be deleted)
- **Manager**: Can manage staff and shops
- **Operator**: Limited permissions for daily operations
- **Custom Roles**: Any combination of permissions

## 🚀 Quick Start Guide

### For Beginners (Step-by-Step)

#### Step 1: Create a Role
1. Go to **Admin Settings** → **Manage Roles**
2. Click **Create Role**
3. Enter role name (e.g., "Manager")
4. Click **Create Role**

#### Step 2: Assign Permissions to Role
1. Go to **Admin Settings** → **Assign Permissions**
2. Select the role from dropdown (e.g., "Manager")
3. Check permissions you want to grant:
   - **Dashboard**: Can View
   - **Roles**: Can View, Can Create, Can Edit, Can Delete
   - **Staff Management**: Can View, Can Create, Can Edit, Can Delete
   - **Activity Logs**: Can View
4. Click **Save Permissions**

#### Step 3: Create Sub-Admin
1. Go to **Admin Settings** → **Add Sub Admin**
2. Fill in admin details
3. Click **Save**

#### Step 4: Assign Role to Sub-Admin
1. Go to **Admin Settings** → **Assign Roles**
2. Select sub-admin from dropdown
3. Select role from dropdown (e.g., "Manager")
4. Click **Assign Role**

#### Step 5: Monitor Activity
1. Go to **Admin Settings** → **Activity Logs**
2. Select admin from dropdown or choose "All Sub Admins"
3. Filter by action or date range
4. View all activities performed by admins

## 📁 File Structure

```
app/Http/Controllers/Admin/
├── RolesController.php          # Role CRUD and permission assignment
├── AssignRoleController.php     # Role assignment to admins
├── AdminActivityController.php  # Activity logs management
└── AdminController.php          # Sub-admin management

resources/views/admin/
├── sidebar.blade.php            # Main navigation menu
├── roles/
│   ├── index.blade.php          # Roles list
│   ├── create.blade.php         # Create role (simple form)
│   ├── edit.blade.php           # Edit role
│   ├── show.blade.php           # Role details
│   └── assign-role.blade.php    # Assign permissions UI
├── assign-roles/
│   └── index.blade.php          # Assign roles UI
└── activity-logs/
    └── index.blade.php          # Activity logs with filters

routes/admin.php                 # Admin routes
```

## 🛠️ Technical Implementation

### Routes
```php
// Roles CRUD
Route::resource('/role', RolesController::class);

// Assign Permissions
Route::get('role/assign', [RolesController::class, 'assignRoleView']);
Route::put('role/assign', [RolesController::class, 'assignRoleUpdate']);

// Assign Roles
Route::get('assign-roles', [AssignRoleController::class, 'index']);
Route::post('assign-roles', [AssignRoleController::class, 'assign']);

// Activity Logs
Route::get('activity-logs', [AdminActivityController::class, 'index']);
```

### Database Tables
- `roles` - Role definitions
- `permissions` - Permission definitions
- `model_has_permissions` - Role-permission relationships
- `model_has_roles` - Admin-role relationships
- `admin_activities` - Activity log entries

### Permission Checks
```php
// In controllers
checkAdminHasPermissionAndThrowException('role.view');

// In views
@can('role.assign')
    // Show this content if user has permission
@endcan
```

## 🔧 Common Issues & Solutions

### Issue: 403 Permission Denied
**Solution**: Ensure admin has the required permission:
```bash
php artisan tinker
>>> Admin::find(1)->givePermissionTo('role.assign');
```

### Issue: Sidebar Menu Not Showing
**Solution**: Check if the admin has the required permission for that menu item.

### Issue: Activity Logs Table Not Found
**Solution**: Run the migration:
```bash
php artisan migrate
```

## 📊 Permission Matrix

| Role | Dashboard | Roles | Staff | Activity Logs | Settings | Create Admin |
|------|-----------|-------|-------|---------------|----------|--------------|
| Super Admin | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Manager | ✅ | ❌ | ✅ | ✅ | ❌ | ❌ |
| Operator | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| Custom | 🔧 | 🔧 | 🔧 | 🔧 | 🔧 | 🔧 |

## 🎨 UI Features

### Sidebar Navigation
- **Simple Structure**: No nested submenus
- **Permission-based**: Items show/hide based on permissions
- **Active State**: Current page is highlighted
- **Consistent**: Same structure across all admin pages

### Forms
- **Clean Design**: Bootstrap-based forms
- **Validation**: Server-side validation with error messages
- **Responsive**: Works on all screen sizes
- **User-friendly**: Clear labels and helpful placeholders

### Tables
- **Sortable**: Click headers to sort
- **Searchable**: Built-in search functionality
- **Paginated**: Large datasets split into pages
- **Exportable**: Data can be exported to CSV/Excel

## 🔄 Workflow Example

### Creating a "Shop Manager" Role:
1. **Create Role**: "Shop Manager"
2. **Assign Permissions**:
   - Dashboard: Can View
   - Staff Management: Can View, Can Edit
   - Shop Management: Can View, Can Edit, Can Create
3. **Create Admin**: New shop manager account
4. **Assign Role**: Assign "Shop Manager" role to new admin
5. **Monitor**: Check activity logs to ensure proper access

## 🚨 Security Considerations

- Super Admin role cannot be deleted or modified
- Permission checks on all sensitive operations
- Activity logging for audit trail
- Role-based access control (RBAC) implementation
- Input validation and sanitization

## 📞 Support

For any issues with the Admin Roles & Permissions system:
1. Check this README first
2. Verify database migrations are run
3. Ensure admin has proper permissions
4. Check Laravel logs for errors

---

**Last Updated**: April 8, 2026
**Version**: 1.0
**Compatible**: Laravel 12.x, PHP 8.2+
