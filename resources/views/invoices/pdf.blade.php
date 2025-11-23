<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; font-size: 14px; margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
        
        /* Header */
        .header-table { margin-bottom: 40px; }
        .logo-cell { width: 60px; padding-right: 15px; }
        .logo { width: 60px; height: 60px; background-color: #B45309; color: white; border-radius: 50%; text-align: center; line-height: 60px; font-size: 30px; font-weight: bold; }
        .company-info-cell { }
        .company-name { color: #B45309; font-weight: bold; font-size: 18px; margin-bottom: 5px; }
        .invoice-details-cell { text-align: right; }
        .invoice-title { color: #B45309; font-size: 32px; text-transform: uppercase; margin-bottom: 5px; }
        .invoice-number { font-weight: bold; font-size: 16px; margin-bottom: 15px; }
        .balance-due-label { font-size: 12px; color: #666; }
        .balance-due-amount { font-size: 20px; font-weight: bold; }

        /* Addresses */
        .addresses-table { margin-bottom: 40px; }
        .bill-to-cell { width: 50%; }
        .dates-cell { width: 50%; text-align: right; }
        .section-title { color: #666; font-size: 12px; margin-bottom: 5px; }
        .client-name { color: #B45309; font-weight: bold; font-size: 16px; margin-bottom: 5px; }
        .date-row { margin-bottom: 5px; }
        .date-label { color: #666; margin-right: 10px; }
        .date-value { font-weight: bold; }

        /* Items */
        .items-table { margin-bottom: 30px; }
        .items-table th { background-color: #B45309; color: white; padding: 10px; text-align: left; font-weight: normal; }
        .items-table td { padding: 10px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }

        /* Footer */
        .footer-table { }
        .notes-cell { width: 60%; padding-right: 20px; }
        .totals-cell { width: 40%; }
        .note-title { font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        .note-text { color: #666; font-size: 12px; margin-bottom: 15px; }
        
        .total-row-table { width: 100%; margin-bottom: 5px; }
        .total-label { color: #666; }
        .total-value { text-align: right; }
        .grand-total-row td { font-weight: bold; font-size: 16px; padding-top: 10px; padding-bottom: 10px; }
        .balance-due-box { background-color: #B45309; color: white; padding: 10px; font-weight: bold; font-size: 16px; }
    </style>
</head>
<body>
    <!-- Header -->
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <div class="logo">Z</div>
            </td>
            <td class="company-info-cell">
                <div class="company-name">Zylker Design Labs</div>
                <div>14B, Northern Street<br>Greater South Avenue<br>New York 10001<br>U.S.A</div>
            </td>
            <td class="invoice-details-cell">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number"># {{ $invoice->invoice_number }}</div>
                <div class="balance-due-label">Balance Due</div>
                <div class="balance-due-amount">{{ $invoice->currency }} {{ $invoice->total_amount }}</div>
            </td>
        </tr>
    </table>

    <!-- Addresses -->
    <table class="addresses-table">
        <tr>
            <td class="bill-to-cell">
                <div class="section-title">Bill To</div>
                <div class="client-name">{{ $invoice->client_name }}</div>
                <div>{!! nl2br(e($invoice->client_address)) !!}</div>
            </td>
            <td class="dates-cell">
                <div class="date-row">
                    <span class="date-label">Invoice Date:</span>
                    <span class="date-value">{{ $invoice->issue_date }}</span>
                </div>
                <div class="date-row">
                    <span class="date-label">Terms:</span>
                    <span class="date-value">Due on Receipt</span>
                </div>
                @if($invoice->due_date)
                <div class="date-row">
                    <span class="date-label">Due Date:</span>
                    <span class="date-value">{{ $invoice->due_date }}</span>
                </div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 45%">Item & Description</th>
                <th class="text-right" style="width: 15%">Qty</th>
                <th class="text-right" style="width: 15%">Rate</th>
                <th class="text-right" style="width: 20%">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <div style="font-weight: bold;">{{ $item['description'] }}</div>
                </td>
                <td class="text-right">{{ number_format($item['quantity'], 2) }}</td>
                <td class="text-right">{{ number_format($item['price'], 2) }}</td>
                <td class="text-right">{{ number_format($item['quantity'] * $item['price'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <table class="footer-table">
        <tr>
            <td class="notes-cell">
                <div class="note-title">Notes</div>
                <div class="note-text">Thanks for your business.</div>
                
                <div class="note-title">Terms & Conditions</div>
                <div class="note-text">All payments must be made in full before the commencement of any design work.</div>
            </td>
            <td class="totals-cell">
                <table class="total-row-table">
                    <tr>
                        <td class="total-label">Sub Total</td>
                        <td class="total-value">{{ $invoice->total_amount }}</td>
                    </tr>
                    <tr>
                        <td class="total-label">Tax Rate</td>
                        <td class="total-value">0.00%</td>
                    </tr>
                    <tr class="grand-total-row">
                        <td style="color: #333;">Total</td>
                        <td class="total-value">{{ $invoice->currency }} {{ $invoice->total_amount }}</td>
                    </tr>
                </table>
                <div class="balance-due-box">
                    <table style="width: 100%">
                        <tr>
                            <td style="text-align: left; color: white;">Balance Due</td>
                            <td style="text-align: right; color: white;">{{ $invoice->currency }} {{ $invoice->total_amount }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
