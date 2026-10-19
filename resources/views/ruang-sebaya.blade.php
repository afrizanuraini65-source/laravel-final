@extends('layouts.public')

@section('title', 'Ruang Sebaya - PIK-R SKAKMATT')

@section('content')
<!-- Page Hero Header -->
<section class="relative py-24 border-b border-emerald-500/10 overflow-hidden">
    <div class="absolute inset-0 bg-pikr-dark overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-1/4 w-[600px] h-[600px] bg-blue-500/10 blur-[120px] rounded-full translate-x-1/2 -translate-y-1/3"></div>
        <div class="absolute bottom-0 left-1/4 w-[500px] h-[500px] bg-pikr-primary/20 blur-[100px] rounded-full -translate-x-1/2 translate-y-1/3"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center justify-center px-4 py-1.5 mb-6 rounded-full bg-blue-500/10 border border-blue-400/30 backdrop-blur-md">
            <span class="text-xs font-bold text-blue-400 uppercase tracking-widest">SKAKMATT CALL CENTER</span>
        </div>
        <h1 class="text-4xl md:text-7xl font-extrabold text-white mb-6 tracking-tight drop-shadow-sm leading-tight">
            KAMU GAPAPA? <br/>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">CERITA YUK!</span>
        </h1>
        <p class="text-xl text-gray-300 max-w-2xl mx-auto leading-relaxed font-light mb-10">
            Via Instagram <strong class="text-white">@ruangsebaya_</strong>. Ruang Sebaya selalu ada buat kamu.
        </p>
        <a href="https://www.instagram.com/ruangsebaya_?utm_source=ig_web_button_share_sheet&stkn=ZDNlZDc0MzIxNw==" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-8 py-4 rounded-full font-bold text-lg hover:from-blue-400 hover:to-indigo-500 transition-all duration-300 shadow-[0_0_20px_rgba(59,130,246,0.4)] transform hover:-translate-y-1 active:translate-y-0">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
            </svg>
            Hubungi Ruang Sebaya
        </a>
    </div>
</section>

