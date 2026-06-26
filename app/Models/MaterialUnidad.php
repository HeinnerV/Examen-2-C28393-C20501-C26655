<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialUnidad extends Model
{
    protected $table = 'material_unidad';

    protected $fillable = [
        'cantidad',
        'idUnidad',
        'codigo',           
        'codigoPresupuesto', 
    ];

    
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'codigo', 'codigo');
    }

   
    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class, 'idUnidad', 'idUnidad');
    }

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class, 'codigoPresupuesto', 'codigoPresupuesto');
    }
}