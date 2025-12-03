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
            $table->string('payment_method')->default('cod')->after('total_amount'); // cod, card, gcash
            $table->decimal('shipping_fee', 10, 2)->default(0)->after('payment_method');
            $table->decimal('discount', 10, 2)->default(0)->after('shipping_fee');
            $table->text('notes')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'shipping_fee', 'discount', 'notes']);
        });
    }
};
