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
        // Permission::create(['guard_name' => 'admin-api', 'name' => '添加后台管理员']);
        // Permission::create(['guard_name' => 'admin-api', 'name' => '删除后台管理员']);
        // Permission::create(['guard_name' => 'admin-api', 'name' => '修改后台管理员']);

        // 创建角色并赋权
        $rootRole = Role::create(['guard_name' => 'admin-api', 'name' => 'root']);
        // $rootRole->givePermissionTo('添加后台管理员');
        // $rootRole->givePermissionTo('删除后台管理员');
        $adminRole = Role::create(['guard_name' => 'admin-api', 'name' => 'admin']);
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
