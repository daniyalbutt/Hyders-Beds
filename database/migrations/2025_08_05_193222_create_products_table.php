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
            $table->string('product_code')->nullable();
            $table->text('description')->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('volume', 10, 2)->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->string('product_range')->nullable();
            $table->string('product_section')->nullable();
            $table->string('production_type')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
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
