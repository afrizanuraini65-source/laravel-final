@extends('layouts.admin')

@section('title', 'Profile Settings - PIK-R SKAKMATT')
@section('header', 'Profile')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="bg-white p-4 shadow sm:rounded-3xl sm:p-8 border border-gray-100">
        <section class="max-w-xl">
            <header>
                <h2 class="text-lg font-medium text-gray-900">Informasi Profil</h2>
                <p class="mt-1 text-sm text-gray-600">Perbarui informasi profil dan alamat email akun Anda.</p>
            </header>

            <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('patch')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama</label>
                    <input id="name" name="name" type="text" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all duration-300" value="{{ old('name', Auth::user()->name) }}" required autofocus autocomplete="name" />
                    @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input id="email" name="email" type="email" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all duration-300" value="{{ old('email', Auth::user()->email) }}" required autocomplete="username" />
                    @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-xl px-6 py-2.5 hover:from-emerald-400 hover:to-teal-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 shadow-md transition-all duration-300 hover:-translate-y-0.5">
                        Simpan
                    </button>
                    @if (session('status') === 'profile-updated')
                        <p class="text-sm text-emerald-600" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">Berhasil disimpan.</p>
                    @endif
                </div>
            </form>
        </section>
    </div>

    <div class="bg-white p-4 shadow sm:rounded-3xl sm:p-8 border border-gray-100">
        <section class="max-w-xl">
            <header>
                <h2 class="text-lg font-medium text-gray-900">Perbarui Password</h2>
                <p class="mt-1 text-sm text-gray-600">Pastikan akun Anda menggunakan password acak yang panjang agar tetap aman.</p>
            </header>

            <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('put')

                <div>
                    <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-1.5">Password Saat Ini</label>
                    <input id="update_password_current_password" name="current_password" type="password" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all duration-300" autocomplete="current-password" />
                    @error('current_password', 'updatePassword')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru</label>
                    <input id="update_password_password" name="password" type="password" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all duration-300" autocomplete="new-password" />
                    @error('password', 'updatePassword')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all duration-300" autocomplete="new-password" />
                    @error('password_confirmation', 'updatePassword')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-xl px-6 py-2.5 hover:from-emerald-400 hover:to-teal-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 shadow-md transition-all duration-300 hover:-translate-y-0.5">
                        Simpan Password
                    </button>
                    @if (session('status') === 'password-updated')
                        <p class="text-sm text-emerald-600" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">Berhasil diperbarui.</p>
                    @endif
                </div>
            </form>
        </section>
    </div>

    <div class="bg-white p-4 shadow sm:rounded-3xl sm:p-8 border border-red-100">
        <section class="max-w-xl" x-data="{ confirmingUserDeletion: false }">
            <header>
                <h2 class="text-lg font-medium text-red-600">Hapus Akun</h2>
                <p class="mt-1 text-sm text-gray-600">Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Harap unduh semua data atau informasi yang ingin Anda simpan.</p>
            </header>

            <button @click="confirmingUserDeletion = true" class="mt-6 bg-red-600 text-white font-bold rounded-xl px-6 py-2.5 hover:bg-red-500 transition-colors duration-300">
                Hapus Akun
            </button>

            <!-- Delete Account Modal -->
            <div x-show="confirmingUserDeletion" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="confirmingUserDeletion" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="confirmingUserDeletion = false"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="confirmingUserDeletion" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6">
                        
                        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                            @csrf
                            @method('delete')

                            <h2 class="text-lg font-medium text-gray-900">
                                Apakah Anda yakin ingin menghapus akun Anda?
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                Setelah akun dihapus, semua data akan dihapus permanen. Silakan masukkan password untuk mengkonfirmasi.
                            </p>

                            <div class="mt-6">
                                <label for="password" class="sr-only">Password</label>
                                <input id="password" name="password" type="password" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all duration-300" placeholder="Password" />
                                @error('password', 'userDeletion')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" @click="confirmingUserDeletion = false" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-500 transition-colors">Hapus Akun</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
