<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use App\Models\Expense;
use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get expense accounts
        $transportationAccount = ChartOfAccount::where('account_name', 'Transportation Expenses')->first();
        $storageAccount = ChartOfAccount::where('account_name', 'Storage Expenses')->first();
        $fuelAccount = ChartOfAccount::where('account_name', 'Fuel Expenses')->first();
        $customsAccount = ChartOfAccount::where('account_name', 'Customs & Duties')->first();

        // Create expenses for each shipment
        $shipments = Shipment::all();

        foreach ($shipments as $shipment) {
            // Create common expenses for the shipment
            Expense::create([
                'shipment_id' => $shipment->id,
                'shipment_leg_id' => null,
                'vendor_name' => 'Global Transport Services',
                'expense_date' => Carbon::now()->subDays(mt_rand(1, 10)),
                'amount' => mt_rand(800, 2000),
                'description' => 'Transport fee for '.$shipment->reference_id,
                'account_id' => $transportationAccount->id,
            ]);

            Expense::create([
                'shipment_id' => $shipment->id,
                'shipment_leg_id' => null,
                'vendor_name' => 'Customs Authority',
                'expense_date' => Carbon::now()->subDays(mt_rand(1, 10)),
                'amount' => mt_rand(300, 800),
                'description' => 'Customs clearance for '.$shipment->reference_id,
                'account_id' => $customsAccount->id,
            ]);

            // Create expenses for each shipment leg
            $legs = $shipment->legs;

            foreach ($legs as $leg) {
                if ($leg->fromNode && $leg->toNode) {
                    if ($leg->fromNode->node_type == 'WAREHOUSE' || $leg->toNode->node_type == 'WAREHOUSE') {
                        // Storage expense for warehouse legs
                        Expense::create([
                            'shipment_id' => $shipment->id,
                            'shipment_leg_id' => $leg->id,
                            'vendor_name' => $leg->fromNode->node_type == 'WAREHOUSE'
                                ? $leg->fromNode->node_name
                                : $leg->toNode->node_name,
                            'expense_date' => Carbon::now()->subDays(mt_rand(1, 5)),
                            'amount' => mt_rand(200, 600),
                            'description' => 'Storage fee at '.($leg->fromNode->node_type == 'WAREHOUSE'
                                ? $leg->fromNode->node_name
                                : $leg->toNode->node_name),
                            'account_id' => $storageAccount->id,
                        ]);
                    }

                    // Fuel expense for all transportation
                    if ($shipment->shipping_mode == 'SEA' || $shipment->shipping_mode == 'LAND') {
                        Expense::create([
                            'shipment_id' => $shipment->id,
                            'shipment_leg_id' => $leg->id,
                            'vendor_name' => 'Fuel Supplier Inc.',
                            'expense_date' => Carbon::now()->subDays(mt_rand(1, 5)),
                            'amount' => mt_rand(400, 1200),
                            'description' => 'Fuel cost for transportation from '.
                                $leg->fromNode->node_name.' to '.$leg->toNode->node_name,
                            'account_id' => $fuelAccount->id,
                        ]);
                    }
                }
            }
        }
    }
}
