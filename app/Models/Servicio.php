<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Servicio
 *
 * Representa un servicio/clase ofrecido por un gimnasio.
 *
 * Tabla: servicio
 */
class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicio';
    protected $primaryKey = 'id_servicio';

    protected $fillable = [
        'id_gimnasio',
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación: un servicio pertenece a un gimnasio.
     */
    public function gimnasio()
    {
        return $this->belongsTo(Gimnasio::class, 'id_gimnasio', 'id_gimnasio');
    }

    /**
     * Relación: un servicio tiene muchas asignaciones.
     */
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'id_servicio', 'id_servicio');
    }
}