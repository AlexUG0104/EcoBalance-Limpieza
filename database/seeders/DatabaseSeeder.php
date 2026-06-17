<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\Monitoreo;
use App\Models\Evidencia;
use App\Models\Comentario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin User
        User::create([
            'name' => 'Administrador EcoBalance',
            'email' => 'admin@ecobalance.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        // 2. Create Primary Client User
        $clientUser = User::create([
            'name' => 'Juan Pérez Gómez',
            'email' => 'cliente@test.com',
            'password' => Hash::make('123456'),
            'role' => 'client',
        ]);

        $primaryCliente = Cliente::create([
            'user_id' => $clientUser->id,
            'nombre' => 'Juan Pérez Gómez',
            'correo' => 'cliente@test.com',
            'telefono' => '8888-7777',
            'direccion' => 'San José, San Pedro, de la rotonda de la Hispanidad 200m este',
        ]);

        // 3. Create 14 more clients (Total 15)
        $nombresClientes = [
            'María Rodríguez Chaves', 'Carlos Alvarado Quesada', 'Ana Laura Castro Mora', 
            'Luis Diego Solís Rivera', 'Sofía Valverde Jiménez', 'Andrés Fonseca Marín',
            'Laura Herrera Rojas', 'Jorge Eduardo Vargas Ruiz', 'Camila Benavides Cruz',
            'Roberto Méndez Segura', 'Elena Delgado Ortiz', 'Gabriel Zamora Brenes',
            'Lucía Miranda Arias', 'Esteban Gutiérrez Sancho'
        ];

        $clientesList = [$primaryCliente];

        foreach ($nombresClientes as $index => $nombre) {
            $email = 'cliente' . ($index + 2) . '@test.com';
            $user = User::create([
                'name' => $nombre,
                'email' => $email,
                'password' => Hash::make('123456'),
                'role' => 'client',
            ]);

            $clientesList[] = Cliente::create([
                'user_id' => $user->id,
                'nombre' => $nombre,
                'correo' => $email,
                'telefono' => '8' . rand(3,9) . rand(10,99) . '-' . rand(1000,9999),
                'direccion' => $this->getRandomDireccion(),
            ]);
        }

        // 4. Create 8 Employees
        $nombresEmpleados = [
            'Mariela Brenes Quirós', 'Alonso Chinchilla Vega', 'Gabriela Mora Madrigal',
            'David Rojas Solano', 'Valeria Castillo Salazar', 'Felipe Escalante Lobo',
            'Tatiana Navarro Ortiz', 'Kenneth Zúñiga Monge'
        ];

        $experiencias = [
            '4 años, Certificación LEED', '3 años, Experiencia en limpieza industrial', '5 años, Especialista en desinfección verde',
            '2 años, Técnico en manejo de residuos ecológicos', '3 años, Especialista en tratamientos de superficies de madera', '6 años, Supervisor de control ecológico',
            '2 años, Certificación en bioseguridad y ecoback', '4 años, Especialista en limpieza pre-mudanza'
        ];

        $fotos = [
            'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=400&fit=crop'
        ];

        $empleadosList = [];
        foreach ($nombresEmpleados as $index => $nombre) {
            $empleadosList[] = Empleado::create([
                'nombre' => $nombre,
                'experiencia' => $experiencias[$index],
                'calificacion' => number_format(4.0 + (rand(0, 100) / 100), 2),
                'estado' => 'activo',
                'foto' => $fotos[$index],
            ]);
        }

        // 5. Create 30 Services with various statuses
        $tiposServicios = [
            'Limpieza ecológica', 'Limpieza residencial', 'Limpieza profunda', 
            'Limpieza post evento', 'Limpieza pre mudanza'
        ];

        // Seeding config
        $serviciosConfig = [
            ['estado' => 'Finalizado', 'cantidad' => 15],
            ['estado' => 'En proceso', 'cantidad' => 5],
            ['estado' => 'En camino', 'cantidad' => 3],
            ['estado' => 'Asignado', 'cantidad' => 4],
            ['estado' => 'Pendiente', 'cantidad' => 3],
        ];

        // Clean room evidence images
        $antesImgs = [
            'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=600&h=450&fit=crop',
            'https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?w=600&h=450&fit=crop',
            'https://images.unsplash.com/photo-1563453392212-326f5185007a?w=600&h=450&fit=crop'
        ];
        $duranteImgs = [
            'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=600&h=450&fit=crop',
            'https://images.unsplash.com/photo-1584622781564-1d987f7333c1?w=600&h=450&fit=crop',
            'https://images.unsplash.com/photo-1517646287270-a5a9ca602e5c?w=600&h=450&fit=crop'
        ];
        $despuesImgs = [
            'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=600&h=450&fit=crop',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&h=450&fit=crop',
            'https://images.unsplash.com/photo-1505691938895-1758d7feb511?w=600&h=450&fit=crop'
        ];

        // Simulated bodycam video URLs
        $videos = [
            'https://assets.mixkit.co/videos/preview/mixkit-cleaning-the-floor-with-a-mop-41682-large.mp4',
            'https://assets.mixkit.co/videos/preview/mixkit-wiping-a-table-with-a-cloth-41680-large.mp4',
            'https://assets.mixkit.co/videos/preview/mixkit-vacuuming-a-carpet-41685-large.mp4'
        ];

        $comentariosFicticios = [
            'Excelente servicio, usaron productos orgánicos que no huelen fuerte y dejaron todo impecable.',
            'Muy puntuales y profesionales. El monitoreo por cámara me dio mucha tranquilidad.',
            'Me gustó mucho el detalle del reporte y ver la limpieza en tiempo real.',
            'Servicio de calidad, muy recomendado. El personal es súper amable.',
            'Muy buen trabajo. Todo quedó brillante y limpio de manera ecológica.',
            'Impresionante tecnología y servicio. Ver los videos del antes y después demuestra el profesionalismo.',
            'Excelente atención de principio a fin. Sin duda los volveré a contratar.',
            'Muy profesionales, los productos que usan huelen riquísimo a lavanda natural y pino.',
            'Excelente servicio en la mudanza, nos ahorraron muchísimo tiempo y dejaron la casa reluciente.',
            'Recomendados al 100%. Calidad, transparencia y muy amigables con el ambiente.'
        ];

        $comentariosCreados = 0;
        $contadorServicios = 1;

        foreach ($serviciosConfig as $config) {
            $estado = $config['estado'];
            $cantidad = $config['cantidad'];

            for ($i = 0; $i < $cantidad; $i++) {
                // Assign first 5 services to our main client for easy testing/demo
                if ($contadorServicios <= 5) {
                    $cliente = $primaryCliente;
                } else {
                    $cliente = $clientesList[array_rand($clientesList)];
                }

                $empleado = ($estado !== 'Pendiente') ? $empleadosList[array_rand($empleadosList)] : null;
                $tipo = $tiposServicios[array_rand($tiposServicios)];
                
                if ($estado === 'Finalizado') {
                    $fecha = Carbon::now()->subDays(rand(1, 60));
                } else if ($estado === 'En proceso' || $estado === 'En camino') {
                    $fecha = Carbon::now();
                } else {
                    $fecha = Carbon::now()->addDays(rand(1, 15));
                }

                $hora = sprintf('%02d:00:00', rand(8, 16));

                $servicio = Servicio::create([
                    'cliente_id' => $cliente->id,
                    'empleado_id' => $empleado ? $empleado->id : null,
                    'nombre_contacto' => $cliente->nombre,
                    'telefono_contacto' => $cliente->telefono,
                    'direccion' => $cliente->direccion,
                    'tipo_servicio' => $tipo,
                    'fecha' => $fecha->format('Y-m-d'),
                    'hora' => $hora,
                    'comentarios_adicionales' => rand(0, 1) ? 'Solicito especial cuidado con los pisos de madera y el uso de jabón biodegradable neutro.' : null,
                    'estado' => $estado,
                ]);

                // Create Evidencias and Monitoreo for appropriate states
                if ($estado === 'Finalizado') {
                    Evidencia::create([
                        'servicio_id' => $servicio->id,
                        'antes_img' => $antesImgs[array_rand($antesImgs)],
                        'durante_img' => $duranteImgs[array_rand($duranteImgs)],
                        'despues_img' => $despuesImgs[array_rand($despuesImgs)],
                    ]);

                    Monitoreo::create([
                        'servicio_id' => $servicio->id,
                        'empleado_id' => $empleado->id,
                        'estado_camara' => 'Inactiva',
                        'hora_inicio' => Carbon::parse($hora)->format('H:i:s'),
                        'hora_final' => Carbon::parse($hora)->addHours(rand(2, 4))->format('H:i:s'),
                        'video_path' => $videos[array_rand($videos)],
                        'duracion' => rand(2, 4) . ':' . sprintf('%02d', rand(0, 59)),
                        'fecha' => $fecha->format('Y-m-d'),
                    ]);

                    if ($comentariosCreados < 20) {
                        Comentario::create([
                            'servicio_id' => $servicio->id,
                            'cliente_id' => $cliente->id,
                            'estrellas' => rand(4, 5),
                            'comentario' => $comentariosFicticios[array_rand($comentariosFicticios)],
                        ]);
                        $comentariosCreados++;
                    }
                } elseif ($estado === 'En proceso') {
                    Evidencia::create([
                        'servicio_id' => $servicio->id,
                        'antes_img' => $antesImgs[array_rand($antesImgs)],
                        'durante_img' => $duranteImgs[array_rand($duranteImgs)],
                        'despues_img' => null,
                    ]);

                    Monitoreo::create([
                        'servicio_id' => $servicio->id,
                        'empleado_id' => $empleado->id,
                        'estado_camara' => 'Activa',
                        'hora_inicio' => Carbon::parse($hora)->format('H:i:s'),
                        'hora_final' => null,
                        'video_path' => $videos[array_rand($videos)],
                        'duracion' => 'En vivo',
                        'fecha' => $fecha->format('Y-m-d'),
                    ]);
                } elseif ($estado === 'En camino') {
                    Monitoreo::create([
                        'servicio_id' => $servicio->id,
                        'empleado_id' => $empleado->id,
                        'estado_camara' => 'Inactiva',
                        'hora_inicio' => null,
                        'hora_final' => null,
                        'video_path' => null,
                        'duracion' => null,
                        'fecha' => $fecha->format('Y-m-d'),
                    ]);
                }

                $contadorServicios++;
            }
        }
    }

    /**
     * Generate random Costa Rican addresses
     */
    private function getRandomDireccion(): string
    {
        $provincias = ['San José', 'Heredia', 'Alajuela', 'Cartago', 'Guanacaste', 'Puntarenas', 'Limón'];
        $cantones = [
            'San José' => ['Escazú', 'San Pedro', 'Tibás', 'Moravia'],
            'Heredia' => ['Belén', 'Barva', 'San Joaquín', 'Santo Domingo'],
            'Alajuela' => ['San Ramón', 'Guácima', 'Palmares', 'Ciudad Quesada'],
            'Cartago' => ['Tres Ríos', 'Oreamuno', 'Paraíso', 'El Guarco'],
            'Guanacaste' => ['Liberia', 'Nicoya', 'Tamarindo', 'Filadelfia'],
            'Puntarenas' => ['Quepos', 'Jacó', 'Esparza', 'Puntarenas Centro'],
            'Limón' => ['Cahuita', 'Guápiles', 'Limón Centro', 'Siquirres'],
        ];

        $provincia = $provincias[array_rand($provincias)];
        $canton = $cantones[$provincia][array_rand($cantones[$provincia])];
        
        $detalles = [
            '100m oeste de la iglesia católica',
            'Residencial Las Flores, casa #24B',
            'frente al parque central',
            'Condominio Los Robles, casa 12',
            '200m sur de la escuela pública',
            'del cruce principal 300m este',
            'diagonal al supermercado local'
        ];

        return "$provincia, $canton, " . $detalles[array_rand($detalles)];
    }
}
