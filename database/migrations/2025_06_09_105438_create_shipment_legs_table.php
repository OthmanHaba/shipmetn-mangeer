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
        Schema::create('shipment_legs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
            $table->integer('sequence_order');
            $table->foreignId('origin_node_id')->constrained('nodes');
            $table->foreignId('destination_node_id')->constrained('nodes');
            $table->enum('status', ['PENDING', 'IN_PROGRESS', 'COMPLETED'])->default('PENDING');
            $table->timestamp('departure_timestamp')->nullable();
            $table->timestamp('arrival_timestamp')->nullable();
            $table->timestamps();

            // Ensure each leg in a shipment has a unique sequence number
            $table->unique(['shipment_id', 'sequence_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_legs');
    }
};
