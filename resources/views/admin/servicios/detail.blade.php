@extends('layouts.app')
@section('title', 'Detalle Servicio Admin - EcoBalance')
@section('content')
@php
$estadoClasses = ['Pendiente'=>'bg-slate-100 text-slate-600','Asignado'=>'bg-blue-100 text-blue-700','En camino'=>'bg-amber-100 text-amber-700','En proceso'=>'bg-orange-100 text-orange-700','Finalizado'=>'bg-ecogreen-light text-ecogreen'];
$class = $estadoClasses[$servicio->estado] ?? 'bg-slate-100 text-slate-600';
@endphp
<div class="space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.servicios.index') }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 shadow-sm"><i class="fa-solid fa-arrow-left text-sm"></i></a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Servicio #{{ str_pad($servicio->id,4,'0',STR_PAD_LEFT) }}</h1>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $class }} mt-1">{{ $servicio->estado }}</span>
        </div>
        <div class="ml-auto">
            <a href="{{ route('admin.servicios.edit', $servicio->id) }}" class="inline-flex items-center gap-2 bg-ecoblue text-white font-semibold px-4 py-2 rounded-xl text-sm hover:bg-ecoblue-dark transition-all">
                <i class="fa-solid fa-pen-to-square"></i> Editar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-5">
            <!-- Info Grid -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h2 class="font-bold text-slate-800 mb-4">Información del Servicio</h2>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-slate-50 rounded-xl p-3">
                        <dt class="text-xs font-semibold text-slate-400 uppercase">Tipo</dt>
                        <dd class="font-semibold text-slate-800 mt-0.5">{{ $servicio->tipo_servicio }}</dd>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3">
                        <dt class="text-xs font-semibold text-slate-400 uppercase">Fecha / Hora</dt>
                        <dd class="font-semibold text-slate-800 mt-0.5">{{ \Carbon\Carbon::parse($servicio->fecha)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($servicio->hora)->format('H:i') }}</dd>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 col-span-2">
                        <dt class="text-xs font-semibold text-slate-400 uppercase">Dirección</dt>
                        <dd class="font-semibold text-slate-800 mt-0.5">{{ $servicio->direccion }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Monitoring -->
            @if($servicio->monitoreo)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h2 class="font-bold text-slate-800 mb-4 flex items-center gap-2"><i class="fa-solid fa-video text-ecoblue"></i> Monitoreo de Cámara</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center text-xs mb-4">
                    @foreach(['Estado Cámara' => $servicio->monitoreo->estado_camara, 'Hora Inicio' => $servicio->monitoreo->hora_inicio ? \Carbon\Carbon::parse($servicio->monitoreo->hora_inicio)->format('H:i') : '—', 'Hora Final' => $servicio->monitoreo->hora_final ? \Carbon\Carbon::parse($servicio->monitoreo->hora_final)->format('H:i') : '—', 'Duración' => $servicio->monitoreo->duracion ?? '—'] as $label => $val)
                    <div class="bg-slate-50 rounded-xl p-3"><p class="text-slate-400 font-semibold mb-1">{{ $label }}</p><p class="font-bold text-slate-800 text-sm">{{ $val }}</p></div>
                    @endforeach
                </div>
                @if($servicio->monitoreo->video_path)
                <div class="rounded-xl overflow-hidden bg-black shadow-lg">
                    <video controls class="w-full max-h-60" preload="metadata">
                        <source src="{{ $servicio->monitoreo->video_path }}" type="video/mp4">
                    </video>
                </div>
                @endif
            </div>
            @endif

            <!-- Evidences -->
            @if($servicio->evidencia)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h2 class="font-bold text-slate-800 mb-4 flex items-center gap-2"><i class="fa-solid fa-camera text-purple-500"></i> Evidencias Fotográficas</h2>
                <div class="grid grid-cols-3 gap-3">
                    @foreach(['antes_img' => 'Antes', 'durante_img' => 'Durante', 'despues_img' => 'Después'] as $field => $label)
                    <div>
                        <p class="text-xs font-semibold text-slate-500 text-center mb-1">{{ $label }}</p>
                        @if($servicio->evidencia->$field)
                            <img src="{{ $servicio->evidencia->$field }}" class="w-full h-28 object-cover rounded-xl border border-slate-100 shadow-sm cursor-pointer hover:scale-105 transition-transform" onclick="document.getElementById('img-modal').src=this.src;document.getElementById('modal-overlay').classList.remove('hidden')">
                        @else
                            <div class="w-full h-28 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-center text-slate-300 text-xs">N/A</div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Rating -->
            @if($servicio->comentario)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h2 class="font-bold text-slate-800 mb-3 flex items-center gap-2"><i class="fa-solid fa-star text-amber-400"></i> Calificación del Cliente</h2>
                <div class="flex gap-1 mb-2">
                    @for($i=1;$i<=5;$i++)<i class="fa-solid fa-star text-lg {{ $i <= $servicio->comentario->estrellas ? 'text-amber-400' : 'text-slate-200' }}"></i>@endfor
                    <span class="text-lg font-bold text-slate-700 ml-2">{{ $servicio->comentario->estrellas }}/5</span>
                </div>
                @if($servicio->comentario->comentario)
                    <p class="text-slate-600 italic bg-slate-50 p-3 rounded-xl text-sm">"{{ $servicio->comentario->comentario }}"</p>
                @endif
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-5">
            <!-- Client -->
            @if($servicio->cliente)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <h3 class="font-bold text-slate-700 mb-3 text-sm flex items-center gap-2"><i class="fa-solid fa-user text-ecoblue"></i> Cliente</h3>
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-10 w-10 rounded-xl bg-ecoblue/10 text-ecoblue font-bold flex items-center justify-center">{{ strtoupper(substr($servicio->cliente->nombre,0,2)) }}</div>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm">{{ $servicio->cliente->nombre }}</p>
                        <p class="text-xs text-slate-400">{{ $servicio->cliente->correo }}</p>
                    </div>
                </div>
                <div class="space-y-1 text-xs text-slate-600">
                    <p><i class="fa-solid fa-phone text-slate-400 mr-2"></i>{{ $servicio->cliente->telefono }}</p>
                    <p class="truncate"><i class="fa-solid fa-location-dot text-slate-400 mr-2"></i>{{ $servicio->cliente->direccion }}</p>
                </div>
            </div>
            @endif

            <!-- Employee -->
            @if($servicio->empleado)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="bg-ecogreen-light px-5 py-4 text-center">
                    <img src="{{ $servicio->empleado->foto }}" class="h-16 w-16 rounded-xl object-cover border-4 border-white shadow mx-auto" onerror="this.src='https://placehold.co/64x64?text=?'">
                    <h3 class="font-bold text-slate-800 text-sm mt-2">{{ $servicio->empleado->nombre }}</h3>
                    <div class="flex items-center justify-center gap-0.5 mt-1">
                        @for($i=1;$i<=5;$i++)<i class="fa-solid fa-star text-xs {{ $i <= round($servicio->empleado->calificacion) ? 'text-amber-400' : 'text-slate-200' }}"></i>@endfor
                    </div>
                </div>
                <div class="p-4 text-xs text-slate-600 space-y-1">
                    <p class="font-medium text-slate-500">{{ $servicio->empleado->experiencia }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div id="modal-overlay" class="hidden fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4" onclick="this.classList.add('hidden')">
    <img id="img-modal" src="" alt="Evidencia" class="max-w-full max-h-[90vh] rounded-xl shadow-2xl">
</div>
@endsection
