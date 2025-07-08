<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->enum('discount_type', ['amount', 'percent', 'none'])->default('none')->change();
            $table->decimal('value', 8, 2)->default(0)->change();
            $table->decimal('min_order_value', 8, 2)->default(0)->change();
            $table->decimal('max_discount_value', 8, 2)->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            // Nếu cần rollback, bạn có thể đổi lại về như cũ
            $table->enum('discount_type', ['amount', 'percent'])->default('amount')->change();
            // Và sửa lại các default nếu cần
        });
    }
};
