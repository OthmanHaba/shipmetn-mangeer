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
            $table->string('invoice_number', 50)->unique();
            $table->foreignId('shipment_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->date('issue_date');
            $table->date('due_date');
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', ['DRAFT', 'SENT', 'PAID', 'VOID'])->default('DRAFT');
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
