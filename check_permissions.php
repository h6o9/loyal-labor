<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Job Delete Permissions in Local Database ===\n\n";

// Check all job delete related permissions
$permissions = \Spatie\Permission\Models\Permission::where('name', 'like', '%job%delete%')
    ->orWhere('name', 'like', '%delete%job%')
    ->get();

echo "Found " . $permissions->count() . " job delete permissions:\n\n";

foreach ($permissions as $permission) {
    echo "ID: {$permission->id}\n";
    echo "Name: {$permission->name}\n";
    echo "Guard: {$permission->guard_name}\n";
    echo "Group: {$permission->group_name}\n";
    echo "Created: {$permission->created_at}\n";
    echo "Updated: {$permission->updated_at}\n";
    echo "---\n";
}

// Also check who has these permissions
echo "\n=== User Assignments ===\n\n";

foreach ($permissions as $permission) {
    echo "Permission: {$permission->name}\n";
    
    $userAssignments = \Spatie\Permission\Models\ModelHasPermissions::where('permission_id', $permission->id)
        ->where('model_type', 'App\\Models\\Admin')
        ->with('model')
        ->get();
    
    foreach ($userAssignments as $assignment) {
        if ($assignment->model) {
            echo "  - User: {$assignment->model->name} ({$assignment->model->email})\n";
        }
    }
    echo "---\n";
}

echo "\n=== Role Assignments ===\n\n";

foreach ($permissions as $permission) {
    echo "Permission: {$permission->name}\n";
    
    $roleAssignments = \Spatie\Permission\Models\RoleHasPermissions::where('permission_id', $permission->id)
        ->with('role')
        ->get();
    
    foreach ($roleAssignments as $assignment) {
        if ($assignment->role) {
            echo "  - Role: {$assignment->role->name}\n";
        }
    }
    echo "---\n";
}
