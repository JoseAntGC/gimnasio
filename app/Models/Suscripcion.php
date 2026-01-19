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
     * Nombre de la tabla asociada al modelo.
     * @var string
     */
    protected $table = 'suscripcion';

    /**
     * Clave primaria de la tabla.
     * @var string
     */
    protected $primaryKey = 'id_suscripcion';

    /**
     * Atributos asignables de forma masiva.
     * @var array<int, string>
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
     * Atributos que deben ser convertidos a tipos nativos.
     * @var array<string, string>
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

    /**
     * Obtiene todos los pagos asociados a la suscripción.
     * * Incluye pagos en cualquier estado (pagado, pendiente, anulado).
     * * @return \Illuminate\Database\Eloquent\Relations\HasMany Relación de uno a muchos con el modelo Pago.
     */
    public function pagos()
    {
        return $this->hasMany(\App\Models\Pago::class, 'id_suscripcion', 'id_suscripcion');
    }

    /**
     * Obtiene el pago más reciente que ha sido marcado como 'pagado'.
     * * * Utilidad: Ideal para verificar rápidamente la última vez que el socio 
     * realizó un abono efectivo o para mostrar la fecha de última facturación.
     * * @return \Illuminate\Database\Eloquent\Relations\HasOne Relación de uno a uno filtrada por estado y orden cronológico.
     */
    public function ultimoPago()
    {
        return $this->hasOne(\App\Models\Pago::class, 'id_suscripcion', 'id_suscripcion')
            ->where('estado','pagado')
            ->latest('fecha_pago');
    }
}
