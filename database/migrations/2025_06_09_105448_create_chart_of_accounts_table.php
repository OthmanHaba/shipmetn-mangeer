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
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_number', 20)->unique();
            $table->string('account_name');
            $table->enum('account_type', ['ASSET', 'LIABILITY', 'EQUITY', 'REVENUE', 'EXPENSE']);
            $table->foreignId('parent_account_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};
