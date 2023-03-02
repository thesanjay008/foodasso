<?php


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $permissions = [
            // DEVELOPER 
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'permission-list',
            'permission-create',
            'permission-delete',
            
			// SUPER ADMIN 
            'user-list',
            'user-create',
            'user-delete',
            'country-list',
            'country-create',
            'country-update',
            'cms-list',
            'cms-create',
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            'restaurant-list',
            'restaurant-create',
            'restaurant-edit',
            'restaurant-delete',

            // VENDOR
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
        ];
        foreach ($permissions as $permission) {
          Permission::firstOrCreate(['name' => $permission]);
        }
    }
}