<!-- Content Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 pb-32">
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Card: Information & QR Code -->
        <div class="lg:col-span-5 bg-gradient-to-br from-blue-900/40 to-pikr-dark/60 backdrop-blur-xl border border-blue-500/20 rounded-3xl p-8 sm:p-12 shadow-2xl flex flex-col items-center text-center sticky top-28">
            <div class="w-20 h-20 bg-blue-500/20 text-blue-400 rounded-2xl flex items-center justify-center mb-6 shadow-[0_0_15px_rgba(59,130,246,0.3)]">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
            </div>
            <h2 class="text-3xl font-bold text-white tracking-tight mb-4">Ruang Sebaya</h2>
            <p class="text-gray-300 leading-relaxed mb-10">
                Kami menyediakan ruang aman untuk bercerita, berdiskusi, dan mencari solusi atas permasalahan remajamu. Privasi dijamin aman!
            </p>
            
            <a href="https://t.me/skakmattcallcenter_bot" target="_blank" rel="noopener noreferrer" class="inline-block bg-white p-4 rounded-3xl shadow-lg border border-gray-100 mb-6 group hover:-translate-y-2 hover:shadow-2xl hover:border-blue-300 transition-all duration-300">
                <img src="/images/ruangsebaya__qr.png" alt="QR Code Ruang Sebaya" class="w-64 h-64 sm:w-80 sm:h-80 object-contain" onerror="this.src='/images/placeholder.png'" />
            </a>
            <p class="text-blue-300 font-medium">Scan QR Code atau klik di atas untuk chat dengan Bot Telegram kami.</p>
        </div>

        <!-- Right Card: How to / Tata Cara -->
        <div class="lg:col-span-7 bg-gradient-to-br from-blue-900/40 to-pikr-dark/60 backdrop-blur-xl border border-blue-500/20 rounded-3xl p-8 sm:p-12 shadow-2xl">
            <div class="mb-10 text-center lg:text-left">
                <h2 class="text-3xl font-bold text-white tracking-tight">Gimana Caranya?</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-400 to-emerald-400 mt-4 rounded-full mx-auto lg:mx-0"></div>
            </div>

            <ol class="space-y-6 text-gray-300 text-lg relative before:absolute before:inset-0 before:ml-6 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-blue-500/30 before:to-transparent lg:before:hidden">
                <li class="relative flex items-center gap-6">
                    <div class="w-12 h-12 rounded-full bg-blue-500/20 border-2 border-blue-400 flex items-center justify-center font-bold text-blue-400 shrink-0 z-10 shadow-[0_0_15px_rgba(59,130,246,0.3)]">1</div>
                    <p class="bg-white/5 border border-white/10 p-4 rounded-2xl flex-grow shadow-lg">Cari dan follow akun <strong class="text-white">@ruangsebaya_</strong> di Instagram.</p>
                </li>
                <li class="relative flex items-center gap-6">
                    <div class="w-12 h-12 rounded-full bg-blue-500/20 border-2 border-blue-400 flex items-center justify-center font-bold text-blue-400 shrink-0 z-10 shadow-[0_0_15px_rgba(59,130,246,0.3)]">2</div>
                    <p class="bg-white/5 border border-white/10 p-4 rounded-2xl flex-grow shadow-lg">Klik tombol <strong>"Message"</strong> atau <strong>"DM"</strong> buat mulai ngobrol.</p>
                </li>
                <li class="relative flex items-center gap-6">
                    <div class="w-12 h-12 rounded-full bg-blue-500/20 border-2 border-blue-400 flex items-center justify-center font-bold text-blue-400 shrink-0 z-10 shadow-[0_0_15px_rgba(59,130,246,0.3)]">3</div>
                    <p class="bg-white/5 border border-white/10 p-4 rounded-2xl flex-grow shadow-lg">Kirim pesan sapaan (hai, hallo, dll).</p>
                </li>
                <li class="relative flex items-center gap-6">
                    <div class="w-12 h-12 rounded-full bg-blue-500/20 border-2 border-blue-400 flex items-center justify-center font-bold text-blue-400 shrink-0 z-10 shadow-[0_0_15px_rgba(59,130,246,0.3)]">4</div>
                    <p class="bg-white/5 border border-white/10 p-4 rounded-2xl flex-grow shadow-lg">Kirim pesan cerita maksimal <strong>3 bubble chat</strong>.</p>
                </li>
                <li class="relative flex items-center gap-6">
                    <div class="w-12 h-12 rounded-full bg-blue-500/20 border-2 border-blue-400 flex items-center justify-center font-bold text-blue-400 shrink-0 z-10 shadow-[0_0_15px_rgba(59,130,246,0.3)]">5</div>
                    <p class="bg-white/5 border border-white/10 p-4 rounded-2xl flex-grow shadow-lg">Cerita apa yang kamu rasakan atau butuh bantuan apa.</p>
                </li>
                <li class="relative flex items-center gap-6">
                    <div class="w-12 h-12 rounded-full bg-blue-500/20 border-2 border-blue-400 flex items-center justify-center font-bold text-blue-400 shrink-0 z-10 shadow-[0_0_15px_rgba(59,130,246,0.3)]">6</div>
                    <p class="bg-white/5 border border-white/10 p-4 rounded-2xl flex-grow shadow-lg">Tunggu respon admin, jangan spam chat <span class="text-gray-400 text-sm">(admin bukan robot)</span>.</p>
                </li>
                <li class="relative flex items-center gap-6">
                    <div class="w-12 h-12 rounded-full bg-blue-500/20 border-2 border-blue-400 flex items-center justify-center font-bold text-blue-400 shrink-0 z-10 shadow-[0_0_15px_rgba(59,130,246,0.3)]">7</div>
                    <p class="bg-white/5 border border-white/10 p-4 rounded-2xl flex-grow shadow-lg">Jangan gunakan bahasa yang mengandung unsur SARA, dan Pornografi.</p>
                </li>
                <li class="relative flex items-center gap-6">
                    <div class="w-12 h-12 rounded-full bg-blue-500/20 border-2 border-blue-400 flex items-center justify-center font-bold text-blue-400 shrink-0 z-10 shadow-[0_0_15px_rgba(59,130,246,0.3)]">8</div>
                    <p class="bg-white/5 border border-white/10 p-4 rounded-2xl flex-grow shadow-lg">Selamat bercerita, <strong class="text-white">"Ruang Sebaya selalu ada untuk kamu"</strong>.</p>
                </li>
            </ol>
        </div>

    </div>
</section>
@endsection
