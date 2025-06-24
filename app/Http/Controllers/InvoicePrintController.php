<?php

namespace App\Http\Controllers;

use App\Models\Invoice;

class InvoicePrintController extends Controller
{
    public function print(Invoice $invoice)
    {
        // Load relationships
        $invoice->load([
            'shipment',
            'customer',
            'shipment.items',
            'shipment.shipper',
            'shipment.consignee',
        ]);

        return view('invoices.print', compact('invoice'));
    }
}
