<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE dues MODIFY COLUMN type ENUM('BULANAN','KEGIATAN','KHUSUS','MINGGUAN') NOT NULL DEFAULT 'BULANAN'");
    }

    public function down(): void
    {
        DB::table('dues')->where('type', 'MINGGUAN')->update(['type' => 'BULANAN']);
        DB::statement("ALTER TABLE dues MODIFY COLUMN type ENUM('BULANAN','KEGIATAN','KHUSUS') NOT NULL DEFAULT 'BULANAN'");
    }
};