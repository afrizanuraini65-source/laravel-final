<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('category')->nullable()->after('description');
            $table->string('docs_link')->nullable()->after('banner_image');
            $table->string('status', 50)->default('Akan Datang')->change();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->dropColumn('docs_link');
            $table->string('status')->default('OPEN')->change(); // Removed ENUM as it's not well supported in SQLite fallback without native ENUM support, string is safer.
        });
    }
};
