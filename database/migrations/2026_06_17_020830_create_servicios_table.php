<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('empleado_id')->nullable()->constrained('empleados')->onDelete('set null');
            $table->string('nombre_contacto');
            $table->string('telefono_contacto');
            $table->string('direccion');
            $table->string('tipo_servicio');
            $table->date('fecha');
            $table->time('hora');
            $table->text('comentarios_adicionales')->nullable();
            $table->string('estado')->default('Pendiente'); // 'Pendiente', 'Asignado', 'En camino', 'En proceso', 'Finalizado'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
