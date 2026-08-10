<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SystemRecordsPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'bookings.view',
            'bookings.delete',
            'bookings.settings.view',
            'bookings.settings.edit',
            'subscriptions.view',
            'subscriptions.create',
            'subscriptions.edit',
            'subscriptions.delete',
            'service.categories.view',
            'service.categories.create',
            'service.categories.edit',
            'service.categories.delete',
            'app.notifications.view',
            'app.notifications.send',
            'complaints.view',
            'complaints.delete',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'admin'],
                ['group_name' => 'System Records']
            );
        }

        // Super Admin should always have every permission, so it doesn't lose
        // access to System Records after this permission set is introduced.
        $superAdminRole = Role::where('name', 'Super Admin')->where('guard_name', 'admin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permissions);
        }

        $superAdmin = \App\Models\Admin::where('is_super_admin', 1)->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }

        app('cache')->forget(config('permission.cache.key'));

        $this->command->info('System Records permissions seeded successfully.');
    }
}
