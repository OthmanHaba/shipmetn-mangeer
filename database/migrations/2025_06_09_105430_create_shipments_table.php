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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id', 50)->unique();
            $table->enum('shipping_mode', ['SEA', 'AIR', 'LAND']);
            $table->enum('status', ['PENDING', 'IN_TRANSIT', 'AT_WAREHOUSE', 'DELIVERED', 'CANCELLED'])
                ->default('PENDING');
            $table->foreignId('shipper_customer_id')->constrained('customers');
            $table->foreignId('consignee_customer_id')->constrained('customers');
            $table->timestamp('estimated_departure')->nullable();
            $table->timestamp('estimated_arrival')->nullable();
            $table->timestamp('actual_departure')->nullable();
            $table->timestamp('actual_arrival')->nullable();
            $table->decimal('shipment_price', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
