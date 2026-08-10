<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\StaffPermission;

class ShopManagementPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all staff members
        $staffMembers = Staff::all();
        
        foreach ($staffMembers as $staff) {
            // Check if staff already has shop_management permissions
            $existingPermission = StaffPermission::where('staff_id', $staff->id)
                ->where('module', 'shop_management')
                ->first();
            
            if (!$existingPermission) {
                // Create shop_management permissions for each staff member
                StaffPermission::create([
                    'staff_id' => $staff->id,
                    'module' => 'shop_management',
                    'can_view' => true, // Most staff can view jobs
                    'can_create' => false, // Only specific staff can create
                    'can_edit' => true, // Most staff can edit job status
                    'can_delete' => false, // Only specific staff can delete
                ]);
            }
        }
        
        $this->command->info('Shop management permissions seeded successfully!');
    }
}
