<div class="min-h-screen bg-[#f8faff] transition-all duration-1000 overflow-hidden relative">
    
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-blue-200/40 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute top-[20%] -right-[10%] w-[40%] h-[40%] bg-indigo-100/50 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[35%] h-[35%] bg-purple-100/30 rounded-full blur-[110px]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-12 relative z-10">
        
        <div class="mb-12 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-[10px] font-black uppercase tracking-widest mb-2 font-jakarta">
                    System Control Center
                </div>
                <h1 class="text-5xl font-black tracking-tighter text-slate-900 leading-none font-jakarta">
                    Bienvenido, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">{{ Auth::user()->name }}</span>
                </h1>
                <p class="text-slate-400 font-bold text-sm uppercase tracking-widest">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
            
            <button wire:click="$refresh" 
                x-data="{ rotating: false }" @click="rotating = true; setTimeout(() => rotating = false, 1000)"
                class="group p-4 bg-white/60 border border-white rounded-2xl shadow-xl hover:bg-white transition-all active:scale-90 relative overflow-hidden">
                <svg :class="rotating ? 'animate-spin text-blue-600' : 'text-slate-400'" class="w-6 h-6 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            
            <div x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false"
                 class="liquid-glass p-8 rounded-[2.5rem] border border-white shadow-xl transition-all duration-500 relative overflow-hidden"
                 :class="hover ? 'translate-y-[-8px] shadow-2xl border-blue-200' : '' ">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Finalizadas</p>
                        <p class="text-4xl font-black text-slate-900 tracking-tighter">{{ $tareasCompletadas }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-600" :class="hover ? 'scale-110 rotate-6' : '' ">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>
            </div>

            <div x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false"
                 class="liquid-glass p-8 rounded-[2.5rem] border border-white shadow-xl transition-all duration-500 relative overflow-hidden"
                 :class="hover ? 'translate-y-[-8px] shadow-2xl border-amber-200' : '' ">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">En Pipeline</p>
                        <p class="text-4xl font-black text-slate-900 tracking-tighter">{{ $tareasHoy }}</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-600" :class="hover ? 'scale-110 -rotate-6' : '' ">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </div>
            </div>

            <div x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false"
                 class="liquid-glass p-8 rounded-[2.5rem] border border-white shadow-xl transition-all duration-500 relative overflow-hidden"
                 :class="hover ? 'translate-y-[-8px] shadow-2xl border-purple-200' : '' ">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Disciplinas</p>
                        <p class="text-4xl font-black text-slate-900 tracking-tighter">{{ $habitosHoy }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-500/10 rounded-2xl flex items-center justify-center text-purple-600" :class="hover ? 'scale-110 rotate-12' : '' ">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
            </div>

            <div x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false"
                 class="liquid-glass p-8 rounded-[2.5rem] border border-white shadow-xl transition-all duration-500 relative overflow-hidden"
                 :class="hover ? 'translate-y-[-8px] shadow-2xl ' + (@js($balanceTotal >= 0) ? 'border-emerald-200' : 'border-rose-200') : '' ">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Cash Flow</p>
                        <p class="text-3xl font-black tracking-tighter {{ $balanceTotal >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $balanceTotal >= 0 ? '+' : '-' }}${{ number_format(abs($balanceTotal), 0) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $balanceTotal >= 0 ? 'bg-emerald-500/10 text-emerald-600' : 'bg-rose-500/10 text-rose-600' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 liquid-glass p-10 rounded-[3.5rem] border border-white shadow-2xl">
                <div class="flex justify-between items-center mb-10">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                        <span class="w-2 h-8 bg-blue-600 rounded-full"></span>
                        Pipeline Crítico
                    </h2>
                    <a href="{{ route('tareas') }}" class="px-6 py-2 bg-slate-100 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-500 hover:bg-blue-600 hover:text-white transition-all">Explorar Todo</a>
                </div>
                
                @if($tareasDelDia->count() > 0)
                    <div class="space-y-4">
                        @foreach($tareasDelDia as $tarea)
                            <div class="group flex items-center gap-6 p-6 bg-white/40 rounded-3xl border border-white/60 hover:bg-white hover:shadow-xl transition-all duration-500">
                                <div class="w-6 h-6 rounded-full border-4 border-slate-200 group-hover:border-blue-500 transition-colors"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-slate-900 text-lg tracking-tight truncate">{{ $tarea->titulo }}</p>
                                    <span class="inline-flex mt-2 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest
                                        @if($tarea->prioridad === 'alta') bg-rose-50 text-rose-600 
                                        @elseif($tarea->prioridad === 'media') bg-amber-50 text-amber-600 
                                        @else bg-emerald-50 text-emerald-600 @endif">
                                        {{ $tarea->prioridad }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-20 bg-slate-50/50 rounded-[3rem] border-2 border-dashed border-slate-200">
                        <div class="text-6xl mb-4 opacity-20">🍃</div>
                        <p class="text-xl font-black text-slate-300 uppercase tracking-widest">Sin Cargas Pendientes</p>
                    </div>
                @endif
            </div>

            <div class="space-y-10">
                <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-[3rem] p-10 text-white shadow-2xl relative overflow-hidden group">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
                    <div class="relative z-10">
                        <p class="text-7xl font-black tracking-tighter mb-2">{{ $notasCreadas }}</p>
                        <p class="text-xs font-black uppercase tracking-[0.3em] opacity-80 leading-relaxed">Activos de <br> Conocimiento</p>
                    </div>
                </div>

                <div class="liquid-glass p-10 rounded-[3.5rem] border border-white shadow-2xl">
                    <h2 class="text-lg font-black text-slate-900 mb-8 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Auditoría
                    </h2>
                    
                    @if($transaccionesRecientes->count() > 0)
                        <div class="space-y-6">
                            @foreach($transaccionesRecientes as $transaccion)
                                <div class="flex items-center justify-between group">
                                    <div class="flex-1 min-w-0 pr-4">
                                        <p class="font-bold text-slate-800 text-sm truncate group-hover:text-blue-600 transition-colors">{{ strtoupper($transaccion->categoria) }}</p>
                                        <p class="text-[10px] font-medium text-slate-400 truncate tracking-wide">ID: {{ $transaccion->id }}</p>
                                    </div>
                                    <p class="font-black text-sm {{ $transaccion->tipo === 'ingreso' ? 'text-emerald-500' : 'text-rose-500' }}">
                                        {{ $transaccion->tipo === 'ingreso' ? '+' : '-' }}${{ number_format($transaccion->monto, 0) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 opacity-30">
                            <p class="text-xs font-black uppercase tracking-widest">Sin Flujos</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .liquid-glass {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(50px) saturate(210%);
            -webkit-backdrop-filter: blur(50px) saturate(210%);
        }
        .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</div>