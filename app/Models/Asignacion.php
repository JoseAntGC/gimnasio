<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Asignacion
 *
 * Representa la asignación de un empleado a un servicio
 * en un día y hora concretos.
 *
 * Tabla: asignacion
 */
class Asignacion extends Model
{
    use HasFactory;

    protected $table = 'asignacion';
    protected $primaryKey = 'id_asignacion';

    protected $fillable = [
        'id_servicio',
        'id_empleado',
        'dia',
        'hora',
    ];

    /**
     * Relación: una asignación pertenece a un empleado.
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    /**
     * Relación: una asignación pertenece a un servicio.
     */
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio', 'id_servicio');
    }
}
