<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Gimnasio
 *
 * Representa un gimnasio dentro de la aplicación.
 * 
 * Campos principales:
 * - id_gimnasio (int)  PK
 * - nombre      (string)
 * - cif         (string)
 * - direccion   (string)
 * - telefono    (string)
 * - email       (string)
 * - activo      (bool)
 */
class Gimnasio extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla asociada
     *
     * @var string
     */
    protected $table = 'gimnasio';

    /**
     * Clave primaria de la tabla
     *
     * @var string
     */
    protected $primaryKey = 'id_gimnasio';

    /**
     * Atributos que se pueden asignar de manera masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'cif',
        'direccion',
        'telefono',
        'email',
        'activo',
    ];

    /**
     * Casts de atributos (conversiones automáticas de tipos).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación: un gimnasio tiene muchos servicios.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'id_gimnasio', 'id_gimnasio');
    }

    /**
     * Relación: un gimnasio tiene muchos empleados.
     *
     * (Puedes usarla luego si la necesitas en listados por gimnasio)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'id_gimnasio', 'id_gimnasio');
    }

    /**
     * Relación: un gimnasio tiene muchas suscripciones.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'id_gimnasio', 'id_gimnasio');
    }
}