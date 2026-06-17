@extends('layouts.app')
@section('title', 'Detalle del Servicio #' . str_pad($servicio->id, 4, '0', STR_PAD_LEFT) . ' - EcoBalance')

@section('content')
@php
$estadoClasses = [
    'Pendiente' => 'bg-slate-100 text-slate-600',
    'Asignado' => 'bg-blue-100 text-blue-700',
    'En camino' => 'bg-amber-100 text-amber-700',
    'En proceso' => 'bg-orange-100 text-orange-700',
    'Finalizado' => 'bg-ecogreen-light text-ecogreen',
];
$estadoIcons = [
    'Pendiente' => 'fa-clock', 'Asignado' => 'fa-user-check',
    'En camino' => 'fa-truck', 'En proceso' => 'fa-broom', 'Finalizado' => 'fa-circle-check',
];
$class = $estadoClasses[$servicio->estado] ?? 'bg-slate-100 text-slate-600';
$icon = $estadoIcons[$servicio->estado] ?? 'fa-circle';
@endphp

<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('cliente.servicios') }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Servicio #{{ str_pad($servicio->id, 4, '0', STR_PAD_LEFT) }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-slate-500 text-sm">{{ $servicio->tipo_servicio }}</span>
                    <span class="text-slate-300">•</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $class }}">
                        <i class="fa-solid {{ $icon }} text-[10px]"></i> {{ $servicio->estado }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- LEFT COLUMN -->
        <div class="xl:col-span-2 space-y-6">

            <!-- Service Info Card -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h2 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-ecoblue"></i> Detalles del Servicio
                </h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="bg-slate-50 rounded-xl p-3">
                        <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Tipo</dt>
                        <dd class="font-semibold text-slate-800 mt-0.5">{{ $servicio->tipo_servicio }}</dd>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3">
                        <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Fecha y Hora</dt>
                        <dd class="font-semibold text-slate-800 mt-0.5">{{ \Carbon\Carbon::parse($servicio->fecha)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($servicio->hora)->format('H:i') }}</dd>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 sm:col-span-2">
                        <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Dirección</dt>
                        <dd class="font-semibold text-slate-800 mt-0.5">{{ $servicio->direccion }}</dd>
                    </div>
                    @if($servicio->comentarios_adicionales)
                    <div class="bg-amber-50 rounded-xl p-3 sm:col-span-2">
                        <dt class="text-xs font-semibold text-amber-600 uppercase tracking-wide flex items-center gap-1"><i class="fa-solid fa-comment-dots"></i> Notas Especiales</dt>
                        <dd class="text-slate-700 text-sm mt-0.5">{{ $servicio->comentarios_adicionales }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- MONITORING SECTION -->
            @if($servicio->monitoreo)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center">
                            <i class="fa-solid fa-video text-white text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-white font-bold">Monitoreo del Servicio</h2>
                            <p class="text-white/60 text-xs">Sistema de cámara corporal EcoBalance</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $servicio->monitoreo->estado_camara === 'Activa' ? 'bg-red-500 text-white' : 'bg-slate-600 text-slate-300' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $servicio->monitoreo->estado_camara === 'Activa' ? 'bg-white animate-ping' : 'bg-slate-400' }}"></span>
                        {{ $servicio->monitoreo->estado_camara === 'Activa' ? 'EN VIVO' : 'INACTIVA' }}
                    </span>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Monitoring details grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center text-sm">
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-xs text-slate-400 font-semibold">Estado Cámara</p>
                            <p class="font-bold text-slate-800 mt-1">{{ $servicio->monitoreo->estado_camara }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-xs text-slate-400 font-semibold">Hora Inicio</p>
                            <p class="font-bold text-slate-800 mt-1">{{ $servicio->monitoreo->hora_inicio ? \Carbon\Carbon::parse($servicio->monitoreo->hora_inicio)->format('H:i') : '—' }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-xs text-slate-400 font-semibold">Hora Final</p>
                            <p class="font-bold text-slate-800 mt-1">{{ $servicio->monitoreo->hora_final ? \Carbon\Carbon::parse($servicio->monitoreo->hora_final)->format('H:i') : '—' }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-xs text-slate-400 font-semibold">Duración</p>
                            <p class="font-bold text-slate-800 mt-1">{{ $servicio->monitoreo->duracion ?? '—' }}</p>
                        </div>
                    </div>

                    <!-- Video Player -->
                    @if($servicio->monitoreo->video_path)
                    <div>
                        <h3 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-film text-ecogreen"></i> Video Disponible
                            <span class="text-xs text-slate-400 font-normal">— Grabación de la cámara corporal</span>
                        </h3>
                        <div class="rounded-xl overflow-hidden bg-black shadow-lg">
                            <video controls class="w-full max-h-72" preload="metadata" poster="">
                                <source src="{{ $servicio->monitoreo->video_path }}" type="video/mp4">
                                <p class="text-white text-center p-4 text-sm">Su navegador no soporta video HTML5.</p>
                            </video>
                        </div>
                        <div class="flex items-center gap-4 mt-2 text-xs text-slate-500">
                            <span><i class="fa-solid fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($servicio->monitoreo->fecha)->format('d/m/Y') }}</span>
                            <span><i class="fa-solid fa-clock mr-1"></i>Duración: {{ $servicio->monitoreo->duracion }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- PHOTO EVIDENCES -->
            @if($servicio->evidencia)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h2 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-camera text-ecoblue"></i> Evidencias Fotográficas
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach(['antes_img' => ['Antes del Servicio', 'bg-slate-100 text-slate-600', 'fa-clock'], 'durante_img' => ['Durante el Servicio', 'bg-orange-100 text-orange-700', 'fa-broom'], 'despues_img' => ['Después del Servicio', 'bg-ecogreen-light text-ecogreen', 'fa-circle-check']] as $field => [$label, $badgeClass, $badgeIcon])
                        <div class="space-y-2">
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                    <i class="fa-solid {{ $badgeIcon }} text-[10px]"></i> {{ $label }}
                                </span>
                            </div>
                            @if($servicio->evidencia->$field)
                                <img src="{{ $servicio->evidencia->$field }}" alt="{{ $label }}" class="w-full h-40 object-cover rounded-xl border border-slate-100 shadow-sm cursor-pointer hover:scale-105 transition-transform" onclick="document.getElementById('img-modal').src=this.src;document.getElementById('modal-overlay').classList.remove('hidden')">
                            @else
                                <div class="w-full h-40 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-center">
                                    <p class="text-slate-300 text-xs text-center">No disponible<br>aún</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        <!-- RIGHT COLUMN -->
        <div class="space-y-6">

            <!-- Employee Profile -->
            @if($servicio->empleado)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="bg-gradient-to-b from-ecogreen-light to-white px-6 pt-6 pb-4 text-center">
                    <img src="{{ $servicio->empleado->foto }}" alt="{{ $servicio->empleado->nombre }}" class="h-24 w-24 rounded-2xl object-cover border-4 border-white shadow-lg mx-auto" onerror="this.src='https://placehold.co/100x100?text=?'">
                    <h3 class="font-bold text-slate-800 mt-3 text-lg">{{ $servicio->empleado->nombre }}</h3>
                    <p class="text-ecogreen text-sm font-medium">Especialista EcoBalance</p>
                    <!-- Stars -->
                    <div class="flex items-center justify-center gap-1 mt-2">
                        @for($i=1;$i<=5;$i++)
                            <i class="fa-solid fa-star text-sm {{ $i <= round($servicio->empleado->calificacion) ? 'text-amber-400' : 'text-slate-200' }}"></i>
                        @endfor
                        <span class="text-xs text-slate-500 ml-1 font-semibold">{{ $servicio->empleado->calificacion }}</span>
                    </div>
                </div>
                <div class="px-5 py-4 space-y-3 text-sm">
                    <div class="flex items-start gap-3">
                        <div class="h-8 w-8 rounded-lg bg-ecogreen-light flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-briefcase text-ecogreen text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase">Experiencia</p>
                            <p class="text-slate-700 font-medium text-xs mt-0.5">{{ $servicio->empleado->experiencia }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-list-check text-ecoblue text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase">Servicios Realizados</p>
                            <p class="text-slate-700 font-bold text-lg">{{ $serviciosEmpleadoCount }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="h-8 w-8 rounded-lg bg-purple-50 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-certificate text-purple-500 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase">Certificaciones</p>
                            @php
                                $certs = explode(',', $servicio->empleado->experiencia);
                            @endphp
                            @foreach($certs as $cert)
                                <span class="inline-block mt-1 mr-1 px-2 py-0.5 bg-purple-50 text-purple-700 rounded-full text-[10px] font-semibold">{{ trim($cert) }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-lg bg-ecogreen-light flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-circle text-xs {{ $servicio->empleado->estado === 'activo' ? 'text-ecogreen' : 'text-slate-400' }}"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase">Estado</p>
                            <p class="font-semibold text-sm {{ $servicio->empleado->estado === 'activo' ? 'text-ecogreen' : 'text-slate-500' }} capitalize">{{ $servicio->empleado->estado }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 text-center">
                <div class="h-16 w-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-user-clock text-slate-300 text-2xl"></i>
                </div>
                <h4 class="font-semibold text-slate-600">Sin Empleado Asignado</h4>
                <p class="text-slate-400 text-sm mt-1">Un especialista será asignado próximamente.</p>
            </div>
            @endif

            <!-- Rating form -->
            @if($servicio->estado === 'Finalizado')
                @if($servicio->comentario)
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2"><i class="fa-solid fa-star text-amber-400"></i> Su Calificación</h3>
                        <div class="flex gap-1 mb-2">
                            @for($i=1;$i<=5;$i++)
                                <i class="fa-solid fa-star text-xl {{ $i <= $servicio->comentario->estrellas ? 'text-amber-400' : 'text-slate-200' }}"></i>
                            @endfor
                        </div>
                        @if($servicio->comentario->comentario)
                            <p class="text-slate-600 text-sm italic bg-slate-50 p-3 rounded-xl border border-slate-100">"{{ $servicio->comentario->comentario }}"</p>
                        @endif
                        <p class="text-xs text-slate-400 mt-2">Publicado el {{ \Carbon\Carbon::parse($servicio->comentario->created_at)->format('d/m/Y') }}</p>
                    </div>
                @else
                    <div class="bg-white rounded-2xl border border-ecogreen/20 shadow-sm p-6" x-data="{ rating: 0 }">
                        <h3 class="font-bold text-slate-800 mb-1 flex items-center gap-2"><i class="fa-solid fa-star text-amber-400"></i> Calificar Servicio</h3>
                        <p class="text-slate-500 text-xs mb-4">¿Cómo fue su experiencia?</p>
                        <form method="POST" action="{{ route('cliente.calificar', $servicio->id) }}">
                            @csrf
                            <div class="flex gap-2 mb-4 justify-center">
                                @for($i=1;$i<=5;$i++)
                                    <button type="button" @click="rating = {{ $i }}" class="text-3xl focus:outline-none transition-transform hover:scale-110">
                                        <i :class="rating >= {{ $i }} ? 'text-amber-400' : 'text-slate-200'" class="fa-solid fa-star"></i>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="estrellas" :value="rating">
                            <textarea name="comentario" rows="3" placeholder="Comparte tu experiencia con nuestro servicio..."
                                class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 resize-none focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10 mb-3"></textarea>
                            <button type="submit" class="w-full bg-ecogreen text-white font-bold py-2.5 rounded-xl text-sm hover:bg-ecogreen-dark transition-all" x-bind:disabled="rating === 0" :class="rating === 0 ? 'opacity-50 cursor-not-allowed' : ''">
                                <i class="fa-solid fa-paper-plane mr-1"></i> Enviar Calificación
                            </button>
                        </form>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="modal-overlay" class="hidden fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4" onclick="this.classList.add('hidden')">
    <img id="img-modal" src="" alt="Evidencia" class="max-w-full max-h-[90vh] rounded-xl shadow-2xl">
</div>
@endsection
