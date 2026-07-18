<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes, Traits\BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'usuario_id',
        'aseguradora_id',
        'nombre',
        'empresa',
        'telefono',
        'contacto',
        'email',
        'numero_poliza',
        'tipo_cobertura_poliza',
        'calle',
        'num_exterior',
        'num_interior',
        'colonia',
        'codigo_postal',
        'localidad',
        'municipio',
        'estado',
    ];

    protected $appends = ['direccion'];

    public function getDireccionAttribute(): ?string
    {
        $parts = array_filter([
            $this->calle,
            $this->num_exterior ? "Ext. {$this->num_exterior}" : null,
            $this->num_interior ? "Int. {$this->num_interior}" : null,
            $this->colonia,
            $this->codigo_postal,
            $this->localidad,
            $this->municipio,
            $this->estado,
        ]);
        return $parts ? implode(', ', $parts) : null;
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function aseguradora()
    {
        return $this->belongsTo(Aseguradora::class);
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class);
    }

    public function servicios()
    {
        return $this->hasManyThrough(Servicio::class, Cotizacion::class, 'cliente_id', 'cotizacion_id');
    }

    public function getUltimoServicioAttribute()
    {
        $max = $this->cotizaciones()->max('created_at');
        return $max ? \Carbon\Carbon::parse($max) : null;
    }

}
