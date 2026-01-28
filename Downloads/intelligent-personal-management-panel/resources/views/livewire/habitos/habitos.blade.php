<div class="min-h-screen bg-[#f8faff] transition-all duration-1000 overflow-hidden relative"
     x-data="{ 
        formOpen: @entangle('mostrarFormulario')
     }">
    
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-blue-200/40 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute top-[20%] -right-[10%] w-[40%] h-[40%] bg-indigo-100/50 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[35%] h-[35%] bg-emerald-100/30 rounded-full blur-[110px]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-12 relative z-10">
        
        <div wire:loading.flex class="fixed top-8 right-8 items-center liquid-glass border border-white/60 px-6 py-3 rounded-[2rem] shadow-2xl z-[100]">
            <div class="relative flex h-3 w-3 mr-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-500 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-600"></span>
            </div>
            <span class="text-[10px] font-black tracking-[0.2em] uppercase text-blue-700/80 font-jakarta">Sincronizando Hábitos</span>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-8 mb-16">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 text-[10px] font-bold uppercase tracking-widest mb-2">
                    Desarrollo de Disciplina
                </div>
                <h1 class="text-6xl font-black tracking-tighter text-slate-900 leading-none font-jakarta">
                    Hábitos<span class="text-emerald-600/50">.</span>
                </h1>
                <p class="text-slate-500/80 font-medium text-lg max-w-md">Construye consistencia a través de sistemas inteligentes.</p>
            </div>
            <button @click="formOpen = !formOpen" 
                class="group relative inline-flex items-center justify-center px-10 py-5 font-bold text-white transition-all duration-500 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[2.5rem] hover:scale-105 active:scale-95 shadow-2xl shadow-blue-500/20">
                <span class="mr-3 text-xl" x-text="formOpen ? '✕' : '+' "></span>
                <span class="tracking-tight" x-text="formOpen ? 'Cerrar Panel' : 'Nuevo Hábito' "></span>
            </button>
        </div>

        @if (session()->has('mensaje'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mb-10 p-6 liquid-glass border-l-[12px] border-emerald-400 rounded-[2.5rem] flex items-center gap-5 animate-slide-down">
                <div class="w-12 h-12 bg-emerald-500/10 rounded-full flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-700/50 mb-1">Logro Registrado</p>
                    <p class="font-bold text-slate-800">{{ session('mensaje') }}</p>
                </div>
            </div>
        @endif

        <div x-show="formOpen"
             x-transition:enter="transition cubic-bezier(0.19, 1, 0.22, 1) duration-700"
             x-transition:enter-start="opacity-0 -translate-y-12"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition cubic-bezier(0.19, 1, 0.22, 1) duration-500"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-12"
             class="liquid-glass mb-16 p-10 rounded-[3.5rem] border border-white shadow-2xl shadow-blue-900/5 relative">
            
            <h2 class="text-3xl font-black text-slate-900 mb-10 flex items-center gap-4 font-jakarta">
                <span class="w-3 h-10 bg-gradient-to-b from-blue-400 to-blue-600 rounded-full"></span>
                <span x-text=" @js($editandoId) ? 'Refinar Estrategia de Hábito' : 'Configurar Nueva Disciplina' "></span>
            </h2>
            
            <form wire:submit="crear" class="space-y-10">
                <div class="space-y-3">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-4">Nombre de la Disciplina</label>
                    <input type="text" wire:model="nombre" 
                        class="w-full px-8 py-5 bg-white/40 border border-slate-200 rounded-[2rem] focus:ring-8 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all outline-none text-slate-900 font-bold"
                        placeholder="Ej. Meditación Cuántica">
                    @error('nombre') <span class="text-red-500 text-[10px] font-black uppercase tracking-tighter ml-6">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-3">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-4">Descripción del Objetivo</label>
                    <textarea wire:model="descripcion" rows="3"
                        class="w-full px-8 py-6 bg-white/40 border border-slate-200 rounded-[2.5rem] focus:ring-8 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all outline-none text-slate-900 font-medium leading-relaxed"
                        placeholder="Define por qué este hábito es vital para tu rendimiento..."></textarea>
                </div>

                <div class="flex flex-col sm:flex-row gap-6 pt-6">
                    <button type="submit" 
                        wire:loading.attr="disabled"
                        class="flex-[2] bg-slate-900 hover:bg-black text-white font-black py-6 rounded-[2.5rem] shadow-2xl transition-all active:scale-95 disabled:opacity-50 tracking-widest text-xs">
                        <span wire:loading.remove wire:target="crear">{{ $editandoId ? 'ACTUALIZAR MÉTRICA' : 'DESPLEGAR HÁBITO' }}</span>
                        <span wire:loading wire:target="crear" class="flex items-center justify-center gap-3">
                            <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                    </button>
                    <button type="button" @click="formOpen = false"
                        class="flex-1 py-6 bg-white border border-slate-200 text-slate-500 font-bold rounded-[2.5rem] hover:bg-slate-50 transition-all active:scale-95 text-xs uppercase tracking-widest">
                        CANCELAR
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @forelse($habitos as $habito)
                <div wire:key="habito-{{ $habito->id }}" 
                     x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false"
                    class="liquid-glass rounded-[3rem] shadow-xl transition-all duration-500 p-10 flex flex-col border-t-[8px] {{ ($habito->completado_hoy > 0) ? 'border-emerald-400' : 'border-blue-400' }}"
                    :class="hovered ? 'translate-y-[-10px] shadow-2xl' : '' ">
                    
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex-1 pr-4">
                            <h3 class="text-2xl font-black text-slate-900 tracking-tight leading-tight mb-2 transition-colors" :class="hovered ? 'text-blue-600' : '' ">{{ $habito->nombre }}</h3>
                            <p class="text-sm font-medium text-slate-400 line-clamp-2 italic leading-relaxed">{{ $habito->descripcion }}</p>
                        </div>
                        <button wire:click="eliminar({{ $habito->id }})" 
                            wire:confirm="¿Confirmar purga de datos de este hábito?"
                            class="p-3 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-2xl transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-10">
                        <div class="bg-white/40 p-5 rounded-[2rem] text-center border border-white/60 shadow-inner">
                            <div class="flex items-center justify-center gap-2 text-blue-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span class="text-3xl font-black">{{ $habito->racha }}</span>
                            </div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Racha Actual</p>
                        </div>
                        <div class="bg-white/40 p-5 rounded-[2rem] text-center border border-white/60 shadow-inner">
                             <div class="flex items-center justify-center gap-2 text-indigo-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-3xl font-black">{{ $habito->puntos }}</span>
                            </div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Karmic Score</p>
                        </div>
                    </div>

                    <div class="mt-auto flex gap-3">
                        @if($habito->completado_hoy > 0)
                            <button wire:click="desmarcarCompletado({{ $habito->id }})" 
                                class="flex-1 py-5 bg-emerald-50 text-emerald-600 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all active:scale-95 flex items-center justify-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Completado
                            </button>
                        @else
                            <button wire:click="marcarCompletado({{ $habito->id }})" 
                                class="flex-1 py-5 bg-blue-600 hover:bg-blue-700 text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-blue-500/20 transition-all active:scale-95 flex items-center justify-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                Marcar Ejecución
                            </button>
                        @endif

                        <button wire:click="editar({{ $habito->id }})" 
                            class="p-5 bg-white border border-slate-100 text-slate-400 rounded-2xl hover:text-blue-600 hover:bg-blue-50 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-40 bg-white/40 rounded-[4rem] border-4 border-dashed border-slate-200">
                    <div class="text-9xl mb-8 opacity-10 grayscale">🎯</div>
                    <h3 class="text-3xl font-black text-slate-300 uppercase tracking-widest font-jakarta">Pipeline de Hábitos Vacío</h3>
                    <p class="text-slate-400 mt-4 text-lg font-medium max-w-sm mx-auto leading-relaxed">Configura tu primer sistema de consistencia para empezar el tracking.</p>
                    <button @click="formOpen = true" class="mt-10 px-12 py-5 bg-slate-900 text-white font-black text-xs uppercase tracking-widest rounded-full hover:scale-105 transition-transform active:scale-95 shadow-2xl">
                        Inicializar Nodo
                    </button>
                </div>
            @endforelse
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