<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Plan
 *
 * Representa el catálogo de planes del gimnasio (precio actual editable por admin).
 *
 * Tabla: planes
 * PK: id_plan
 *
 * Campos:
 * - id_plan   (int) PK
 * - nombre    (string, único)  Ej: maquinas, clases, ...
 * - precio    (decimal)        Precio actual del plan
 * - activo    (bool)
 */
class Plan extends Model
{
    use HasFactory;

    protected $table = 'planes';
    protected $primaryKey = 'id_plan';

    protected $fillable = [
        'nombre',
        'precio',
        'activo',
    ];

    /**
     * Relación: un plan tiene muchas suscripciones (histórico).
     */
    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'id_plan', 'id_plan');
    }
}
