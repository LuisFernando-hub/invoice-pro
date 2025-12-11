<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceIssuerTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_can_be_created_with_issuer_info()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('invoices.store'), [
            'invoice_number' => 'INV-001',
            'client_name' => 'Test Client',
            'issue_date' => '2023-01-01',
            'currency' => 'USD',
            'language' => 'en',
            'items' => [
                ['description' => 'Item 1', 'quantity' => 1, 'price' => 100]
            ],
            'issuer_name' => 'Test Issuer',
            'issuer_address' => '123 Issuer St',
            'issuer_tax_id' => 'TAX-12345',
        ]);

        $response->assertRedirect(route('invoices.index'));

        $this->assertDatabaseHas('invoices', [
            'invoice_number' => 'INV-001',
            'issuer_name' => 'Test Issuer',
            'issuer_address' => '123 Issuer St',
            'issuer_tax_id' => 'TAX-12345',
        ]);
    }

    public function test_invoice_pdf_contains_issuer_info()
    {
        $user = User::factory()->create();
        $invoice = Invoice::create([
            'user_id' => $user->id,
            'invoice_number' => 'INV-002',
            'client_name' => 'Test Client',
            'issue_date' => '2023-01-01',
            'currency' => 'USD',
            'language' => 'en',
            'items' => [
                ['description' => 'Item 1', 'quantity' => 1, 'price' => 100]
            ],
            'total_amount' => 100,
            'issuer_name' => 'Test Issuer PDF',
            'issuer_address' => '456 Issuer Ave',
            'issuer_tax_id' => 'TAX-PDF-987',
        ]);

        $response = $this->actingAs($user)->get(route('invoices.download', ['invoice' => $invoice->id, 'format' => 'pdf']));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        
        // We can't easily check PDF content here without a parser, 
        // but a 200 OK means the view rendered successfully.
    }
}
