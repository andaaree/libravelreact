<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
        ]);
        $super = User::create([
            'name' => 'super',
            'email' => 'super@example.com',
            'username' => 'super',
            'email_verified_at' => now(),
            'password' => Hash::make('super123'),
        ]);

        // create permissions
        Permission::create(['name' => 'manage books']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'process loans']);

        // create roles and assign created permissions
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $adminRole = Role::create(['name' => 'admin']);
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdminRole->givePermissionTo(Permission::all());
        $adminRole->givePermissionTo(['manage books', 'process loans']);

        $super->assignRole($superAdminRole);
        $admin->assignRole($adminRole);

    }
}
