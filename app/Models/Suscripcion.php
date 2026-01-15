<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Suscripcion
 *
 * Representa una suscripción (contrato) de un usuario a un plan.
 *
 * Tabla: suscripcion
 * PK: id_suscripcion
 *
 * Relación importante:
 * - La suscripción guarda el precio como histórico (campo precio).
 * - El plan guarda el precio actual (planes.precio).
 *   Cambiar el precio del plan NO cambia suscripciones ya creadas.
 */
class Suscripcion extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'suscripcion';

    /**
     * @var string
     */
    protected $primaryKey = 'id_suscripcion';

    /**
     * @var array<int,string>
     */
    protected $fillable = [
        'id_usuario',
        'id_gimnasio',
        'id_plan',
        'precio',
        'fecha_alta',
        'fecha_baja',
        'activa',
    ];

    /**
     * @var array<string,string>
     */
    protected $casts = [
        'precio'     => 'decimal:2',
        'activa'     => 'boolean',
        'fecha_alta' => 'date',
        'fecha_baja' => 'date',
    ];

    /**
     * Relación: la suscripción pertenece a un usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación: la suscripción pertenece a un gimnasio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gimnasio()
    {
        return $this->belongsTo(Gimnasio::class, 'id_gimnasio', 'id_gimnasio');
    }

    /**
     * Relación: la suscripción pertenece a un plan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'id_plan', 'id_plan');
    }
}
