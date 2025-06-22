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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('shipment_leg_id')->nullable()->constrained()->nullOnDelete();
            $table->string('vendor_name');
            $table->date('expense_date');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->foreignId('account_id')->constrained('chart_of_accounts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
