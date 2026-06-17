@extends('layouts.app')
@section('title', 'Gestión de Servicios - EcoBalance')
@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Gestión de Servicios</h1>
        <p class="text-slate-500 text-sm mt-1">{{ $servicios->count() }} servicios en total. Administre asignaciones y estados.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                    <th class="text-left px-5 py-4 font-semibold">#</th>
                    <th class="text-left px-5 py-4 font-semibold">Cliente</th>
                    <th class="text-left px-5 py-4 font-semibold">Servicio</th>
                    <th class="text-left px-5 py-4 font-semibold">Fecha</th>
                    <th class="text-left px-5 py-4 font-semibold">Empleado</th>
                    <th class="text-left px-5 py-4 font-semibold">Estado</th>
                    <th class="text-left px-5 py-4 font-semibold">Acciones</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($servicios as $servicio)
                    @php
                        $estadoClasses = ['Pendiente'=>'bg-slate-100 text-slate-600','Asignado'=>'bg-blue-100 text-blue-700','En camino'=>'bg-amber-100 text-amber-700','En proceso'=>'bg-orange-100 text-orange-700','Finalizado'=>'bg-ecogreen-light text-ecogreen'];
                        $class = $estadoClasses[$servicio->estado] ?? 'bg-slate-100 text-slate-600';
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-4 text-slate-400 font-bold text-xs">#{{ str_pad($servicio->id,4,'0',STR_PAD_LEFT) }}</td>
                        <td class="px-5 py-4">
                            <p class="font-semibold text-slate-800 text-xs">{{ $servicio->cliente->nombre ?? 'N/A' }}</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $servicio->cliente->telefono ?? '' }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-semibold text-slate-700 text-xs">{{ $servicio->tipo_servicio }}</span>
                        </td>
                        <td class="px-5 py-4 text-slate-600 text-xs">
                            {{ \Carbon\Carbon::parse($servicio->fecha)->format('d/m/Y') }}<br>
                            <span class="text-slate-400">{{ \Carbon\Carbon::parse($servicio->hora)->format('H:i') }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @if($servicio->empleado)
                                <div class="flex items-center gap-1.5">
                                    <img src="{{ $servicio->empleado->foto }}" class="h-6 w-6 rounded-full object-cover border border-slate-200" onerror="this.src='https://placehold.co/24x24?text=?'">
                                    <span class="text-xs text-slate-700 font-medium">{{ $servicio->empleado->nombre }}</span>
                                </div>
                            @else
                                <span class="text-slate-400 text-xs italic">Sin asignar</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $class }}">
                                {{ $servicio->estado }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.servicios.detail', $servicio->id) }}" class="p-1.5 bg-ecogreen-light text-ecogreen hover:bg-ecogreen hover:text-white rounded-lg text-xs transition-all" title="Ver detalle">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.servicios.edit', $servicio->id) }}" class="p-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white rounded-lg text-xs transition-all" title="Editar">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
