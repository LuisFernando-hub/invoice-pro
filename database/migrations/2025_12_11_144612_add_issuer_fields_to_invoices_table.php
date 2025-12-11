<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('issuer_name')->nullable()->after('invoice_number');
            $table->text('issuer_address')->nullable()->after('issuer_name');
            $table->string('issuer_tax_id')->nullable()->after('issuer_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['issuer_name', 'issuer_address', 'issuer_tax_id']);
        });
    }
};
