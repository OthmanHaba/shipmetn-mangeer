<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class JournalEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the accountant user
        $accountant = User::first();

        if (! $accountant) {
            $accountant = User::first(); // Fallback to any user if accountant doesn't exist
        }

        // Get accounts
        $accountsReceivable = ChartOfAccount::where('account_name', 'Accounts Receivable')->first();
        $seaRevenue = ChartOfAccount::where('account_name', 'Sea Shipping Revenue')->first();
        $airRevenue = ChartOfAccount::where('account_name', 'Air Shipping Revenue')->first();
        $landRevenue = ChartOfAccount::where('account_name', 'Land Shipping Revenue')->first();
        $cash = ChartOfAccount::where('account_name', 'Cash')->first();

        // Create journal entries for invoices
        $invoices = Invoice::all();

        foreach ($invoices as $invoice) {
            // Create a journal entry for this invoice
            $journalEntry = JournalEntry::create([
                'entry_date' => $invoice->issue_date,
                'description' => 'Invoice #'.$invoice->invoice_number.' for Shipment #'.$invoice->shipment->reference_id,
                'reference_type' => 'INVOICE',
                'reference_id' => $invoice->id,
                'shipment_id' => $invoice->shipment_id,
                'created_by_user_id' => $accountant->id,
            ]);

            // Determine which revenue account to use based on shipping mode
            $revenueAccount = match ($invoice->shipment->shipping_mode) {
                'SEA' => $seaRevenue->id,
                'AIR' => $airRevenue->id,
                'LAND' => $landRevenue->id,
                default => $seaRevenue->id,
            };

            // Create debit line (accounts receivable)
            JournalLine::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $accountsReceivable->id,
                'entry_type' => 'DEBIT',
                'amount' => $invoice->total_amount,
            ]);

            // Create credit line (revenue)
            JournalLine::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $revenueAccount,
                'entry_type' => 'CREDIT',
                'amount' => $invoice->total_amount,
            ]);

            // For paid invoices, create payment entries
            if ($invoice->status === 'PAID') {
                // Create a payment journal entry
                $paymentEntry = JournalEntry::create([
                    'entry_date' => Carbon::parse($invoice->issue_date)->addDays(mt_rand(1, 15)), // Payment sometime after invoice
                    'description' => 'Payment received for Invoice #'.$invoice->invoice_number,
                    'reference_type' => 'PAYMENT',
                    'reference_id' => $invoice->id,
                    'shipment_id' => $invoice->shipment_id,
                    'created_by_user_id' => $accountant->id,
                ]);

                // Create debit line (cash)
                JournalLine::create([
                    'journal_entry_id' => $paymentEntry->id,
                    'account_id' => $cash->id,
                    'entry_type' => 'DEBIT',
                    'amount' => $invoice->total_amount,
                ]);

                // Create credit line (accounts receivable)
                JournalLine::create([
                    'journal_entry_id' => $paymentEntry->id,
                    'account_id' => $accountsReceivable->id,
                    'entry_type' => 'CREDIT',
                    'amount' => $invoice->total_amount,
                ]);
            }
        }
    }
}
