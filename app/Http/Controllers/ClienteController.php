<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\Cliente;
use App\Models\Comentario;
use App\Models\Evidencia;
use App\Models\Monitoreo;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    /**
     * Get the client profile associated with the authenticated user.
     */
    private function getCliente()
    {
        $cliente = Auth::user()->cliente;
        if (!$cliente) {
            // Fallback for demo if admin somehow hits client dashboard
            $cliente = Cliente::first();
        }
        return $cliente;
    }

    /**
     * Client dashboard index.
     */
    public function dashboard()
    {
        $cliente = $this->getCliente();

        $servicios = Servicio::where('cliente_id', $cliente->id)->get();
        
        $totalContratados = $servicios->count();
        $totalFinalizados = $servicios->where('estado', 'Finalizado')->count();
        $totalEnProceso = $servicios->whereIn('estado', ['En camino', 'En proceso'])->count();
        
        $calificacionPromedio = Comentario::where('cliente_id', $cliente->id)->avg('estrellas');
        $calificacionPromedio = $calificacionPromedio ? number_format($calificacionPromedio, 1) : '5.0';

        $ultimosServicios = Servicio::where('cliente_id', $cliente->id)
            ->with('empleado')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->take(5)
            ->get();

        return view('cliente.dashboard', compact(
            'totalContratados',
            'totalFinalizados',
            'totalEnProceso',
            'calificacionPromedio',
            'ultimosServicios'
        ));
    }

    /**
     * Show request service form.
     */
    public function showSolicitar()
    {
        $cliente = $this->getCliente();
        return view('cliente.solicitar', compact('cliente'));
    }

    /**
     * Store new service request.
     */
    public function solicitar(Request $request)
    {
        $cliente = $this->getCliente();

        $validated = $request->validate([
            'nombre_contacto' => 'required|string|max:255',
            'telefono_contacto' => 'required|string|max:50',
            'direccion' => 'required|string|max:500',
            'tipo_servicio' => 'required|string|in:Limpieza ecológica,Limpieza residencial,Limpieza profunda,Limpieza post evento,Limpieza pre mudanza',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'comentarios_adicionales' => 'nullable|string',
        ]);

        $servicio = Servicio::create([
            'cliente_id' => $cliente->id,
            'nombre_contacto' => $validated['nombre_contacto'],
            'telefono_contacto' => $validated['telefono_contacto'],
            'direccion' => $validated['direccion'],
            'tipo_servicio' => $validated['tipo_servicio'],
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'comentarios_adicionales' => $validated['comentarios_adicionales'],
            'estado' => 'Pendiente',
        ]);

        return redirect()->route('cliente.servicios')->with('success', 'Servicio solicitado exitosamente. Pronto le asignaremos un especialista.');
    }

    /**
     * Show client's service catalog.
     */
    public function servicios()
    {
        $cliente = $this->getCliente();
        $servicios = Servicio::where('cliente_id', $cliente->id)
            ->with('empleado')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->get();

        return view('cliente.servicios', compact('servicios'));
    }

    /**
     * Show detail of a single service (includes employee, bodycam video monitoring, photo evidences, and rating).
     */
    public function detalle($id)
    {
        $cliente = $this->getCliente();
        $servicio = Servicio::where('cliente_id', $cliente->id)
            ->where('id', $id)
            ->with(['empleado', 'monitoreo', 'evidencia', 'comentario'])
            ->firstOrFail();

        // Count employee total finished services to show on employee profile
        $serviciosEmpleadoCount = 0;
        if ($servicio->empleado) {
            $serviciosEmpleadoCount = Servicio::where('empleado_id', $servicio->empleado_id)
                ->where('estado', 'Finalizado')
                ->count();
        }

        return view('cliente.detalle', compact('servicio', 'serviciosEmpleadoCount'));
    }

    /**
     * Rate a completed service.
     */
    public function calificar(Request $request, $id)
    {
        $cliente = $this->getCliente();
        $servicio = Servicio::where('cliente_id', $cliente->id)
            ->where('id', $id)
            ->where('estado', 'Finalizado')
            ->firstOrFail();

        // Check if already rated
        if ($servicio->comentario) {
            return back()->with('error', 'Este servicio ya ha sido calificado.');
        }

        $validated = $request->validate([
            'estrellas' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        Comentario::create([
            'servicio_id' => $servicio->id,
            'cliente_id' => $cliente->id,
            'estrellas' => $validated['estrellas'],
            'comentario' => $validated['comentario'],
        ]);

        // Update employee average rating
        if ($servicio->empleado_id) {
            $empleado = $servicio->empleado;
            $newAvg = Comentario::whereHas('servicio', function ($query) use ($empleado) {
                $query->where('empleado_id', $empleado->id);
            })->avg('estrellas');
            
            if ($newAvg) {
                $empleado->update([
                    'calificacion' => number_format($newAvg, 2)
                ]);
            }
        }

        return redirect()->route('cliente.detalle', $servicio->id)->with('success', '¡Gracias por su calificación! Nos ayuda a mejorar.');
    }
}
