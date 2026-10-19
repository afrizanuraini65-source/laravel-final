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
        // 1. Kategori Tugas PDD
        Schema::create('pdd_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('leader_name');
            $table->text('description')->nullable();
            $table->string('icon')->default('camera');
            $table->string('color_theme')->default('purple');
            $table->integer('order_num')->default(0);
            $table->timestamps();
        });

        // 2. Anggota per Kategori PDD
        Schema::create('pdd_category_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pdd_category_id')->constrained('pdd_categories')->onDelete('cascade');
            $table->string('member_name');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('role', ['PJ', 'ANGGOTA'])->default('ANGGOTA');
            $table->timestamps();
        });

        // 3. Tugas PDD (Task Management)
        Schema::create('pdd_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('pdd_category_id')->constrained('pdd_categories')->onDelete('cascade');
            $table->string('event_name')->nullable();
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->string('assigned_pj')->nullable();
            $table->text('assigned_members')->nullable(); // JSON or comma-separated names
            $table->date('assigned_date')->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status', ['Belum Dikerjakan', 'Sedang Dikerjakan', 'Selesai'])->default('Belum Dikerjakan');
            $table->integer('progress')->default(0);
            $table->string('result_link')->nullable(); // Canva or G-Drive or URL
            $table->string('result_file')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // 4. Catatan & Riwayat Status Tugas
        Schema::create('pdd_task_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pdd_task_id')->constrained('pdd_tasks')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status_from')->nullable();
            $table->string('status_to');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pdd_task_histories');
        Schema::dropIfExists('pdd_tasks');
        Schema::dropIfExists('pdd_category_members');
        Schema::dropIfExists('pdd_categories');
    }
};
