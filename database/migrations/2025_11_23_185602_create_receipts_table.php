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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();

            $table->enum('receipt_type', ['in', 'out']);  // قبض / صرف

            $table->unsignedBigInteger('accountable_id');
            $table->string('accountable_type');

            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();

            $table->foreignId('cash_box_id')->constrained();
            $table->foreignId('created_by')->constrained('users');

            $table->timestamps();

            $table->index(['accountable_id', 'accountable_type']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
