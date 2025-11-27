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
        Schema::create('repair_invoices', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('device_id')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('device_type')->nullable();
            $table->date('date')->nullable();

            $table->enum('status', ['received', 'in_progress', 'ready', 'delivered'])
                ->default('ready');
            $table->decimal('service_fee', 10, 2)->default(0);
            $table->decimal('total_parts_price', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);

            $table->enum('payment_status', ['cash', 'credit'])->default('cash');
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('remaining_amount', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_invoices');
    }
};
