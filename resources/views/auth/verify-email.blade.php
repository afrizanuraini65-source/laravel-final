@extends('layouts.guest')

@section('title', 'Email Verification')

@section('content')
<div class="mb-4 text-sm text-gray-400 text-center">
    Terima kasih sudah mendaftar! Sebelum memulai, bisakah kamu memverifikasi alamat email dengan mengklik tautan yang baru saja kami kirimkan ke email kamu? Jika kamu tidak menerima email tersebut, kami akan dengan senang hati mengirimkan yang baru.
</div>

@if (session('status') == 'verification-link-sent')
    <div class="mb-4 text-sm font-medium text-emerald-400 bg-emerald-400/10 p-3 rounded-lg border border-emerald-400/20 text-center">
        Tautan verifikasi baru telah dikirimkan ke alamat email yang kamu berikan saat pendaftaran.
    </div>
@endif

<div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-xl px-4 py-3 mt-6 hover:from-emerald-400 hover:to-teal-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 shadow-[0_0_15px_rgba(16,185,129,0.3)] hover:shadow-[0_0_25px_rgba(16,185,129,0.5)] transform transition-all duration-300 hover:-translate-y-1 active:translate-y-0">
            Kirim Ulang Email Verifikasi
        </button>
    </form>
    
    <form method="POST" action="{{ route('logout') }}" class="mt-6 sm:mt-0">
        @csrf
        <button type="submit" class="rounded-md text-sm text-gray-400 underline hover:text-white transition-colors">
            Log Out
        </button>
    </form>
</div>
@endsection
