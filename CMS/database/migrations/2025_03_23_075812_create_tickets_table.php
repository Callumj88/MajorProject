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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicleID')->constrained('vehicles');
            $table->string('ticketTitle', 255)->nullable(false);
            $table->text('description')->nullable(false);
            $table->text('notes')->nullable();  
            $table->string('status', 50)->nullable(false);  // e.g., open, in-progress, closed
            $table->decimal('quotedPrice', 10, 2)->nullable();
            $table->text('partsRequired')->nullable();
            $table->decimal('workHours', 5, 2)->nullable();
            $table->text('createdBy');  
            $table->foreignId('assignedUserID')->nullable()->constrained('users');
            $table->foreignId('customerID')->nullable()->constrained('customers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
