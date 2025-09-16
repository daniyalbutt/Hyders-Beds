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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer')->nullable();
            $table->foreign('customer')->references('id')->on('customers')->onDelete('cascade');
            $table->string('address')->nullable();
            $table->date('order_date')->nullable();
            $table->string('order_reference')->nullable();
            $table->string('order_type')->nullable();
            $table->date('required_date')->nullable();
            $table->unsignedBigInteger('salesperson_one')->nullable();
            $table->foreign('salesperson_one')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('salesperson_two')->nullable();
            $table->foreign('salesperson_two')->references('id')->on('users')->onDelete('cascade');
            $table->string('customer_contact')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
