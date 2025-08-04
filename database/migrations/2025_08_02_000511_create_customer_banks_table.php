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
        Schema::create('customer_banks', function (Blueprint $table) {
            $table->id();
            $table->string('bank')->nullable();
            $table->string('bank_address')->nullable();
            $table->string('name_of_account')->nullable();
            $table->string('account_number')->nullable();
            $table->string('sort_code')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_banks');
    }
};
