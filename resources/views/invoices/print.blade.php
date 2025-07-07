<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('general.invoice.title') }} #{{ $invoice->invoice_number }}</title>
    <style>
        @media print {
            body {
                margin: 0;
            }

            .no-print {
                display: none !important;
            }

            @page {
                margin: 0.5cm;
            }
        }

        @font-face {
            font-family: 'Cairo';
            src: url('{{ asset('fonts/Cairo/static/Cairo-Regular.ttf') }}') format('truetype');
        }

        body {
            font-family: 'Cairo', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 210mm; /* A4 width */
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-sizing: border-box;
        }

        @media screen {
            body {
                /* Shadow effect to show A4 page on screen */
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                margin: 20px auto;
                min-height: 297mm; /* A4 height */
            }
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            margin: 0;
            color: #1f2937;
            font-size: 24px;
        }

        .invoice-number {
            color: #6b7280;
            font-size: 13px;
            margin-top: 5px;
        }

        .parties {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 30px;
        }

        .party h3 {
            color: #1f2937;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .party p {
            margin: 4px 0;
            color: #4b5563;
            font-size: 13px;
        }

        .invoice-details {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .detail-item {
            text-align: center;
        }

        .detail-label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            background-color: #f3f4f6;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            font-size: 13px;
        }

        .items-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .text-right {
            text-align: right;
        }

        .totals {
            margin-left: auto;
            width: 300px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .total-row.grand-total {
            border-bottom: none;
            border-top: 2px solid #1f2937;
            margin-top: 10px;
            padding-top: 12px;
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #2563eb;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .print-button:hover {
            background-color: #1d4ed8;
        }

        /* RTL support */
        [dir="rtl"] .header {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .invoice-title {
            text-align: left;
        }

        [dir="rtl"] .items-table th,
        [dir="rtl"] .items-table td {
            text-align: right;
        }

        [dir="rtl"] .text-right {
            text-align: left;
        }

        [dir="rtl"] .totals {
            margin-left: 0;
            margin-right: auto;
        }

        [dir="rtl"] .print-button {
            right: auto;
            left: 20px;
        }

        /* A4 page setup */
        @page {
            size: A4;
            margin: 15mm; /* Consistent margins all around */
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                width: 100%;
                max-width: 100%;
                min-height: auto;
                box-shadow: none;
            }

            .no-print {
                display: none !important;
            }

            .page-break {
                page-break-after: always;
            }

            /* Adjust content spacing for print */
            .header {
                margin-bottom: 20px;
                padding-bottom: 15px;
            }

            .parties {
                margin-bottom: 20px;
            }

            .invoice-details {
                margin-bottom: 20px;
                padding: 15px;
            }

            .items-table {
                margin-bottom: 20px;
            }

            .footer {
                margin-top: 30px;
            }
        }
    </style>
</head>
<body>
<button class="print-button no-print" onclick="window.print()">
    {{ __('general.invoice.print_invoice') }}
</button>

<div class="header">
    <div class="logo">
        {{--             <img src="{{ asset('applogo.png') }}" alt="logo" height="100" width="150">--}}
    </div>
    <div class="invoice-title">
        <h1>{{ __('general.invoice.title') }}</h1>
        <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
    </div>
</div>

<div class="parties">
    <div class="party">
        <h3>{{ __('general.shipment.shipper') }}</h3>
        <p><strong>{{ $invoice->shipment->shipper->customer_name }}</strong></p>
        @if($invoice->shipment->shipper->email)
            <p>{{ $invoice->shipment->shipper->email }}</p>
        @endif
        @if($invoice->shipment->shipper->phone)
            <p>{{ $invoice->shipment->shipper->phone }}</p>
        @endif
        @if($invoice->shipment->shipper->address)
            <p>{{ $invoice->shipment->shipper->address }}</p>
        @endif
        @if($invoice->shipment->shipper->city || $invoice->shipment->shipper->country)
            <p>{{ $invoice->shipment->shipper->city }}{{ $invoice->shipment->shipper->city && $invoice->shipment->shipper->country ? ', ' : '' }}{{ $invoice->shipment->shipper->country }}</p>
        @endif
    </div>

    <div class="party">
        <h3>{{ __('general.shipment.consignee') }}</h3>
        <p><strong>{{ $invoice->shipment->consignee->customer_name }}</strong></p>
        @if($invoice->shipment->consignee->email)
            <p>{{ $invoice->shipment->consignee->email }}</p>
        @endif
        @if($invoice->shipment->consignee->phone)
            <p>{{ $invoice->shipment->consignee->phone }}</p>
        @endif
        @if($invoice->shipment->consignee->address)
            <p>{{ $invoice->shipment->consignee->address }}</p>
        @endif
        @if($invoice->shipment->consignee->city || $invoice->shipment->consignee->country)
            <p>{{ $invoice->shipment->consignee->city }}{{ $invoice->shipment->consignee->city && $invoice->shipment->consignee->country ? ', ' : '' }}{{ $invoice->shipment->consignee->country }}</p>
        @endif
    </div>
</div>

<div class="invoice-details">
    <div class="detail-item">
        <div class="detail-label">{{ __('general.invoice.issue_date') }}</div>
        <div class="detail-value">{{ $invoice->issue_date->format('Y-m-d') }}</div>
    </div>
    <div class="detail-item">
        <div class="detail-label">{{ __('general.invoice.due_date') }}</div>
        <div class="detail-value">{{ $invoice->due_date->format('Y-m-d') }}</div>
    </div>
    <div class="detail-item">
        <div class="detail-label">{{ __('general.shipment.reference_id') }}</div>
        <div class="detail-value">{{ $invoice->shipment->reference_id }}</div>
    </div>
    <div class="detail-item">
        <div class="detail-label">{{ __('general.invoice.status') }}</div>
        <div class="detail-value">
            @switch($invoice->status)
                @case('DRAFT')
                    {{ __('general.invoice.statuses.draft') }}
                    @break
                @case('SENT')
                    {{ __('general.invoice.statuses.sent') }}
                    @break
                @case('PAID')
                    {{ __('general.invoice.statuses.paid') }}
                    @break
                @case('VOID')
                    {{ __('general.invoice.statuses.void') }}
                    @break
            @endswitch
        </div>
    </div>
</div>

@if($invoice->shipment->items->count() > 0)
    <table class="items-table">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ __('general.shipment_item.weight') }}</th>
            <th>{{ __('general.shipment_item.height') }} x {{ __('general.shipment_item.width') }}
                x {{ __('general.shipment_item.length') }}</th>
            <th>{{ __('general.shipment_item.package_count') }}</th>
            <th>{{ __('general.shipment_item.price_per_cubic_meter') }}</th>
            <th class="text-right">{{ __('general.shipment_item.total_price') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoice->shipment->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->weight }} kg</td>
                <td>{{ $item->height }} x {{ $item->width }} x {{ $item->length }} m</td>
                <td>{{ $item->package_count }}</td>
                <td>LYD {{ number_format($item->price_per_cubic_meter, 2) }}</td>
                <td class="text-right">LYD {{ number_format($item->total_price, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

<div class="totals">
    <div class="total-row grand-total">
        <span>{{ __('general.invoice.total_amount') }}</span>
        <span>LYD {{ number_format($invoice->total_amount, 2) }}</span>
    </div>
</div>

<div class="footer">
    <p>{{ __('general.invoice.thank_you') }}</p>
</div>

<script>
    // Auto print on load if requested
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('auto_print') === 'true') {
        window.onload = function () {
            window.print();
        }
    }
</script>
</body>
</html>
