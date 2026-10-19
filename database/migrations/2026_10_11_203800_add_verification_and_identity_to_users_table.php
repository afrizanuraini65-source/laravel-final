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
            $table->string('birth_place')->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('birth_place');
            $table->string('kelas')->nullable()->after('birth_date');
            $table->enum('status_verifikasi', ['PENDING', 'APPROVED', 'REJECTED'])->default('APPROVED')->after('role');
            $table->text('rejection_reason')->nullable()->after('status_verifikasi');
            $table->string('email_verification_code', 10)->nullable()->after('rejection_reason');
            $table->timestamp('email_verification_expires_at')->nullable()->after('email_verification_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'birth_place',
                'birth_date',
                'kelas',
                'status_verifikasi',
                'rejection_reason',
                'email_verification_code',
                'email_verification_expires_at',
            ]);
        });
    }
};
