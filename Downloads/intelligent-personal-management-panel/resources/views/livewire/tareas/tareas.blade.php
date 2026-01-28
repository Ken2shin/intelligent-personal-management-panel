<div class="min-h-screen bg-[#f8faff] transition-all duration-1000 overflow-hidden relative">
    
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-blue-200/40 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute top-[20%] -right-[10%] w-[40%] h-[40%] bg-indigo-100/50 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[35%] h-[35%] bg-purple-100/40 rounded-full blur-[110px]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-12 relative z-10">
        
        <div wire:loading.flex class="fixed top-8 right-8 items-center liquid-glass border border-white/60 px-6 py-3 rounded-[2rem] shadow-2xl z-[100] animate-in fade-in zoom-in duration-300">
            <div class="relative flex h-3 w-3 mr-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-500 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-600"></span>
            </div>
            <span class="text-[10px] font-black tracking-[0.2em] uppercase text-blue-700/80">Sincronizando Cloud</span>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-8 mb-16">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-[10px] font-bold uppercase tracking-widest mb-2">
                    Gestión de Productividad
                </div>
                <h1 class="text-6xl font-black tracking-tighter text-slate-900 leading-none">
                    Tareas<span class="text-blue-600/50">.</span>
                </h1>
                <p class="text-slate-500/80 font-medium text-lg max-w-md">Flujo de trabajo inteligente diseñado para profesionales.</p>
            </div>
            <button wire:click="$toggle('mostrarFormulario')" 
                class="group relative inline-flex items-center justify-center px-10 py-5 font-bold text-white transition-all duration-500 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[2rem] hover:scale-105 active:scale-95 shadow-2xl shadow-blue-500/20">
                <span class="mr-3 text-xl">{{ $mostrarFormulario ? '✕' : '+' }}</span>
                <span class="tracking-tight">{{ $mostrarFormulario ? 'Cerrar Panel' : 'Nueva Tarea' }}</span>
            </button>
        </div>

        @if (session()->has('mensaje'))
            <div class="mb-10 p-6 liquid-glass border-l-[12px] border-emerald-400 rounded-[2rem] flex items-center gap-5 animate-slide-down">
                <div class="w-12 h-12 bg-emerald-500/10 rounded-full flex items-center justify-center text-emerald-600 shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-emerald-700/50 mb-1">Notificación</p>
                    <p class="font-bold text-slate-800">{{ session('mensaje') }}</p>
                </div>
            </div>
        @endif

        @if($mostrarFormulario)
            <div class="liquid-glass mb-16 p-10 rounded-[3rem] border border-white shadow-2xl shadow-blue-900/5 animate-slide-down relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <svg class="w-32 h-32 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>

                <h2 class="text-3xl font-black text-slate-900 mb-10 flex items-center gap-4">
                    <span class="w-3 h-10 bg-gradient-to-b from-blue-400 to-blue-600 rounded-full"></span>
                    {{ $editandoId ? 'Editar registro existente' : 'Crear nueva actividad' }}
                </h2>
                
                <form wire:submit="crear" class="space-y-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="group space-y-3">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-2">Título del Proyecto</label>
                            <input type="text" wire:model="titulo" 
                                class="w-full px-8 py-5 bg-white/40 border border-slate-200 rounded-[2rem] focus:ring-8 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all outline-none text-slate-900 font-semibold placeholder:text-slate-300"
                                placeholder="Ej. Arquitectura de Software">
                            @error('titulo') <span class="text-red-500 text-[10px] font-black uppercase tracking-tighter ml-4">{{ $message }}</span> @enderror
                        </div>

                        <div class="group space-y-3">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-2">Deadline</label>
                            <input type="date" wire:model="fecha_limite"
                                class="w-full px-8 py-5 bg-white/40 border border-slate-200 rounded-[2rem] focus:ring-8 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all outline-none text-slate-900 font-bold">
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-2">Objetivos y Detalles</label>
                        <textarea wire:model="descripcion" rows="3"
                            class="w-full px-8 py-5 bg-white/40 border border-slate-200 rounded-[2rem] focus:ring-8 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all outline-none text-slate-900 font-medium placeholder:text-slate-300"
                            placeholder="Describe los alcances de esta tarea..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="group space-y-3">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-2">Prioridad Corporativa</label>
                            <div class="relative">
                                <select wire:model="prioridad"
                                    class="w-full px-8 py-5 bg-white/40 border border-slate-200 rounded-[2rem] appearance-none focus:ring-8 focus:ring-blue-500/10 transition-all outline-none text-slate-900 font-black tracking-tight cursor-pointer">
                                    <option value="baja">Baja Importancia</option>
                                    <option value="media">Prioridad Media</option>
                                    <option value="alta">Urgencia Alta</option>
                                </select>
                            </div>
                        </div>

                        <div class="group space-y-3">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-2">Pipeline de Estado</label>
                            <select wire:model="estado"
                                class="w-full px-8 py-5 bg-white/40 border border-slate-200 rounded-[2rem] appearance-none focus:ring-8 focus:ring-blue-500/10 transition-all outline-none text-slate-900 font-black tracking-tight cursor-pointer">
                                <option value="pendiente">Pendiente</option>
                                <option value="en_progreso">En Ejecución</option>
                                <option value="completada">Finalizada</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-6 pt-6">
                        <button type="submit" 
                            wire:loading.attr="disabled"
                            class="flex-[2] bg-slate-900 hover:bg-black text-white font-black py-6 rounded-[2.5rem] shadow-2xl transition-all active:scale-95 disabled:opacity-50 tracking-tight">
                            <span wire:loading.remove wire:target="crear">{{ $editandoId ? 'ACTUALIZAR PIPELINE' : 'DESPLEGAR TAREA' }}</span>
                            <span wire:loading wire:target="crear" class="flex items-center justify-center gap-3">
                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                PROCESANDO...
                            </span>
                        </button>
                        <button type="button" wire:click="limpiarFormulario"
                            class="flex-1 py-6 bg-white border border-slate-200 text-slate-500 font-bold rounded-[2.5rem] hover:bg-slate-50 transition-all active:scale-95">
                            CANCELAR
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <div class="liquid-glass mb-12 p-8 rounded-[2.5rem] border border-white/50 shadow-xl">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Filtro de Estado</label>
                    <select wire:model.live="filtroEstado"
                        class="w-full px-6 py-4 bg-white/60 border-none rounded-2xl text-xs font-black text-slate-700 focus:ring-4 focus:ring-blue-500/10 transition-all">
                        <option value="">TODOS LOS ESTADOS</option>
                        <option value="pendiente">PENDIENTE</option>
                        <option value="en_progreso">EN PROGRESO</option>
                        <option value="completada">COMPLETADA</option>
                    </select>
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Jerarquía</label>
                    <select wire:model.live="filtroPrioridad"
                        class="w-full px-6 py-4 bg-white/60 border-none rounded-2xl text-xs font-black text-slate-700 focus:ring-4 focus:ring-blue-500/10 transition-all">
                        <option value="">TODAS LAS PRIORIDADES</option>
                        <option value="baja">BAJA</option>
                        <option value="media">MEDIA</option>
                        <option value="alta">ALTA</option>
                    </select>
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Línea de Tiempo</label>
                    <input type="date" wire:model.live="filtroFecha"
                        class="w-full px-6 py-4 bg-white/60 border-none rounded-2xl text-xs font-black text-slate-700 focus:ring-4 focus:ring-blue-500/10 transition-all">
                </div>
            </div>
        </div>

        <div class="liquid-glass rounded-[3rem] overflow-hidden border border-white/60 shadow-2xl relative">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/50 backdrop-blur-2xl">
                            <th class="px-10 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tarea & Descripción</th>
                            <th class="px-10 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Prioridad</th>
                            <th class="px-10 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Pipeline</th>
                            <th class="px-10 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Vencimiento</th>
                            <th class="px-10 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/50">
                        @forelse($tareas as $tarea)
                            <tr wire:key="tarea-{{ $tarea->id }}" class="group hover:bg-white/80 transition-all duration-500">
                                <td class="px-10 py-8">
                                    <div class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors tracking-tight">{{ $tarea->titulo }}</div>
                                    @if($tarea->descripcion)
                                        <div class="text-sm font-medium text-slate-400 mt-2 line-clamp-1 italic">{{ $tarea->descripcion }}</div>
                                    @endif
                                </td>
                                <td class="px-10 py-8 text-center">
                                    <span class="inline-flex px-5 py-2 rounded-[1rem] text-[9px] font-black uppercase tracking-widest shadow-sm
                                        @if($tarea->prioridad === 'alta') bg-red-50 text-red-500 border border-red-100
                                        @elseif($tarea->prioridad === 'media') bg-amber-50 text-amber-600 border border-amber-100
                                        @else bg-emerald-50 text-emerald-600 border border-emerald-100
                                        @endif">
                                        {{ $tarea->prioridad }}
                                    </span>
                                </td>
                                <td class="px-10 py-8 text-center">
                                    <select wire:change="cambiarEstado({{ $tarea->id }}, $event.target.value)" 
                                        class="bg-blue-50/50 text-[10px] font-black uppercase tracking-tighter text-blue-700 px-4 py-2 rounded-xl border-none focus:ring-0 cursor-pointer hover:bg-blue-100 transition-all">
                                        <option value="pendiente" {{ $tarea->estado === 'pendiente' ? 'selected' : '' }}>PENDIENTE</option>
                                        <option value="en_progreso" {{ $tarea->estado === 'en_progreso' ? 'selected' : '' }}>PROGRESO</option>
                                        <option value="completada" {{ $tarea->estado === 'completada' ? 'selected' : '' }}>COMPLETADA</option>
                                    </select>
                                </td>
                                <td class="px-10 py-8 text-center text-xs font-black text-slate-400 tracking-tighter">
                                    {{ $tarea->fecha_limite ? \Carbon\Carbon::parse($tarea->fecha_limite)->format('d . M . Y') : '---' }}
                                </td>
                                <td class="px-10 py-8 text-right space-x-4">
                                    <button wire:click="editar({{ $tarea->id }})" 
                                        class="p-4 bg-blue-50 text-blue-600 rounded-2xl hover:bg-blue-600 hover:text-white transition-all active:scale-90 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>
                                    <button wire:click="eliminar({{ $tarea->id }})" 
                                        wire:confirm="¿Desea archivar permanentemente este registro?"
                                        class="p-4 bg-red-50 text-red-400 rounded-2xl hover:bg-red-500 hover:text-white transition-all active:scale-90 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-10 py-32 text-center">
                                    <div class="text-8xl mb-6 opacity-20">📂</div>
                                    <h3 class="text-2xl font-black text-slate-300 uppercase tracking-widest">Base de datos vacía</h3>
                                    <p class="text-slate-400 mt-2 font-medium">Inicia el pipeline creando tu primera tarea corporativa.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-12 px-6">
            {{ $tareas->links() }} 
        </div>
    </div>

    <style>
        .liquid-glass {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(50px) saturate(200%);
            -webkit-backdrop-filter: blur(50px) saturate(200%);
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: opacity(0.3);
            cursor: pointer;
        }
        @keyframes slide-down { from { opacity: 0; transform: translateY(-40px); } to { opacity: 1; transform: translateY(0); } }
        .animate-slide-down { animation: slide-down 0.6s cubic-bezier(0.23, 1, 0.32, 1); }
        
        /* Personalización de Scrollbar para iOS Feel */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.05); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(0,0,0,0.1); }
    </style>
</div>