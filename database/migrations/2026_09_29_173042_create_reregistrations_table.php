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
        Schema::create('reregistrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->string('kelas')->nullable();
            $table->string('jurusan')->nullable();
            $table->boolean('status_keaktifan')->default(true); // true = Ya, false = Tidak
            $table->text('alasan_tidak_melanjutkan')->nullable();
            $table->string('status_verifikasi')->default('Menunggu Verifikasi'); // Menunggu Verifikasi, Terverifikasi, Tidak Melanjutkan
            $table->timestamp('tanggal_daftar_ulang')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reregistrations');
    }
};
