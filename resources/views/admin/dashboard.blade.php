@extends('layouts.app')
@section('title', 'Dashboard Admin - EcoBalance')

@section('content')
<div class="space-y-8">

    <div>
        <h1 class="text-2xl font-bold text-slate-800">Panel Administrativo</h1>
        <p class="text-slate-500 text-sm mt-1">Bienvenido al sistema de gestión de EcoBalance Limpieza S.A.</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        @php
        $kpis = [
            ['label' => 'Clientes', 'value' => $totalClientes, 'icon' => 'fa-users', 'color' => 'bg-blue-50 text-blue-600', 'route' => 'admin.clientes.index'],
            ['label' => 'Empleados Activos', 'value' => $empleadosActivos, 'icon' => 'fa-id-card', 'color' => 'bg-ecogreen-light text-ecogreen', 'route' => 'admin.empleados.index'],
            ['label' => 'Servicios Activos', 'value' => $serviciosActivos, 'icon' => 'fa-broom', 'color' => 'bg-amber-50 text-amber-600', 'route' => 'admin.servicios.index'],
            ['label' => 'Completados', 'value' => $serviciosCompletados, 'icon' => 'fa-circle-check', 'color' => 'bg-purple-50 text-purple-600', 'route' => 'admin.servicios.index'],
            ['label' => 'Ingresos (CRC)', 'value' => '₡' . number_format($ingresosSimulados, 0, ',', '.'), 'icon' => 'fa-coins', 'color' => 'bg-emerald-50 text-emerald-600', 'route' => null],
        ];
        @endphp

        @foreach($kpis as $kpi)
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-all {{ $kpi['route'] ? 'cursor-pointer' : '' }}" {{ $kpi['route'] ? 'onclick="location.href=\''.route($kpi['route']).'\'"' : '' }}>
            <div class="h-10 w-10 rounded-xl {{ $kpi['color'] }} flex items-center justify-center mb-3 shadow-sm">
                <i class="fa-solid {{ $kpi['icon'] }} text-base"></i>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $kpi['value'] }}</p>
            <p class="text-xs text-slate-500 font-medium mt-1">{{ $kpi['label'] }}</p>
        </div>
        @endforeach
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Services by Month -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-bold text-slate-800 mb-1">Servicios por Mes</h3>
            <p class="text-slate-500 text-xs mb-5">Distribución de servicios durante el año</p>
            @if(!empty($serviciosPorMes))
            <div class="space-y-2">
                @php $maxVal = max(array_values($serviciosPorMes)); @endphp
                @foreach($serviciosPorMes as $mes => $total)
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold text-slate-500 w-8 text-right shrink-0">{{ $mes }}</span>
                    <div class="flex-1 h-7 bg-slate-100 rounded-lg overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-ecogreen to-ecoblue rounded-lg flex items-center justify-end pr-2 transition-all duration-700"
                             style="width: {{ ($maxVal > 0) ? ($total / $maxVal * 100) : 0 }}%">
                            <span class="text-white text-xs font-bold">{{ $total }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-slate-400 text-sm text-center py-8">No hay datos disponibles.</p>
            @endif
        </div>

        <!-- Service Types Donut -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-bold text-slate-800 mb-1">Servicios Más Solicitados</h3>
            <p class="text-slate-500 text-xs mb-5">Por tipo de limpieza</p>
            @php
            $colors = ['bg-ecogreen', 'bg-ecoblue', 'bg-amber-500', 'bg-purple-500', 'bg-pink-500'];
            $totalSvc = $serviciosMasSolicitados->sum('total');
            @endphp
            <div class="space-y-3">
                @foreach($serviciosMasSolicitados as $idx => $tipo)
                @php $pct = $totalSvc > 0 ? round($tipo->total / $totalSvc * 100) : 0; @endphp
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full {{ $colors[$idx % count($colors)] }} inline-block"></span>
                            <span class="text-sm font-medium text-slate-700">{{ $tipo->tipo_servicio }}</span>
                        </div>
                        <span class="text-xs font-bold text-slate-500">{{ $tipo->total }} ({{ $pct }}%)</span>
                    </div>
                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $colors[$idx % count($colors)] }} rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Top Employees -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-bold text-slate-800 mb-1">Empleados Mejor Calificados</h3>
            <p class="text-slate-500 text-xs mb-5">Top 5 basado en calificaciones de clientes</p>
            <div class="space-y-3">
                @foreach($empleadosCalificados as $idx => $emp)
                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-all">
                    <div class="relative">
                        <img src="{{ $emp->foto }}" class="h-10 w-10 rounded-xl object-cover border-2 border-slate-100" onerror="this.src='https://placehold.co/40x40?text=?'">
                        @if($idx < 3)
                        <span class="absolute -top-1.5 -right-1.5 h-5 w-5 rounded-full flex items-center justify-center text-[10px] font-bold text-white {{ ['bg-amber-400','bg-slate-400','bg-amber-700'][$idx] }} shadow">{{ $idx+1 }}</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-slate-800 text-sm">{{ $emp->nombre }}</p>
                        <div class="flex items-center gap-0.5 mt-0.5">
                            @for($i=1;$i<=5;$i++)
                                <i class="fa-solid fa-star text-[10px] {{ $i <= round($emp->calificacion) ? 'text-amber-400' : 'text-slate-200' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <span class="text-lg font-bold text-ecogreen">{{ $emp->calificacion }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Clients -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-bold text-slate-800 mb-1">Clientes Frecuentes</h3>
            <p class="text-slate-500 text-xs mb-5">Clientes con más servicios contratados</p>
            <div class="space-y-3">
                @foreach($clientesFrecuentes as $idx => $cli)
                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-all">
                    <div class="h-10 w-10 rounded-xl bg-ecoblue/10 flex items-center justify-center text-ecoblue font-bold text-sm shrink-0">
                        {{ strtoupper(substr($cli->nombre, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-800 text-sm truncate">{{ $cli->nombre }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $cli->correo }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="font-bold text-slate-800">{{ $cli->servicios_count }}</p>
                        <p class="text-[10px] text-slate-400">servicios</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
