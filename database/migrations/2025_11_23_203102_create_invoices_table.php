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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->string('client_name');
            $table->text('client_address')->nullable();
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->json('items');
            $table->string('currency', 3)->default('GEL');
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('draft');
            $table->string('language', 2)->default('en');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
