<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - EcoBalance Limpieza S.A.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        primary: '#1F8F4E',
                        ecogreen: { DEFAULT: '#1F8F4E', dark: '#166E3A', light: '#E9F8EF' },
                        ecoblue: '#2F8DE4',
                        ecogray: '#5F6B66',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .eco-bg {
            background: linear-gradient(135deg, #0d5c30 0%, #1F8F4E 40%, #2F8DE4 100%);
        }
        .floating-leaf {
            position: absolute;
            opacity: 0.08;
            animation: float 8s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }
        .card-shadow { box-shadow: 0 25px 60px rgba(0,0,0,0.18), 0 8px 25px rgba(0,0,0,0.10); }
        .input-focus:focus { outline: none; border-color: #1F8F4E; box-shadow: 0 0 0 3px rgba(31,143,78,0.12); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center eco-bg relative overflow-hidden">

    <!-- Decorative elements -->
    <i class="fa-solid fa-leaf floating-leaf text-white text-9xl" style="top:5%;left:3%;animation-delay:0s;"></i>
    <i class="fa-solid fa-seedling floating-leaf text-white text-7xl" style="top:15%;right:5%;animation-delay:2s;"></i>
    <i class="fa-solid fa-tree floating-leaf text-white text-8xl" style="bottom:10%;left:8%;animation-delay:4s;"></i>
    <i class="fa-solid fa-wind floating-leaf text-white text-6xl" style="bottom:20%;right:10%;animation-delay:1s;"></i>
    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black/20"></div>

    <!-- LOGIN CARD -->
    <div class="relative z-10 w-full max-w-md mx-4">
        <div class="bg-white rounded-2xl card-shadow overflow-hidden">

            <!-- Card Header -->
            <div class="bg-gradient-to-r from-ecogreen-dark to-ecogreen px-8 pt-8 pb-10 text-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <i class="fa-solid fa-leaf text-white" style="font-size:200px;position:absolute;right:-30px;bottom:-40px;transform:rotate(-20deg)"></i>
                </div>
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm mb-4 shadow-lg overflow-hidden">
                        <img src="/images/logo.jpeg" alt="EcoBalance Logo" class="w-full h-full object-cover">
                    </div>
                    <h1 class="text-white font-bold text-2xl tracking-tight">EcoBalance Limpieza</h1>
                    <p class="text-white/80 text-sm font-medium mt-1">Tecnología · Sostenibilidad · Confianza</p>
                    <div class="flex items-center justify-center gap-4 mt-3">
                        <span class="inline-flex items-center gap-1 text-white/70 text-xs"><i class="fa-solid fa-video"></i> Monitoreo Live</span>
                        <span class="inline-flex items-center gap-1 text-white/70 text-xs"><i class="fa-solid fa-leaf"></i> Eco-Certificado</span>
                    </div>
                </div>
            </div>

            <!-- Login Form -->
            <div class="px-8 py-8">
                <h2 class="text-xl font-bold text-slate-800 text-center mb-1">Bienvenido de vuelta</h2>
                <p class="text-slate-500 text-sm text-center mb-6">Ingrese sus credenciales para acceder al portal</p>

                @if ($errors->any())
                    <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-sm text-red-700 font-medium">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            <i class="fa-solid fa-envelope text-ecogreen mr-1.5"></i>Correo Electrónico
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required autofocus
                            placeholder="ejemplo@correo.com"
                            class="input-focus w-full px-4 py-3 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-800 transition-all placeholder-slate-400"
                        >
                    </div>

                    <!-- Password Field -->
                    <div x-data="{ showPass: false }">
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            <i class="fa-solid fa-lock text-ecogreen mr-1.5"></i>Contraseña
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                :type="showPass ? 'text' : 'password'"
                                name="password"
                                required
                                placeholder="••••••••"
                                class="input-focus w-full px-4 py-3 pr-12 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-800 transition-all placeholder-slate-400"
                            >
                            <button type="button" @click="showPass = !showPass" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                                <i :class="showPass ? 'fa-eye-slash' : 'fa-eye'" class="fa-solid"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-ecogreen-dark to-ecogreen text-white font-bold py-3.5 rounded-xl hover:from-ecogreen hover:to-ecogreen-dark transition-all duration-300 shadow-lg hover:shadow-ecogreen/30 hover:shadow-xl flex items-center justify-center gap-2 text-sm mt-2"
                    >
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Ingresar al Portal
                    </button>
                </form>

                <!-- Demo credentials hint -->
                <div class="mt-6 p-4 bg-ecogreen-light rounded-xl border border-ecogreen/20">
                    <p class="text-xs font-bold text-ecogreen text-center mb-2.5"><i class="fa-solid fa-circle-info mr-1.5"></i>Credenciales de Demo</p>
                    <div class="grid grid-cols-2 gap-2 text-xs text-slate-600">
                        <div class="bg-white rounded-lg p-2 border border-ecogreen/15">
                            <p class="font-bold text-slate-700 mb-1">👔 Administrador</p>
                            <p class="font-mono text-slate-500">admin@ecobalance.com</p>
                            <p class="font-mono text-slate-500">123456</p>
                        </div>
                        <div class="bg-white rounded-lg p-2 border border-ecogreen/15">
                            <p class="font-bold text-slate-700 mb-1">🏠 Cliente</p>
                            <p class="font-mono text-slate-500">cliente@test.com</p>
                            <p class="font-mono text-slate-500">123456</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Footer -->
            <div class="px-8 pb-6 text-center">
                <p class="text-xs text-slate-400">
                    <i class="fa-solid fa-shield-halved text-ecogreen mr-1"></i>
                    Conexión protegida · EcoBalance Limpieza S.A. © 2026
                </p>
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
