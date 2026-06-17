@extends('layouts.app')
@section('title', 'Gestión de Empleados - EcoBalance')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Gestión de Empleados</h1>
            <p class="text-slate-500 text-sm mt-1">{{ $empleados->count() }} empleados registrados</p>
        </div>
        <a href="{{ route('admin.empleados.create') }}" class="inline-flex items-center gap-2 bg-ecogreen text-white font-semibold px-5 py-2.5 rounded-xl shadow-md hover:bg-ecogreen-dark transition-all text-sm">
            <i class="fa-solid fa-user-plus"></i> Nuevo Empleado
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        @foreach($empleados as $emp)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all overflow-hidden">
            <!-- Photo Header -->
            <div class="relative h-28 bg-gradient-to-b from-ecogreen-light to-white overflow-hidden">
                <img src="{{ $emp->foto }}" alt="{{ $emp->nombre }}" class="absolute inset-0 w-full h-full object-cover opacity-20">
                <div class="absolute inset-0 flex items-end justify-center pb-0">
                    <img src="{{ $emp->foto }}" alt="{{ $emp->nombre }}" class="h-20 w-20 rounded-2xl object-cover border-4 border-white shadow-lg translate-y-6" onerror="this.src='https://placehold.co/80x80?text=?'">
                </div>
            </div>
            <div class="pt-8 pb-4 px-4 text-center">
                <h3 class="font-bold text-slate-800 text-sm">{{ $emp->nombre }}</h3>
                <div class="flex items-center justify-center gap-0.5 mt-1">
                    @for($i=1;$i<=5;$i++)
                        <i class="fa-solid fa-star text-xs {{ $i <= round($emp->calificacion) ? 'text-amber-400' : 'text-slate-200' }}"></i>
                    @endfor
                    <span class="text-xs text-slate-500 ml-1">{{ $emp->calificacion }}</span>
                </div>
                <p class="text-xs text-slate-500 mt-1.5">{{ $emp->servicios_count }} servicios</p>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold mt-2 {{ $emp->estado === 'activo' ? 'bg-ecogreen-light text-ecogreen' : 'bg-slate-100 text-slate-500' }}">
                    <span class="w-1.5 h-1.5 rounded-full mr-1 {{ $emp->estado === 'activo' ? 'bg-ecogreen' : 'bg-slate-400' }}"></span>
                    {{ ucfirst($emp->estado) }}
                </span>
                <p class="text-[11px] text-slate-400 mt-2 line-clamp-2">{{ $emp->experiencia }}</p>
            </div>
            <div class="px-4 pb-4 flex gap-2">
                <a href="{{ route('admin.empleados.edit', $emp->id) }}" class="flex-1 flex items-center justify-center gap-1 bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold px-3 py-1.5 rounded-lg text-xs transition-all">
                    <i class="fa-solid fa-pen-to-square"></i> Editar
                </a>
                <form method="POST" action="{{ route('admin.empleados.destroy', $emp->id) }}" onsubmit="return confirm('¿Eliminar a {{ $emp->nombre }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="flex items-center justify-center bg-red-50 text-red-700 hover:bg-red-100 font-semibold px-3 py-1.5 rounded-lg text-xs transition-all">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
