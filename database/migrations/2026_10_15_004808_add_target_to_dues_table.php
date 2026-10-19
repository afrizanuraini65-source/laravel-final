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
        Schema::table('dues', function (Blueprint $table) {
            $table->string('target_type')->default('SEMUA')->after('type');
            $table->foreignId('target_division_id')->nullable()->after('target_type')->constrained('divisions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dues', function (Blueprint $table) {
            $table->dropForeign(['target_division_id']);
            $table->dropColumn(['target_type', 'target_division_id']);
        });
    }
};
