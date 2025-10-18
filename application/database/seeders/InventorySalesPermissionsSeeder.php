<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySalesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Update existing roles with default permissions for inventory and sales
        $roles = DB::table('roles')->get();
        
        foreach ($roles as $role) {
            $updates = [];
            
            // Set default permissions based on role type
            switch ($role->role_id) {
                case 1: // Admin role
                    $updates['role_inventory'] = 3; // Full access
                    $updates['role_sales'] = 3; // Full access
                    break;
                case 2: // Client role
                    $updates['role_inventory'] = 1; // View only
                    $updates['role_sales'] = 1; // View only
                    break;
                default: // Other roles
                    $updates['role_inventory'] = 0; // No access
                    $updates['role_sales'] = 0; // No access
                    break;
            }
            
            // Update the role with new permissions
            DB::table('roles')
                ->where('role_id', $role->role_id)
                ->update($updates);
        }
        
        $this->command->info('Inventory and Sales permissions have been set for all roles.');
    }
}
