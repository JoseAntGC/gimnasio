<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Modelo Empleado
 *
 * Representa a empleados del gimnasio (incluye administradores y monitores).
 *
 * Tabla: empleado
 */
class Empleado extends Authenticatable
{
    protected $table = 'empleado';
    protected $primaryKey = 'id_empleado';
    
    /**
     * Atributos asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'id_gimnasio',
        'nombre',
        'apellidos',
        'DNI',
        'email',
        'telefono',
        'password',
        'rol',
        'activo',
    ];

     /**
     * Atributos que deben ocultarse para arrays.
     *
     * @var array
     */

    protected $hidden = ['password', 'remember_token'];

    /**
     * Atributos que deben convertirse a tipos nativos.
     *
     * @var array
    */
    protected $casts = [
        'activo' => 'boolean',
    ];

     /**
     * Relación: un empleado pertenece a un gimnasio.
     */
    public function gimnasio()
    {
        return $this->belongsTo(Gimnasio::class, 'id_gimnasio', 'id_gimnasio');
    }

    /**
     * Relación: un empleado tiene muchas asignaciones.
     */
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'id_empleado', 'id_empleado');
    }

}
