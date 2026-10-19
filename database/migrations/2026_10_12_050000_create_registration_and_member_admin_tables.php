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
        // 1. Update members table: allow offline members (user_id nullable), add name, nisn, phone
        Schema::table('members', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            if (!Schema::hasColumn('members', 'name')) {
                $table->string('name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('members', 'nisn')) {
                $table->string('nisn')->nullable()->after('name');
            }
            if (!Schema::hasColumn('members', 'phone')) {
                $table->string('phone')->nullable()->after('nisn');
            }
        });

        // 2. Registration Settings
        if (!Schema::hasTable('registration_settings')) {
            Schema::create('registration_settings', function (Blueprint $table) {
                $table->id();
                $table->boolean('is_account_registration_open')->default(true);
                $table->dateTime('account_registration_start')->nullable();
                $table->dateTime('account_registration_end')->nullable();
                $table->boolean('is_member_registration_open')->default(true);
                $table->dateTime('member_registration_start')->nullable();
                $table->dateTime('member_registration_end')->nullable();
                $table->string('announcement_title')->nullable();
                $table->text('announcement_description')->nullable();
                $table->text('requirements')->nullable();
                $table->foreignId('updated_by_user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
        }

        // 3. Registration Setting Logs
        if (!Schema::hasTable('registration_setting_logs')) {
            Schema::create('registration_setting_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('action');
                $table->text('details')->nullable();
                $table->timestamps();
            });
        }

        // 4. Member Applications (Pengajuan Penerimaan Anggota Baru)
        if (!Schema::hasTable('member_applications')) {
            Schema::create('member_applications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->string('name');
                $table->string('nisn')->nullable();
                $table->string('kelas');
                $table->string('jurusan')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->text('reason')->nullable();
                $table->boolean('data_consent')->default(true);
                $table->string('validation_status')->default('PERLU_PEMERIKSAAN'); // VALID_AUTOMATIS, PERLU_PEMERIKSAAN, TIDAK_DITEMUKAN
                $table->text('validation_notes')->nullable();
                $table->string('status')->default('MENUNGGU_PERSETUJUAN'); // MENUNGGU_PERSETUJUAN, PERLU_PEMERIKSAAN, DISETUJUI, DITOLAK
                $table->text('rejection_reason')->nullable();
                $table->foreignId('reviewed_by_user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_applications');
        Schema::dropIfExists('registration_setting_logs');
        Schema::dropIfExists('registration_settings');

        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['name', 'nisn', 'phone']);
        });
    }
};
