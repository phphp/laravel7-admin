<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 创建可用的权限
        Permission::create(['guard_name' => 'admin-api', 'name' => 'admins.store']);
        Permission::create(['guard_name' => 'admin-api', 'name' => 'admins.update']);
        Permission::create(['guard_name' => 'admin-api', 'name' => 'admins.destroy']);
        Permission::create(['guard_name' => 'admin-api', 'name' => 'roles.store']);
        Permission::create(['guard_name' => 'admin-api', 'name' => 'roles.update']);
        Permission::create(['guard_name' => 'admin-api', 'name' => 'roles.destroy']);
        Permission::create(['guard_name' => 'admin-api', 'name' => 'permissions.store']);
        Permission::create(['guard_name' => 'admin-api', 'name' => 'permissions.update']);
        Permission::create(['guard_name' => 'admin-api', 'name' => 'permissions.destroy']);

        // 创建角色并赋权
        $rootRole = Role::create(['guard_name' => 'admin-api', 'name' => 'root']);

        $adminRole = Role::create(['guard_name' => 'admin-api', 'name' => 'admin']);
        $adminRole->givePermissionTo('permissions.store');
        $adminRole->givePermissionTo('permissions.update');
        $adminRole->givePermissionTo('permissions.destroy');

        $visitorRole = Role::create(['guard_name' => 'admin-api', 'name' => 'visitor']);

        // 创建管理员
        $root = Admin::create([
            'name' => 'root',
            'email' => 'root@your.email',
            'password' => bcrypt('secret'),
        ]);
        $root->assignRole($rootRole);

        $admin = Admin::create([
            'name' => 'admin',
            'email' => 'admin@your.email',
            'password' => bcrypt('secret'),
        ]);
        $admin->assignRole($adminRole);

        $visitor = Admin::create([
            'name' => 'visitor',
            'email' => 'visitor@your.email',
            'password' => bcrypt('secret'),
        ]);
        $visitor->assignRole($visitorRole);

    }
}
