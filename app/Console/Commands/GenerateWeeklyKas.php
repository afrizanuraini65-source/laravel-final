<?php

namespace App\Console\Commands;

use App\Models\Due;
use App\Models\OrganizationPeriod;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateWeeklyKas extends Command
{
    protected $signature = 'kas:generate-weekly
                            {--weeks=4 : Jumlah minggu ke belakang yang akan diperiksa (default 4)}
                            {--dry-run : Tampilkan tagihan yang akan dibuat tanpa menyimpan}';

    protected $description = 'Generate tagihan kas mingguan Rp 2.000 setiap Rabu. Aman dijalankan berulang - tidak membuat duplikat.';

    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        $weeksBack = (int) $this->option('weeks');

        $this->info($isDryRun ? '[DRY RUN] Simulasi generate tagihan kas mingguan...' : 'Generating tagihan kas mingguan...');

        $period = OrganizationPeriod::where('is_active', true)->first()
            ?? OrganizationPeriod::first();

        if (! $period) {
            $this->error('Tidak ada OrganizationPeriod aktif. Buat terlebih dahulu.');
            return self::FAILURE;
        }

        // Cari semua Rabu dari (weeksBack) minggu lalu hingga Rabu mendatang
        $today = Carbon::today();
        $startDate = $today->copy()->subWeeks($weeksBack)->startOfWeek()->next(Carbon::WEDNESDAY);

        // Normalkan ke Rabu pertama yang cocok
        $currentWednesday = Carbon::today()->startOfWeek()->next(Carbon::WEDNESDAY);
        if ($currentWednesday->isFuture()) {
            $currentWednesday = $currentWednesday->subWeek();
        }

        $wednesdays = [];
        $cursor = $startDate->copy();
        while ($cursor->lte($currentWednesday)) {
            $wednesdays[] = $cursor->toDateString();
            $cursor->addWeek();
        }

        if (empty($wednesdays)) {
            $this->warn('Tidak ada tanggal Rabu yang perlu diproses.');
            return self::SUCCESS;
        }

        $this->line('Tanggal Rabu yang akan diproses: ' . implode(', ', $wednesdays));

        $created = 0;
        $skipped = 0;

        foreach ($wednesdays as $wednesday) {
            $weekLabel = Carbon::parse($wednesday)->translatedFormat('d M Y');

            // Cek apakah sudah ada tagihan untuk minggu ini
            $existing = Due::where('weekly_period', $wednesday)->first();

            if ($existing) {
                $this->line("  [SKIP] Tagihan minggu {$weekLabel} sudah ada: {$existing->title}");
                $skipped++;
                continue;
            }

            $title = 'Kas Mingguan ' . Carbon::parse($wednesday)->format('d/m/Y');

            if ($isDryRun) {
                $this->info("  [AKAN DIBUAT] {$title} - Rp 2.000 (jatuh tempo: {$weekLabel})");
            } else {
                Due::create([
                    'organization_period_id' => $period->id,
                    'title'                  => $title,
                    'amount'                 => 2000,
                    'type'                   => 'MINGGUAN',
                    'weekly_period'          => $wednesday,
                    'deadline'               => $wednesday,
                    'target_type'            => 'SEMUA',
                    'target_division_id'     => null,
                    'description'            => 'Kas rutin mingguan PIK-R SKAKMATT sebesar Rp 2.000 per anggota, dibayarkan setiap Rabu.',
                ]);
                $this->info("  [DIBUAT] {$title} - Rp 2.000");
            }
            $created++;
        }

        $this->newLine();
        if ($isDryRun) {
            $this->info("Dry run selesai: {$created} tagihan akan dibuat, {$skipped} dilewati (sudah ada).");
        } else {
            $this->info("Selesai: {$created} tagihan baru dibuat, {$skipped} dilewati (sudah ada).");
        }

        return self::SUCCESS;
    }
}