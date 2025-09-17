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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0)->after('status');
            $table->decimal('vat', 10, 2)->default(0)->after('subtotal');
            $table->decimal('deposit_total', 10, 2)->default(0)->after('vat');
            $table->decimal('grand_total', 10, 2)->default(0)->after('deposit_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'vat', 'deposit_total', 'grand_total']);
        });
    }
};
