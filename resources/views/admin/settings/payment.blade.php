@extends('layouts.admin')

@section('title', 'Pengaturan Pembayaran - PIK-R SKAKMATT')
@section('header', 'Pengaturan Pembayaran')

@section('content')
<div class="max-w-4xl space-y-6">
    <div>
        <h1 class="text-2xl font-black text-white">Informasi Pembayaran</h1>
        <p class="mt-1 text-sm text-gray-400">Nomor rekening dan penyedia pembayaran ditampilkan pada halaman tagihan. QRIS memakai gambar yang tersedia di aplikasi.</p>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm text-emerald-200">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-rose-500/30 bg-rose-500/10 p-4 text-sm text-rose-200">Periksa kembali data rekening atau penyedia pembayaran.</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.payment.update') }}" class="space-y-6 rounded-2xl border border-white/10 bg-[#0B1221]/90 p-6">
        @csrf
        @method('PUT')

        <section class="space-y-4">
            <h2 class="border-b border-white/10 pb-3 text-sm font-bold uppercase tracking-wider text-emerald-300">Rekening Bank</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="text-xs text-gray-300">Nama bank atau penyedia
                    <input name="bank_name" value="{{ old('bank_name', $settings->bank_name) }}" maxlength="100" class="mt-1 w-full rounded-xl border border-white/10 bg-slate-900 px-4 py-2.5 text-sm text-white" autocomplete="organization">
                </label>
                <label class="text-xs text-gray-300">Nama pemilik rekening
                    <input name="account_holder" value="{{ old('account_holder', $settings->account_holder) }}" maxlength="150" class="mt-1 w-full rounded-xl border border-white/10 bg-slate-900 px-4 py-2.5 text-sm text-white" autocomplete="name">
                </label>
            </div>
            <label class="block text-xs text-gray-300">Nomor rekening
                <input name="account_number" value="{{ old('account_number', $settings->account_number) }}" maxlength="40" inputmode="numeric" class="mt-1 w-full rounded-xl border border-white/10 bg-slate-900 px-4 py-2.5 text-sm font-mono text-white">
            </label>
        </section>

        <section class="space-y-4">
            <h2 class="border-b border-white/10 pb-3 text-sm font-bold uppercase tracking-wider text-cyan-300">E-Wallet</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="text-xs text-gray-300">Nama penyedia
                    <input name="wallet_provider" value="{{ old('wallet_provider', $settings->wallet_provider) }}" maxlength="100" class="mt-1 w-full rounded-xl border border-white/10 bg-slate-900 px-4 py-2.5 text-sm text-white">
                </label>
                <label class="text-xs text-gray-300">Nama pemilik akun
                    <input name="wallet_account_holder" value="{{ old('wallet_account_holder', $settings->wallet_account_holder) }}" maxlength="150" class="mt-1 w-full rounded-xl border border-white/10 bg-slate-900 px-4 py-2.5 text-sm text-white">
                </label>
            </div>
            <label class="block text-xs text-gray-300">Nomor akun
                <input name="wallet_account_number" value="{{ old('wallet_account_number', $settings->wallet_account_number) }}" maxlength="40" inputmode="numeric" class="mt-1 w-full rounded-xl border border-white/10 bg-slate-900 px-4 py-2.5 text-sm font-mono text-white">
            </label>
        </section>

        <label class="block text-xs text-gray-300">Instruksi pembayaran
            <textarea name="instructions" rows="4" maxlength="2000" class="mt-1 w-full rounded-xl border border-white/10 bg-slate-900 px-4 py-2.5 text-sm text-white">{{ old('instructions', $settings->instructions) }}</textarea>
        </label>

        <div class="flex justify-end border-t border-white/10 pt-4">
            <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-500">Simpan Pengaturan</button>
        </div>
    </form>
</div>
@endsection