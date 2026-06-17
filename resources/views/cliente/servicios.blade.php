@extends('layouts.app')
@section('title', 'Mis Servicios - EcoBalance')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Mis Servicios</h1>
            <p class="text-slate-500 text-sm mt-1">Historial completo de todos sus servicios contratados.</p>
        </div>
        <a href="{{ route('cliente.solicitar') }}" class="inline-flex items-center gap-2 bg-ecogreen text-white font-semibold px-5 py-2.5 rounded-xl shadow-md hover:bg-ecogreen-dark transition-all text-sm">
            <i class="fa-solid fa-plus"></i>
            Nuevo Servicio
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        @if($servicios->isEmpty())
            <div class="p-16 text-center">
                <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-5">
                    <i class="fa-solid fa-clipboard-list text-slate-300 text-3xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-slate-700 mb-2">No tiene servicios registrados</h4>
                <p class="text-slate-400 text-sm mb-6 max-w-sm mx-auto">Solicite su primer servicio de limpieza ecológica profesional.</p>
                <a href="{{ route('cliente.solicitar') }}" class="inline-flex items-center gap-2 bg-ecogreen text-white font-semibold px-6 py-2.5 rounded-xl text-sm hover:bg-ecogreen-dark transition-all">
                    <i class="fa-solid fa-calendar-plus"></i> Solicitar ahora
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                            <th class="text-left px-6 py-4 font-semibold">#</th>
                            <th class="text-left px-6 py-4 font-semibold">Servicio</th>
                            <th class="text-left px-6 py-4 font-semibold">Fecha / Hora</th>
                            <th class="text-left px-6 py-4 font-semibold">Empleado Asignado</th>
                            <th class="text-left px-6 py-4 font-semibold">Estado</th>
                            <th class="text-left px-6 py-4 font-semibold">Monitoreo</th>
                            <th class="text-left px-6 py-4 font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($servicios as $servicio)
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
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-400 text-xs">#{{ str_pad($servicio->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="h-9 w-9 rounded-xl bg-ecogreen-light flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-broom text-ecogreen text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $servicio->tipo_servicio }}</p>
                                        <p class="text-xs text-slate-400 mt-0.5 truncate max-w-[180px]">{{ $servicio->direccion }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($servicio->fecha)->format('d/m/Y') }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($servicio->hora)->format('H:i') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($servicio->empleado)
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $servicio->empleado->foto }}" class="h-8 w-8 rounded-full object-cover border-2 border-ecogreen/20" onerror="this.src='https://placehold.co/30x30?text=?'">
                                        <div>
                                            <p class="font-medium text-slate-700 text-xs">{{ $servicio->empleado->nombre }}</p>
                                            <div class="flex items-center gap-0.5 mt-0.5">
                                                @for($i=1;$i<=5;$i++)
                                                    <i class="fa-solid fa-star text-[8px] {{ $i <= round($servicio->empleado->calificacion) ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-400 text-xs italic">Por asignar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $class }}">
                                    <i class="fa-solid {{ $icon }} text-[10px]"></i>
                                    {{ $servicio->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if(in_array($servicio->estado, ['En proceso', 'Finalizado']))
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $servicio->estado === 'En proceso' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-600' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $servicio->estado === 'En proceso' ? 'bg-red-500 animate-ping' : 'bg-slate-400' }}"></span>
                                        {{ $servicio->estado === 'En proceso' ? 'LIVE' : 'Grabado' }}
                                    </span>
                                @else
                                    <span class="text-slate-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('cliente.detalle', $servicio->id) }}" class="inline-flex items-center gap-1.5 bg-ecogreen-light text-ecogreen font-semibold px-3 py-1.5 rounded-lg text-xs hover:bg-ecogreen hover:text-white transition-all">
                                    <i class="fa-solid fa-eye"></i> Detalles
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
