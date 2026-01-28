<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Inteligente | Liquid Experience</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #f0f4f9;
        }
        /* Estilo Liquid Glass */
        .liquid-glass {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(25px) saturate(180%);
            -webkit-backdrop-filter: blur(25px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }
        /* Animación de esferas líquidas */
        .blob {
            position: absolute;
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, #60a5fa 0%, #a855f7 100%);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
            animation: move 20s infinite alternate;
        }
        @keyframes move {
            from { transform: translate(-10%, -10%) scale(1); }
            to { transform: translate(20%, 20%) scale(1.2); }
        }
    </style>
</head>
<body class="text-slate-900 overflow-x-hidden">

    <div class="blob opacity-30 top-[-10%] left-[-10%]"></div>
    <div class="blob opacity-20 bottom-[-10%] right-[-10%]" style="background: #34d399; animation-delay: -5s;"></div>

    <nav class="fixed top-4 left-1/2 -translate-x-1/2 w-[95%] max-w-7xl z-50 liquid-glass rounded-3xl px-6 h-16 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-tr from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <span class="font-extrabold text-xl tracking-tight hidden sm:block">Panel<span class="text-blue-600">Inteligente</span></span>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('login') }}" class="text-sm font-bold py-2.5 px-5 rounded-2xl hover:bg-white/40 transition-all active:scale-95">Entrar</a>
            <a href="{{ route('register') }}" class="text-sm font-bold bg-slate-900 text-white py-2.5 px-6 rounded-2xl hover:bg-slate-800 transition-all shadow-lg active:scale-95">Empezar</a>
        </div>
    </nav>

    <header class="relative pt-48 pb-24 px-6">
        <div class="max-w-5xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-white/50 border border-white/50 text-blue-700 text-xs font-bold uppercase tracking-widest mb-8 shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                </span>
                Próxima Generación
            </div>
            <h1 class="text-6xl md:text-8xl font-extrabold text-slate-900 tracking-tight mb-8 leading-[1.1]">
                Fluye con tu <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-purple-600 to-emerald-500">productividad.</span>
            </h1>
            <p class="text-xl md:text-2xl text-slate-600/80 mb-12 max-w-2xl mx-auto font-medium">
                La experiencia de gestión más fluida jamás creada. Minimalismo líquido para mentes imparables.
            </p>

            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                <a href="{{ route('register') }}" class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-indigo-600 hover:shadow-2xl hover:shadow-blue-500/40 text-white font-bold py-5 px-12 rounded-3xl transition-all hover:-translate-y-1 active:scale-95">
                    Crear mi Espacio
                </a>
            </div>
        </div>
    </header>

    <section id="features" class="max-w-7xl mx-auto px-6 py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="liquid-glass p-8 rounded-[2.5rem] hover:translate-y-[-8px] transition-all duration-500 group">
                <div class="w-14 h-14 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="text-2xl font-bold mb-3">Tareas</h3>
                <p class="text-slate-600 font-medium text-sm leading-relaxed">Priorización con algoritmos de enfoque profundo.</p>
            </div>

            <div class="liquid-glass p-8 rounded-[2.5rem] hover:translate-y-[-8px] transition-all duration-500 group">
                <div class="w-14 h-14 bg-purple-500/10 rounded-2xl flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-2xl font-bold mb-3">Hábitos</h3>
                <p class="text-slate-600 font-medium text-sm leading-relaxed">Rachas visuales impulsadas por consistencia orgánica.</p>
            </div>

            <div class="liquid-glass p-8 rounded-[2.5rem] hover:translate-y-[-8px] transition-all duration-500 group">
                <div class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-2xl font-bold mb-3">Finanzas</h3>
                <p class="text-slate-600 font-medium text-sm leading-relaxed">Tus activos bajo control con claridad quirúrgica.</p>
            </div>

            <div class="liquid-glass p-8 rounded-[2.5rem] hover:translate-y-[-8px] transition-all duration-500 group">
                <div class="w-14 h-14 bg-orange-500/10 rounded-2xl flex items-center justify-center text-orange-600 mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h3 class="text-2xl font-bold mb-3">Notas</h3>
                <p class="text-slate-600 font-medium text-sm leading-relaxed">Captura el pensamiento antes de que se evapore.</p>
            </div>
        </div>
    </section>

    <section class="max-w-4xl mx-auto px-6 py-20 text-center">
        <h4 class="text-slate-400 font-bold uppercase tracking-widest text-xs mb-10">Powering your performance</h4>
        <div class="flex flex-wrap justify-center gap-10 opacity-40">
            <span class="text-2xl font-extrabold tracking-tighter italic">LARAVEL 11</span>
            <span class="text-2xl font-extrabold tracking-tighter italic">LIVEWIRE 3</span>
            <span class="text-2xl font-extrabold tracking-tighter italic">SUPABASE</span>
            <span class="text-2xl font-extrabold tracking-tighter italic">TAILWIND</span>
        </div>
    </section>

    <footer class="py-12 text-center text-slate-400 font-medium text-sm border-t border-slate-200/50">
        <p>© 2026 Panel Inteligente. Nicaragua para el mundo.</p>
    </footer>

</body>
</html>