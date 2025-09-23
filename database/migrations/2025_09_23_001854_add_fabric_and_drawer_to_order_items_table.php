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
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('fabric_name')->nullable();
            $table->decimal('fabric_price', 10, 2)->default(0);
            $table->string('drawer_name')->nullable();
            $table->decimal('drawer_price', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['fabric_name', 'fabric_price', 'drawer_name', 'drawer_price']);
        });
    }
};
