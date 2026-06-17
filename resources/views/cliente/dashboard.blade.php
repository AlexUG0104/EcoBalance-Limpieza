@extends('layouts.app')
@section('title', 'Mi Dashboard - EcoBalance')

@section('content')
<div class="space-y-8">

    <!-- Page Title -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Mi Dashboard</h1>
            <p class="text-slate-500 text-sm mt-1">Bienvenido, <strong>{{ Auth::user()->name }}</strong>. Aquí encontrará el resumen de sus servicios.</p>
        </div>
        <a href="{{ route('cliente.solicitar') }}" class="inline-flex items-center gap-2 bg-ecogreen text-white font-semibold px-5 py-2.5 rounded-xl shadow-md hover:bg-ecogreen-dark transition-all text-sm">
            <i class="fa-solid fa-plus"></i>
            Solicitar Servicio
        </a>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Servicios Contratados</p>
                    <p class="text-3xl font-bold text-slate-800 mt-2">{{ $totalContratados }}</p>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Total histórico</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-clipboard-list text-ecoblue text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Servicios Finalizados</p>
                    <p class="text-3xl font-bold text-ecogreen mt-2">{{ $totalFinalizados }}</p>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Completados con éxito</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-ecogreen-light flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-circle-check text-ecogreen text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">En Proceso / En Camino</p>
                    <p class="text-3xl font-bold text-amber-600 mt-2">{{ $totalEnProceso }}</p>
                    <p class="text-xs text-slate-400 mt-1 font-medium flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping inline-block"></span>
                        Activos ahora
                    </p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-amber-50 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-truck text-amber-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Calificación Promedio</p>
                    <p class="text-3xl font-bold text-amber-500 mt-2">{{ $calificacionPromedio }} <span class="text-lg">★</span></p>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Sobre 5.0 posibles</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-amber-50 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-star text-amber-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- EcoBalance Tech Banner -->
    <div class="bg-gradient-to-r from-ecogreen-dark via-ecogreen to-ecoblue rounded-2xl p-6 text-white relative overflow-hidden shadow-lg">
        <div class="absolute inset-0 opacity-10"><i class="fa-solid fa-leaf text-white" style="font-size:200px;position:absolute;right:-20px;bottom:-40px;transform:rotate(-15deg)"></i></div>
        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/20 text-white backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-300 animate-ping mr-1.5"></span>
                        MONITOREO ACTIVO
                    </span>
                </div>
                <h3 class="text-xl font-bold mb-1">Cámaras Corporales en Tiempo Real</h3>
                <p class="text-white/80 text-sm max-w-lg">EcoBalance es la primera empresa de limpieza en Costa Rica que utiliza <strong>bodycam verification</strong> — cada servicio es grabado y disponible para usted en tiempo real.</p>
            </div>
            <a href="{{ route('cliente.servicios') }}" class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 border border-white/30 text-white font-semibold px-5 py-2.5 rounded-xl backdrop-blur-sm transition-all text-sm whitespace-nowrap">
                <i class="fa-solid fa-video"></i>
                Ver Mis Videos
            </a>
        </div>
    </div>

    <!-- Últimos Servicios Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg text-slate-800">Últimos Servicios</h3>
                <p class="text-slate-500 text-sm">Historial reciente de sus contrataciones</p>
            </div>
            <a href="{{ route('cliente.servicios') }}" class="text-ecogreen font-semibold text-sm hover:underline flex items-center gap-1">
                Ver todos <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        @if($ultimosServicios->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-broom text-slate-300 text-2xl"></i>
                </div>
                <h4 class="text-slate-700 font-semibold mb-1">Aún no tiene servicios</h4>
                <p class="text-slate-400 text-sm mb-4">Solicite su primer servicio de limpieza ecológica.</p>
                <a href="{{ route('cliente.solicitar') }}" class="inline-flex items-center gap-2 bg-ecogreen text-white font-semibold px-5 py-2 rounded-xl text-sm hover:bg-ecogreen-dark transition-all">
                    <i class="fa-solid fa-plus"></i> Solicitar ahora
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                            <th class="text-left px-6 py-3 font-semibold">#ID</th>
                            <th class="text-left px-6 py-3 font-semibold">Tipo de Servicio</th>
                            <th class="text-left px-6 py-3 font-semibold">Fecha</th>
                            <th class="text-left px-6 py-3 font-semibold">Empleado</th>
                            <th class="text-left px-6 py-3 font-semibold">Estado</th>
                            <th class="text-left px-6 py-3 font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($ultimosServicios as $servicio)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-400">#{{ str_pad($servicio->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="h-8 w-8 rounded-lg bg-ecogreen-light flex items-center justify-center">
                                        <i class="fa-solid fa-broom text-ecogreen text-sm"></i>
                                    </div>
                                    <span class="font-semibold text-slate-700">{{ $servicio->tipo_servicio }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                <div>{{ \Carbon\Carbon::parse($servicio->fecha)->format('d/m/Y') }}</div>
                                <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($servicio->hora)->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($servicio->empleado)
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $servicio->empleado->foto }}" alt="{{ $servicio->empleado->nombre }}" class="h-7 w-7 rounded-full object-cover border-2 border-ecogreen/20" onerror="this.src='https://placehold.co/30x30?text=?'">
                                        <span class="font-medium text-slate-700 text-xs">{{ $servicio->empleado->nombre }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400 text-xs italic">Por asignar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $estadoClasses = [
                                        'Pendiente' => 'bg-slate-100 text-slate-600',
                                        'Asignado' => 'bg-blue-100 text-blue-700',
                                        'En camino' => 'bg-amber-100 text-amber-700',
                                        'En proceso' => 'bg-orange-100 text-orange-700',
                                        'Finalizado' => 'bg-ecogreen-light text-ecogreen',
                                    ];
                                    $estadoIcons = [
                                        'Pendiente' => 'fa-clock',
                                        'Asignado' => 'fa-user-check',
                                        'En camino' => 'fa-truck',
                                        'En proceso' => 'fa-broom',
                                        'Finalizado' => 'fa-circle-check',
                                    ];
                                    $class = $estadoClasses[$servicio->estado] ?? 'bg-slate-100 text-slate-600';
                                    $icon = $estadoIcons[$servicio->estado] ?? 'fa-circle';
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $class }}">
                                    <i class="fa-solid {{ $icon }} text-[10px]"></i>
                                    {{ $servicio->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('cliente.detalle', $servicio->id) }}" class="inline-flex items-center gap-1 text-ecogreen font-semibold text-xs hover:underline">
                                    <i class="fa-solid fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
