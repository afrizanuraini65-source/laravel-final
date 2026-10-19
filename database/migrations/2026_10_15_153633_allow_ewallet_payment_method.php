<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_method')->default('CASH')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('payments')->where('payment_method', 'EWALLET')->update([
            'payment_method' => 'TRANSFER',
        ]);

        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_method', ['TRANSFER', 'QRIS', 'CASH'])->default('CASH')->change();
        });
    }
};
