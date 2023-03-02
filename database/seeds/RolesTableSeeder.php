<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::firstOrCreate([
		            'name' => 'developer',
		            'guard_name' => 'web'
		        ]);
       	$permissions1 = Permission::whereIn('name',['role-list','role-create','role-edit','role-delete','permission-list','permission-create','permission-delete','developer-dashboard'])->get();
       	$role1->syncPermissions($permissions1);
        
        $role2 = Role::firstOrCreate([
		            'name' => 'superAdmin',
		            'guard_name' => 'web'
		        ]);
        $permissions2 = Permission::whereIn('name',[
            'user-list',
            'user-create',
            'user-delete',
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            'restaurant-list',
            'restaurant-create',
            'restaurant-edit',
            'restaurant-delete',
			'res_coupon-list',
            'res_coupon-create',
            'res_coupon-edit',
            'res_coupon-delete',
            'res_menu_category-list',
            'res_menu_category-create',
            'res_menu_category-edit',
            'res_menu_category-delete',
            'res_order-list',
            'res_order-create',
            'res_order-edit',
            'res_order-delete',
            'res_menu-list',
            'res_menu-create',
            'res_menu-edit',
            'res_menu-delete',
            'vendor_restaurant-edit',
            'vendor-dashboard',
            'variation-list',
            'variation-create',
            'variation-edit',
            'variation-delete',
            'addon_group-list',
            'addon_group-create',
            'addon_group-edit',
            'addon_group-delete',
            'addon-list',
            'addon-create',
            'addon-edit',
            'addon-delete',
        ])->get();
       	$role2->syncPermissions($permissions2);

        $role3 = Role::firstOrCreate([
                'name' => 'Outlet',
                'guard_name' => 'web'
            ]);
        $permissions3 = Permission::whereIn('name',[
			'vendor-dashboard',
            'res_coupon-list',
            'res_coupon-create',
            'res_coupon-edit',
            'res_coupon-delete',
            'res_menu_category-list',
            'res_menu_category-create',
            'res_menu_category-edit',
            'res_menu_category-delete',
            'res_order-list',
            'res_order-create',
            'res_order-edit',
            'res_order-delete',
            'res_menu-list',
            'res_menu-create',
            'res_menu-edit',
            'res_menu-delete',
            'vendor_restaurant-edit',
            'variation-list',
            'variation-create',
            'variation-edit',
            'variation-delete',
            'addon_group-list',
            'addon_group-create',
            'addon_group-edit',
            'addon_group-delete',
            'addon-list',
            'addon-create',
            'addon-edit',
            'addon-delete',
		])->get();
        $role3->syncPermissions($permissions3);
    }
}
