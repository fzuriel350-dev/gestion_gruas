<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccesoRapido extends Model
{
    use \App\Models\Traits\BelongsToEmpresa;

    protected $table = 'accesos_rapidos';

    protected $fillable = [
        'empresa_id',
        'titulo',
        'icono',
        'icono_imagen',
        'url',
        'orden',
        'activo',
    ];

    protected $appends = ['icono_imagen_url'];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'orden' => 'integer',
        ];
    }

    public function getIconoImagenUrlAttribute(): ?string
    {
        if (!$this->icono_imagen) {
            return null;
        }
        $scheme = request()->getScheme();
        $host = request()->getHttpHost();
        return "{$scheme}://{$host}/storage/{$this->icono_imagen}";
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
