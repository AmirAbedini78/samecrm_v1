<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GuaranteeLettersPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if columns exist before updating
        if (!Schema::hasColumn('roles', 'role_guarantee_letters')) {
            $this->command->warn('role_guarantee_letters column does not exist. Please run migrations first.');
            return;
        }

        // Update existing roles with default permissions for guarantee letters
        $roles = DB::table('roles')->get();
        
        foreach ($roles as $role) {
            $updates = [];
            
            // Set default permissions based on role type
            switch ($role->role_id) {
                case 1: // Admin role
                    $updates['role_guarantee_letters'] = 3; // Full access
                    $updates['role_guarantee_letters_scope'] = 'global'; // Global scope
                    break;
                case 2: // Client role
                    $updates['role_guarantee_letters'] = 0; // No access for clients
                    $updates['role_guarantee_letters_scope'] = 'own';
                    break;
                default: // Other roles
                    $updates['role_guarantee_letters'] = 0; // No access by default
                    $updates['role_guarantee_letters_scope'] = 'own';
                    break;
            }
            
            // Update the role with new permissions
            DB::table('roles')
                ->where('role_id', $role->role_id)
                ->update($updates);
        }
        
        $this->command->info('Guarantee Letters permissions have been set for all roles.');
    }
}

