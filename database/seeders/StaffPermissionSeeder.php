<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\StaffPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaffPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all active staff members
        $staffMembers = Staff::where('is_active', true)->get();

        // Available modules
        $modules = [
            'shop_management' => 'Shop Management',
            'navigation' => 'Navigation',
            'visit_history' => 'Visit History',
        ];

        foreach ($staffMembers as $staff) {
            // Give all staff members basic permissions
            foreach ($modules as $moduleKey => $moduleName) {
                StaffPermission::updateOrCreate(
                    [
                        'staff_id' => $staff->id,
                        'module' => $moduleKey,
                    ],
                    [
                        'can_view' => true, // Give view permissions to all
                        'can_create' => in_array($moduleKey, ['shop_management']), // Only some can create
                        'can_edit' => in_array($moduleKey, ['shop_management']), // Only some can edit
                        'can_delete' => false, // No one can delete by default
                    ]
                );
            }
        }

        $this->command->info('Staff permissions seeded successfully!');
    }
}
