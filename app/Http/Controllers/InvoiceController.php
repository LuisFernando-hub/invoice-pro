<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = auth()->user()->invoices()->latest()->get();
        return inertia('Invoices/Index', [
            'invoices' => $invoices
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->canCreateInvoice()) {
            return redirect()->route('dashboard')->with('error', 'Trial period expired. Please subscribe to create more invoices.');
        }
        return inertia('Invoices/Create');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Invoice $invoice)
    {
        if ($invoice->user_id !== auth()->id()) {
            abort(403);
        }

        if ($invoice->due_date && Carbon::parse($invoice->due_date)->isPast()) {
            return redirect()->back()->with('error', 'Cannot delete invoice with past due date.');
        }

        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->user()->canCreateInvoice()) {
            return redirect()->route('dashboard')->with('error', 'Trial period expired. Please subscribe to create more invoices.');
        }

        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_address' => 'nullable|string',
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'currency' => 'required|string|size:3',
            'language' => 'required|string|size:2',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $total = collect($request->items)->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });

        $request->user()->invoices()->create([
            ...$validated,
            'total_amount' => $total,
            'status' => 'draft',
        ]);

        return redirect()->route('invoices.index');
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Invoice $invoice)
    {
        if ($invoice->user_id !== auth()->id()) {
            abort(403);
        }
        return inertia('Invoices/Show', [
            'invoice' => $invoice
        ]);
    }

    public function duplicate(\App\Models\Invoice $invoice)
    {
        if ($invoice->user_id !== auth()->id()) {
            abort(403);
        }

        if (!auth()->user()->canCreateInvoice()) {
            return redirect()->route('dashboard')->with('error', 'Trial period expired. Please subscribe to create more invoices.');
        }

        $newInvoice = $invoice->replicate();
        $newInvoice->invoice_number = $invoice->invoice_number . '-COPY-' . time();
        $newInvoice->status = 'draft';
        $newInvoice->created_at = now();
        $newInvoice->updated_at = now();
        $newInvoice->save();

        return redirect()->route('invoices.index')->with('success', 'Invoice duplicated successfully.');
    }

    public function download(\App\Models\Invoice $invoice, string $format)
    {
        if ($invoice->user_id !== auth()->id()) {
            abort(403);
        }

        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', compact('invoice'));
            return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
        }

        if ($format === 'word') {
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();
            
            // Header
            $section->addText('INVOICE #' . $invoice->invoice_number, ['bold' => true, 'size' => 24]);
            $section->addText('Date: ' . $invoice->issue_date);
            $section->addTextBreak(1);

            // Client Info
            $section->addText('Bill To:', ['bold' => true]);
            $section->addText($invoice->client_name);
            if ($invoice->client_address) {
                $section->addText($invoice->client_address);
            }
            $section->addTextBreak(1);

            // Items Table
            $table = $section->addTable();
            $table->addRow();
            $table->addCell(4000)->addText('Description', ['bold' => true]);
            $table->addCell(2000)->addText('Quantity', ['bold' => true]);
            $table->addCell(2000)->addText('Price', ['bold' => true]);
            $table->addCell(2000)->addText('Total', ['bold' => true]);

            foreach ($invoice->items as $item) {
                $table->addRow();
                $table->addCell(4000)->addText($item['description']);
                $table->addCell(2000)->addText($item['quantity']);
                $table->addCell(2000)->addText($item['price']);
                $table->addCell(2000)->addText($item['quantity'] * $item['price']);
            }

            $section->addTextBreak(1);
            $section->addText('Total Amount: ' . $invoice->total_amount . ' ' . $invoice->currency, ['bold' => true, 'size' => 14]);

            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            
            $fileName = 'invoice-' . $invoice->invoice_number . '.docx';
            $tempFile = tempnam(sys_get_temp_dir(), $fileName);
            $objWriter->save($tempFile);

            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
        }

        abort(404);
    }
}
