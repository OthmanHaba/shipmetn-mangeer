<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create parent accounts first
        $assetAccount = ChartOfAccount::create([
            'account_number' => '1000',
            'account_name' => 'Assets',
            'account_type' => 'ASSET',
            'parent_account_id' => null,
        ]);

        $liabilityAccount = ChartOfAccount::create([
            'account_number' => '2000',
            'account_name' => 'Liabilities',
            'account_type' => 'LIABILITY',
            'parent_account_id' => null,
        ]);

        $equityAccount = ChartOfAccount::create([
            'account_number' => '3000',
            'account_name' => 'Equity',
            'account_type' => 'EQUITY',
            'parent_account_id' => null,
        ]);

        $revenueAccount = ChartOfAccount::create([
            'account_number' => '4000',
            'account_name' => 'Revenue',
            'account_type' => 'REVENUE',
            'parent_account_id' => null,
        ]);

        $expenseAccount = ChartOfAccount::create([
            'account_number' => '5000',
            'account_name' => 'Expenses',
            'account_type' => 'EXPENSE',
            'parent_account_id' => null,
        ]);

        // Create sub-accounts
        // Asset sub-accounts
        $accounts = [
            // Asset sub-accounts
            [
                'account_number' => '1100',
                'account_name' => 'Cash',
                'account_type' => 'ASSET',
                'parent_account_id' => $assetAccount->id,
            ],
            [
                'account_number' => '1200',
                'account_name' => 'Accounts Receivable',
                'account_type' => 'ASSET',
                'parent_account_id' => $assetAccount->id,
            ],
            [
                'account_number' => '1300',
                'account_name' => 'Equipment',
                'account_type' => 'ASSET',
                'parent_account_id' => $assetAccount->id,
            ],

            // Liability sub-accounts
            [
                'account_number' => '2100',
                'account_name' => 'Accounts Payable',
                'account_type' => 'LIABILITY',
                'parent_account_id' => $liabilityAccount->id,
            ],
            [
                'account_number' => '2200',
                'account_name' => 'Loans Payable',
                'account_type' => 'LIABILITY',
                'parent_account_id' => $liabilityAccount->id,
            ],

            // Equity sub-accounts
            [
                'account_number' => '3100',
                'account_name' => 'Capital',
                'account_type' => 'EQUITY',
                'parent_account_id' => $equityAccount->id,
            ],
            [
                'account_number' => '3200',
                'account_name' => 'Retained Earnings',
                'account_type' => 'EQUITY',
                'parent_account_id' => $equityAccount->id,
            ],

            // Revenue sub-accounts
            [
                'account_number' => '4100',
                'account_name' => 'Sea Shipping Revenue',
                'account_type' => 'REVENUE',
                'parent_account_id' => $revenueAccount->id,
            ],
            [
                'account_number' => '4200',
                'account_name' => 'Air Shipping Revenue',
                'account_type' => 'REVENUE',
                'parent_account_id' => $revenueAccount->id,
            ],
            [
                'account_number' => '4300',
                'account_name' => 'Land Shipping Revenue',
                'account_type' => 'REVENUE',
                'parent_account_id' => $revenueAccount->id,
            ],

            // Expense sub-accounts
            [
                'account_number' => '5100',
                'account_name' => 'Transportation Expenses',
                'account_type' => 'EXPENSE',
                'parent_account_id' => $expenseAccount->id,
            ],
            [
                'account_number' => '5200',
                'account_name' => 'Storage Expenses',
                'account_type' => 'EXPENSE',
                'parent_account_id' => $expenseAccount->id,
            ],
            [
                'account_number' => '5300',
                'account_name' => 'Salary Expenses',
                'account_type' => 'EXPENSE',
                'parent_account_id' => $expenseAccount->id,
            ],
            [
                'account_number' => '5400',
                'account_name' => 'Fuel Expenses',
                'account_type' => 'EXPENSE',
                'parent_account_id' => $expenseAccount->id,
            ],
            [
                'account_number' => '5500',
                'account_name' => 'Customs & Duties',
                'account_type' => 'EXPENSE',
                'parent_account_id' => $expenseAccount->id,
            ],
        ];

        foreach ($accounts as $account) {
            ChartOfAccount::create($account);
        }
    }
}
