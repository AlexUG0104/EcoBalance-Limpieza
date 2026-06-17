@extends('layouts.app')
@section('title', 'Monitoreo Administrativo - EcoBalance')
@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Panel de Monitoreo Bodycam</h1>
        <p class="text-slate-500 text-sm mt-1">Control en tiempo real de cámaras corporales activas y registros históricos.</p>
    </div>

    <!-- Active Sessions -->
    <div>
        <div class="flex items-center gap-3 mb-4">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-bold bg-red-100 text-red-700">
                <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                Servicios Activos — {{ $serviciosActivos->count() }} en vivo
            </span>
        </div>

        @if($serviciosActivos->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 text-center">
                <div class="h-16 w-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-video-slash text-slate-300 text-2xl"></i>
                </div>
                <h4 class="font-semibold text-slate-600">Sin sesiones activas</h4>
                <p class="text-slate-400 text-sm mt-1">No hay servicios en proceso ni en camino en este momento.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($serviciosActivos as $servicio)
                <div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden">
                    <!-- Header with live indicator -->
                    <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-5 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold {{ $servicio->estado === 'En proceso' ? 'bg-red-500 text-white' : 'bg-amber-500 text-white' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $servicio->estado === 'En proceso' ? 'bg-white animate-ping' : 'bg-white' }}"></span>
                                {{ $servicio->estado === 'En proceso' ? 'LIVE' : 'EN CAMINO' }}
                            </span>
                            <span class="text-white/70 text-xs">Servicio #{{ str_pad($servicio->id,4,'0',STR_PAD_LEFT) }}</span>
                        </div>
                        <span class="text-slate-400 text-xs">{{ $servicio->monitoreo ? 'Cámara: '.$servicio->monitoreo->estado_camara : 'Sin cam.' }}</span>
                    </div>

                    <div class="p-5 space-y-3">
                        <!-- Employee -->
                        @if($servicio->empleado)
                        <div class="flex items-center gap-3">
                            <img src="{{ $servicio->empleado->foto }}" class="h-10 w-10 rounded-xl object-cover border-2 border-ecogreen/20" onerror="this.src='https://placehold.co/40x40?text=?'">
                            <div>
                                <p class="font-bold text-slate-800 text-sm">{{ $servicio->empleado->nombre }}</p>
                                <p class="text-xs text-ecogreen font-medium">Especialista en servicio</p>
                            </div>
                        </div>
                        @endif

                        <!-- Details -->
                        <div class="text-xs text-slate-600 space-y-1.5">
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-user text-slate-400 mt-0.5 w-3"></i>
                                <span class="font-medium">{{ $servicio->cliente->nombre ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-broom text-slate-400 mt-0.5 w-3"></i>
                                <span>{{ $servicio->tipo_servicio }}</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-calendar text-slate-400 mt-0.5 w-3"></i>
                                <span>{{ \Carbon\Carbon::parse($servicio->fecha)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($servicio->hora)->format('H:i') }}</span>
                            </div>
                            @if($servicio->monitoreo && $servicio->monitoreo->hora_inicio)
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-clock text-slate-400 mt-0.5 w-3"></i>
                                <span>Inicio grabación: {{ \Carbon\Carbon::parse($servicio->monitoreo->hora_inicio)->format('H:i') }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Video (if in process) -->
                        @if($servicio->monitoreo && $servicio->monitoreo->video_path && $servicio->estado === 'En proceso')
                        <div class="rounded-xl overflow-hidden bg-black mt-2 shadow">
                            <video controls class="w-full max-h-36" preload="metadata">
                                <source src="{{ $servicio->monitoreo->video_path }}" type="video/mp4">
                            </video>
                        </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex gap-2 pt-1">
                            <a href="{{ route('admin.servicios.detail', $servicio->id) }}" class="flex-1 flex items-center justify-center gap-1.5 bg-ecogreen-light text-ecogreen hover:bg-ecogreen hover:text-white font-semibold px-3 py-2 rounded-lg text-xs transition-all">
                                <i class="fa-solid fa-eye"></i> Ver Completo
                            </a>
                            <a href="{{ route('admin.servicios.edit', $servicio->id) }}" class="flex items-center gap-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white font-semibold px-3 py-2 rounded-lg text-xs transition-all">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Historical Records -->
    <div>
        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left text-ecogray"></i>
            Registros Históricos (Últimos 10)
        </h2>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                        <th class="text-left px-5 py-4 font-semibold">#</th>
                        <th class="text-left px-5 py-4 font-semibold">Empleado</th>
                        <th class="text-left px-5 py-4 font-semibold">Cliente</th>
                        <th class="text-left px-5 py-4 font-semibold">Tipo de Servicio</th>
                        <th class="text-left px-5 py-4 font-semibold">Fecha</th>
                        <th class="text-left px-5 py-4 font-semibold">Cámara</th>
                        <th class="text-left px-5 py-4 font-semibold">Video</th>
                        <th class="text-left px-5 py-4 font-semibold">Acción</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($serviciosFinalizados as $servicio)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-3 text-slate-400 font-bold text-xs">#{{ str_pad($servicio->id,4,'0',STR_PAD_LEFT) }}</td>
                            <td class="px-5 py-3">
                                @if($servicio->empleado)
                                <div class="flex items-center gap-2">
                                    <img src="{{ $servicio->empleado->foto }}" class="h-7 w-7 rounded-lg object-cover" onerror="this.src='https://placehold.co/28x28?text=?'">
                                    <span class="text-xs font-medium text-slate-700">{{ $servicio->empleado->nombre }}</span>
                                </div>
                                @else <span class="text-slate-400 text-xs">N/A</span>@endif
                            </td>
                            <td class="px-5 py-3 text-xs text-slate-700 font-medium">{{ $servicio->cliente->nombre ?? 'N/A' }}</td>
                            <td class="px-5 py-3 text-xs text-slate-600">{{ $servicio->tipo_servicio }}</td>
                            <td class="px-5 py-3 text-xs text-slate-600">{{ \Carbon\Carbon::parse($servicio->fecha)->format('d/m/Y') }}</td>
                            <td class="px-5 py-3">
                                @if($servicio->monitoreo)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        {{ $servicio->monitoreo->estado_camara }}
                                    </span>
                                @else <span class="text-slate-300 text-xs">—</span>@endif
                            </td>
                            <td class="px-5 py-3">
                                @if($servicio->monitoreo && $servicio->monitoreo->video_path)
                                    <span class="inline-flex items-center gap-1 text-ecoblue text-xs font-semibold"><i class="fa-solid fa-video"></i> {{ $servicio->monitoreo->duracion }}</span>
                                @else
                                    <span class="text-slate-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <a href="{{ route('admin.servicios.detail', $servicio->id) }}" class="inline-flex items-center gap-1 bg-ecogreen-light text-ecogreen hover:bg-ecogreen hover:text-white font-semibold px-3 py-1.5 rounded-lg text-xs transition-all">
                                    <i class="fa-solid fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
