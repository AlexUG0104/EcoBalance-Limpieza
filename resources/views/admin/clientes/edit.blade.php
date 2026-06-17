@extends('layouts.app')
@section('title', 'Editar Cliente - EcoBalance')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.clientes.index') }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 transition-all shadow-sm"><i class="fa-solid fa-arrow-left text-sm"></i></a>
        <div><h1 class="text-2xl font-bold text-slate-800">Editar Cliente</h1><p class="text-slate-500 text-sm">Actualizar información de {{ $cliente->nombre }}</p></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.clientes.update', $cliente->id) }}" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nombre Completo *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Correo Electrónico *</label>
                    <input type="email" name="correo" value="{{ old('correo', $cliente->correo) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Teléfono *</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Dirección *</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $cliente->direccion) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:outline-none focus:border-ecogreen focus:ring-2 focus:ring-ecogreen/10">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-ecogreen text-white font-bold py-3 rounded-xl shadow-lg hover:bg-ecogreen-dark transition-all text-sm"><i class="fa-solid fa-floppy-disk mr-2"></i>Guardar Cambios</button>
                <a href="{{ route('admin.clientes.index') }}" class="px-6 py-3 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-sm font-semibold transition-all">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
