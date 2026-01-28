<div class="min-h-screen bg-[#f8faff] transition-all duration-1000 overflow-hidden relative"
     x-data="{ 
        formOpen: <?php if ((object) ('mostrarFormulario') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('mostrarFormulario'->value()); ?>')<?php echo e('mostrarFormulario'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('mostrarFormulario'); ?>')<?php endif; ?>,
        searchFocus: false 
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
            <span class="text-[10px] font-black tracking-[0.2em] uppercase text-blue-700/80 font-jakarta">Indexando Datos</span>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-8 mb-16">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-[10px] font-bold uppercase tracking-widest mb-2">
                    Base de Conocimiento
                </div>
                <h1 class="text-6xl font-black tracking-tighter text-slate-900 leading-none font-jakarta">
                    Notas<span class="text-indigo-600/50">.</span>
                </h1>
                <p class="text-slate-500/80 font-medium text-lg max-w-md">Captura ideas y gestiona el conocimiento corporativo.</p>
            </div>
            <button @click="formOpen = !formOpen" 
                class="group relative inline-flex items-center justify-center px-10 py-5 font-bold text-white transition-all duration-500 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[2.5rem] hover:scale-105 active:scale-95 shadow-2xl shadow-blue-500/20">
                <span class="mr-3 text-xl" x-text="formOpen ? '✕' : '+' "></span>
                <span class="tracking-tight" x-text="formOpen ? 'Cerrar Knowledge' : 'Nueva Nota' "></span>
            </button>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('mensaje')): ?>
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="mb-10 p-6 liquid-glass border-l-[12px] border-emerald-400 rounded-[2.5rem] flex items-center gap-5">
                <div class="w-12 h-12 bg-emerald-500/10 rounded-full flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-700/50 mb-1">Sincronización Exitosa</p>
                    <p class="font-bold text-slate-800"><?php echo e(session('mensaje')); ?></p>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="liquid-glass mb-12 p-8 rounded-[3rem] border border-white/50 shadow-xl transition-all duration-500"
             :class="searchFocus ? 'ring-[12px] ring-blue-500/5 border-blue-200' : '' ">
            <div class="space-y-6">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4 mb-3 block">Motor de Búsqueda</label>
                    <div class="relative group">
                        <input type="text" wire:model.live.debounce.500ms="busqueda"
                            @focus="searchFocus = true" @blur="searchFocus = false"
                            class="w-full pl-16 pr-8 py-6 bg-white/60 border border-slate-100 rounded-[2.5rem] text-slate-900 font-bold transition-all outline-none"
                            placeholder="Filtrar por metadatos o contenido...">
                        <div class="absolute inset-y-0 left-0 pl-7 flex items-center pointer-events-none text-blue-500/50">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>
                </div>

                <label class="inline-flex items-center gap-4 cursor-pointer px-6 py-3 bg-white/40 rounded-2xl hover:bg-white/80 transition-all border border-transparent hover:border-slate-200">
                    <input type="checkbox" wire:model.live="mostrarArchivadas" class="w-6 h-6 accent-blue-600 rounded-lg border-none shadow-inner">
                    <span class="text-slate-700 text-xs font-black uppercase tracking-widest select-none">Incluir Archivo Histórico</span>
                </label>
            </div>
        </div>

        <div x-show="formOpen"
             x-transition:enter="transition cubic-bezier(0.19, 1, 0.22, 1) duration-700"
             x-transition:enter-start="opacity-0 -translate-y-12"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition cubic-bezier(0.19, 1, 0.22, 1) duration-500"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-12"
             class="liquid-glass mb-16 p-10 rounded-[3.5rem] border border-white shadow-2xl shadow-indigo-900/5 relative">
            
            <h2 class="text-3xl font-black text-slate-900 mb-10 flex items-center gap-4 font-jakarta">
                <span class="w-3 h-10 bg-gradient-to-b from-indigo-400 to-indigo-600 rounded-full"></span>
                <span x-text=" <?php echo \Illuminate\Support\Js::from($editandoId)->toHtml() ?> ? 'Editando Metadata' : 'Nuevo Registro de Conocimiento' "></span>
            </h2>
            
            <form wire:submit="crear" class="space-y-10">
                <div class="space-y-3">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-4">Título del Documento</label>
                    <input type="text" wire:model="titulo" 
                        class="w-full px-8 py-5 bg-white/40 border border-slate-200 rounded-[2rem] focus:ring-8 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all outline-none text-slate-900 font-bold"
                        placeholder="Defina un título descriptivo...">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['titulo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-[10px] font-black uppercase tracking-tighter ml-6"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="space-y-3">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-4">Cuerpo del Contenido</label>
                    <textarea wire:model="contenido" rows="6"
                        class="w-full px-8 py-6 bg-white/40 border border-slate-200 rounded-[2.5rem] focus:ring-8 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all outline-none text-slate-900 font-medium leading-relaxed"
                        placeholder="Desarrolle su idea aquí..."></textarea>
                </div>

                <div class="space-y-3">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-4">Etiquetas (Indexación)</label>
                    <input type="text" wire:model="tags" 
                        class="w-full px-8 py-5 bg-white/40 border border-slate-200 rounded-[2rem] focus:ring-8 focus:ring-blue-500/10 transition-all outline-none text-blue-600 font-bold"
                        placeholder="trabajo, estrategia, despliegue">
                </div>

                <div class="flex flex-col sm:flex-row gap-6 pt-6">
                    <button type="submit" 
                        wire:loading.attr="disabled"
                        class="flex-[2] bg-slate-900 hover:bg-black text-white font-black py-6 rounded-[2.5rem] shadow-2xl transition-all active:scale-95 disabled:opacity-50">
                        <span wire:loading.remove wire:target="crear" x-text=" <?php echo \Illuminate\Support\Js::from($editandoId)->toHtml() ?> ? 'ACTUALIZAR CONOCIMIENTO' : 'PUBLICAR NOTA' "></span>
                        <span wire:loading wire:target="crear" class="flex items-center justify-center gap-3">
                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            PROCESANDO...
                        </span>
                    </button>
                    <button type="button" @click="formOpen = false"
                        class="flex-1 py-6 bg-white border border-slate-200 text-slate-500 font-bold rounded-[2.5rem] hover:bg-slate-50 transition-all active:scale-95 uppercase tracking-widest text-xs">
                        CANCELAR
                    </button>
                </div>
            </form>
        </div>

        <div class="relative min-h-[400px]">
            <div wire:loading.flex wire:target="busqueda, mostrarArchivadas, page" 
                class="absolute inset-0 bg-[#f8faff]/60 z-10 flex items-start justify-center pt-32 backdrop-blur-md rounded-[3rem] transition-all duration-500">
                <div class="liquid-glass px-10 py-5 rounded-full shadow-2xl border border-white flex items-center gap-4">
                    <svg class="animate-spin h-6 w-6 text-blue-600" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span class="text-xs font-black uppercase tracking-widest text-slate-700">Reorganizando Grilla...</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $notas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div wire:key="nota-<?php echo e($nota->id); ?>" 
                         x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false"
                        class="liquid-glass rounded-[3rem] shadow-xl transition-all duration-500 p-10 flex flex-col border-t-[8px] <?php echo e($nota->archivada ? 'border-slate-300 opacity-60' : 'border-indigo-400'); ?>"
                        :class="hovered ? 'translate-y-[-10px] shadow-2xl' : '' ">
                        
                        <div class="flex justify-between items-start mb-6">
                            <h3 class="text-2xl font-black text-slate-900 transition-colors tracking-tight leading-tight flex-1"
                                :class="hovered ? 'text-indigo-600' : '' "><?php echo e($nota->titulo); ?></h3>
                            <span class="p-3 bg-white/60 rounded-2xl shadow-inner transition-transform"
                                  :class="hovered ? 'rotate-12' : '' ">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($nota->archivada): ?>
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                <?php else: ?>
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </span>
                        </div>

                        <div class="text-slate-500 font-medium text-base mb-8 flex-1 line-clamp-5 leading-relaxed tracking-wide">
                            <?php echo e($nota->contenido ?? 'Sin metadata adicional'); ?>

                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($nota->tags): ?>
                            <div class="flex flex-wrap gap-2 mb-8">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = explode(',', $nota->tags); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(trim($tag)): ?>
                                        <span class="bg-indigo-50 text-indigo-600 text-[9px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full border border-indigo-100/50">
                                            #<?php echo e(trim($tag)); ?>

                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="flex gap-3 mt-auto pt-8 border-t border-slate-100/50">
                            <button wire:click="editar(<?php echo e($nota->id); ?>)" 
                                class="flex-1 py-4 bg-white/60 text-indigo-600 font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-indigo-600 hover:text-white transition-all active:scale-95 shadow-sm">
                                Editar
                            </button>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($nota->archivada): ?>
                                <button wire:click="desarchivar(<?php echo e($nota->id); ?>)" 
                                    class="flex-1 py-4 bg-white border border-slate-200 text-slate-500 font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-slate-900 hover:text-white transition-all active:scale-95">
                                    Restaurar
                                </button>
                            <?php else: ?>
                                <button wire:click="archivar(<?php echo e($nota->id); ?>)" 
                                    class="flex-1 py-4 bg-amber-50 text-amber-600 font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-amber-600 hover:text-white transition-all active:scale-95 border border-amber-100">
                                    Archivar
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            
                            <button wire:click="eliminar(<?php echo e($nota->id); ?>)" 
                                wire:confirm="¿Confirmar purga permanente de esta información?"
                                class="p-4 bg-red-50 text-red-500 rounded-2xl hover:bg-red-600 hover:text-white transition-all active:scale-90 border border-red-100 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full text-center py-40 bg-white/40 rounded-[4rem] border-4 border-dashed border-slate-200">
                        <div class="text-9xl mb-8 opacity-10 grayscale">📝</div>
                        <h3 class="text-3xl font-black text-slate-300 uppercase tracking-widest font-jakarta">Knowledge Base Vacía</h3>
                        <p class="text-slate-400 mt-4 text-lg font-medium max-w-md mx-auto leading-relaxed">
                            <?php echo e($busqueda ? 'No hay registros que coincidan con los parámetros de búsqueda.' : 'Inicie la documentación de su proyecto creando la primera nota corporativa.'); ?>

                        </p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($busqueda): ?>
                            <button wire:click="$set('busqueda', '')" class="mt-8 px-10 py-4 bg-slate-900 text-white font-black text-xs uppercase tracking-widest rounded-full hover:scale-105 transition-transform active:scale-95">
                                Reestablecer Filtros
                            </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            
            <div class="mt-16 px-10">
                <?php echo e($notas->links()); ?>

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
</div><?php /**PATH C:\Users\EMANUELCLEMENTEMARTI\Downloads\intelligent-personal-management-panel\resources\views/livewire/notas/notas.blade.php ENDPATH**/ ?>