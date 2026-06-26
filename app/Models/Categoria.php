<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    protected $table = 'materiales';

    protected $fillable = [
        'codigo',
        'unidadMedida',
        'descripcion',
        'ubicacion',
        'idCategoria',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'idCategoria', 'idCategoria');
    }

    public function materialUnidades(): HasMany
    {
        return $this->hasMany(MaterialUnidad::class, 'codigo', 'codigo');
    }
}
