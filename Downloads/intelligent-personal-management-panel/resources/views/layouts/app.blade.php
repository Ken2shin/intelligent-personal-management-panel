<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PanelPro Enterprise') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #f4f7fb;
        }

        /* Efecto Liquid Glass Maestro */
        .liquid-glass {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(50px) saturate(210%);
            -webkit-backdrop-filter: blur(50px) saturate(210%);
            border-right: 1px solid rgba(255, 255, 255, 0.3);
        }

        .liquid-nav-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(35px) saturate(160%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Animación de esferas de fondo orgánicas */
        .bg-blob {
            position: fixed;
            width: 700px;
            height: 700px;
            filter: blur(120px);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.25;
            pointer-events: none;
            animation: move 30s infinite alternate ease-in-out;
        }

        @keyframes move {
            from { transform: translate(-15%, -15%) rotate(0deg) scale(1); }
            to { transform: translate(25%, 25%) rotate(15deg) scale(1.1); }
        }

        /* Scrollbar Invisible / iOS Style */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.05); border-radius: 10px; }
        
        /* Transición de alta fidelidad para el sidebar */
        .transition-sidebar { 
            transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1); 
        }
    </style>

    @livewireStyles
</head>
<body class="min-h-screen antialiased overflow-hidden"
      x-data="{ 
          sidebarOpen: localStorage.getItem('sidebarState') !== 'false',
          isMobile: window.innerWidth < 768,
          toggleSidebar() {
              this.sidebarOpen = !this.sidebarOpen;
              localStorage.setItem('sidebarState', this.sidebarOpen);
          }
      }"
      @resize.window="isMobile = window.innerWidth < 768; if(isMobile) sidebarOpen = false">

    <div class="bg-blob top-[-10%] left-[-10%] bg-blue-500"></div>
    <div class="bg-blob bottom-[-10%] right-[-10%] bg-indigo-400" style="animation-delay: -7s;"></div>

    <div class="flex h-screen overflow-hidden relative">
        
        <aside class="fixed inset-y-0 left-0 z-50 transition-sidebar liquid-glass overflow-hidden flex flex-col shadow-2xl md:shadow-none"
               :class="{ 
                   'w-80': sidebarOpen, 
                   'w-0': !sidebarOpen
               }">
            
            <div class="p-8 flex items-center justify-between gap-4 min-w-[320px]">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-[1.25rem] flex-shrink-0 flex items-center justify-center shadow-xl shadow-blue-500/20 border border-white/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div class="whitespace-nowrap">
                        <h1 class="text-2xl font-extrabold tracking-tighter text-slate-900 leading-none">PANEL<span class="text-blue-600">PRO</span></h1>
                        <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 mt-1">SaaS Infrastructure</p>
                    </div>
                </div>

                <button @click="toggleSidebar()" class="group p-2.5 rounded-xl hover:bg-white/60 text-slate-400 hover:text-blue-600 transition-all border border-transparent hover:border-white/50 hover:shadow-lg hover:shadow-blue-900/5 cursor-pointer mr-2">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-5 space-y-4 overflow-y-auto mt-6 min-w-[320px]">
                @php 
                    $nav = [
                        ['route' => 'dashboard', 'label' => 'Inicio', 'svg' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['route' => 'tareas', 'label' => 'Tareas', 'svg' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                        ['route' => 'habitos', 'label' => 'Habitos', 'svg' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                        ['route' => 'finanzas', 'label' => 'Finanzas', 'svg' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'notas', 'label' => 'Notas', 'svg' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                    ];
                @endphp

                @foreach($nav as $item)
                    <a href="{{ route($item['route']) }}" 
                       class="flex items-center gap-5 px-6 py-5 rounded-[2rem] transition-all duration-500 group {{ request()->routeIs($item['route']) ? 'bg-white shadow-2xl shadow-blue-600/10 text-blue-600 scale-[1.02]' : 'text-slate-500 hover:bg-white/60 hover:text-slate-900' }}">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['svg'] }}"/></svg>
                        </div>
                        <span class="text-sm font-black tracking-tight whitespace-nowrap">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="p-6 mt-auto border-t border-white/10 min-w-[320px]">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-5 px-6 py-5 bg-red-50/30 text-red-500 font-black text-[10px] uppercase tracking-[0.2em] rounded-[1.5rem] hover:bg-red-500 hover:text-white transition-all group shadow-inner">
                        <svg class="w-5 h-5 flex-shrink-0 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Finalizar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <div x-show="isMobile && sidebarOpen" 
             @click="toggleSidebar()" 
             x-transition:enter="transition opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 md:hidden" x-cloak>
        </div>

        <div class="flex-1 flex flex-col h-screen overflow-hidden transition-all duration-500">
            
            <header class="h-24 liquid-nav-glass px-10 flex justify-between items-center z-40">
                <div class="flex items-center gap-8">
                    <button @click="toggleSidebar()" 
                            class="group p-4 bg-white/40 rounded-2xl hover:bg-white transition-all shadow-sm active:scale-90 border border-white/50 relative overflow-hidden">
                        <svg x-show="!sidebarOpen" x-cloak class="w-6 h-6 text-slate-600 animate-in zoom-in duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                        <svg x-show="sidebarOpen" class="w-6 h-6 text-blue-600 animate-in zoom-in duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <div class="hidden lg:block">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] bg-white/50 border border-white/80 px-5 py-2 rounded-full shadow-inner">Node Status: Optimized</span>
                    </div>
                </div>
                
                <div class="flex items-center gap-4" x-data="{ open: false }">
                    <div @click="open = !open" class="flex items-center gap-5 bg-white/60 p-2 pr-8 rounded-[2.5rem] border border-white hover:border-blue-200 transition-all cursor-pointer shadow-xl shadow-blue-900/5 relative group">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-black shadow-lg border-2 border-white ring-4 ring-blue-500/5 uppercase text-lg group-hover:scale-105 transition-transform">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="text-left leading-tight hidden md:block">
                            <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-1">Authenticated</p>
                            <p class="text-base font-bold text-slate-900 tracking-tighter">{{ Auth::user()->name }}</p>
                        </div>

                        <div x-show="open" @click.away="open = false" x-cloak
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             class="absolute top-full right-0 mt-6 w-56 liquid-glass rounded-[2rem] shadow-2xl p-3 border border-white z-[60]">
                             <div class="p-4 mb-2 border-b border-black/5 text-center">
                                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Plan</p>
                                 <p class="text-xs font-bold text-blue-600">Enterprise Edition</p>
                             </div>
                             <a href="#" class="flex items-center gap-3 px-5 py-4 text-xs font-bold text-slate-700 hover:bg-blue-600 hover:text-white rounded-2xl transition-all group">
                                 <svg class="w-4 h-4 text-slate-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                 Configuración
                             </a>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto relative scroll-smooth">
                <div class="p-0 animate-in fade-in zoom-in-95 duration-1000">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <div class="fixed top-10 left-1/2 -translate-x-1/2 z-[100] w-full max-w-sm pointer-events-none px-6">
        @if (session()->has('mensaje'))
            <div class="pointer-events-auto liquid-glass border border-white p-6 rounded-[2.5rem] shadow-2xl flex items-center gap-5" 
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transition cubic-bezier(0.34, 1.56, 0.64, 1) duration-600"
                 x-transition:enter-start="opacity-0 -translate-y-24 scale-75"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-400"
                 x-transition:leave-end="opacity-0 scale-90 blur-lg">
                <div class="w-14 h-14 bg-emerald-500 rounded-3xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/20 ring-4 ring-emerald-500/5 flex-shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="font-black text-slate-900 text-sm tracking-tight uppercase leading-none">Sincronización</h4>
                    <p class="text-[11px] font-bold text-slate-500 mt-1 truncate">{{ session('mensaje') }}</p>
                </div>
            </div>
        @endif
    </div>

    @livewireScripts
</body>
</html>