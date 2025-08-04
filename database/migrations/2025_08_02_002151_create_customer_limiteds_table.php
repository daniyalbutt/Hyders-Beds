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
        Schema::create('customer_limiteds', function (Blueprint $table) {
            $table->id();
            $table->string('director_official_name')->nullable();
            $table->string('position')->nullable();
            $table->string('telepcontact_name_for_paymenthone')->nullable();
            $table->string('account_phone_no')->nullable();
            $table->string('account_email')->nullable();
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
        Schema::dropIfExists('customer_limiteds');
    }
};
