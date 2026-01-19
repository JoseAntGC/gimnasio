<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Pago
 * Representa un pago (cuota) asociado a una suscripción.
 */
class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';

    protected $fillable = [
        'id_suscripcion',
        'periodo',
        'importe',
        'fecha_pago',
        'metodo',
        'estado',
        'referencia',
        'observaciones',
    ];

    protected $casts = [
        'periodo'    => 'date',
        'fecha_pago' => 'datetime',
        'importe'    => 'decimal:2',
    ];

    /**
     * Relación: el pago pertenece a una suscripción.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class, 'id_suscripcion', 'id_suscripcion');
    }
}
