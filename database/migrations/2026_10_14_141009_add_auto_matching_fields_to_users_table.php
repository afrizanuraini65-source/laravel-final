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
        Schema::table('users', function (Blueprint $table) {
            $table->string('match_status')->default('PENDING')->after('status_verifikasi');
            $table->string('matched_name')->nullable()->after('match_status');
            $table->string('matched_kelas')->nullable()->after('matched_name');
            $table->string('matched_nisn')->nullable()->after('matched_kelas');
            $table->string('membership_status')->nullable()->after('matched_nisn');
            $table->text('match_notes')->nullable()->after('membership_status');
            $table->foreignId('matched_application_id')->nullable()->after('match_notes')->constrained('member_applications')->nullOnDelete();
            $table->foreignId('matched_master_id')->nullable()->after('matched_application_id')->constrained('master_members')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['matched_application_id']);
            $table->dropForeign(['matched_master_id']);
            $table->dropColumn([
                'match_status',
                'matched_name',
                'matched_kelas',
                'matched_nisn',
                'membership_status',
                'match_notes',
                'matched_application_id',
                'matched_master_id',
            ]);
        });
    }
};
