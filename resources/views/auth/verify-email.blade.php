<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Correo - Panel Inteligente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2 text-center">PIGP</h1>
            <p class="text-slate-600 text-center mb-8">Verificar Correo Electrónico</p>

            @if (session('resent'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg">
                    Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
                </div>
            @endif

            <div class="mb-6 p-4 bg-blue-50 text-blue-700 rounded-lg text-sm">
                <p class="mb-2">Antes de continuar, verifica tu correo electrónico.</p>
                <p>Si no recibiste el correo, presiona el botón abajo.</p>
            </div>

            <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
                @csrf

                <button 
                    type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded-lg transition">
                    Reenviar Correo de Verificación
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-slate-600">
                <p>¿Quieres cambiar de correo?</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-blue-500 hover:text-blue-700 font-semibold">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
