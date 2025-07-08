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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->enum('voucher_type', ['discount', 'freeship'])->default('discount');
            $table->enum('discount_type', ['amount', 'percent', 'none'])->default('none');
            $table->decimal('value', 8, 2)->default(0);
            $table->decimal('min_order_value', 8, 2)->default(0);
            $table->decimal('max_discount_value', 8, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('limit')->default(1);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
