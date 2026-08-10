<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== SHOPS TABLE STRUCTURE ===\n";
$columns = DB::select('DESCRIBE shops');
foreach ($columns as $col) {
    echo "Column: {$col->Field}, Type: {$col->Type}, Null: {$col->Null}, Default: {$col->Default}\n";
}

echo "\n=== CHECK IF district_id EXISTS ===\n";
$districtColumn = DB::select("
    SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'shops' 
    AND COLUMN_NAME = 'district_id'
");

if (empty($districtColumn)) {
    echo "❌ district_id COLUMN DOES NOT EXIST in shops table\n";
} else {
    echo "✅ district_id COLUMN EXISTS:\n";
    foreach ($districtColumn as $col) {
        echo "  - Column: {$col->COLUMN_NAME}, Type: {$col->DATA_TYPE}, Null: {$col->IS_NULLABLE}\n";
    }
}

echo "\n=== SAMPLE SHOPS DATA ===\n";
$shops = DB::table('shops')->limit(3)->get();
foreach ($shops as $shop) {
    echo "Shop ID: {$shop->id}, Name: " . ($shop->name ?? $shop->shop_name ?? 'N/A') . "\n";
    if (isset($shop->district_id)) {
        echo "  District ID: {$shop->district_id}\n";
    } else {
        echo "  District ID: NOT SET\n";
    }
}

echo "\n=== DISTRICTS TABLE CHECK ===\n";
try {
    $districts = DB::select('SELECT COUNT(*) as count FROM districts');
    echo "Districts table exists with {$districts[0]->count} records\n";
    
    $districtColumns = DB::select('DESCRIBE districts');
    echo "Districts table columns:\n";
    foreach ($districtColumns as $col) {
        echo "  - {$col->Field}: {$col->Type}\n";
    }
} catch (Exception $e) {
    echo "❌ Districts table error: " . $e->getMessage() . "\n";
}

echo "\n=== QUICK FIX SUGGESTION ===\n";
if (empty($districtColumn)) {
    echo "RUN THIS SQL TO ADD district_id COLUMN:\n";
    echo "ALTER TABLE shops ADD COLUMN district_id BIGINT UNSIGNED NULL AFTER phone_number;\n";
    echo "\nTHEN ADD FOREIGN KEY:\n";
    echo "ALTER TABLE shops ADD CONSTRAINT shops_district_id_foreign FOREIGN KEY (district_id) REFERENCES districts(id) ON DELETE SET NULL;\n";
} else {
    echo "✅ district_id column exists - check your Laravel model relationship\n";
}
