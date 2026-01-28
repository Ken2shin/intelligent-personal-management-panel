<div class="min-h-screen bg-[#f8faff] transition-all duration-1000 overflow-hidden relative"
     x-data="{ 
        formOpen: <?php if ((object) ('mostrarFormulario') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('mostrarFormulario'->value()); ?>')<?php echo e('mostrarFormulario'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('mostrarFormulario'); ?>')<?php endif; ?>
     }">
    
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute -top-[10%] -right-[10%] w-[50%] h-[50%] bg-emerald-200/40 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute top-[40%] -left-[10%] w-[40%] h-[40%] bg-blue-100/50 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[20%] w-[35%] h-[35%] bg-rose-100/30 rounded-full blur-[110px]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-12 relative z-10">
        
        <div wire:loading.flex class="fixed top-8 right-8 items-center liquid-glass border border-white/60 px-6 py-3 rounded-[2rem] shadow-2xl z-[100]">
            <div class="relative flex h-3 w-3 mr-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-600"></span>
            </div>
            <span class="text-[10px] font-black tracking-[0.2em] uppercase text-emerald-700/80 font-jakarta">Conciliando Tesorería</span>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-8 mb-16">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-[10px] font-bold uppercase tracking-widest mb-2 font-jakarta">
                    Gestión de Activos & Capital
                </div>
                <h1 class="text-6xl font-black tracking-tighter text-slate-900 leading-none font-jakarta">
                    Finanzas<span class="text-blue-600/50">.</span>
                </h1>
                <p class="text-slate-500/80 font-medium text-lg max-w-md">Control analítico de flujos de efectivo y balances.</p>
            </div>
            <button @click="formOpen = !formOpen" 
                class="group relative inline-flex items-center justify-center px-10 py-5 font-bold text-white transition-all duration-500 bg-slate-900 hover:bg-black rounded-[2.5rem] hover:scale-105 active:scale-95 shadow-2xl shadow-slate-900/20">
                <span class="mr-3 text-xl" x-text="formOpen ? '✕' : '+' "></span>
                <span class="tracking-tight uppercase text-xs font-black tracking-widest" x-text="formOpen ? 'Cerrar Registro' : 'Nueva Transacción' "></span>
            </button>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('mensaje')): ?>
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mb-10 p-6 liquid-glass border-l-[12px] border-blue-500 rounded-[2.5rem] flex items-center gap-5 animate-slide-down">
                <div class="w-12 h-12 bg-blue-500/10 rounded-full flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-blue-700/50 mb-1">Libro Mayor Actualizado</p>
                    <p class="font-bold text-slate-800"><?php echo e(session('mensaje')); ?></p>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <div class="liquid-glass p-8 rounded-[3rem] border border-white shadow-xl group overflow-hidden relative">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] mb-2">Ingresos Consolidados</p>
                <p class="text-4xl font-black text-slate-900 tracking-tighter">$<?php echo e(number_format($ingresos, 2)); ?></p>
            </div>

            <div class="liquid-glass p-8 rounded-[3rem] border border-white shadow-xl group overflow-hidden relative">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.2em] mb-2">Egresos Totales</p>
                <p class="text-4xl font-black text-slate-900 tracking-tighter">$<?php echo e(number_format($gastos, 2)); ?></p>
            </div>

            <div class="liquid-glass p-8 rounded-[3rem] border border-white shadow-xl group overflow-hidden relative">
                <?php $balance = $ingresos - $gastos; ?>
                <div class="absolute -right-4 -top-4 w-24 h-24 <?php echo e($balance >= 0 ? 'bg-blue-500/5' : 'bg-rose-500/5'); ?> rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                <p class="text-[10px] font-black <?php echo e($balance >= 0 ? 'text-blue-600' : 'text-rose-500'); ?> uppercase tracking-[0.2em] mb-2">Balance Operativo</p>
                <p class="text-4xl font-black text-slate-900 tracking-tighter">
                    <?php echo e($balance >= 0 ? '' : '-'); ?>$<?php echo e(number_format(abs($balance), 2)); ?>

                </p>
            </div>
        </div>

        <div x-show="formOpen"
             x-transition:enter="transition cubic-bezier(0.19, 1, 0.22, 1) duration-700"
             x-transition:enter-start="opacity-0 -translate-y-12"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-500"
             class="liquid-glass mb-16 p-12 rounded-[3.5rem] border border-white shadow-2xl relative">
            
            <h2 class="text-3xl font-black text-slate-900 mb-10 flex items-center gap-4 font-jakarta uppercase tracking-tighter">
                <span class="w-3 h-10 bg-blue-600 rounded-full"></span>
                <?php echo e($editandoId ? 'Modificar Registro Contable' : 'Nueva Transacción de Capital'); ?>

            </h2>
            
            <form wire:submit="crear" class="space-y-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-4">Naturaleza del Flujo</label>
                        <select wire:model="tipo" class="w-full px-8 py-5 bg-white/40 border border-slate-200 rounded-[2rem] focus:ring-8 focus:ring-blue-500/10 outline-none text-slate-900 font-bold appearance-none cursor-pointer">
                            <option value="gasto">EGRESO / GASTO</option>
                            <option value="ingreso">INGRESO / ACTIVO</option>
                        </select>
                    </div>
                    <div class="space-y-3">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-4">Categorización</label>
                        <input type="text" wire:model="categoria" list="lista-categorias"
                            class="w-full px-8 py-5 bg-white/40 border border-slate-200 rounded-[2rem] focus:ring-8 focus:ring-blue-500/10 outline-none text-slate-900 font-bold"
                            placeholder="Ej. Infraestructura">
                        <datalist id="lista-categorias">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($cat); ?>"> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </datalist>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['categoria'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-rose-500 text-[10px] font-black uppercase ml-6"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <div class="space-y-3">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-4">Monto Nominal</label>
                        <input type="number" step="0.01" wire:model="monto" 
                            class="w-full px-8 py-5 bg-white/40 border border-slate-200 rounded-[2rem] focus:ring-8 focus:ring-blue-500/10 outline-none text-slate-900 font-black text-xl"
                            placeholder="0.00">
                    </div>
                    <div class="space-y-3">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-4">Fecha Contable</label>
                        <input type="date" wire:model="fecha"
                            class="w-full px-8 py-5 bg-white/40 border border-slate-200 rounded-[2rem] focus:ring-8 focus:ring-blue-500/10 outline-none text-slate-900 font-bold">
                    </div>
                    <div class="space-y-3">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-4">Glosa / Descripción</label>
                        <input type="text" wire:model="descripcion" 
                            class="w-full px-8 py-5 bg-white/40 border border-slate-200 rounded-[2rem] focus:ring-8 focus:ring-blue-500/10 outline-none text-slate-900 font-medium"
                            placeholder="Detalles opcionales...">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-6 pt-6">
                    <button type="submit" wire:loading.attr="disabled"
                        class="flex-[2] bg-blue-600 hover:bg-blue-700 text-white font-black py-6 rounded-[2.5rem] shadow-2xl transition-all active:scale-95 tracking-widest text-xs uppercase">
                        <span wire:loading.remove wire:target="crear"><?php echo e($editandoId ? 'Confirmar Modificación' : 'Ejecutar Transacción'); ?></span>
                        <span wire:loading wire:target="crear">PROCESANDO...</span>
                    </button>
                    <button type="button" @click="formOpen = false"
                        class="flex-1 py-6 bg-white border border-slate-200 text-slate-500 font-bold rounded-[2.5rem] hover:bg-slate-50 transition-all uppercase tracking-widest text-[10px]">
                        ANULAR OPERACIÓN
                    </button>
                </div>
            </form>
        </div>

        <div class="liquid-glass mb-12 p-8 rounded-[3rem] border border-white shadow-xl">
            <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-8 ml-4">Parámetros de Auditoría</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="space-y-3">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Tipo</label>
                    <select wire:model.live="filtroTipo" class="w-full px-6 py-4 bg-white/60 border-none rounded-2xl text-[10px] font-black text-slate-700 focus:ring-4 focus:ring-blue-500/10 transition-all">
                        <option value="">TODOS</option>
                        <option value="ingreso">INGRESOS</option>
                        <option value="gasto">EGRESOS</option>
                    </select>
                </div>
                <div class="space-y-3">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Categoría</label>
                    <select wire:model.live="filtroCategoria" class="w-full px-6 py-4 bg-white/60 border-none rounded-2xl text-[10px] font-black text-slate-700 focus:ring-4 focus:ring-blue-500/10 transition-all">
                        <option value="">TODAS</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($cat); ?>"><?php echo e(strtoupper($cat)); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <div class="space-y-3">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Desde</label>
                    <input type="date" wire:model.live="fechaInicio" class="w-full px-6 py-4 bg-white/60 border-none rounded-2xl text-[10px] font-black text-slate-700 focus:ring-4 focus:ring-blue-500/10">
                </div>
                <div class="space-y-3">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Hasta</label>
                    <input type="date" wire:model.live="fechaFin" class="w-full px-6 py-4 bg-white/60 border-none rounded-2xl text-[10px] font-black text-slate-700 focus:ring-4 focus:ring-blue-500/10">
                </div>
            </div>
        </div>

        <div class="liquid-glass rounded-[3.5rem] overflow-hidden border border-white/60 shadow-2xl relative">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/50 backdrop-blur-2xl">
                            <th class="px-10 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Centro de Costos</th>
                            <th class="px-10 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Tipo</th>
                            <th class="px-10 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Importe Nominal</th>
                            <th class="px-10 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Fecha Valor</th>
                            <th class="px-10 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/50">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $transacciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaccion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr wire:key="tx-<?php echo e($transaccion->id); ?>" class="group hover:bg-white/80 transition-all duration-500">
                                <td class="px-10 py-8">
                                    <div class="text-xl font-bold text-slate-900 tracking-tight"><?php echo e($transaccion->categoria); ?></div>
                                    <div class="text-xs font-medium text-slate-400 mt-1 italic line-clamp-1"><?php echo e($transaccion->descripcion ?? 'Sin glosa descriptiva'); ?></div>
                                </td>
                                <td class="px-10 py-8 text-center">
                                    <span class="inline-flex px-5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest
                                        <?php echo e($transaccion->tipo === 'ingreso' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-500 border border-rose-100'); ?>">
                                        <?php echo e($transaccion->tipo); ?>

                                    </span>
                                </td>
                                <td class="px-10 py-8 text-center font-black text-lg <?php echo e($transaccion->tipo === 'ingreso' ? 'text-emerald-600' : 'text-rose-500'); ?>">
                                    <?php echo e($transaccion->tipo === 'ingreso' ? '+' : '-'); ?>$<?php echo e(number_format($transaccion->monto, 2)); ?>

                                </td>
                                <td class="px-10 py-8 text-center text-xs font-black text-slate-400 tracking-tighter">
                                    <?php echo e($transaccion->fecha->format('d . M . Y')); ?>

                                </td>
                                <td class="px-10 py-8 text-right space-x-4">
                                    <button wire:click="editar(<?php echo e($transaccion->id); ?>)" class="p-4 bg-blue-50 text-blue-600 rounded-2xl hover:bg-blue-600 hover:text-white transition-all active:scale-90">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>
                                    <button wire:click="eliminar(<?php echo e($transaccion->id); ?>)" wire:confirm="¿Desea purgar este registro de capital?" class="p-4 bg-rose-50 text-rose-400 rounded-2xl hover:bg-rose-600 hover:text-white transition-all active:scale-90">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-10 py-32 text-center">
                                    <div class="text-8xl mb-6 opacity-10">💰</div>
                                    <h3 class="text-2xl font-black text-slate-300 uppercase tracking-widest">Sin registros de capital</h3>
                                    <p class="text-slate-400 mt-2 font-medium">Inicie la gestión financiera mediante un nuevo registro.</p>
                                </td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-12 px-10">
            <?php echo e($transacciones->links()); ?>

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
</div><?php /**PATH C:\Users\EMANUELCLEMENTEMARTI\Downloads\intelligent-personal-management-panel\resources\views/livewire/finanzas/finanzas.blade.php ENDPATH**/ ?>