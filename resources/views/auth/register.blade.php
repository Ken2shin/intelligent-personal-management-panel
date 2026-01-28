<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Panel Inteligente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2 text-center">PIGP</h1>
            <p class="text-slate-600 text-center mb-8">Crear Nueva Cuenta</p>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-900 mb-1">Nombre</label>
                    <input 
                        id="name" 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                        required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

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

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-900 mb-1">Contraseña</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                        required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-900 mb-1">Confirmar Contraseña</label>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <button 
                    type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded-lg transition">
                    Registrarse
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-slate-600">
                <p>¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700 font-semibold">Inicia sesión</a></p>
            </div>
        </div>
    </div>
</body>
</html>
