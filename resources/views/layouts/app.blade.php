<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIKELUH - Sistem Keluhan Masyarakat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Efek Scrollbar Tipis */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen flex text-slate-800">

    <aside class="w-72 bg-white border-r border-slate-200 flex flex-col justify-between p-7 shrink-0 sticky top-0 h-screen shadow-[4px_0_24px_rgba(0,0,0,0.03)]">
        <div class="space-y-10">
            <div class="flex items-center gap-3 px-2">
                <div class="w-10 h-10 bg-blue-600 rounded-2xl flex items-center justify-center text-white font-black text-lg shadow-lg shadow-blue-600/20">S</div>
                <div>
                    <span class="text-lg font-black text-slate-900 block leading-tight">SIKELUH</span>
                    <span class="text-[10px] text-blue-600 font-bold uppercase tracking-widest">Sistem Keluhan</span>
                </div>
            </div>
            
            <nav class="space-y-2">
                @php $navItems = [
                    ['url' => '/dashboard', 'icon' => '📊', 'label' => 'Dashboard'],
                    ['url' => '/complaints/create', 'icon' => '📝', 'label' => 'Kirim Pengaduan', 'role' => 'user'],
                    ['url' => '/complaints', 'icon' => '📂', 'label' => auth()->user()->role === 'admin' ? 'Manajemen Aduan' : 'Aduan Saya'],
                    ['url' => '/profile', 'icon' => '👤', 'label' => 'Profil Akun']
                ]; @endphp

                @foreach($navItems as $item)
                    @if(isset($item['role']) && auth()->user()->role === 'admin') @continue @endif
                    <a href="{{ $item['url'] }}" 
                       class="flex items-center gap-4 py-3.5 px-4 rounded-2xl font-bold text-sm transition-all duration-200 
                       {{ Request::is(trim($item['url'], '/').'*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <span>{{ $item['icon'] }}</span> {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>
        
        <div class="bg-slate-50 p-4 rounded-2xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white border border-slate-200 text-blue-600 rounded-full font-black text-sm flex items-center justify-center uppercase shadow-sm">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-xs font-black text-slate-900 leading-tight">{{ auth()->user()->name }}</p>
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">{{ auth()->user()->role }}</span>
                </div>
            </div>
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-red-500 font-bold transition">⏻</button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-10 overflow-y-auto">
        <div class="max-w-5xl mx-auto">
            @yield('content')
        </div>
    </main>

</body>
</html>