# Home Services Staff Management System

A comprehensive Laravel-based staff management system for home services with role-based permissions, shop management, and job assignment capabilities.

## 📋 Table of Contents

1. [Overview](#overview)
2. [Features](#features)
3. [Requirements](#requirements)
4. [Installation](#installation)
5. [File Structure](#file-structure)
6. [Database Schema](#database-schema)
7. [User Roles & Permissions](#user-roles--permissions)
8. [API Endpoints](#api-endpoints)
9. [Common Issues & Solutions](#common-issues--solutions)
10. [Development Guidelines](#development-guidelines)

## 🎯 Overview

This system provides a complete solution for managing home service staff, shops, and job assignments. It includes:
- Staff authentication and authorization
- Permission-based access control
- Shop management with location tracking
- Job assignment and tracking
- Mobile-responsive design

## ✨ Features

### Staff Panel Features
- **Dashboard**: Overview cards with statistics
- **Shop Management**: Add, view, edit, and delete shops
- **Permission-Based Access**: Staff only see modules they have permission for
- **Mobile Responsive**: Optimized for mobile devices
- **Photo Management**: Upload and manage shop photos
- **Location Tracking**: Integrated map for shop locations

### Admin Panel Features
- **Staff Management**: Create, edit, and manage staff members
- **Permission Management**: Granular permissions for each staff member
- **Shop Management**: View all shops with staff activity tracking
- **Job Assignment**: Assign jobs to staff members with scheduling
- **Activity Tracking**: Monitor staff actions and job progress

## 🛠 Requirements

- PHP >= 8.0
- Laravel >= 9.0
- MySQL >= 5.7
- Composer
- Node.js & NPM (for asset compilation)

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd homeservices-12Mar2026
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homeservices
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. Seed Permissions
```bash
php artisan db:seed --class=StaffPermissionSeeder
```

### 7. Link Storage
```bash
php artisan storage:link
```

### 8. Compile Assets
```bash
npm run dev
```

### 9. Start Development Server
```bash
php artisan serve
```

## 📁 File Structure

```
homeservices-12Mar2026/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/
│   │   │   ├── ShopManagementController.php    # Admin shop management
│   │   │   ├── StaffPermissionController.php    # Staff permissions
│   │   │   └── StaffController.php              # Staff CRUD
│   │   └── Staff/
│   │       ├── ShopController.php               # Staff shop operations
│   │       └── StaffboardController.php         # Staff dashboard
│   ├── Models/
│   │   ├── Staff.php                            # Staff model with permissions
│   │   ├── Shop.php                             # Shop model with relationships
│   │   ├── StaffPermission.php                  # Staff permissions model
│   │   └── StaffJob.php                         # Job assignments model
│   └── Providers/
├── database/
│   ├── migrations/
│   │   ├── 2026_03_31_071439_create_staff_permissions_table.php
│   │   └── 2026_03_31_071538_create_staff_jobs_table.php
│   └── seeders/
│       └── StaffPermissionSeeder.php
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── shop-management/                 # Admin shop management views
│       │   └── staff-permissions/               # Staff permission views
│       └── staff/
│           ├── dashboard.blade.php              # Staff dashboard
│           └── shop/
│               └── index.blade.php              # Shop listing
└── routes/
    └── admin.php                                # Admin and staff routes
```

## 🗄 Database Schema

### Staff Permissions Table
```sql
staff_permissions
├── id (bigint, primary)
├── staff_id (bigint, foreign)
├── module (string, e.g., 'add_shop', 'shop_list')
├── can_view (boolean)
├── can_create (boolean)
├── can_edit (boolean)
├── can_delete (boolean)
└── timestamps
```

### Staff Jobs Table
```sql
staff_jobs
├── id (bigint, primary)
├── shop_id (bigint, foreign)
├── assigned_by (bigint, foreign - admin)
├── assigned_to (bigint, foreign - staff)
├── job_type (string, e.g., 'visit', 'repair')
├── description (text)
├── status (string: 'pending', 'in_progress', 'completed')
├── scheduled_date (date)
├── scheduled_time (time)
├── notes (text)
└── timestamps
```

## 👥 User Roles & Permissions

### Available Modules
1. **add_shop** - Can add new shops
2. **shop_list** - Can view and manage shop list
3. **navigation** - Can use navigation features
4. **visit_history** - Can view visit history

### Permission Types
- **can_view** - View the module
- **can_create** - Create new items
- **can_edit** - Edit existing items
- **can_delete** - Delete items

### Job Types
- **visit** - Site visit
- **repair** - Repair work
- **install** - Installation
- **maintenance** - Maintenance
- **inspection** - Inspection
- **follow_up** - Follow-up visit

## 🌐 API Endpoints

### Staff Routes
```
GET  /staff/dashboard                    - Staff dashboard
GET  /staff/shops                        - Shop listing
GET  /staff/shops/create                 - Create shop form
POST /staff/shops                        - Save shop
GET  /staff/shops/{id}                   - Shop details
PUT  /staff/shops/{id}                   - Update shop
DELETE /staff/shops/{id}                 - Delete shop
```

### Admin Routes
```
GET  /admin/staff-permissions             - Staff permissions list
GET  /admin/staff-permissions/{id}        - View staff permissions
GET  /admin/staff-permissions/{id}/edit   - Edit staff permissions
PUT  /admin/staff-permissions/{id}        - Update permissions

GET  /admin/shop-management               - Shop management
GET  /admin/shop-management/{id}          - Shop details
POST /admin/shop-management/{id}/assign   - Assign job
```

## 🔧 Common Issues & Solutions

### 1. 405 Method Not Allowed Error
**Problem**: When updating permissions, getting 405 error.
**Solution**: Ensure form includes `@method('PUT')` directive:
```blade
<form method="POST" action="{{ route('admin.staff-permissions.update', $staff->id) }}">
    @csrf
    @method('PUT')
```

### 2. Blank Shop List Page
**Problem**: Shop list shows blank page.
**Solution**: 
- Check if staff has `shop_list` permission with `can_view` enabled
- Ensure database has shops or proper "No shops found" message is displayed
- Verify `@section('staff-content')` is used instead of `@section('admin-content')`

### 3. Routes Not Working
**Problem**: Staff routes returning 404.
**Solution**: 
- Clear routes cache: `php artisan route:clear`
- Ensure staff is logged in
- Check route definitions in `routes/admin.php`

### 4. Mobile Responsiveness Issues
**Problem**: Interface not mobile-friendly.
**Solution**: 
- Ensure responsive CSS is included
- Check viewport meta tag: `<meta name="viewport" content="width=device-width, initial-scale=1">`
- Test on different screen sizes

### 5. Permissions Not Working
**Problem**: Staff can access all modules regardless of permissions.
**Solution**:
- Run permission seeder: `php artisan db:seed --class=StaffPermissionSeeder`
- Check permission checks in controllers
- Verify staff has assigned permissions

## 👨‍💻 Development Guidelines

### Adding New Modules
1. Add module to `StaffPermission::$modules` array
2. Update seeder with default permissions
3. Add permission checks in controller
4. Update views with permission-based display
5. Add routes with proper middleware

### Creating New Controllers
```php
<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewController extends Controller
{
    public function index()
    {
        // Check permission
        if (!auth('staff')->user()->hasPermission('module_name', 'can_view')) {
            abort(403, 'You do not have permission to view this module.');
        }
        
        // Your logic here
    }
}
```

### Adding Permission Checks in Views
```blade
@if(auth('staff')->user()->hasPermission('module_name', 'can_create'))
    <button class="btn btn-primary">Create New</button>
@endif
```

### Database Migration Best Practices
```php
Schema::create('table_name', function (Blueprint $table) {
    $table->id();
    $table->foreignId('staff_id')->constrained()->onDelete('cascade');
    $table->string('module');
    $table->boolean('can_view')->default(false);
    $table->boolean('can_create')->default(false);
    $table->boolean('can_edit')->default(false);
    $table->boolean('can_delete')->default(false);
    $table->timestamps();
});
```

## 📱 Mobile Optimization

The system is fully responsive with:
- Touch-friendly buttons (minimum 44px height)
- Optimized layouts for mobile devices
- Swipe gestures for navigation
- Compressed images for faster loading
- Adaptive font sizes

## 🔄 Maintenance

### Regular Tasks
1. Clear caches: `php artisan cache:clear`
2. Optimize database: `php artisan db:optimize`
3. Backup database regularly
4. Update dependencies
5. Monitor logs for errors

### Troubleshooting Commands
```bash
# Clear all caches
php artisan optimize:clear

# Check routes
php artisan route:list

# Check migrations
php artisan migrate:status

# View logs
tail -f storage/logs/laravel.log
```

## 📞 Support

For issues and support:
1. Check this README first
2. Review common issues section
3. Check Laravel logs
4. Verify all installation steps were followed

---

## 🎉 You're Ready!

Your Home Services Staff Management System is now ready to use. Staff can log in, manage shops based on their permissions, and admins can oversee all operations with full control over permissions and job assignments.

**Default URLs:**
- Admin Panel: `http://localhost:8000/admin`
- Staff Panel: `http://localhost:8000/staff`
