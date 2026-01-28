<div class="min-h-screen bg-slate-50 dark:bg-slate-900 animate-pulse">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8">
            <div class="h-8 bg-slate-200 dark:bg-slate-700 rounded w-1/3 mb-2"></div>
            <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-1/4"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = range(1,4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6 h-32">
                    <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-1/2 mb-4"></div>
                    <div class="h-10 bg-slate-200 dark:bg-slate-700 rounded w-1/3"></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-lg h-96"></div>
            <div class="space-y-6">
                <div class="bg-slate-200 dark:bg-slate-700 rounded-lg h-40"></div>
                <div class="bg-white dark:bg-slate-800 rounded-lg h-60"></div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\EMANUELCLEMENTEMARTI\Downloads\intelligent-personal-management-panel\resources\views/livewire/dashboard/placeholder.blade.php ENDPATH**/ ?>