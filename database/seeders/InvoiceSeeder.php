<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create invoices for the existing shipments
        $shipments = Shipment::all();

        foreach ($shipments as $index => $shipment) {
            // Generate a random invoice amount based on shipping mode
            $baseAmount = match ($shipment->shipping_mode) {
                'SEA' => 5000,
                'AIR' => 8500,
                'LAND' => 3000,
                default => 2500,
            };

            // Add some random variance
            $amount = $baseAmount + mt_rand(-500, 1500);

            // Create invoice
            Invoice::create([
                'invoice_number' => 'INV-'.date('Y').'-'.str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'shipment_id' => $shipment->id,
                'customer_id' => $shipment->shipper_customer_id, // Bill the shipper
                'issue_date' => Carbon::now()->subDays(mt_rand(1, 10)),
                'due_date' => Carbon::now()->addDays(30),
                'total_amount' => $amount,
                'status' => $shipment->status === 'DELIVERED' ? 'PAID' : 'SENT',
            ]);
        }
    }
}
