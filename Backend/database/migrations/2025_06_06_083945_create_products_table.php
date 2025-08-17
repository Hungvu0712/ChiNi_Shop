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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->string('name')->unique();
            $table->decimal('price', 8, 2);
            $table->longText('description')->nullable();
            $table->string('product_image')->nullable();
            $table->string('public_product_image_id')->nullable();
            $table->string('weight')->nullable(); // trọng lượng sản phẩm
            $table->integer('quantity')->default(0);
            $table->integer('quantity_warning')->default(0);
            $table->string('tags')->nullable();
            $table->string('sku');
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
