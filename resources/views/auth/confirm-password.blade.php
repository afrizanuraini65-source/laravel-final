@extends('layouts.guest')

@section('title', 'Confirm Password')

@section('content')
<div class="mb-4 text-sm text-gray-400 text-center">
    Ini adalah area aman pada aplikasi. Mohon konfirmasi password kamu sebelum melanjutkan.
</div>

<form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
    @csrf

    <div>
        <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
        <input
            id="password"
            type="password"
            name="password"
            class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all duration-300 backdrop-blur-sm"
            required
            autofocus
        />
        @error('password')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <button
        type="submit"
        class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-xl px-4 py-3.5 mt-6 hover:from-emerald-400 hover:to-teal-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 shadow-[0_0_15px_rgba(16,185,129,0.3)] hover:shadow-[0_0_25px_rgba(16,185,129,0.5)] transform transition-all duration-300 hover:-translate-y-1 active:translate-y-0"
    >
        KONFIRMASI
    </button>
</form>
@endsection
