<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EcoBalance Limpieza S.A.') - Monitoreo y Sostenibilidad</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="EcoBalance Limpieza S.A. combina sostenibilidad ambiental, monitoreo digital en vivo con cámaras corporales y transparencia ecológica en Costa Rica.">
    <meta name="author" content="EcoBalance Limpieza S.A.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#1F8F4E',
                        'ecogreen': {
                            DEFAULT: '#1F8F4E',
                            dark: '#166E3A',
                            light: '#E9F8EF',
                            hover: '#dcf5e6',
                        },
                        'ecoblue': {
                            DEFAULT: '#2F8DE4',
                            dark: '#1e75c6',
                            light: '#eaf4fd',
                        },
                        'ecogray': {
                            DEFAULT: '#5F6B66',
                            dark: '#4c5652',
                            light: '#f2f4f3',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        .sidebar-active {
            background-color: #E9F8EF;
            color: #1F8F4E;
            font-weight: 600;
            border-left: 4px solid #1F8F4E;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @yield('styles')
</head>
<body class="h-full text-slate-800 antialiased" x-data="{ mobileSidebar: false }">
    <div class="min-h-full flex flex-col md:flex-row">
        
        <!-- MOBILE HEADER -->
        <header class="md:hidden flex items-center justify-between px-4 py-3 bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-2">
                <img src="/images/logo.jpeg" alt="EcoBalance Logo" class="h-8 w-auto rounded-md object-contain">
                <span class="font-bold text-lg text-ecogreen tracking-tight">EcoBalance</span>
            </div>
            <button @click="mobileSidebar = true" class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-md focus:outline-none">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
        </header>

        <!-- SIDEBAR CONTAINER (DESKTOP & MOBILE WRAPPER) -->
        <!-- Mobile Background Overlay -->
        <div x-show="mobileSidebar" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileSidebar = false" 
             class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 md:hidden"></div>

        <!-- Sidebar Panel -->
        <aside :class="mobileSidebar ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
               class="fixed md:static inset-y-0 left-0 w-72 bg-white border-r border-slate-200 z-50 md:z-20 transform transition-transform duration-300 ease-in-out flex flex-col justify-between shadow-lg md:shadow-none h-screen sticky top-0">
            
            <div class="flex flex-col overflow-y-auto scrollbar-hide">
                <!-- Sidebar Header -->
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <img src="/images/logo.jpeg" alt="EcoBalance Logo" class="h-10 w-auto rounded-lg shadow-sm border border-slate-100">
                        <div>
                            <h1 class="font-bold text-lg text-ecogreen leading-tight">EcoBalance</h1>
                            <p class="text-xs text-ecogray font-medium">Limpieza Ecológica S.A.</p>
                        </div>
                    </a>
                    <button @click="mobileSidebar = false" class="md:hidden p-2 text-slate-400 hover:text-slate-600 rounded-md hover:bg-slate-100">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- User Profile Summary in Sidebar -->
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-ecogreen/10 text-ecogreen flex items-center justify-center font-bold shadow-inner">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm text-slate-800 leading-tight">{{ Auth::user()->name ?? 'Usuario Demo' }}</h4>
                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-medium {{ Auth::user()->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-ecogreen-light text-ecogreen' }}">
                                <i class="fa-solid {{ Auth::user()->isAdmin() ? 'fa-user-shield' : 'fa-handshake' }} mr-1"></i>
                                {{ Auth::user()->isAdmin() ? 'Administrador' : 'Cliente Sostenible' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="px-4 py-6 space-y-1">
                    @if(Auth::check() && Auth::user()->isAdmin())
                        <!-- ADMIN LINKS -->
                        <span class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Administración</span>
                        
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all {{ Route::is('admin.dashboard') ? 'sidebar-active' : '' }}">
                            <i class="fa-solid fa-chart-line text-lg w-5"></i>
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('admin.clientes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all {{ Route::is('admin.clientes.*') ? 'sidebar-active' : '' }}">
                            <i class="fa-solid fa-users text-lg w-5"></i>
                            <span>Clientes</span>
                        </a>

                        <a href="{{ route('admin.empleados.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all {{ Route::is('admin.empleados.*') ? 'sidebar-active' : '' }}">
                            <i class="fa-solid fa-id-card text-lg w-5"></i>
                            <span>Empleados</span>
                        </a>

                        <a href="{{ route('admin.servicios.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all {{ Route::is('admin.servicios.*') ? 'sidebar-active' : '' }}">
                            <i class="fa-solid fa-broom text-lg w-5"></i>
                            <span>Servicios</span>
                        </a>

                        <a href="{{ route('admin.monitoreo.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all {{ Route::is('admin.monitoreo.index') ? 'sidebar-active' : '' }}">
                            <i class="fa-solid fa-video text-lg w-5"></i>
                            <span>Monitoreo Live (Bodycams)</span>
                        </a>

                    @else
                        <!-- CLIENT LINKS -->
                        <span class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Portal Cliente</span>

                        <a href="{{ route('cliente.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all {{ Route::is('cliente.dashboard') ? 'sidebar-active' : '' }}">
                            <i class="fa-solid fa-chart-pie text-lg w-5"></i>
                            <span>Mi Dashboard</span>
                        </a>

                        <a href="{{ route('cliente.solicitar') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all {{ Route::is('cliente.solicitar') ? 'sidebar-active' : '' }}">
                            <i class="fa-solid fa-calendar-plus text-lg w-5"></i>
                            <span>Solicitar Servicio</span>
                        </a>

                        <a href="{{ route('cliente.servicios') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all {{ Route::is('cliente.servicios') || Route::is('cliente.detalle') ? 'sidebar-active' : '' }}">
                            <i class="fa-solid fa-clipboard-list text-lg w-5"></i>
                            <span>Mis Servicios</span>
                        </a>
                    @endif
                </nav>
            </div>

            <!-- Sidebar Footer & Logout -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-3 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 hover:bg-red-50 hover:text-red-600 hover:border-red-100 rounded-lg transition-all text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-red-200">
                        <i class="fa-solid fa-right-from-bracket text-base"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
                <div class="mt-4 text-center">
                    <p class="text-[10px] text-slate-400 font-medium">EcoBalance © 2026</p>
                    <p class="text-[8px] text-ecogreen font-semibold tracking-wide uppercase mt-0.5"><i class="fa-solid fa-leaf mr-1"></i>Tecnología Limpia</p>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT CONTENT AREA -->
        <main class="flex-1 flex flex-col overflow-hidden min-h-screen">
            
            <!-- TOP BAR (DESKTOP) -->
            <header class="hidden md:flex items-center justify-between px-8 py-4 bg-white border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                <div>
                    <h2 class="text-sm font-semibold text-slate-500">Costa Rica</h2>
                    <p class="text-xs text-slate-400 font-medium">{{ \Carbon\Carbon::now()->translatedFormat('d \d\e F, Y') }}</p>
                </div>
                <div class="flex items-center gap-6">
                    <!-- Tech badges -->
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-ping"></span>
                            Servidor Activo
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                            SQLite DB
                        </span>
                    </div>
                    
                    <div class="h-6 w-[1px] bg-slate-200"></div>

                    <!-- User details -->
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</span>
                        <div class="h-9 w-9 rounded-full bg-ecogreen text-white flex items-center justify-center font-bold shadow-md">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- VIEWPORT SCROLL WRAPPER -->
            <div class="flex-1 p-6 md:p-8 overflow-y-auto">
                
                <!-- FLASH ALERTS -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-3 text-emerald-800 shadow-sm animate-fade-in" x-data="{ show: true }" x-show="show">
                        <i class="fa-solid fa-circle-check text-xl text-emerald-500 mt-0.5"></i>
                        <div class="flex-1">
                            <h4 class="font-bold text-sm">Operación Exitosa</h4>
                            <p class="text-xs mt-0.5 font-medium">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 focus:outline-none">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3 text-red-800 shadow-sm animate-fade-in" x-data="{ show: true }" x-show="show">
                        <i class="fa-solid fa-circle-exclamation text-xl text-red-500 mt-0.5"></i>
                        <div class="flex-1">
                            <h4 class="font-bold text-sm">Atención</h4>
                            <p class="text-xs mt-0.5 font-medium">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="text-red-400 hover:text-red-600 focus:outline-none">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-950 shadow-sm animate-fade-in" x-data="{ show: true }" x-show="show">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-triangle-exclamation text-xl text-amber-600 mt-0.5"></i>
                            <div class="flex-1">
                                <h4 class="font-bold text-sm">Errores de Validación</h4>
                                <ul class="list-disc list-inside mt-1 text-xs space-y-0.5 font-medium text-amber-800">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button @click="show = false" class="text-amber-400 hover:text-amber-600 focus:outline-none">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- MAIN WORKSPACE -->
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </div>

            <!-- FOOTER -->
            <footer class="bg-white border-t border-slate-200 px-8 py-4 text-center text-xs text-slate-400 flex flex-col sm:flex-row justify-between items-center gap-2">
                <div>
                    <strong>EcoBalance Limpieza S.A.</strong> - Prototipo Corporativo 2026.
                </div>
                <div class="flex items-center gap-4 font-medium">
                    <span>Universidad de Costa Rica (Exposición Académica)</span>
                    <span class="text-slate-300">|</span>
                    <a href="#" class="text-ecogreen hover:underline"><i class="fa-solid fa-leaf mr-1"></i>Tecnología & Sostenibilidad</a>
                </div>
            </footer>
        </main>
    </div>
    @yield('scripts')
</body>
</html>
