<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name' => 'Logistics Manager',
                'email' => 'logistics@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name' => 'Accountant User',
                'email' => 'accountant@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name' => 'Customer Service',
                'email' => 'service@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

    }

    public function seedRoles()
    {
        $adminRole = Role::create([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);
        $logisticsManagerRole = Role::create([
            'name' => 'Logistics Manager',
            'guard_name' => 'web',
        ]);
        $accountantRole = Role::create([
            'name' => 'Accountant',
            'guard_name' => 'web',
        ]);
        $customerServiceRole = Role::create([
            'name' => 'Customer Service',
            'guard_name' => 'web',
        ]);

        $admin = User::where('email', 'admin@admin.com')->first();

        $admin->assignRole('super_admin');

        // $permissions = [
        //     'create-shipment',
        //     'edit-shipment',
        //     'delete-shipment',
        //     'view-shipment',
        //     'create-shipment-leg',
        //     'edit-shipment-leg',
        //     'delete-shipment-leg',
        //     'view-shipment-leg',
        //     'create-invoice',
        //     'edit-invoice',
        //     'delete-invoice',
        //     'view-invoice',
        //     'create-expense',
        //     'edit-expense',
        //     'delete-expense',
        //     'view-expense',
        //     'create-journal-entry',
        //     'edit-journal-entry',
        //     'delete-journal-entry',
        //     'view-journal-entry',
        //     'create-customer',
        //     'edit-customer',
        //     'delete-customer',
        //     'view-customer',
        //     'create-node',
        //     'edit-node',
        //     'delete-node',
        //     'view-node',
        //     'create-chart-of-account',
        //     'edit-chart-of-account',
        //     'delete-chart-of-account',
        //     'view-chart-of-account',
        //     'create-user',
        //     'edit-user',
        //     'delete-user',
        //     'view-user',
        //     'create-role',
        //     'edit-role',
        //     'delete-role',
        //     'view-role',
        //     'create-permission',
        //     'edit-permission',
        //     'delete-permission',
        //     'view-permission',
        // ];

        // $adminRole->givePermissionTo($permissions);
        // $logisticsManagerRole->givePermissionTo($permissions);
        // $accountantRole->givePermissionTo($permissions);
        // $customerServiceRole->givePermissionTo($permissions);
    }
}
