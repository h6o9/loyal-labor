<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check all tables first
echo "=== ALL TABLES ===\n";
$tables = DB::select('SHOW TABLES');
foreach ($tables as $table) {
    foreach ($table as $tableName) {
        echo "- $tableName\n";
    }
}

echo "\n=== ADMIN USERS ===\n";
$admins = DB::table('admins')->get();
foreach ($admins as $admin) {
    echo "ID: {$admin->id}, Email: {$admin->email}, Name: {$admin->name}\n";
}

echo "\n=== PERMISSIONS TABLE STRUCTURE ===\n";
$permColumns = DB::select('DESCRIBE permissions');
foreach ($permColumns as $col) {
    echo "Column: {$col->Field}, Type: {$col->Type}\n";
}

echo "\n=== ALL AVAILABLE PERMISSIONS ===\n";
$allPerms = DB::table('permissions')->get();
foreach ($allPerms as $perm) {
    $name = $perm->name ?? 'N/A';
    echo "Name: $name\n";
}

echo "\n=== ADMIN PERMISSIONS (using model_has_permissions) ===\n";
// Check permissions for first admin (change ID as needed)
$adminId = 1;
$permissions = DB::table('model_has_permissions')
    ->join('permissions', 'model_has_permissions.permission_id', '=', 'permissions.id')
    ->where('model_has_permissions.model_type', 'App\\Models\\Admin')
    ->where('model_has_permissions.model_id', $adminId)
    ->select('permissions.name as permission_name')
    ->get();

foreach ($permissions as $perm) {
    echo "Permission: {$perm->permission_name}\n";
}

echo "\n=== DISTRICT SPECIFIC PERMISSIONS ===\n";
$districtPerms = DB::table('model_has_permissions')
    ->join('permissions', 'model_has_permissions.permission_id', '=', 'permissions.id')
    ->where('model_has_permissions.model_type', 'App\\Models\\Admin')
    ->where('model_has_permissions.model_id', $adminId)
    ->where('permissions.name', 'like', '%district%')
    ->select('permissions.name as permission_name')
    ->get();

if ($districtPerms->isEmpty()) {
    echo "NO DISTRICT PERMISSIONS FOUND FOR ADMIN ID $adminId\n";
} else {
    foreach ($districtPerms as $perm) {
        echo "District Permission: {$perm->permission_name}\n";
    }
}

echo "\n=== CHECK IF DISTRICT.VIEW EXISTS ===\n";
$districtView = DB::table('permissions')
    ->where('name', 'like', '%district%')
    ->get();

if ($districtView->isEmpty()) {
    echo "NO DISTRICT PERMISSIONS FOUND IN SYSTEM\n";
} else {
    foreach ($districtView as $perm) {
        echo "Found: {$perm->name}\n";
    }
}
