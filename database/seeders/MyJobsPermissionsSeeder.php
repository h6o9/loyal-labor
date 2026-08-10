<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\StaffPermission;

class MyJobsPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all staff members
        $staffMembers = Staff::all();
        
        foreach ($staffMembers as $staff) {
            // Create or update my_jobs permissions for each staff member
            StaffPermission::updateOrCreate([
                'staff_id' => $staff->id,
                'module' => 'my_jobs'
            ], [
                'can_view' => true, // Staff can view their jobs
                'can_create' => false, // Cannot create jobs
                'can_edit' => true, // Can edit job status (mark done/undone)
                'can_delete' => false, // Cannot delete jobs
            ]);
        }
        
        $this->command->info('My Jobs permissions seeded successfully!');
    }
}
