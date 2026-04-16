<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('address_id')->constrained('addresses');
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('set null');
            $table->enum('status', ['packing', 'on_way', 'delivered'])->default('packing');
            $table->timestamp('ordered_at')->useCurrent();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->softDeletes();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};