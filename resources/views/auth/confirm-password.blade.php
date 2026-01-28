<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Contraseña - Panel Inteligente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2 text-center">PIGP</h1>
            <p class="text-slate-600 text-center mb-8">Confirmar Contraseña</p>

            <p class="text-sm text-slate-600 mb-6">
                Por razones de seguridad, confirma tu contraseña para continuar.
            </p>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
                @csrf

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

                <button 
                    type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded-lg transition">
                    Confirmar
                </button>
            </form>
        </div>
    </div>
</body>
</html>
