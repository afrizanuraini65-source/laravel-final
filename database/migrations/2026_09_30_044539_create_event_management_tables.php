<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_committees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('event_committee_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_committee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role')->default('Anggota'); // e.g. Koordinator, Anggota, Ketua Pelaksana
            $table->timestamps();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('event_committee_id')->nullable()->constrained('event_committees')->cascadeOnDelete();
        });
        
        // Add event phase tracking
        Schema::table('events', function (Blueprint $table) {
            $table->string('phase')->default('DRAFT')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('phase');
        });
        
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropForeign(['event_committee_id']);
            $table->dropColumn(['event_id', 'event_committee_id']);
        });

        Schema::dropIfExists('event_committee_members');
        Schema::dropIfExists('event_committees');
    }
};
