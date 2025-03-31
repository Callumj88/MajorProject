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
            $table->foreignId('ticketID')->constrained('tickets');
            $table->dateTime('invoiceDate')->nullable();
            $table->text('invoiceDetails')->nullable();  // Breakdown of costs
            $table->decimal('totalPrice', 10, 2)->nullable();
            $table->string('paymentStatus', 50)->nullable();  // e.g., unpaid, paid, pending
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
