<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\Monitoreo;
use App\Models\Evidencia;
use App\Models\Comentario;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Admin dashboard index with KPI metrics and visual reports data.
     */
    public function dashboard()
    {
        // KPIs
        $totalClientes = Cliente::count();
        $empleadosActivos = Empleado::where('estado', 'activo')->count();
        $serviciosActivos = Servicio::whereIn('estado', ['Asignado', 'En camino', 'En proceso'])->count();
        $serviciosCompletados = Servicio::where('estado', 'Finalizado')->count();
        
        // Simulated revenues: 45,000 CRC per completed service
        $ingresosSimulados = $serviciosCompletados * 45000;

        // --- REPORT DATA ---
        // 1. Services by month (SQLite format)
        $serviciosPorMesRaw = Servicio::selectRaw("strftime('%m', fecha) as mes, count(*) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $mesesNombres = [
            '01' => 'Ene', '02' => 'Feb', '03' => 'Mar', '04' => 'Abr', '05' => 'May', '06' => 'Jun',
            '07' => 'Jul', '08' => 'Ago', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dic'
        ];

        $serviciosPorMes = [];
        foreach ($serviciosPorMesRaw as $item) {
            $nombreMes = $mesesNombres[$item->mes] ?? $item->mes;
            $serviciosPorMes[$nombreMes] = $item->total;
        }

        // 2. Best rated employees
        $empleadosCalificados = Empleado::orderBy('calificacion', 'desc')
            ->take(5)
            ->get();

        // 3. Frequent clients
        $clientesFrecuentes = Cliente::withCount('servicios')
            ->orderBy('servicios_count', 'desc')
            ->take(5)
            ->get();

        // 4. Most requested service types
        $serviciosMasSolicitados = Servicio::selectRaw('tipo_servicio, count(*) as total')
            ->groupBy('tipo_servicio')
            ->orderBy('total', 'desc')
            ->get();

        return view('admin.dashboard', compact(
            'totalClientes',
            'empleadosActivos',
            'serviciosActivos',
            'serviciosCompletados',
            'ingresosSimulados',
            'serviciosPorMes',
            'empleadosCalificados',
            'clientesFrecuentes',
            'serviciosMasSolicitados'
        ));
    }

    // ==========================================
    // CLIENTES CRUD
    // ==========================================

    public function clientesIndex()
    {
        $clientes = Cliente::withCount('servicios')->orderBy('nombre')->get();
        return view('admin.clientes.index', compact('clientes'));
    }

    public function clienteCreate()
    {
        return view('admin.clientes.create');
    }

    public function clienteStore(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:users,email|unique:clientes,correo',
            'telefono' => 'required|string|max:50',
            'direccion' => 'required|string|max:500',
        ]);

        // Create user login first
        $user = User::create([
            'name' => $validated['nombre'],
            'email' => $validated['correo'],
            'password' => Hash::make('123456'), // default password
            'role' => 'client',
        ]);

        // Create client profile
        Cliente::create([
            'user_id' => $user->id,
            'nombre' => $validated['nombre'],
            'correo' => $validated['correo'],
            'telefono' => $validated['telefono'],
            'direccion' => $validated['direccion'],
        ]);

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente registrado exitosamente. Cuenta de acceso creada.');
    }

    public function clienteEdit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function clienteUpdate(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:clientes,correo,' . $cliente->id . '|unique:users,email,' . $cliente->user_id,
            'telefono' => 'required|string|max:50',
            'direccion' => 'required|string|max:500',
        ]);

        // Update profile
        $cliente->update([
            'nombre' => $validated['nombre'],
            'correo' => $validated['correo'],
            'telefono' => $validated['telefono'],
            'direccion' => $validated['direccion'],
        ]);

        // Update login user if associated
        if ($cliente->user) {
            $cliente->user->update([
                'name' => $validated['nombre'],
                'email' => $validated['correo'],
            ]);
        }

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function clienteDestroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        
        // Delete login user if exists
        if ($cliente->user) {
            $cliente->user->delete();
        }
        
        $cliente->delete();

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }

    // ==========================================
    // EMPLEADOS CRUD
    // ==========================================

    public function empleadosIndex()
    {
        $empleados = Empleado::withCount('servicios')->get();
        return view('admin.empleados.index', compact('empleados'));
    }

    public function empleadoCreate()
    {
        return view('admin.empleados.create');
    }

    public function empleadoStore(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'experiencia' => 'required|string|max:255',
            'calificacion' => 'required|numeric|min:1|max:5',
            'estado' => 'required|string|in:activo,inactivo',
            'foto' => 'nullable|url|max:500',
        ]);

        $defaultFoto = 'https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=400&h=400&fit=crop'; // fallback
        
        Empleado::create([
            'nombre' => $validated['nombre'],
            'experiencia' => $validated['experiencia'],
            'calificacion' => $validated['calificacion'],
            'estado' => $validated['estado'],
            'foto' => $validated['foto'] ?: $defaultFoto,
        ]);

        return redirect()->route('admin.empleados.index')->with('success', 'Empleado registrado exitosamente.');
    }

    public function empleadoEdit($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('admin.empleados.edit', compact('empleado'));
    }

    public function empleadoUpdate(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'experiencia' => 'required|string|max:255',
            'calificacion' => 'required|numeric|min:1|max:5',
            'estado' => 'required|string|in:activo,inactivo',
            'foto' => 'nullable|url|max:500',
        ]);

        $empleado->update($validated);

        return redirect()->route('admin.empleados.index')->with('success', 'Empleado actualizado exitosamente.');
    }

    public function empleadoDestroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();
        return redirect()->route('admin.empleados.index')->with('success', 'Empleado eliminado exitosamente.');
    }

    // ==========================================
    // SERVICIOS CRUD
    // ==========================================

    public function serviciosIndex()
    {
        $servicios = Servicio::with(['cliente', 'empleado'])->orderBy('fecha', 'desc')->get();
        return view('admin.servicios.index', compact('servicios'));
    }

    public function servicioEdit($id)
    {
        $servicio = Servicio::findOrFail($id);
        $empleados = Empleado::where('estado', 'activo')->get();
        return view('admin.servicios.edit', compact('servicio', 'empleados'));
    }

    public function servicioUpdate(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $validated = $request->validate([
            'empleado_id' => 'nullable|exists:empleados,id',
            'estado' => 'required|string|in:Pendiente,Asignado,En camino,En proceso,Finalizado',
            'fecha' => 'required|date',
            'hora' => 'required',
            'direccion' => 'required|string|max:500',
        ]);

        // If status changes to En proceso, we should ensure Monitoreo and Evidencias exist
        if ($validated['estado'] === 'En proceso') {
            if (!$servicio->monitoreo && $validated['empleado_id']) {
                Monitoreo::create([
                    'servicio_id' => $servicio->id,
                    'empleado_id' => $validated['empleado_id'],
                    'estado_camara' => 'Activa',
                    'hora_inicio' => Carbon::now()->format('H:i:s'),
                    'fecha' => $validated['fecha'],
                    'video_path' => 'https://assets.mixkit.co/videos/preview/mixkit-cleaning-the-floor-with-a-mop-41682-large.mp4',
                    'duracion' => 'En vivo'
                ]);
            } else if ($servicio->monitoreo) {
                $servicio->monitoreo->update([
                    'estado_camara' => 'Activa',
                    'empleado_id' => $validated['empleado_id'] ?: $servicio->monitoreo->empleado_id
                ]);
            }

            if (!$servicio->evidencia) {
                Evidencia::create([
                    'servicio_id' => $servicio->id,
                    'antes_img' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=600&h=450&fit=crop',
                    'durante_img' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=600&h=450&fit=crop',
                ]);
            }
        }

        // If status changes to Finalizado, close monitoring and complete evidence
        if ($validated['estado'] === 'Finalizado') {
            if ($servicio->monitoreo) {
                $servicio->monitoreo->update([
                    'estado_camara' => 'Inactiva',
                    'hora_final' => Carbon::now()->format('H:i:s'),
                    'duracion' => '2:30'
                ]);
            } else if ($validated['empleado_id']) {
                Monitoreo::create([
                    'servicio_id' => $servicio->id,
                    'empleado_id' => $validated['empleado_id'],
                    'estado_camara' => 'Inactiva',
                    'hora_inicio' => Carbon::parse($servicio->hora)->format('H:i:s'),
                    'hora_final' => Carbon::parse($servicio->hora)->addHours(2)->format('H:i:s'),
                    'fecha' => $validated['fecha'],
                    'video_path' => 'https://assets.mixkit.co/videos/preview/mixkit-cleaning-the-floor-with-a-mop-41682-large.mp4',
                    'duracion' => '2:30'
                ]);
            }

            if ($servicio->evidencia) {
                $servicio->evidencia->update([
                    'despues_img' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=600&h=450&fit=crop'
                ]);
            } else {
                Evidencia::create([
                    'servicio_id' => $servicio->id,
                    'antes_img' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=600&h=450&fit=crop',
                    'durante_img' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=600&h=450&fit=crop',
                    'despues_img' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=600&h=450&fit=crop'
                ]);
            }
        }

        $servicio->update($validated);

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio actualizado exitosamente.');
    }

    public function servicioDetail($id)
    {
        $servicio = Servicio::with(['cliente', 'empleado', 'monitoreo', 'evidencia', 'comentario'])->findOrFail($id);
        return view('admin.servicios.detail', compact('servicio'));
    }

    // ==========================================
    // MONITOREO ADMINISTRATIVO PANEL
    // ==========================================

    public function monitoreoIndex()
    {
        // Fetch services currently En proceso or En camino (active bodycam sessions)
        // Also fetch all others with camera configuration to simulate historical data
        $serviciosActivos = Servicio::whereIn('estado', ['En camino', 'En proceso'])
            ->with(['cliente', 'empleado', 'monitoreo'])
            ->get();

        $serviciosFinalizados = Servicio::where('estado', 'Finalizado')
            ->with(['cliente', 'empleado', 'monitoreo'])
            ->orderBy('fecha', 'desc')
            ->take(10)
            ->get();

        return view('admin.monitoreo', compact('serviciosActivos', 'serviciosFinalizados'));
    }
}
