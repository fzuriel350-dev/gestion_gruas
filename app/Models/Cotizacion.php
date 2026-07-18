<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cotizacion extends Model
{
    use HasFactory, \App\Models\Traits\BelongsToEmpresa;

    protected $table = 'cotizaciones';

    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'aseguradora_id',
        'tipo_servicio_id',
        'folio',
        'origen_direccion',
        'destino_direccion',
        'origen_lat',
        'origen_lng',
        'destino_lat',
        'destino_lng',
        'origen_calle',
        'origen_num_exterior',
        'origen_num_interior',
        'origen_colonia',
        'origen_codigo_postal',
        'origen_localidad',
        'origen_municipio',
        'origen_estado',
        'destino_calle',
        'destino_num_exterior',
        'destino_num_interior',
        'destino_colonia',
        'destino_codigo_postal',
        'destino_localidad',
        'destino_municipio',
        'destino_estado',
        'distancia_km',
        'tiempo_estimado_minutos',
        'costo_banderazo',
        'costo_km',
        'km_excedente',
        'incluye_peajes',
        'costo_aprox_casetas',
        'num_casetas',
        'costo_total',
        'convenio_aplicado_id',
        'usuario_creador_id',
        'estatus',
        'notas',
    ];

    const ESTATUS = ['pendiente', 'aprobado', 'rechazado'];

    public function getOrigenAttribute()
    {
        return $this->origen_direccion;
    }

    public function getDestinoAttribute()
    {
        return $this->destino_direccion;
    }

    public function getOrigenDireccionAttribute($value)
    {
        $parts = array_filter([
            $this->origen_calle,
            $this->origen_num_exterior ? "Ext. {$this->origen_num_exterior}" : null,
            $this->origen_num_interior ? "Int. {$this->origen_num_interior}" : null,
            $this->origen_colonia,
            $this->origen_codigo_postal,
            $this->origen_localidad,
            $this->origen_municipio,
            $this->origen_estado,
        ]);
        return $parts ? implode(', ', $parts) : $value;
    }

    public function getDestinoDireccionAttribute($value)
    {
        $parts = array_filter([
            $this->destino_calle,
            $this->destino_num_exterior ? "Ext. {$this->destino_num_exterior}" : null,
            $this->destino_num_interior ? "Int. {$this->destino_num_interior}" : null,
            $this->destino_colonia,
            $this->destino_codigo_postal,
            $this->destino_localidad,
            $this->destino_municipio,
            $this->destino_estado,
        ]);
        return $parts ? implode(', ', $parts) : $value;
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function aseguradora()
    {
        return $this->belongsTo(Aseguradora::class);
    }

    public function tipoServicio()
    {
        return $this->belongsTo(TipoServicio::class, 'tipo_servicio_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'usuario_creador_id');
    }

    public function convenio()
    {
        return $this->belongsTo(Convenio::class, 'convenio_aplicado_id');
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }
}
