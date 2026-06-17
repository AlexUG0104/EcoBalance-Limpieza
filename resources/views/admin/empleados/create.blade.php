@extends('layouts.app')
@section('title', 'Nuevo Empleado - EcoBalance')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.empleados.index') }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 transition-all shadow-sm"><i class="fa-solid fa-arrow-left text-sm"></i></a>
        <div><h1 class="text-2xl font-bold text-slate-800">Nuevo Empleado</h1><p class="text-slate-500 text-sm">Registrar nuevo especialista EcoBalance</p></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.empleados.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nombre Completo *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Experiencia y Certificaciones *</label>
                    <input type="text" name="experiencia" value="{{ old('experiencia') }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10" placeholder="3 años, Certificación LEED, Manejo de residuos ecológicos">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Calificación Inicial (1-5) *</label>
                    <input type="number" name="calificacion" value="{{ old('calificacion', '5') }}" min="1" max="5" step="0.1" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Estado *</label>
                    <select name="estado" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10">
                        <option value="activo" {{ old('estado') === 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">URL de Foto</label>
                    <input type="url" name="foto" value="{{ old('foto') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10" placeholder="https://...">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-ecogreen text-white font-bold py-3 rounded-xl shadow-lg hover:bg-ecogreen-dark transition-all text-sm"><i class="fa-solid fa-id-card mr-2"></i>Registrar Empleado</button>
                <a href="{{ route('admin.empleados.index') }}" class="px-6 py-3 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-sm font-semibold transition-all">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
