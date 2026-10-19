@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran Kas')
@section('header', 'Verifikasi Pembayaran')

@section('content')
<div x-data="{ 
    selectedPayment: null,
    action: null,
    openAction(payment, act) {
        this.selectedPayment = payment;
        this.action = act;
    }
}">

    <div class="mb-6">
        <h3 class="text-xl font-bold text-slate-800">Daftar Tunggu Verifikasi</h3>
        <p class="text-gray-500 text-sm mt-1">Periksa bukti pembayaran yang dikirimkan oleh anggota.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="p-4 font-semibold text-sm text-gray-500">Tanggal</th>
                    <th class="p-4 font-semibold text-sm text-gray-500">Anggota</th>
                    <th class="p-4 font-semibold text-sm text-gray-500">Tagihan</th>
                    <th class="p-4 font-semibold text-sm text-gray-500">Metode</th>
                    <th class="p-4 font-semibold text-sm text-gray-500">Status</th>
                    <th class="p-4 font-semibold text-sm text-gray-500 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="p-4 text-sm text-gray-700">{{ $p->created_at->format('d/m/Y') }}</td>
                        <td class="p-4 text-sm font-medium text-slate-800">{{ $p->user->name ?? 'Unknown' }}</td>
                        <td class="p-4 text-sm text-gray-700">
                            {{ $p->due->title }} <br/> 
                            <span class="font-bold text-gray-900">Rp {{ number_format($p->amount_paid, 0, ',', '.') }}</span>
                        </td>
                        <td class="p-4 text-sm text-gray-700">{{ $p->payment_method }}</td>
                        <td class="p-4 text-sm">
                            @if($p->status === 'MENUNGGU_VERIFIKASI')
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">MENUNGGU</span>
                            @elseif($p->status === 'LUNAS')
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">LUNAS</span>
                            @elseif($p->status === 'DITOLAK')
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">DITOLAK</span>
                            @endif
                        </td>
                        <td class="p-4 text-sm text-right space-x-2">
                            @if($p->status === 'MENUNGGU_VERIFIKASI')
                                <a href="{{ asset('storage/' . $p->proof_file) }}" target="_blank" class="text-blue-600 hover:underline font-medium text-xs">Cek Bukti</a>
                                <button @click="openAction({{ $p }}, 'APPROVE')" class="text-emerald-600 hover:underline font-medium text-xs">Setujui</button>
                                <button @click="openAction({{ $p }}, 'REJECT')" class="text-red-600 hover:underline font-medium text-xs">Tolak</button>
                            @else
                                <span class="text-gray-400 text-xs">Selesai (Oleh: {{ $p->verifier->name ?? 'Sistem' }})</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500">
                            Belum ada pembayaran yang perlu diverifikasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Action -->
    <div x-show="selectedPayment" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="selectedPayment" x-transition.opacity class="fixed inset-0 bg-black/50" @click="selectedPayment = null"></div>
        <div x-show="selectedPayment" x-transition class="bg-white rounded-2xl w-full max-w-md p-6 relative z-10 shadow-2xl">
            <h3 class="text-lg font-bold text-slate-800 mb-4" x-text="action === 'APPROVE' ? 'Setujui Pembayaran' : 'Tolak Pembayaran'"></h3>
            <p class="text-sm text-gray-600 mb-4">
                Anda akan <span x-text="action === 'APPROVE' ? 'menyetujui' : 'menolak'"></span> pembayaran <strong x-text="selectedPayment?.due?.title"></strong> dari <strong x-text="selectedPayment?.user?.name"></strong>.
            </p>
            
            <form :action="`/kas/verifikasi/${selectedPayment?.id}`" method="POST">
                @csrf
                <input type="hidden" name="status" :value="action === 'APPROVE' ? 'LUNAS' : 'DITOLAK'">

                <template x-if="action === 'REJECT'">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan</label>
                        <textarea name="rejection_reason" rows="3" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200" required></textarea>
                    </div>
                </template>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="selectedPayment = null" class="px-4 py-2 text-gray-600 font-medium hover:bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 text-white font-medium rounded-lg" :class="action === 'APPROVE' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-red-600 hover:bg-red-700'" x-text="action === 'APPROVE' ? 'Ya, Setujui' : 'Tolak Pembayaran'">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

