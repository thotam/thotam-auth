<?php

namespace Thotam\ThotamAuth\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Auth_Role_Permission_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Permission::where("name", "view-user")->count() == 0) {
            $permission[] = Permission::create(['name' => 'view-user', "description" => "Xem Người dùng", "group" => "User", "order" => 1, "lock" => true,]);
        } else {
            $permission[] = Permission::where("name", "view-user")->first();
        }

        if (Permission::where("name", "add-user")->count() == 0) {
            $permission[] = Permission::create(['name' => 'add-user', "description" => "Thêm Người dùng", "group" => "User", "order" => 2, "lock" => true,]);
        } else {
            $permission[] = Permission::where("name", "add-user")->first();
        }

        if (Permission::where("name", "edit-user")->count() == 0) {
            $permission[] = Permission::create(['name' => 'edit-user', "description" => "Sửa Người dùng", "group" => "User", "order" => 3, "lock" => true,]);
        } else {
            $permission[] = Permission::where("name", "edit-user")->first();
        }

        if (Permission::where("name", "link-user")->count() == 0) {
            $permission[] = Permission::create(['name' => 'link-user', "description" => "Link Người dùng", "group" => "User", "order" => 4, "lock" => true,]);
        } else {
            $permission[] = Permission::where("name", "link-user")->first();
        }

        if (Permission::where("name", "delete-user")->count() == 0) {
            $permission[] = Permission::create(['name' => 'delete-user', "description" => "Xóa Người dùng", "group" => "User", "order" => 5, "lock" => true,]);
        } else {
            $permission[] = Permission::where("name", "delete-user")->first();
        }

        if (Role::where("name", "super-admin")->count() == 0) {
            $super_admin =  Role::create(['name' => 'super-admin', "description" => "Super Admin", "group" => "Admin", "order" => 1, "lock" => true,]);
        } else {
            $super_admin= Role::where("name", "super-admin")->first();
        }

        if (Role::where("name", "admin")->count() == 0) {
            $admin = Role::create(['name' => 'admin', "description" => "Admin", "group" => "Admin", "order" => 2, "lock" => true,]);
        } else {
            $admin = Role::where("name", "admin")->first();
        }

        if (Role::where("name", "admin-user")->count() == 0) {
            $admin_user = Role::create(['name' => 'admin-user', "description" => "Admin User", "group" => "Admin", "order" => 3, "lock" => true,]);
        } else {
            $admin_user = Role::where("name", "admin-user")->first();
        }

        $super_admin->givePermissionTo($permission);
        $admin->givePermissionTo($permission);
        $admin_user->givePermissionTo($permission);
    }
}
