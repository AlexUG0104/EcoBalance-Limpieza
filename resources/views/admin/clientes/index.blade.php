@extends('layouts.app')
@section('title', 'Gestión de Clientes - EcoBalance')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Gestión de Clientes</h1>
            <p class="text-slate-500 text-sm mt-1">{{ $clientes->count() }} clientes registrados</p>
        </div>
        <a href="{{ route('admin.clientes.create') }}" class="inline-flex items-center gap-2 bg-ecogreen text-white font-semibold px-5 py-2.5 rounded-xl shadow-md hover:bg-ecogreen-dark transition-all text-sm">
            <i class="fa-solid fa-user-plus"></i> Nuevo Cliente
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-100">
                    <th class="text-left px-6 py-4 font-semibold">#</th>
                    <th class="text-left px-6 py-4 font-semibold">Cliente</th>
                    <th class="text-left px-6 py-4 font-semibold">Teléfono</th>
                    <th class="text-left px-6 py-4 font-semibold">Dirección</th>
                    <th class="text-left px-6 py-4 font-semibold">Servicios</th>
                    <th class="text-left px-6 py-4 font-semibold">Acciones</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($clientes as $cliente)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-6 py-4 text-slate-400 font-bold text-xs">#{{ str_pad($cliente->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl bg-ecoblue/10 text-ecoblue font-bold flex items-center justify-center text-sm shrink-0">
                                    {{ strtoupper(substr($cliente->nombre, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $cliente->nombre }}</p>
                                    <p class="text-xs text-slate-400">{{ $cliente->correo }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $cliente->telefono }}</td>
                        <td class="px-6 py-4 text-slate-600 max-w-xs"><p class="truncate">{{ $cliente->direccion }}</p></td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-ecogreen-light text-ecogreen">
                                {{ $cliente->servicios_count }} servicio(s)
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.clientes.edit', $cliente->id) }}" class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold px-3 py-1.5 rounded-lg text-xs transition-all">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>
                                <form method="POST" action="{{ route('admin.clientes.destroy', $cliente->id) }}" onsubmit="return confirm('¿Eliminar este cliente?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 bg-red-50 text-red-700 hover:bg-red-100 font-semibold px-3 py-1.5 rounded-lg text-xs transition-all">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
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
