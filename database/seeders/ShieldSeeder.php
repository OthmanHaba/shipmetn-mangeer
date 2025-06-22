<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_chart::of::account","view_any_chart::of::account","create_chart::of::account","update_chart::of::account","restore_chart::of::account","restore_any_chart::of::account","replicate_chart::of::account","reorder_chart::of::account","delete_chart::of::account","delete_any_chart::of::account","force_delete_chart::of::account","force_delete_any_chart::of::account","view_customer","view_any_customer","create_customer","update_customer","restore_customer","restore_any_customer","replicate_customer","reorder_customer","delete_customer","delete_any_customer","force_delete_customer","force_delete_any_customer","view_expense","view_any_expense","create_expense","update_expense","restore_expense","restore_any_expense","replicate_expense","reorder_expense","delete_expense","delete_any_expense","force_delete_expense","force_delete_any_expense","view_invoice","view_any_invoice","create_invoice","update_invoice","restore_invoice","restore_any_invoice","replicate_invoice","reorder_invoice","delete_invoice","delete_any_invoice","force_delete_invoice","force_delete_any_invoice","view_journal::entry","view_any_journal::entry","create_journal::entry","update_journal::entry","restore_journal::entry","restore_any_journal::entry","replicate_journal::entry","reorder_journal::entry","delete_journal::entry","delete_any_journal::entry","force_delete_journal::entry","force_delete_any_journal::entry","view_node","view_any_node","create_node","update_node","restore_node","restore_any_node","replicate_node","reorder_node","delete_node","delete_any_node","force_delete_node","force_delete_any_node","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_shipment","view_any_shipment","create_shipment","update_shipment","restore_shipment","restore_any_shipment","replicate_shipment","reorder_shipment","delete_shipment","delete_any_shipment","force_delete_shipment","force_delete_any_shipment","view_shipment::leg","view_any_shipment::leg","create_shipment::leg","update_shipment::leg","restore_shipment::leg","restore_any_shipment::leg","replicate_shipment::leg","reorder_shipment::leg","delete_shipment::leg","delete_any_shipment::leg","force_delete_shipment::leg","force_delete_any_shipment::leg","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","widget_StatsOverview","widget_ShipmentChart","widget_LatestShipments","widget_FinancialStatsChart"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
