@extends('layouts.app')
@section('title', 'Editar Servicio - EcoBalance')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.servicios.index') }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 shadow-sm"><i class="fa-solid fa-arrow-left text-sm"></i></a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Editar Servicio #{{ str_pad($servicio->id,4,'0',STR_PAD_LEFT) }}</h1>
            <p class="text-slate-500 text-sm">{{ $servicio->tipo_servicio }} — {{ $servicio->cliente->nombre ?? 'N/A' }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.servicios.update', $servicio->id) }}" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Empleado Asignado</label>
                    <select name="empleado_id" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10">
                        <option value="">— Sin asignar —</option>
                        @foreach($empleados as $emp)
                            <option value="{{ $emp->id }}" {{ $servicio->empleado_id == $emp->id ? 'selected' : '' }}>{{ $emp->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Estado del Servicio *</label>
                    <select name="estado" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10">
                        @foreach(['Pendiente','Asignado','En camino','En proceso','Finalizado'] as $estado)
                            <option value="{{ $estado }}" {{ $servicio->estado === $estado ? 'selected' : '' }}>{{ $estado }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Fecha *</label>
                    <input type="date" name="fecha" value="{{ old('fecha', $servicio->fecha) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Hora *</label>
                    <input type="time" name="hora" value="{{ old('hora', \Carbon\Carbon::parse($servicio->hora)->format('H:i')) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Dirección *</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $servicio->direccion) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10">
                </div>
            </div>
            <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 text-xs text-amber-800">
                <i class="fa-solid fa-triangle-exclamation mr-1.5"></i>
                Al cambiar el estado a <strong>En proceso</strong>, se activará automáticamente el monitoreo de cámara. Al cambiar a <strong>Finalizado</strong>, se cerrará la sesión y se generarán las evidencias.
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-ecogreen text-white font-bold py-3 rounded-xl shadow-lg hover:bg-ecogreen-dark transition-all text-sm"><i class="fa-solid fa-floppy-disk mr-2"></i>Guardar Cambios</button>
                <a href="{{ route('admin.servicios.index') }}" class="px-6 py-3 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-sm font-semibold transition-all">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
