<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Panel Inteligente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2 text-center">PIGP</h1>
            <p class="text-slate-600 text-center mb-8">Recuperar Contraseña</p>

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <p class="text-sm text-slate-600 mb-4">
                    Ingresa tu correo electrónico y te enviaremos un enlace para recuperar tu contraseña.
                </p>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-900 mb-1">Correo Electrónico</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                        required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button 
                    type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded-lg transition">
                    Enviar Enlace de Recuperación
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-slate-600">
                <p><a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700 font-semibold">Volver a iniciar sesión</a></p>
            </div>
        </div>
    </div>
</body>
</html>
