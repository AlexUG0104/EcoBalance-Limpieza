@extends('layouts.app')
@section('title', 'Solicitar Servicio - EcoBalance')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-800">Solicitar Servicio</h1>
        <p class="text-slate-500 text-sm mt-1">Complete el formulario para agendar su servicio de limpieza ecológica.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-ecogreen-dark to-ecogreen px-6 py-4 flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fa-solid fa-calendar-plus text-white text-lg"></i>
            </div>
            <div>
                <h2 class="text-white font-bold">Nueva Solicitud de Servicio</h2>
                <p class="text-white/70 text-xs">Los campos con * son obligatorios</p>
            </div>
        </div>

        <form method="POST" action="{{ route('cliente.solicitar') }}" class="p-6 space-y-6">
            @csrf

            <!-- Contact Info -->
            <div>
                <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wider mb-4 flex items-center gap-2"><i class="fa-solid fa-user text-ecogreen"></i> Información de Contacto</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nombre_contacto" class="block text-sm font-semibold text-slate-700 mb-1.5">Nombre de Contacto *</label>
                        <input id="nombre_contacto" type="text" name="nombre_contacto" value="{{ old('nombre_contacto', $cliente->nombre) }}" required
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-800 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10 transition-all">
                    </div>
                    <div>
                        <label for="telefono_contacto" class="block text-sm font-semibold text-slate-700 mb-1.5">Teléfono *</label>
                        <input id="telefono_contacto" type="text" name="telefono_contacto" value="{{ old('telefono_contacto', $cliente->telefono) }}" required
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-800 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10 transition-all" placeholder="8888-9999">
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div>
                <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wider mb-4 flex items-center gap-2"><i class="fa-solid fa-location-dot text-ecogreen"></i> Ubicación del Servicio</h3>
                <div>
                    <label for="direccion" class="block text-sm font-semibold text-slate-700 mb-1.5">Dirección Completa *</label>
                    <textarea id="direccion" name="direccion" rows="2" required
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-800 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10 transition-all resize-none"
                        placeholder="Provincia, Cantón, señas exactas...">{{ old('direccion', $cliente->direccion) }}</textarea>
                </div>
            </div>

            <!-- Service Details -->
            <div>
                <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wider mb-4 flex items-center gap-2"><i class="fa-solid fa-broom text-ecogreen"></i> Detalles del Servicio</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-3">
                        <label for="tipo_servicio" class="block text-sm font-semibold text-slate-700 mb-1.5">Tipo de Servicio *</label>
                        <select id="tipo_servicio" name="tipo_servicio" required
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-800 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10 transition-all">
                            <option value="">— Seleccione un tipo —</option>
                            @foreach(['Limpieza ecológica', 'Limpieza residencial', 'Limpieza profunda', 'Limpieza post evento', 'Limpieza pre mudanza'] as $tipo)
                                <option value="{{ $tipo }}" {{ old('tipo_servicio') === $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="fecha" class="block text-sm font-semibold text-slate-700 mb-1.5">Fecha Preferida *</label>
                        <input id="fecha" type="date" name="fecha" value="{{ old('fecha') }}" required min="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-800 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10 transition-all">
                    </div>
                    <div>
                        <label for="hora" class="block text-sm font-semibold text-slate-700 mb-1.5">Hora *</label>
                        <select id="hora" name="hora" required
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-800 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10 transition-all">
                            <option value="">— Hora —</option>
                            @for($h = 7; $h <= 17; $h++)
                                @php $val = sprintf('%02d:00', $h); @endphp
                                <option value="{{ $val }}:00" {{ old('hora') === "$val:00" ? 'selected' : '' }}>{{ $val }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <!-- Additional Comments -->
            <div>
                <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wider mb-4 flex items-center gap-2"><i class="fa-solid fa-comment-dots text-ecogreen"></i> Comentarios Adicionales</h3>
                <textarea id="comentarios_adicionales" name="comentarios_adicionales" rows="3"
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-800 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10 transition-all resize-none"
                    placeholder="Instrucciones especiales, áreas prioritarias, alergias a productos, mascotas en casa...">{{ old('comentarios_adicionales') }}</textarea>
            </div>

            <!-- Eco notice -->
            <div class="flex items-start gap-3 p-4 bg-ecogreen-light rounded-xl border border-ecogreen/20">
                <i class="fa-solid fa-leaf text-ecogreen mt-0.5 text-lg"></i>
                <div class="text-sm">
                    <p class="font-semibold text-ecogreen">100% Ecológico y Monitoreado</p>
                    <p class="text-slate-600 text-xs mt-0.5">Su servicio será realizado con productos biodegradables y bajo monitoreo por cámara corporal para su tranquilidad y la nuestra.</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="submit" class="flex-1 bg-gradient-to-r from-ecogreen-dark to-ecogreen text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-ecogreen/30 hover:shadow-xl transition-all flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-calendar-check"></i>
                    Enviar Solicitud de Servicio
                </button>
                <a href="{{ route('cliente.dashboard') }}" class="flex-none sm:w-auto flex items-center justify-center gap-2 px-6 py-3 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-sm font-semibold transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
