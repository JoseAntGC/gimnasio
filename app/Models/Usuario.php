<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // <- importante
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Usuario
 *
 * Representa a los clientes del gimnasio que acceden al portal de usuario.
 *
 * Tabla: usuarios
 *
 * Campos principales:
 * - id_usuario (int) PK
 * - nombre      (string)
 * - apellidos   (string)
 * - DNI         (string, único)
 * - email       (string, único)
 * - telefono    (string|null)
 * - password    (string, hash)
 * - activo      (bool)
 * - categoria   (enum: Principiante / Intermedio / Experto)
 */
class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

     /**
     * Nombre de la tabla asociada
     *
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * Clave primaria de la tabla
     *
     * @var string
     */
    protected $primaryKey = 'id_usuario';

    /**
     * Indica si la clave primaria es autoincremental.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Tipo de la clave primaria.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Atributos asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_gimnasio',
        'nombre',
        'apellidos',
        'DNI',
        'email',
        'telefono',
        'password',
        'activo',
        'categoria',
    ];

    /**
     * Atributos que deben ocultarse al serializar el modelo.
     *
     * @var array<int, string>
     */
   protected $hidden = ['password', 'remember_token'];

    /**
     * Casts de atributos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación: un usuario tiene muchas suscripciones.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación: un usuario pertenece a un gimnasio.
     */
    public function gimnasio()
    {
        return $this->belongsTo(\App\Models\Gimnasio::class, 'id_gimnasio', 'id_gimnasio');
    }
}
