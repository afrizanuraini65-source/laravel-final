<?php

namespace App\Console\Commands;

use App\Models\Due;
use App\Models\OrganizationPeriod;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

#[Signature('app:generate-weekly-dues {--from= : First Wednesday in Y-m-d format} {--through= : Last Wednesday in Y-m-d format}')]
#[Description('Create idempotent Rp2,000 weekly dues for Wednesdays')]
class GenerateWeeklyDues extends Command
{
    public function handle(): int
    {
        $fromOption = $this->option('from');
        $throughOption = $this->option('through');

        if (($fromOption && ! $throughOption) || (! $fromOption && $throughOption)) {
            $this->error('Gunakan --from dan --through secara bersamaan.');

            return self::FAILURE;
        }

        $dateValidation = Validator::make([
            'from' => $fromOption,
            'through' => $throughOption,
        ], [
            'from' => ['nullable', 'date_format:Y-m-d'],
            'through' => ['nullable', 'date_format:Y-m-d'],
        ]);

        if ($dateValidation->fails()) {
            $this->error('Tanggal harus menggunakan format Y-m-d yang valid.');

            return self::FAILURE;
        }

        $firstWednesday = $fromOption
            ? Carbon::createFromFormat('!Y-m-d', $fromOption, 'Asia/Jakarta')
            : Carbon::now('Asia/Jakarta')->next(Carbon::WEDNESDAY)->startOfDay();
        $lastWednesday = $throughOption
            ? Carbon::createFromFormat('!Y-m-d', $throughOption, 'Asia/Jakarta')
            : $firstWednesday->copy();

        if ($firstWednesday->dayOfWeek !== Carbon::WEDNESDAY
            || $lastWednesday->dayOfWeek !== Carbon::WEDNESDAY
            || $firstWednesday->greaterThan($lastWednesday)) {
            $this->error('Rentang harus berisi tanggal Rabu berurutan yang valid.');

            return self::FAILURE;
        }

        $period = OrganizationPeriod::where('is_active', true)->first()
            ?? OrganizationPeriod::first();
        $created = 0;

        for ($date = $firstWednesday->copy(); $date->lte($lastWednesday); $date->addWeek()) {
            $due = Due::firstOrCreate(
                ['weekly_period' => $date->toDateString()],
                [
                    'organization_period_id' => $period?->id,
                    'title' => 'Kas Mingguan - Rabu '.$date->format('d/m/Y'),
                    'type' => 'BULANAN',
                    'target_type' => 'SEMUA',
                    'amount' => 2000,
                    'deadline' => $date->toDateString(),
                    'description' => 'Iuran kas mingguan Rp2.000 per anggota aktif; jatuh tempo setiap Rabu.',
                ]
            );

            if ($due->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->info("{$created} tagihan kas mingguan dibuat. Tagihan yang sudah ada tidak diubah.");

        return self::SUCCESS;
    }
}
